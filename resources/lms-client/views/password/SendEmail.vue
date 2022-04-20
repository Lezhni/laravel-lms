<template>
  <section class="send-email">

    <breadcrumbs
        :links="[{link: '/login', text: 'Авторизация'}]"
        active="Восстановление пароля"
    />

    <scrolled-content>
      <b-container>
        <b-row>
          <b-col>
            <h2 class="text-center pt-4">Восстановление пароля</h2>
          </b-col>
        </b-row>
        <b-row class="d-flex justify-content-center pt-5">
          <b-col cols="12" sm="8" md="6" lg="5" align-self="center">
            <b-form @submit.prevent="onSubmit" @reset="onReset">
              <b-form-group
                  id="input-group-1"
                  label="Введите Email"
                  label-for="input-1"
              >
                <b-form-input
                    id="input-1"
                    v-model="email"
                    type="email"
                    placeholder="Email"
                    aria-describedby="input-live-email-feedback"
                    @input="emailState = null"
                    :state="emailState"
                    required
                ></b-form-input>
                <b-form-invalid-feedback id="input-live-email-feedback">
                  {{ emailFeedbackText }}
                </b-form-invalid-feedback>
              </b-form-group>
              <b-button block type="submit" variant="primary">Отправить</b-button>
            </b-form>
          </b-col>
        </b-row>
      </b-container>

      <footer-block/>
    </scrolled-content>
  </section>
</template>

<script>
import {validateEmail} from '../../assets/js/helpers.js'
import ScrolledContent from "../../components/layouts/ScrolledContent";
import Breadcrumbs from "../../components/UI/Breadcrumbs";
import FooterBlock from "../../components/FooterBlock";
import axios from "axios";

export default {
  components: {ScrolledContent, Breadcrumbs, FooterBlock},
  data() {
    return {
      emailState: null,
      emailFeedbackText: "Введите корректный Email",
      isError: false
    }
  },
  computed: {
    email: {
      get() {
        return this.$store.getters['getSendEmail']
      },
      set(value) {
        this.$store.dispatch('setSendEmail', value)
      }
    },
  },
  methods: {
    async onSubmit(event) {
      this.isError = false

      if (!validateEmail(this.email)) {
        this.emailState = false
        this.isError = true
      }

      if (!this.isError) {
        await this.$store.dispatch('setIsLoading', true)

        await axios.post('/api/password/send-link', {email: this.email}).then(res => {

          this.$bvModal.msgBoxOk(res.data.message, {
            title: null,
            size: 'md',
            buttonSize: 'sm',
            okVariant: 'primary',
            headerClass: 'p-2 border-bottom-0',
            footerClass: 'p-2 border-top-0',
            bodyClass: 'text-center',
            centered: true
          })
              .then(value => {
                this.$router.push({name: 'login'})
              })
              .catch(err => {
                this.$router.push({name: 'login'})
              })
        })
        .catch(error => {
          for (let field in error.response.data.errors) {
            if (error.response.data.errors.hasOwnProperty(field)) {
              eventBus.$emit('errorMess', error.response.data.errors[field][0], error.response.status);
            }
          }
        })
      }
    },
    onReset(event) {
      event.preventDefault()

      this.form.email = ''
    }
  },
  mounted() {
    this.$store.dispatch('setContentLoading', false)
  }
}
</script>

<style scoped>

</style>