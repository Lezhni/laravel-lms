<template>
  <div
      id="main-left-menu__wrapper"
      class="main-left-menu__wrapper full"
  >
    <div
        class="main-left-menu__close"
        :class="menuOpen ? 'active' : null"
        @click="toggleSidebar"
    ></div>
    <div id="main-left-menu-lite" class="main-left-menu-lite flex-shrink-0" :class="menuOpen ? null : 'active'">
      <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
        <li class="nav-item">
          <a id="main-left-menu-lite__btn" href="#" class="nav-link py-3" aria-current="page"
             title="Home" data-bs-toggle="tooltip" data-bs-placement="right" @click.prevent="toggleSidebar">
            <b-icon icon="arrow-right-circle-fill" aria-hidden="true" class="blue-dark-color"></b-icon>
          </a>
        </li>
        <li>
          <a href="#" class="nav-link py-3" title="Dashboard" data-bs-toggle="tooltip"
             data-bs-placement="right" @click.prevent="getCalendarData(courseInfo.id)">
            <b-icon icon="calendar2-week" aria-hidden="true" class="blue-dark-color"></b-icon>
          </a>
        </li>
      </ul>
    </div>
    <div id="main-left-menu" class="main-left-menu flex-shrink-0 text-white" :class="menuOpen ? 'active' : null">

      <scrolled-wrapper-sidebar>
        <div class="main-left-menu__head flex-column">
          <div class="main-left-menu__head-logo">
            <b-link :to="`/course/${courseInfo.id}`">
              <b-icon icon="arrow-left" scale="1.3"></b-icon>
              <span>Вернуться к курсу</span>
            </b-link>
          </div>
          <a href="#" class="btn calendar-modal main-left-menu__head-calendar" role="button"
             @click.prevent="getCalendarData(courseInfo.id)">
            <b-icon icon="calendar2-week" aria-hidden="true"></b-icon>
            <span>Расписание</span>
          </a>
        </div>

        <ul class="main-left-menu__list ist-unstyled">
          <template v-for="(lesson, lessonIdx) in lessons">
            <sidebar-lesson
                :action="false"
                :lesson="lesson"
                :index="lessonIdx"
                v-if="new Date(lesson['started_at']).setHours(0, 0, 0, 0) < new Date()"
            />
            <sidebar-lesson-closed
                dir="ltr"
                :lesson="lesson"
                :index="lessonIdx"
                v-if="!(new Date(lesson['started_at']).setHours(0, 0, 0, 0) < new Date())"
            />
          </template>
        </ul>
      </scrolled-wrapper-sidebar>

    </div>
  </div>
</template>

<script>
import axios from "axios";
import SidebarLesson from "../../components/UI/SidebarLesson";
import SidebarLessonClosed from "../../components/UI/SidebarLessonClosed";
import ScrolledWrapperSidebar from "../../components/layouts/ScrolledWrapperSidebar";

export default {
  components: {SidebarLessonClosed, SidebarLesson, ScrolledWrapperSidebar},
  computed: {
    courseInfo() {
      return this.$store.getters["getCourseInfo"]
    },
    lessons() {
      return this.$store.getters["getCourseLessons"]
    },
    menuOpen() {
      return this.$store.getters["getMenuOpen"]
    },
    fullSidebar() {
      return this.$store.getters["getFullSidebar"]
    },
  },
  methods: {
    toggleSidebar() {
      this.$store.dispatch('setToggleMenu')
    },
    async getCalendarData(courseId) {
      await axios.get(`/api/course-calendar/${courseId}`).then(res => {
        this.$store.dispatch('setCalendarData', res.data)
        this.$bvModal.show('calendar-modal')
      })
    },
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