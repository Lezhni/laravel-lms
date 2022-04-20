<template>
  <div class="breadcrumbs-wrapper">
    <b-breadcrumb class="mb-0" ref="breadcrumbs">
      <b-breadcrumb-item
          v-for="(link, idx) in links"
          :key="idx"
          :to="link.link"
      >{{link.text}}</b-breadcrumb-item>
      <b-breadcrumb-item active>{{ active }}</b-breadcrumb-item>
    </b-breadcrumb>
  </div>
</template>

<script>
export default {
  props: {
    links: {
      type: Array,
      required: false
    },
    active: {
      type: String,
      required: false
    }
  },
  methods: {
    breadcrumbsResize(e) {
      // Метод пересчета высоты хлебных крошек
      this.$store.dispatch('setBreadcrumbsHeight', this.$refs.breadcrumbs.offsetHeight)
    }
  },
  mounted() {
    this.breadcrumbsResize()
    // вешаем обработчик resize на хлебные крошки при монтировании компонента на страницу
    window.addEventListener('resize', this.breadcrumbsResize)
  },
  destroyed() {
    // и снимаем обработчик при размонтировании компонента
    window.removeEventListener('resize', this.breadcrumbsResize)
  }
}
</script>

<style lang="scss" scoped>

</style>