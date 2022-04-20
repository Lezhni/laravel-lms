<template>
  <li class="main-left-menu__item pt-2 pb-2" :class="lesson.completed ? 'finished' : null">

    <router-link
        :to="{
          name: 'course-lesson',
          params: { lesson: lesson.id }
        }"
        class="link-dark rounded"
        custom
        v-slot="{ href, route, navigate, isActive, isExactActive }"
    >
      <b-button
          v-if="action"
          class="bg-transparent align-items-start border-0 open"
          v-b-toggle="`accordion-${lesson.id}`"
          @click="navigate" :class="isExactActive && 'router-link-exact-active'"
      >
        <div class="icon">
          <div class="icon-block" :class="lesson.completed ? 'done' : null"></div>
        </div>
        <span class="text">{{ lesson.name }}</span>
      </b-button>

      <b-button
          v-else
          class="bg-transparent align-items-start border-0 open"
          v-b-toggle="`accordion-${lesson.id}`" :class="isExactActive && 'router-link-exact-active'"
      >
        <div class="icon">
          <div class="icon-block" :class="lesson.done ? 'done' : null"></div>
        </div>
        <span class="text">{{ lesson.name }}</span>
      </b-button>


      <b-collapse :visible="isActive" :id="`accordion-${lesson.id}`" accordion="my-accordion" role="tabpanel">
        <ul class="btn-toggle-nav main-left-menu__submenu list-unstyled fw-normal pb-1 small">
          <li class="p-1">
            <router-link
                :to="{ name: 'course-lesson', params: { lesson: lesson.id }}"
                class="link-dark rounded"
                custom
                v-slot="{ href, route, navigate, isActive, isExactActive }"
            >
              <a href="#" @click="navigate" :class="isExactActive && 'router-link-exact-active'">
                Посмотрите занятие
              </a>
            </router-link>
          </li>
          <li class="p-1" v-for="quiz in lesson.quizzes" :key="quiz.id">
            <router-link
                :to="{ name: 'course-quiz', params: { lesson: lesson.id, quiz: quiz.id }}"
                class="link-dark rounded"
                custom
                v-slot="{ href, route, navigate, isActive, isExactActive }"
            >
              <a href="#"
                 @click="navigate"
                 :class="[isActive && 'router-link-exact-active', isExactActive && 'router-link-exact-active']"
              >
                Пройдите тест {{ quiz.name }}
              </a>
            </router-link>
          </li>
          <li class="p-1" v-if="lesson['homework']">
            <router-link
                :to="{ name: 'course-schoolwork', params: { lesson: lesson.id}}"
                class="link-dark rounded"
                custom
                v-slot="{ href, route, navigate, isActive, isExactActive }"
            >
              <a href="#" @click="navigate" :class="isExactActive && 'router-link-exact-active'">
                Домашнее задание
              </a>
            </router-link>
          </li>
        </ul>
      </b-collapse>
    </router-link>
  </li>
</template>

<script>
export default {
  props: {
    action: {
      type: Boolean,
      required: true
    },
    lesson: {
      type: Object,
      required: true
    },
    index: {
      type: Number,
      required: true
    }
  },
  computed: {
    superPermission() {
      return this.$store.getters["getSuperPermissionChange"]
    }
  }
}
</script>

<style scoped>

</style>