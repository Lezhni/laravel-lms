<template>
  <div class="description">
    <div class="description--icon mr-2 mr-md-3">
      <img :src="`/assets/images/icons/${icon}`" alt="lessons">
    </div>
    <div class="description--text mr-2 mr-md-3">
      {{text}}
    </div>
    <div class="description--value">
      {{access_closed_at | formatDateShort}}
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
    access_closed_at: {
      required: true
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