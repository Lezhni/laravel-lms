<template>
  <div class="courses-item">
    <b-row>
      <b-col cols="12">
        <div class="courses-item-top">
          <div class="courses-item-top__poster">
            <img :src="course.image" alt="">
          </div>

          <div class="courses-item-top__title">
            <h4 class="courses-item-top__title--name">
              <router-link class="courses-item-top__title--name__link" :to="`/course/${course.id}`">{{ course.name }}</router-link>
            </h4>
            <span class="courses-item-top__title--author">{{ course.teachers | implode('name') }}</span>
          </div>
          <div class="courses-item-top__btn">
            <router-link :to="`/course/${course.id}`" custom v-slot="{ navigate, isActive }">
              <b-button :active="isActive" @click="navigate" @keypress.enter="navigate" class="button-orange">
                Перейти
              </b-button>
            </router-link>
          </div>
        </div>
      </b-col>
      <b-col cols="12">
        <div class="courses-item-bottom">
          <div class="courses-item-bottom__item">
            <b-button size="md" class="button-blue" @click="getCalendarData(course.id)">
              <b-icon icon="calendar2-week" aria-hidden="true"></b-icon>
              <span>Расписание</span>
            </b-button>
          </div>
          <div class="courses-item-bottom__item" v-if="course['lessons_count']">
            <courses-item-description
                icon="lessons.svg?v=1"
                text="Занятий:"
                :value="course['lessons_count']"
            />
          </div>
          <div class="courses-item-bottom__item" v-if="course['started_at']">
            <courses-item-description-date
                icon="clarity_clock-solid.svg?v=1"
                text="Старт:"
                :started_at="course['started_at']"
            />
          </div>
          <div class="courses-item-bottom__item" v-if="course['finished_at']">
            <courses-item-description-date
                icon="clarity_clock-solid.svg?v=1"
                text="Окончание:"
                :finished_at="course['finished_at']"
            />
          </div>
          <div class="courses-item-bottom__item" v-if="course['finished_at'] !== course['access_closed_at']">
            <courses-item-description-access-date
                icon="clarity_clock-solid.svg?v=1"
                text="Доступен до:"
                :access_closed_at="course['access_closed_at']"
            />
          </div>
        </div>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import axios from "axios";
import CoursesItemDescription from "./CoursesItemDescription";
import CoursesItemDescriptionDate from "./CoursesItemDescriptionDate";
import CoursesItemDescriptionAccessDate from "./CoursesItemDescriptionAccessDate";

export default {
  props: {
    course: Object,
    coursesLength: Number
  },
  components: {CoursesItemDescription, CoursesItemDescriptionDate, CoursesItemDescriptionAccessDate},
  methods: {
    async getCalendarData(courseId) {
      // Метод получения данных календаря
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