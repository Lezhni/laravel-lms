<template>
  <scrolled-wrapper>
    <b-container>
      <b-row class="d-flex justify-content-center pt-5">
        <b-col cols="12" sm="8" md="6" lg="5" align-self="center">
          <LoginForm/>
        </b-col>
      </b-row>
    </b-container>

    <footer-block/>
  </scrolled-wrapper>
</template>

<script>
import LoginForm from "../components/forms/LoginForm";
import ScrolledWrapper from "../components/layouts/ScrolledWrapper";
import FooterBlock from "../components/FooterBlock";

export default {
  components: {LoginForm, ScrolledWrapper, FooterBlock},
  computed: {
    headerHeight() {
      return this.$store.getters["getHeaderHeight"]
    }
  },
  mounted() {
    this.$store.dispatch('setContentLoading', false)
    this.$store.dispatch('setAuth', auth.check())
    eventBus.$on('userLoggedIn', (user) => {
      this.$store.dispatch('setAuth', true)
    });
  },
}
</script>

<style scoped>

</style>