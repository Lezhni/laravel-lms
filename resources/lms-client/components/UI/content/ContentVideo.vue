<template>
  <b-row>
    <b-col :cols="12" xl="9">
      <div class="course-lesson-video-wrapper mb-3">
        <video-embed v-if="content.attributes.type === 'player'"
                     :src="content.attributes.link"></video-embed>
        <div v-else-if="content.attributes.type === 'iframe'"
             class="iframe-wrapper">
          <iframe :src="content.attributes.link" allowfullscreen=""></iframe>
        </div>
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