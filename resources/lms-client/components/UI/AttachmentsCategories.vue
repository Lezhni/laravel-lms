<template>
  <div>
    <div v-for="category in categories">
      <page-title
          :title="category.name"
          :bordered="true"
          class="mt-3 mb-4"
      />
      <attachments :attachments="category.attachments"/>
    </div>
  </div>
</template>

<script>
import Attachments from "./Attachments";
import PageTitle from "./PageTitle";

export default {
 components: {
   PageTitle,
   Attachments
 },
  props: {
    categories: {
      type: Array,
      required: false
    }
  }
}
</script>



<style lang="scss" scoped>

</style>

