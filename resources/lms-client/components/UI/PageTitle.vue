<template>
  <h2
      class="page-title m-0"
      :class="bordered ? 'bordered-title' : null"
  >{{ title }}</h2>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: false
    },
    bordered: {
      type: Boolean,
      required: false
    }
  }
}
</script>

<style lang="scss" scoped>
.page-title {
  font-family: 'Montserrat Medium', sans-serif;
}
.bordered-title {
  padding-left: 20px;
  border-left: 4px solid #5775b9;
}
</style>