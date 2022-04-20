<template>
  <section class="sidebar-section">
    <sidebar :courseInfo="courseInfo" :lessons="courseLessons"/>

    <router-view></router-view>

  </section>
</template>

<script>
import Sidebar from "../components/Sidebar";
import axios from "axios"

export default {
  components: {Sidebar},
  computed: {
    courseInfo() {
      return this.$store.getters["getCourseInfo"]
    },
    courseLessons() {
      return this.$store.getters["getCourseLessons"]
    },
    courseID() {

    }
  },
  methods: {
    setData(course) {
      this.$store.dispatch('setCourseData', course)
      this.$store.dispatch('setContentLoading', false)
    }
  },
  beforeRouteEnter(to, from, next) {
    // Метод жизненного цикла beforeRouteEnter отрабатывает до смены url
    // Сперва идет запрос на сервер, получаем данные, и только после метод next()
    // позволяет сменить роут и страница рендерится с уже готовыми данными
    const getCourse = async () => {
      await axios.get(`/api/course/${to.params.id}`).then(res => {
        next(vm => vm.setData(res.data))
      }).catch(error => {
        eventBus.$emit('errorMess', error.response.data.message, error.response.status);
      })
    }
    getCourse()
  },
}
</script>

<style scoped>

</style>