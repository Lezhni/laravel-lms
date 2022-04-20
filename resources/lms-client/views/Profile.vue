<template>
  <section class="profile">

    <breadcrumbs
        :links="[{link: '/', text: 'Главная'}]"
        active="Профиль"
    />

    <scrolled-content>
      <b-container :fluid="false" class="pb-4">
        <div class="course-lesson pt-4 px-0 px-md-3">
          <page-title title="Личные данные" :bordered="true"/>

          <form action="" @submit.prevent="onSubmit">
            <b-row class="mt-5">
              <b-col cols="12" md="6" class="mb-3">
                <div class="profile-form__avatar-input">
                  <b-form-file hidden ref="avatarInputFile" @change="changeFilePreview($event)" v-model="file2"
                               class="mt-3" plain
                               name="avatar" accept="image/*"></b-form-file>

                  <img v-if="filePreview" :src="filePreview" alt="">
                </div>
                <div
                    class="profile-form__avatar d-flex flex-column align-items-center align-items-md-start justify-content-start">
                  <div class="profile-form__avatar-img" @click="changeAvatar">
                    <img ref="avatarImage"
                         :src="student['avatarUrl'] ? student['avatarUrl'] : `/assets/images/icons/choice.webp`"
                         alt="choice">
                    <b-button class="p-1" pill size="sm" variant="warning">
                      <b-icon icon="camera-fill" variant="warning" font-scale="1"></b-icon>
                    </b-button>
                  </div>
                </div>
              </b-col>

              <b-col cols="12" md="6">

              </b-col>
            </b-row>
            <b-row>
              <b-col cols="12" md="6">
                <b-form-group
                    id="fieldset-1"
                    label="Имя"
                    label-for="input-name"
                    class="fieldset-required"
                >
                  <b-form-input
                      type="text"
                      id="input-name"
                      name="name"
                      :value="student.name ? student.name : ''"
                      trim
                      required
                  ></b-form-input>
                </b-form-group>
              </b-col>
              <b-col cols="12" md="6">
                <b-form-group label="Пол" v-slot="{ ariaDescribedby }">
                  <b-form-radio-group
                      id="radio-group-1"
                      v-model="student['sex']"
                      :options="options"
                      :aria-describedby="ariaDescribedby"
                      name="extra_fields[sex]"
                  ></b-form-radio-group>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row>
              <b-col cols="12" md="6">
                <b-form-group
                    id="fieldset-1"
                    label="Телефон"
                    label-for="input-phone"
                >
                  <b-form-input
                      type="tel"
                      id="input-phone"
                      name="phone"
                      :value="student.phone"
                      pattern="(\+?\d[- .]*){7,13}"
                      trim
                  ></b-form-input>
                </b-form-group>
              </b-col>
              <b-col cols="12" md="6">
                <b-form-group
                    id="fieldset-1"
                    label="E-mail"
                    label-for="input-email"
                    class="fieldset-required"
                >
                  <b-form-input
                      type="email"
                      id="input-email"
                      name="email"
                      :value="student.email ? student.email : ''"
                      @blur="blurInputEmail"
                      trim
                      required
                  ></b-form-input>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row>
              <b-col cols="12" md="6">
                <b-form-group
                    id="fieldset-telegram"
                    label="Логин Telegram"
                    label-for="input-telegram"
                    description="Логин Telegram, без начального символа @"
                >
                  <b-form-input
                      type="text"
                      id="input-telegram"
                      name="extra_fields[telegram]"
                      :value="student.telegram"
                      trim
                      :formatter="formatterTelegram"
                      lazy-formatter
                  ></b-form-input>
                </b-form-group>
              </b-col>
              <b-col cols="12" md="6">
                <!--   -->
              </b-col>
            </b-row>

            <b-row>
              <b-col cols="12" md="6">
                <b-form-group
                    id="fieldset-1"
                    label="Страна"
                    label-for="input-country"
                >
                  <b-form-select
                      class="mb-3"
                      v-model="countriesValue"
                      @change="changeMulti"
                      name="extra_fields[country]"
                  >
                    <template #first>
                      <b-form-select-option :value="null" disabled>-- Выберите страну --</b-form-select-option>
                    </template>

                    <b-form-select-option
                        v-for="(country, idx) in countries"
                        :value="country"
                        :key="idx"
                    >
                      {{ country }}
                    </b-form-select-option>
                  </b-form-select>
                </b-form-group>
              </b-col>

              <b-col cols="12" md="6">
                <b-form-group
                    id="fieldset-1"
                    label="Город"
                    label-for="input-city"
                >
                  <b-form-select
                      v-model="citiesValue"
                      class="mb-3"
                      :value="null"
                      :disabled="cities.length === 0"
                      name="extra_fields[city]"
                  >
                    <template #first>
                      <b-form-select-option :value="null" disabled>
                        -- {{ countriesValue ? "Выберите город" : "Выберите страну" }} --
                      </b-form-select-option>
                    </template>

                    <b-form-select-option
                        v-for="(city, idx) in cities"
                        :value="city"
                        :key="idx"
                    >
                      {{ city }}
                    </b-form-select-option>
                  </b-form-select>
                </b-form-group>
              </b-col>
            </b-row>

            <transition name="fade">
              <b-row v-if="showChangePassword">
                <b-col cols="12" md="6">

                  <b-form-group
                      id="fieldset-1"
                      label="Новый пароль"
                      label-for="input-new-password"
                      class="fieldset-required"
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
                </b-col>

                <b-col cols="12" md="6">
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
                </b-col>
              </b-row>
            </transition>

            <b-row>
              <b-col>
                <div class="d-flex justify-content-center justify-content-sm-end flex-wrap">
                  <b-button
                      variant="outline-secondary"
                      class="adaptive-btn"
                      @click="showChangePassword = true"
                      v-if="!showChangePassword"
                  >Изменить пароль
                  </b-button>
                  <b-button type="submit" class="button-orange adaptive-btn">
                    Сохранить
                  </b-button>
                </div>
              </b-col>
            </b-row>

          </form>
        </div>
      </b-container>

      <footer-block/>
    </scrolled-content>
  </section>
</template>

<script>
import axios from "axios";
import PageTitle from "../components/UI/PageTitle";
import ScrolledContent from "../components/layouts/ScrolledContent";
import Breadcrumbs from "../components/UI/Breadcrumbs";
import FooterBlock from "../components/FooterBlock";

export default {
  components: {PageTitle, ScrolledContent, Breadcrumbs, FooterBlock},
  data() {
    return {
      openChangeAvatar: false,
      file2: null,
      filePreview: null,
      selected: 'first',
      yourValue: '',
      password: '',
      confirmPassword: '',
      showChangePassword: false,
      passwordState: null,
      passwordConfirmState: null,
      passwordStateShow: false,
      passwordConfirmStateShow: false,
      passwordFeedbackText: "Пароль не может быть короче 8 символов",
      isError: false,
      options: [
        {text: 'Мужской', value: 'Мужской'},
        {text: 'Женский', value: 'Женский'},
        {text: 'Другое', value: 'Другое'},
      ],
      bindProps: {
        mode: "auto",
        styleClasses: "form-control",
        validCharactersOnly: true,
        inputOptions: {
          name: "phone",
          maxlength: 17
        }

      }
    }
  },
  computed: {
    student() {
      return this.$store.getters["getProfileStudent"]
    },
    badgesChecked() {
      return this.$store.getters["getCheckedBadges"]
    },
    badgesUnChecked() {
      return this.$store.getters["getUnCheckedBadges"]
    },
    countries() {
      return this.$store.getters["getCountries"]
    },
    cities() {
      return this.$store.getters["getCities"]
    },
    countriesValue: {
      set(val) {
        return this.$store.dispatch("setCountriesValue", val)
      },
      get() {
        return this.$store.getters["getCountriesValue"]
      }
    },
    citiesValue: {
      set(val) {
        return this.$store.dispatch("setCitiesValue", val)
      },
      get() {
        return this.$store.getters["getCitiesValue"]
      }
    }
  },
  methods: {
    formatterTelegram(value) {
      return value.replace(/^\@/, "")
    },
    blurInputEmail(e) {

    },
    changeAvatar() {
      this.$refs.avatarInputFile.$el.click()
    },
    inputPhonePattern(val) {
      return val.replace(/\D/, '')
    },
    async changeCountries() {
      await axios.get(`/api/countries/${this.countriesValue}`).then(res => {
        this.$store.dispatch("setCitiesData", res.data.cities)
      })
    },
    changeCities() {
      return this.$store.dispatch("setCitiesValue", null)
    },
    changeMulti() {
      this.changeCountries()
      this.changeCities()
    },
    unCheckingBadge(badge) {
      this.$store.dispatch('unCheckingBadge', badge)
    },
    checkingBadge(badge) {
      this.$store.dispatch('checkingBadge', badge)
    },
    changeFilePreview(e) {
      if (e.target.files && e.target.files[0]) {
        let reader = new FileReader();

        reader.onload = (e) => {
          this.$refs.avatarImage.src = e.target.result
        };

        reader.readAsDataURL(e.target.files[0]);
      }
    },
    async onSubmit(e) {
      const data = new FormData(e.target)

      this.isError = false

      if (this.showChangePassword && this.password.length < 8) {
        this.passwordState = false
        this.passwordFeedbackText = "Пароль не может быть короче 8 символов"
        this.isError = true
      }

      if (this.showChangePassword && this.password !== this.confirmPassword) {
        this.passwordConfirmState = false
        this.passwordFeedbackText = "Пароли не совпадают"
        this.isError = true
      }

      if (!this.isError) {
        await data.append("userId", this.student.id)

        await axios.post('/api/profile', data).then(res => {

          const newUser = {
            avatar: res.data.student.avatarUrl,
            created_at: res.data.student.created_at,
            email: res.data.student.email,
            id: res.data.student.id,
            name: res.data.student.name,
            isAdmin: res.data.student.is_admin
          }

          this.showChangePassword = false

          auth.changeUser(newUser)

          this.$store.dispatch('setAuthUser', newUser)

          eventBus.$emit('errorMess', 'Профиль обновлен', res.status);
        })
      }
    },
    setData({student, countries}) {
      this.$store.dispatch('setCountriesData', countries)
      this.$store.dispatch('setStudentData', student)


      if (student.country) {
        this.changeCountries(student)
      }

      if (student.city) {
        this.$store.dispatch("setCitiesValue", student.city)
      }

      if (student.sex) {
        this.selected = student.sex
      }

      this.$store.dispatch('setContentLoading', false)
    }
  },
  beforeRouteEnter(to, from, next) {
    // Метод жизненного цикла beforeRouteEnter отрабатывает до смены url
    // Сперва идет запрос на сервер, получаем данные, и только после метод next()
    // позволяет сменить роут и страница рендерится с уже готовыми данными
    const getAttachments = async () => {
      let countries = {}

      await axios.get(`/api/countries`).then(res => {
        countries = res.data.countries
      }).catch(error => {
        eventBus.$emit('errorMess', error.response.data.message, error.response.status);
      })

      await axios.get(`/api/profile`).then(res => {
        next(vm => vm.setData({student: res.data.student, countries}))
      }).catch(error => {
        eventBus.$emit('errorMess', error.response.data.message, error.response.status);
      })
    }
    getAttachments()
  }
}
</script>

<style lang="scss" scoped>
.badge {
  margin-right: 5px;
  border-radius: 15px;
  padding: 0.25em 10px;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */
{
  opacity: 0;
}
</style>