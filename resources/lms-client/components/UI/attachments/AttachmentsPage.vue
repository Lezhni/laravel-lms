<template>
  <b-card
      no-body
      img-top
      tag="article"
      style="max-width: 20rem;"
      class="mb-3 mr-3"
  >
    <b-card-img
        src="/assets/images/lesson-themes/material_3.webp?v=1"
        alt="Image"
        class="rounded-0"
    ></b-card-img>
    <b-card-body class="attachment-subtitle pb-0 pt-0">
      <b-card-sub-title>Дополнительный материал</b-card-sub-title>
    </b-card-body>
    <b-card-body class="d-flex flex-column justify-content-between">
      <b-card-title class="h5">{{ attach.name }}</b-card-title>
      <b-card-text
          v-if="attach.description"
          style="font-style: italic; font-size: 14px"
      >
        {{ attach.description }}
      </b-card-text>

      <b-button
          block
          :href="attach.link"
          target="_blank"
          variant="primary"
      >Просмотреть
      </b-button>
    </b-card-body>
  </b-card>
</template>

<script>
export default {
  props: {
    attach: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped>

</style>