<template>
  <b-button
      block
      variant="primary button-orange"
      :href="content.attributes.link"
      target="_blank"
  >Ссылка на занятие</b-button>
</template>

<script>
export default {
  props: {
    content: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped>

</style>