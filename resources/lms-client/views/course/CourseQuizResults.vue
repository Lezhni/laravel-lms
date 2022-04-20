<template>
  <div class="w-100">

    <breadcrumbs
        :links="[{link: '/', text: 'Главная'}, {link: `/course/${courseInfo.id}`, text: courseInfo.name}]"
        :active="quiz.name"
    />

    <scrolled-content>
      <b-container :fluid="true" class="pb-4">
        <div class="course-quiz" v-if="!isCompleteQuiz">

          <mobile-links/>

          <b-row class="mt-4">
            <b-col>
              <b-card no-body v-for="(question, idx) in quiz.questions" :key="question.id" class="mb-3">
                <b-card-header header-bg-variant="transparent">
                  Вопрос {{ idx + 1 }}
                </b-card-header>

                <b-card-body class="text-start">
                  <b-card-title>{{ question.name }}</b-card-title>
                  <b-card-text v-for="content in question.content" :key="content.key">
                    <p
                        v-if="content.layout === 'block-textarea'"
                        v-html="content.attributes.text"
                    ></p>
                    <img
                        class="course-quiz-image"
                        v-if="content.layout === 'block-image'"
                        :src="content.attributes.image"
                        :alt="content.attributes.image"
                    >
                  </b-card-text>
                  <b-form-group class="m-0">
                    <b-form-radio
                        v-model="question.selected"
                        v-for="(answer, answerIdx) in question['answers']"
                        :key="answer.id"
                        :name="`answer[${idx}]`"
                        :value="answer.id"
                        class="course-quiz-answer__item"
                        :class="[answer['is_correct'] ? 'is_correct' : 'not_correct',
                       answer['selected_by_student'] ? 'selected_by_student' : null]"
                        :disabled="true"
                    >
                      {{ answer.name }}
                      <span
                          v-if="answer['selected_by_student']"
                          v-html="'<b><i>(Ваш ответ)</i></b>'"
                          class="selected_by_student"
                      ></span>
                    </b-form-radio>
                  </b-form-group>
                </b-card-body>
              </b-card>
            </b-col>
          </b-row>
        </div>
        <div class="course-quiz-complete" v-else>
          <b-row class="mt-4">
            <b-col>
              <b-card no-body>
                <b-card-body class="text-center">

                  <b-card-title>Вы прошли тест</b-card-title>
                  <b-card-text>Баллов: {{ successQuestions.filter(item => item.isCorrect).length }} /
                    {{ quiz.questions.length }}
                  </b-card-text>
                  <b-card-text>* баллы не начисляются за повторное прохождение теста</b-card-text>

                  <router-link
                      class="btn btn-outline-primary"
                      :to="{ name: 'course-schoolwork', params: { lesson: lesson}}"
                  >
                    Выполнить домашнее задание
                  </router-link>
                </b-card-body>
              </b-card>
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
import Breadcrumbs from "../../components/UI/Breadcrumbs";
import FooterBlock from "../../components/FooterBlock";
import MobileLinks from "../../components/UI/MobileLinks";

export default {
  components: {ScrolledContent, Breadcrumbs, FooterBlock, MobileLinks},
  data() {
    return {
      lesson: null,
      currentAnswerId: {}
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
  },
  methods: {
    async finishQuiz() {
      const newArray = this.quiz.questions.map((question, questionIdx) => {
        return {
          id: questionIdx,
          question: question.id,
          answers: question.selected
        }
      })

      await axios.post(`/api/course/${this.$route.params.id}/lesson/${this.$route.params.lesson}/quiz/${this.$route.params.quiz}/process`, {results: newArray})

      setTimeout(() => {
        this.$store.dispatch('setIsCompleteQuiz', true)
      }, 1000)
    },
    incCurrentQuiz() {
      this.$store.dispatch('setUpdateProgressQuiz', {
        isCorrect: this.currentAnswerId.isCorrect,
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
      await axios.get(`/api/course/${to.params.id}/lesson/${to.params.lesson}/quiz/${to.params.quiz}/results`).then(res => {
        next(vm => vm.setData(res.data))
      })
    }
    getAttachments()
  },
  beforeRouteUpdate(to, from, next) {
    // Метод жизненного цикла beforeRouteUpdate при обновлении страницы
    // Сперва идет запрос на сервер, получаем данные, и только после метод next()
    // позволяет сменить роут и страница рендерится с уже готовыми данными
    axios.get(`/api/course/${to.params.id}/lesson/${to.params.lesson}/quiz/${to.params.quiz}/results`).then(res => {
      this.$store.dispatch('setLessonQuiz', res.data)
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

.fade-enter
{
  opacity: 0;
}
</style>