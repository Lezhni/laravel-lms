<template>
  <div class="content-wrapper mb-3" :class="content['layout']">
    <component :is="content.attributes.type">{{content.attributes.text}}</component>
  </div>
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
h1, h2, h3, h4, h5, h6 {
  margin-bottom: 0;
}
</style>