<template>
  <b-row v-if="attachments && attachments.length !== 0">
    <b-col class="d-flex justify-content-center justify-content-md-start flex-wrap">
      <template v-for="attach in attachments">
        <attachments-link :attach="attach" v-if="attach.type === 'link'"/>
        <attachments-video :attach="attach" v-if="attach.type === 'video'"/>
        <attachments-file :attach="attach" v-else-if="attach.type === 'file'"/>
        <attachments-page :attach="attach" v-else-if="attach.type === 'page'"/>
      </template>
    </b-col>
  </b-row>
</template>

<script>
// Блок компонентов материалов. Разделены целенаправленно. Ожидаются сильные изменения логики каждого из них
import AttachmentsLink from "./attachments/AttachmentsLink";
import AttachmentsFile from "./attachments/AttachmentsFile";
import AttachmentsPage from "./attachments/AttachmentsPage";
import AttachmentsVideo from "./attachments/AttachmentsVideo";

export default {
  components: {AttachmentsLink, AttachmentsFile, AttachmentsPage, AttachmentsVideo},
    props: {
      attachments: {
        type: Array,
        required: false
      }
    }
}
</script>

<style scoped>

</style>