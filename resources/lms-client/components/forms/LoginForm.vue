<template>
  <b-form @submit.prevent="onSubmit" @reset="onReset">
    <b-form-group
        id="input-group-1"
        label="Введите Email"
        label-for="input-1"
    >
      <b-form-input
          id="input-1"
          v-model="userEmail"
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

    <b-form-group id="input-group-2" label="Введите Пароль" label-for="input-2" class="mb-2">
      <b-input-group>
        <b-form-input
            id="input-2"
            v-model="userPassword"
            :type="passwordStateShow ? 'text' : 'password'"
            placeholder="Пароль"
            autocomplete="on"
            :state="passwordState"
            @input="passwordState = null"
            aria-describedby="input-live-password-feedback"
            required
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

    <b-link to="/password/send-email" class="d-block mb-2">Восстановить пароль?</b-link>

    <b-button block type="submit" variant="primary">Войти</b-button>
  </b-form>
</template>

<script>
import {validateEmail} from '@/assets/js/helpers.js'

export default {
  data() {
    return {
      emailState: null,
      emailFeedbackText: "Введите корректный Email",
      passwordState: null,
      passwordStateShow: false,
      passwordFeedbackText: "Пароль не может быть короче 8 символов",
      isError: false
    }
  },
  computed: {
    userEmail: {
      get() {
        return this.$store.getters['getUserEmail']
      },
      set(value) {
        this.$store.dispatch('setUserEmail', value)
      }
    },
    userPassword: {
      get() {
        return this.$store.getters['getUserPassword']
      },
      set(value) {
        this.$store.dispatch('setUserPassword', value)
      }
    },
    user() {
      return this.$store.getters['getAuthUser']
    }
  },
  methods: {
    async onSubmit(event) {
      this.isError = false

      if (!validateEmail(this.userEmail)) {
        this.emailState = false
        this.isError = true
      }
      if (this.userPassword.length < 8) {
        this.passwordState = false
        this.passwordFeedbackText = "Пароль не может быть короче 8 символов"
        this.isError = true
      }

      if (!this.isError) {
        await this.$store.dispatch('setIsLoading', true)

        await this.$store.dispatch('setAuthLogin', {
          email: this.userEmail,
          password: this.userPassword
        })
            .then(res => {
              auth.login(res.data.data.token, res.data.data.user);
              this.$router.push('/courses')
              this.$store.dispatch('setIsLoading', false)

              Echo.private('student.' + res.data.data.user.id)
                  .notification((notification) => {
                    this.$store.dispatch('setAddNewNotification', notification)
                  });
            })
        .catch(e => {
          this.passwordState = false
          this.passwordFeedbackText = e.response.data.message || "Неправильный логин или пароль"
        })
      }
    },
    onReset(event) {
      event.preventDefault()

      this.form.email = ''
      this.form.name = ''
    }
  }
}
</script>

<style scoped>

</style>