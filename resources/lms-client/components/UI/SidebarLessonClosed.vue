<template>
  <li class="main-left-menu__item pt-2 pb-2">
    <b-button class="bg-transparent align-items-start border-0 closed" v-b-toggle="`collapse-${lesson.id}`">
      <div class="icon">
        <div class="icon-block" :class="lesson.done ? 'done' : null"></div>
      </div>
      <span class="text">{{lesson.name}}</span>
    </b-button>
  </li>
</template>

<script>
export default {
  props: {
    lesson: {
      type: Object,
      required: true
    },
    index: {
      type: Number,
      required: true
    }
  }
}
</script>

<style scoped>

</style>