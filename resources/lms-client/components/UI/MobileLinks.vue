<template>
  <b-row class="mobile-links border-bottom">
    <b-col cols="9">
      <router-link
          v-if="courseInfo.lessons.length > 0"
          class="lesson-list-btn pt-3 pb-3 d-block"
          :to="`/course/${courseInfo.id}/lessons`"
          custom v-slot="{ navigate, isActive }"
      >
        <a :active="isActive" @click="navigate" @keypress.enter="navigate">
          <b-icon icon="arrow-left"></b-icon> Список занятий
        </a>
      </router-link>
    </b-col>

    <b-col cols="3">
      <a href="#" class="btn calendar-modal main-left-menu__head-calendar m-0 pt-3 pb-3 d-block" role="button"
         @click.prevent="getCalendarData(courseInfo.id)">
        <b-icon icon="calendar2-week" aria-hidden="true"></b-icon>
      </a>
    </b-col>
  </b-row>
</template>

<script>
import axios from "axios";

export default {
  computed: {
    courseInfo() {
      return this.$store.getters["getCourseInfo"]
    },
  },
  methods: {
    async getCalendarData(courseId) {
      await axios.get(`/api/course-calendar/${courseId}`).then(res => {
        this.$store.dispatch('setCalendarData', res.data)
        this.$bvModal.show('calendar-modal')
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.lesson-list-btn {
  color: #5775b9;
  text-decoration: none;
}
</style>