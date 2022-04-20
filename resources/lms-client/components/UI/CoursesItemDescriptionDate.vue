<template>
  <div class="description" v-if="started_at || finished_at">
    <div class="description--icon mr-2 mr-md-3">
      <img :src="`/assets/images/icons/${icon}`" alt="lessons">
    </div>
    <div class="description--text mr-2 mr-md-3">
      {{text}}
    </div>

    <div class="description--value" v-if="started_at && finished_at">
      {{started_at | formatDateShort}} - {{finished_at | formatDateShort}}
    </div>
    <div class="description--value" v-else-if="started_at || !finished_at">
      {{started_at | formatDateShort}}
    </div>
    <div class="description--value" v-else-if="!started_at || finished_at">
      {{finished_at | formatDateShort}}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    icon: {
      type: String,
      required: true
    },
    text: {
      type: String,
      required: true
    },
    started_at: {
      required: false
    },
    finished_at: {
      required: false
    }
  }
}
</script>

<style lang="scss" scoped>
.description {
  display: flex;
  align-items: center;
  font-size: 14px;
  flex-wrap: wrap;

  &--value {
    font-family: 'Montserrat Bold', sans-serif;
  }
}
</style>