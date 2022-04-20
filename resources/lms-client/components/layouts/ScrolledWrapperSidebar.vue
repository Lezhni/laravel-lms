<template>
  <div class="scrolled-wrapper-sidebar"
       :style="{height: `calc(100vh - ${headerHeight}px)`}"
  >
    <vue-scroll><slot></slot></vue-scroll>
  </div>
</template>

<script>
// Компонент-обертка для кастомного скролла
export default {
  computed: {
    headerHeight() {
      return this.$store.getters["getHeaderHeight"]
    }
  }
}
</script>

<style scoped>

</style>