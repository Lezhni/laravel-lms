<template>
  <section class="new-password">

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
                  id="fieldset-1"
                  label="Новый пароль"
                  label-for="input-new-password"
                  class="fieldset-required mb-3"
              >
                <b-input-group>
                  <b-form-input
                      v-model="password"
                      :type="passwordStateShow ? 'text' : 'password'"
                      id="input-new-password"
                      name="password"
                      autocomplete="new-password"
                      aria-describedby="input-live-password-feedback"
                      :state="passwordState"
                      @input="passwordState = null"
                      trim
                  ></b-form-input>
                  <b-form-invalid-feedback  id="input-live-password-feedback">
                    {{ passwordFeedbackText }}
                  </b-form-invalid-feedback>
                  <b-input-group-append>
                    <b-button
                        variant="info"
                        v-if="passwordState || passwordState === null"
                        @click="passwordStateShow = !passwordStateShow"
                    >
                      <b-icon :icon="passwordStateShow ? 'eye-fill' : 'eye-slash-fill'"></b-icon>
                    </b-button>
                  </b-input-group-append>
                </b-input-group>
              </b-form-group>

              <b-form-group
                  id="fieldset-1"
                  label="Повторите новый пароль"
                  label-for="input-new-password_confirmation"
                  class="fieldset-required"
              >
                <b-input-group>
                  <b-form-input
                      v-model="confirmPassword"
                      :type="passwordConfirmStateShow ? 'text' : 'password'"
                      id="input-new-password_confirmation"
                      name="password_confirmation"
                      autocomplete="new-password"
                      aria-describedby="input-live-password-feedback"
                      :state="passwordConfirmState"
                      @input="passwordConfirmState = null"
                      trim
                  ></b-form-input>
                  <b-form-invalid-feedback id="input-live-password-feedback">
                    {{ passwordFeedbackText }}
                  </b-form-invalid-feedback>
                  <b-input-group-append>
                    <b-button
                        variant="info"
                        v-if="passwordConfirmState || passwordConfirmState === null"
                        @click="passwordConfirmStateShow = !passwordConfirmStateShow"
                    >
                      <b-icon :icon="passwordConfirmStateShow ? 'eye-fill' : 'eye-slash-fill'"></b-icon>
                    </b-button>
                  </b-input-group-append>
                </b-input-group>
              </b-form-group>

              <b-button block type="submit" variant="primary">Войти</b-button>
            </b-form>
          </b-col>
        </b-row>
      </b-container>

      <footer-block/>
    </scrolled-content>
  </section>
</template>

<script>
import axios from "axios";
import ScrolledContent from "../../components/layouts/ScrolledContent";
import Breadcrumbs from "../../components/UI/Breadcrumbs";
import FooterBlock from "../../components/FooterBlock";

export default {
  components: {ScrolledContent, Breadcrumbs, FooterBlock},
  data() {
    return {
      passwordState: null,
      passwordConfirmState: null,
      passwordStateShow: false,
      passwordConfirmStateShow: false,
      passwordFeedbackText: "Пароль не может быть короче 8 символов",
      isError: false
    }
  },
  computed: {
    password: {
      get() {
        return this.$store.getters['getNewPassword']
      },
      set(value) {
        this.$store.dispatch('setNewPassword', value)
      }
    },
    confirmPassword: {
      get() {
        return this.$store.getters['getNewPasswordConfirm']
      },
      set(value) {
        this.$store.dispatch('setNewPasswordConfirm', value)
      }
    }
  },
  methods: {
    async onSubmit(event) {
      this.isError = false

      if (this.password.length < 8) {
        this.passwordState = false
        this.passwordFeedbackText = "Пароль не может быть короче 8 символов"
        this.isError = true
      }

      if (this.password !== this.confirmPassword) {
        this.passwordConfirmState = false
        this.passwordFeedbackText = "Пароли не совпадают"
        this.isError = true
      }

      if (!this.isError) {
        await this.$store.dispatch('setIsLoading', true)

        await axios.post('/api/password/reset', {
          token: this.$route.params.token,
          password: this.password,
          password_confirmation: this.password,
        }).then(res => {
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

      // this.form.email = ''
      // this.form.name = ''
    }
  },
  mounted() {
    this.$store.dispatch('setContentLoading', false)
  }
}
</script>

<style scoped>

</style>