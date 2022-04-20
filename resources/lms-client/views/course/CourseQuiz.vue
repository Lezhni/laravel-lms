<template>
  <div class="w-100">

    <breadcrumbs
        :links="[
            {link: '/', text: 'Главная'},
            {link: `/course/${courseInfo.id}`, text: courseInfo.name}
        ]"
        :active="quiz.name"
    />

    <scrolled-content>
      <b-container :fluid="true" class="pb-4">
        <div class="course-quiz px-0 px-md-3">

          <mobile-links/>

          <b-row class="pt-4">
            <b-col>
              <page-title :title="quiz.name" :bordered="true"/>
            </b-col>
          </b-row>

          <b-row>
            <b-col>
              <hr class="mb-4 mt-4">
            </b-col>
          </b-row>

          <b-row>
            <b-col>
              <span>Вопрос: {{ currentQuiz + 1 }} из {{ quiz.questions.length }}</span>
            </b-col>
          </b-row>
          <b-row class="">
            <b-col>
              <b-progress class="course-quiz-progress mt-2" :max="quiz.questions.length">
                <b-progress-bar
                    v-for="(progress, idx) in quiz.questions"
                    :key="progress.id"
                    value="1"
                    :id="progress.id"
                    @click.native="updateCurrentQuiz(idx)"
                    :variant="progress.variant"
                    class="course-quiz-progress__item"
                    :class="idx === currentQuiz ? 'active' : null"
                ></b-progress-bar>
              </b-progress>
            </b-col>
          </b-row>
          <b-row class="mt-4">
            <b-col>

              <transition name="fade">
                <b-row
                    v-for="(question, idx) in quiz.questions"
                    :key="question.id"
                    v-if="idx === currentQuiz"
                >
                  <b-col cols="12">
                    <h3 class="course-quiz-item-title">{{ question.name }}</h3>
                  </b-col>
                  <b-col cols="12">
                    <content-block
                        :content="question.content"
                    />
                  </b-col>

                  <b-col cols="12">
                    <b-form-group v-if="!question.multiple">
                      <b-form-radio
                          v-for="(answer, answerIdx) in question['answers']"
                          v-model="question.selected"
                          :key="answer.id"
                          :name="`answer[${idx}]`"
                          :value="answer.id"
                          class="course-quiz-answer__item"
                          @change="[checkCorrectRadios(question), dismissCountDown = 0]"
                      >
                        {{ answer.name }}
                      </b-form-radio>
                    </b-form-group>

                    <b-form-group v-else>
                      <b-form-checkbox
                          v-for="(answer, answerIdx) in question['answers']"
                          v-model="question.selected"
                          :key="answer.id"
                          :name="`answer[${idx}]`"
                          :value="answer.id"
                          class="course-quiz-answer__item"
                          @change="[checkCorrectCheckboxes(question), dismissCountDown = 0]"
                      >
                        {{ answer.name }}
                      </b-form-checkbox>
                    </b-form-group>
                  </b-col>
                </b-row>
              </transition>
            </b-col>
          </b-row>

          <b-row>
            <b-col>
              <b-button
                  variant="danger"
                  @click="decCurrentQuiz"
                  v-if="currentQuiz !== 0"
              >
                Назад
              </b-button>

              <b-button
                  variant="primary"
                  @click="incCurrentQuiz"
                  v-if="currentQuiz < quiz.questions.length - 1"
                  :disabled="quiz.questions[currentQuiz].selected.length === 0"
              >
                Дальше
              </b-button>

              <b-button
                  variant="primary"
                  @click="finishQuiz"
                  v-if="currentQuiz === quiz.questions.length - 1"
                  :disabled="quiz.questions[currentQuiz].selected.length === 0"
              >
                Дальше
              </b-button>
            </b-col>
          </b-row>

          <b-row class="mt-3">
            <b-col>
              <b-alert
                  variant="danger"
                  dismissible
                  fade
                  :show="dismissCountDown"
                  @dismiss-count-down="countDownChanged"
              >
                Вы не ответили на {{ notCompletedTest }} {{ completedQuiz.length > 1 ? 'вопросы' : 'вопрос' }}!
              </b-alert>
            </b-col>
          </b-row>
        </div>
      </b-container>

      <footer-block/>
    </scrolled-content>
  </div>
</template>

<script>
import axios from "axios";
import ScrolledContent from "../../components/layouts/ScrolledContent";
import PageTitle from "../../components/UI/PageTitle";
import Breadcrumbs from "../../components/UI/Breadcrumbs";
import ContentBlock from "../../components/UI/ContentBlock";
import FooterBlock from "../../components/FooterBlock";
import MobileLinks from "../../components/UI/MobileLinks";

export default {
  components: {ScrolledContent, Breadcrumbs, PageTitle, ContentBlock, FooterBlock, MobileLinks},
  data() {
    return {
      lesson: null,
      currentAnswers: false,
      dismissSecs: 5,
      dismissCountDown: 0,
      notCompletedTest: ''
    }
  },
  computed: {
    quiz() {
      return this.$store.getters["getLessonQuiz"]
    },
    currentQuiz() {
      return this.$store.getters["getCurrentQuiz"]
    },
    successQuestions() {
      return this.$store.getters["getSuccessQuestions"]
    },
    isCompleteQuiz() {
      return this.$store.getters["getIsCompleteQuiz"]
    },
    courseInfo() {
      return this.$store.getters["getCourseInfo"]
    },
    completedQuiz() {
      return this.$store.getters["getCompletedQuiz"]
    }
  },
  methods: {
    checkCorrectCheckboxes(question) {
      const currentAnswers = question.answers.filter(answer => answer['is_correct'])

      currentAnswers.forEach(answer => {
        this.currentAnswers = question.selected.some(item => item === answer.id) &&
            question.selected.length === currentAnswers.length
      })
    },
    checkCorrectRadios(question) {
      const currentAnswers = question.answers.filter(answer => answer['is_correct'])

      currentAnswers.forEach(answer => {
        this.currentAnswers = question.selected === answer.id
      })
    },
    async finishQuiz() {
      await this.$store.dispatch('setUpdateProgressQuiz', {
        isCorrect: this.currentAnswers,
        idxQuestion: this.currentQuiz
      })

      await this.$store.dispatch('setIncCurrentQuiz')

      if (this.completedQuiz.length > 0) {
        this.showAlert(this.completedQuiz)
        return this.$store.dispatch('setUpdateCurrentQuiz', this.completedQuiz[0].idx)
      }

      const newArray = this.quiz.questions.map((question, questionIdx) => {
        return {
          id: questionIdx,
          question: question.id,
          answers: question.selected
        }
      })

      await axios.post(`/api/course/${this.$route.params.id}/lesson/${this.$route.params.lesson}/quiz/${this.$route.params.quiz}/process`, {results: newArray})

      await setTimeout(() => {
        this.$router.push({
          name: 'course-quiz-complete',
          params: {lesson: this.$route.params.lesson, quiz: this.$route.params.quiz}
        })
      }, 500)
    },
    incCurrentQuiz() {
      this.$store.dispatch('setUpdateProgressQuiz', {
        isCorrect: this.currentAnswers,
        idxQuestion: this.currentQuiz
      })
      this.$store.dispatch('setIncCurrentQuiz')
    },
    decCurrentQuiz() {
      this.$store.dispatch('setDecCurrentQuiz')
    },
    updateCurrentQuiz(val) {
      this.$store.dispatch('setUpdateCurrentQuiz', val)
    },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert(array) {
      const newArray = []

      array.forEach(item => {
        newArray.push(item.idx + 1)
      })

      this.notCompletedTest = newArray.join(', ')

      this.dismissCountDown = this.dismissSecs
    },
    setData(quiz) {
      this.$store.dispatch('setLessonQuiz', quiz)
      this.$store.dispatch('setContentLoading', false)
    },
  },
  beforeRouteEnter(to, from, next) {
    // Метод жизненного цикла beforeRouteEnter отрабатывает до смены url
    // Сперва идет запрос на сервер, получаем данные, и только после метод next()
    // позволяет сменить роут и страница рендерится с уже готовыми данными
    const getAttachments = async () => {
      await axios.get(`/api/course/${to.params.id}/lesson/${to.params.lesson}/quiz/${to.params.quiz}`)
          .then(res => {
            if (res.data.quiz.completed && !to.query.reQuiz) {
              next(`/course/${to.params.id}/lesson/${to.params.lesson}/quiz/${to.params.quiz}/complete`)
            } else {
              next(vm => vm.setData(res.data))
            }
          })
          .catch(error => {
            eventBus.$emit('errorMess', error.response.data.message, error.response.status);
          })
    }
    getAttachments()
  },
  beforeRouteUpdate(to, from, next) {
    // Метод жизненного цикла beforeRouteUpdate при обновлении страницы
    // Сперва идет запрос на сервер, получаем данные, и только после метод next()
    // позволяет сменить роут и страница рендерится с уже готовыми данными
    axios.get(`/api/course/${to.params.id}/lesson/${to.params.lesson}/quiz/${to.params.quiz}`).then(res => {
      if (res.data.quiz.completed && !to.query.reQuiz) {
        next(`/course/${to.params.id}/lesson/${to.params.lesson}/quiz/${to.params.quiz}/complete`)
      } else {
        this.$store.dispatch('setLessonQuiz', res.data)
      }

      this.$store.dispatch('setContentLoading', false)

      next()
    }).catch(error => {
      eventBus.$emit('errorMess', error.response.data.message, error.response.status);
    })
  },
  mounted() {
    this.lesson = this.$route.params.lesson
  },
}
</script>

<style scoped>
.fade-enter-active {
  transition: opacity .5s;
}

.fade-enter /* .fade-leave-active до версии 2.1.8 */
{
  opacity: 0;
}
</style>