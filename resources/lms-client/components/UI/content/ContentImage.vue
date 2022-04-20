<template>
  <b-row>
    <b-col :cols="12" xl="9">
      <div class="content-wrapper mb-3" :class="content['layout']">
        <img
            style="width: auto; max-width: 100%"
            :src="content.attributes.image"
            :alt="content.attributes.alt"
        >
      </div>
    </b-col>
  </b-row>
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