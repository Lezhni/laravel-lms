<template>
  <div>
    <b-row
        v-for="contentItem in content"
        :key="contentItem.key"
    >
      <b-col
          class="pb-4"
          :cols="12"
          :lg="contentItem['layout'] === 'block-file' ? 6 :
          contentItem['layout'] === 'block-zoom-room' ? 6 : 12
        "
          :xl="contentItem['layout'] === 'block-file' ? 6 :
          contentItem['layout'] === 'block-zoom-room' ? 6 :
          contentItem['layout'] === 'block-heading' ? 12 : 12
        "
      >
        <content-title v-if="contentItem['layout'] === 'block-heading'" :content="contentItem"/>
        <content-textarea v-else-if="contentItem['layout'] === 'block-textarea'" :content="contentItem"/>
        <content-wysiwyg v-else-if="contentItem['layout'] === 'block-wysiwyg'" :content="contentItem"/>
        <content-link v-else-if="contentItem['layout'] === 'block-zoom-room'" :content="contentItem"/>
        <content-file v-else-if="contentItem['layout'] === 'block-file'" :content="contentItem"/>
        <content-image v-else-if="contentItem['layout'] === 'block-image'" :content="contentItem"/>
        <content-video v-else-if="contentItem['layout'] === 'block-video'" :content="contentItem"/>
        <content-sale-button v-else-if="contentItem['layout'] === 'block-sale-button'" :content="contentItem"/>
      </b-col>
    </b-row>
  </div>
</template>

<script>
// Блок компонентов контентных блоков. Разделены целенаправленно. Ожидаются сильные изменения логики каждого из них
import ContentTitle from "./content/ContentTitle";
import ContentTextarea from "./content/ContentTextarea";
import ContentWysiwyg from "./content/ContentWysiwyg";
import ContentLink from "./content/ContentLink";
import ContentFile from "./content/ContentFile";
import ContentImage from "./content/ContentImage";
import ContentVideo from "./content/ContentVideo";
import ContentSaleButton from "./content/ContentSaleButton";

export default {
  components: {
    ContentTitle,
    ContentTextarea,
    ContentWysiwyg,
    ContentLink,
    ContentFile,
    ContentImage,
    ContentVideo,
    ContentSaleButton
  },
  props: {
    content: {
      type: Array,
      required: false
    }
  },
}
</script>

<style lang="scss" scoped>

</style>