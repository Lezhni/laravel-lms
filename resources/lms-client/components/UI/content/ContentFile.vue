<template>
  <b-button
      block
      variant="info button-blue"
      :download="content.attributes.name | replaceNameFile(content.attributes.file)"
      :href="content.attributes.file"
  >{{content.attributes.name}}</b-button>
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