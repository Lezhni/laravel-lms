<template>
  <scrolled-wrapper>
    <b-container class="not-found">
      <b-row class="d-flex justify-content-center pt-5">
        <b-col class="text-center">
          <img src="/assets/images/icons/icon-404.svg" alt="Logo">
        </b-col>
      </b-row>
      <b-row class="d-flex justify-content-center pt-5">
        <b-col class="text-center">
          <h1 class="mb-3 not-found-title">
            <span class="blue-dark-color">ОШИБКА</span>
            <span class="orange-dark-color">404</span>
          </h1>
          <h4 class="mb-3 not-found-subtitle">СТРАНИЦА НЕ НАЙДЕНА</h4>
          <p class="not-found-text">
            Пожалуйста, выберите другую страницу в меню или зайдите на
            <router-link to="/" custom v-slot="{ navigate, isActive }">
              <span class="not-found-link link-span" @click="navigate">
                стартовую
              </span>
            </router-link>
          </p>
        </b-col>
      </b-row>
    </b-container>

    <footer-block/>
  </scrolled-wrapper>
</template>

<script>
import ScrolledWrapper from "../components/layouts/ScrolledWrapper";
import FooterBlock from "../components/FooterBlock";

export default {
  components: {ScrolledWrapper, FooterBlock},
  mounted() {
    this.$store.dispatch('setContentLoading', false)
  }
}
</script>

<style scoped>

</style>