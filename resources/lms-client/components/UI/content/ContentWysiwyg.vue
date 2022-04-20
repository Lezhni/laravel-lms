<template>
  <div
      class="content-wrapper mb-3"
      :class="content['layout']"
      v-html="content.attributes.text"
  ></div>
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

<style lang="scss" scoped>

</style>