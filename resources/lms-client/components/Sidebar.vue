<template>
  <div
      id="main-left-menu__wrapper"
      class="main-left-menu__wrapper"
      :class="menuOpen ? 'active' : null"
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

      <scrolled-wrapper-sidebar ref="scroll-sidebar">
        <div class="main-left-menu__head flex-column">
          <div class="main-left-menu__head-logo">
            <b-link :to="`/course/${courseInfo.id}`">{{ courseInfo.name }}</b-link>
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
                :action="true"
                :lesson="lesson"
                :index="lessonIdx"
                v-if="new Date(lesson['started_at']).setHours(0, 0, 0, 0) < new Date() || superPermission"
            />

            <sidebar-lesson-closed
                dir="ltr"
                :lesson="lesson"
                :index="lessonIdx"
                v-if="new Date(lesson['started_at']).setHours(0, 0, 0, 0) >= new Date() && !superPermission"
            />
          </template>
        </ul>
      </scrolled-wrapper-sidebar>

    </div>
  </div>
</template>

<script>
// Компоненты открытых и закрытых заданий разделены. Проверка проводится по 00:00 числа старта занятия.
// Если занятие стартует, к примеру, 01.12 то ровно в 00:00 задание будет открыто и выведется через компонент sidebar-lesson

import SidebarLesson from "./UI/SidebarLesson";
import SidebarLessonClosed from "./UI/SidebarLessonClosed";
import ScrolledWrapperSidebar from "./layouts/ScrolledWrapperSidebar";
import axios from "axios";

export default {
  components: {SidebarLessonClosed, SidebarLesson, ScrolledWrapperSidebar},
  data() {
    return {
      options: {
        scrollPanel: {}
      }
    }
  },
  props: {
    courseInfo: {
      type: Object,
      required: true
    },
    lessons: {
      type: Array,
      required: false
    }
  },
  computed: {
    menuOpen() {
      return this.$store.getters["getMenuOpen"]
    },
    superPermission() {
      return this.$store.getters["getSuperPermissionChange"]
    }
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
    }
  }
}
</script>

<style scoped>

</style>