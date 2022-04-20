<template>
  <div class="overlay" :class="menuOpen ? 'active' : null"></div>
</template>

<script>
// Оверлей для планшетного выезжающего меню
export default {
  computed: {
    menuOpen() {
      return this.$store.getters["getMenuOpen"]
    }
  }
}
</script>

<style lang="scss" scoped>
.overlay {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background-color: black;
  opacity: 0;
  z-index: -1;
  transition: opacity .3s;

  &.active {
    z-index: 99;
    opacity: .7;
  }
}
</style>