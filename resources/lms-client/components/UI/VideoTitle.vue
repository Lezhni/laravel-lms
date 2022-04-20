<template>
  <div class="video-title__wrapper">
    <h4
        class="video-title"
    >{{ title }}</h4>
  </div>
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
    },
    fontSize: {
      type: String,
      default: '2rem'
    }
  }
}
</script>

<style lang="scss" scoped>
.video-title {
  font-family: 'Montserrat Medium', sans-serif;

  &__wrapper {
    padding: 10px;
    background-color: #fff;
    position: absolute;
    top: -25px;
    left: 35px;
  }


  @media (max-width: 768px) {
    font-size: 1.2rem;

    &__wrapper {
      position: static;
      padding: 0;
    }
  }
}
</style>