<template>
  <b-overlay :show="contentLoading" rounded="sm" spinner-variant="primary" spinner-type="grow" variant="light">
    <div class="scrolled-content"
         :style="{height: `calc(100vh - ${breadcrumbsHeight}px - ${headerHeight - 1}px)`}"
    >
      <vue-scroll>
        <slot></slot>
      </vue-scroll>
    </div>
  </b-overlay>
</template>

<script>
// Компонент-обертка для кастомного скролла

// Компонент b-overlay и спиннера.
// Спиннер запускается сеттером this.$store.dispatch('setContentLoading', true)
// Спиннер останавливается сеттером this.$store.dispatch('setContentLoading', false)
// Состояние спиннера получается геттером

export default {
  computed: {
    contentLoading() {
      return this.$store.getters["getContentLoading"]
    },
    breadcrumbsHeight() {
      return this.$store.getters["getBreadcrumbsHeight"]
    },
    headerHeight() {
      return this.$store.getters["getHeaderHeight"]
    }
  }
}
</script>

<style scoped>

</style>