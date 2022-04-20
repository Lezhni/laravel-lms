<template>
  <div class="w-100">

    <breadcrumbs
        :links="[{link: '/', text: 'Главная'}, {link: `/course/${courseInfo.id}`, text: courseInfo.name}]"
        :active="quiz.name"
    />

    <scrolled-content>
      <b-container :fluid="true" class="pb-4">
        <div class="course-quiz-complete">

          <mobile-links/>

          <b-row class="pt-4">
            <b-col>
              <b-card no-body>
                <b-card-body class="text-center">
                  <b-card-title>Вы прошли тест</b-card-title>
                  <b-card-text>Ваш результат - {{ correctQuestions }} / {{ allQuestions }}</b-card-text>

                  <b-button
                      class="adaptive-btn ml-0"
                      variant="outline-primary"
                      @click="redirectToResults"
                  >
                    Посмотреть ответы
                  </b-button>

                  <b-button
                      class="adaptive-btn ml-0"
                      v-if="searchHomeworks()"
                      variant="outline-primary"
                      @click="redirectToSchoolwork"
                  >
                    Выполнить домашнее задание
                  </b-button>

                  <b-button
                      class="adaptive-btn ml-0"
                      variant="outline-primary"
                      @click="redirectToReQuiz"
                  >
                    Повторно пройти тест
                  </b-button>

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
  computed: {
    quiz() {
      return this.$store.getters["getLessonQuiz"]
    },
    currentQuiz() {
      return this.$store.getters["getCurrentQuiz"]
    },
    allQuestions() {
      return this.$store.getters["getAllQuestions"]
    },
    correctQuestions() {
      return this.$store.getters["getCorrectQuestions"]
    },
    isCompleteQuiz() {
      return this.$store.getters["getIsCompleteQuiz"]
    },
    courseInfo() {
      return this.$store.getters["getCourseInfo"]
    },
  },
  methods: {
    searchHomeworks() {
      return this.courseInfo.lessons.find(lesson => +lesson.id === +this.$route.params.lesson && lesson.homework)
    },
    redirectToReQuiz() {
      this.$store.dispatch('resetQuizStore')
      this.$router.push({
        name: 'course-quiz',
        params: {lesson: this.$route.params.lesson, quiz: this.$route.params.quiz},
        query: {reQuiz: true}
      })
    },
    redirectToResults() {
      this.$store.dispatch('setIsCompleteQuiz', false)
      this.$router.push({
        name: 'course-quiz-results',
        params: {lesson: this.$route.params.lesson, quiz: this.$route.params.quiz}
      })
    },
    redirectToSchoolwork() {
      this.$router.push({
        name: 'course-schoolwork',
        params: {lesson: this.$route.params.lesson}
      })
    },
    setData(response) {
      this.$store.dispatch('setLessonQuiz', response.quiz.data)
      this.$store.dispatch('setShortResultQuiz', response.results.data)
      this.$store.dispatch('setContentLoading', false)
    },
  },
  beforeRouteEnter(to, from, next) {
    // Метод жизненного цикла beforeRouteEnter отрабатывает до смены url
    // Сперва идет запрос на сервер, получаем данные, и только после метод next()
    // позволяет сменить роут и страница рендерится с уже готовыми данными
    const getAttachments = async () => {
      await axios.get(`/api/course/${to.params.id}/lesson/${to.params.lesson}/quiz/${to.params.quiz}`)
          .then(async (res) => {
            const results = await axios.get(`/api/course/${to.params.id}/lesson/${to.params.lesson}/quiz/${to.params.quiz}/results`)
            return {quiz: res, results}
          })
          .then(res => {
            next(vm => vm.setData(res))
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
      this.$store.dispatch('setLessonQuiz', res.data)
      this.$store.dispatch('setContentLoading', false)
      next()
    }).catch(error => {
      eventBus.$emit('errorMess', error.response.data.message, error.response.status);
    })
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