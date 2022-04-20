<template>
  <b-navbar class="header" toggleable="md" type="light" ref="header">

    <router-link class="navbar-brand order-1" to="/">
      <img src="/assets/images/logo.svg?v=3" alt="Logo">
    </router-link>

    <b-collapse id="nav-collapse" class="justify-content-end order-3 align-items-center" is-nav>

      <b-navbar-nav class="ml-auto align-items-center mb-2 mb-md-0">

        <router-link class="d-md-none" to="/courses" custom v-slot="{ navigate, isActive }">
          <b-nav-item :active="isActive" @click="navigate" @keypress.enter="navigate">
            Мои курсы
          </b-nav-item>
        </router-link>

        <router-link class="d-md-none" to="/profile" custom v-slot="{ navigate, isActive }">
          <b-nav-item :active="isActive" @click="navigate" @keypress.enter="navigate">
            Открыть профиль
          </b-nav-item>
        </router-link>

        <router-link class="d-md-none" to="/support" custom v-slot="{ navigate, isActive }">
          <b-nav-item :active="isActive" @click="navigate" @keypress.enter="navigate">
            Вопросы и ответы
          </b-nav-item>
        </router-link>

        <b-nav-item
            class="d-md-none mb-2 border-bottom border-light pb-3"
            @click="logout"
        >Выход</b-nav-item>

        <router-link
            v-if="courseInfo.name"
            class="d-md-none"
            :to="`/course/${courseInfo.id}`"
            custom v-slot="{ navigate, isActive }"
        >
          <b-nav-item :active="isActive" @click="navigate" @keypress.enter="navigate">
           Вернуться к курсу
          </b-nav-item>
        </router-link>

        <router-link
            v-if="courseInfo.lessons.length > 0"
            class="d-md-none mb-4"
            :to="`/course/${courseInfo.id}/lessons`"
            custom v-slot="{ navigate, isActive }"
        >
          <b-nav-item :active="isActive" @click="navigate" @keypress.enter="navigate">
            Список занятий
          </b-nav-item>
        </router-link>

        <b-button href="https://tradings.world/catalog/" target="_blank" class="button-orange">
          Каталог курсов
        </b-button>

        <b-nav-item-dropdown
            v-if="userName"
            right
            class="navbar-dropdown-block d-none d-md-block"
        >
          <template #button-content>
              <span class="navbar-dropdown-btn" @click="closeNotify">
                <span class="navbar-dropdown-btn__avatar">
                  <img :src="avatar ? avatar : '/assets/images/icons/choice.webp'" alt="avatar">
                </span>
                <span class="navbar-dropdown-btn__text pr-1">{{userName.substr(0, 30)}}</span>
                 <b-icon class="orange-dark-color" icon="arrow-down-circle-fill" scale="0.8"></b-icon>
              </span>
          </template>

          <router-link class="text-center" to="/profile" exact custom v-slot="{ navigate, isActive }">
            <b-dropdown-item :active="isActive" @click="navigate" @keypress.enter="navigate">Открыть профиль</b-dropdown-item>
          </router-link>
          <router-link class="text-center" to="/support" exact custom v-slot="{ navigate, isActive }">
            <b-dropdown-item :active="isActive" @click="navigate" @keypress.enter="navigate">Вопросы и ответы</b-dropdown-item>
          </router-link>
          <b-dropdown-item class="text-center" @click="logout">Выход</b-dropdown-item>
        </b-nav-item-dropdown>

        <div class="splitter d-none d-md-block" v-if="userName"></div>

      </b-navbar-nav>
    </b-collapse>

    <div class="d-flex align-items-center order-2 order-md-5" v-if="userName">
      <div
          class="navbar-bell m-md-0"
          :class="[addedMessage ? 'animate' : null, noReadMessage ? 'no-read' : null]"
          @click="toggleShowNotifications"
      >
        <b-icon scale="1.3" icon="bell-fill" variant="light"></b-icon>
      </div>
      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
    </div>
  </b-navbar>
</template>

<script>
export default {
  computed: {
    user() {
      return this.$store.getters['getAuthUser']
    },
    userName() {
      return this.$store.getters['getAuthUserName']
    },
    avatar() {
      return this.$store.getters["getAvatar"]
    },
    courseInfo() {
      return this.$store.getters["getCourseInfo"]
    },
    addedMessage() {
      return this.$store.getters["getAddedMessage"]
    },
    noReadMessage() {
      return this.$store.getters["getNoReadMessage"]
    }
  },
  methods: {
    closeNotify(e) {
      this.$store.dispatch('setCloseNotifications')
    },
    toggleShowNotifications() {
      this.$store.dispatch('setIsShowNotifications')
      this.$store.dispatch('setCancelAddedMessage')
    },
    logout() {
      // открытие стандартного confirm модального окна от bootstrap
      // then обрабатывает положительный ответ
      // catch обрабатывает отрицательный ответ
      this.$bvModal.msgBoxConfirm('Желаете выйти?', {
        title: 'Выход',
        size: 'sm',
        buttonSize: 'sm',
        okVariant: 'primary',
        okTitle: 'Да',
        cancelTitle: 'Нет',
        footerClass: 'p-2',
        hideHeaderClose: false,
        centered: true
      })
          .then(value => {
            if (value) {
              this.$store.dispatch('resetQuizStore')
              auth.logout();
              this.$router.push('/login')
              this.$store.dispatch('setResetUser')
            }
          })
          .catch(err => {
            // An error occurred
          })
    },
    headerResize(e) {
      // Метод пересчета высоты шапки
      this.$store.dispatch('setHeaderHeight', this.$refs.header.$el.offsetHeight)
    }
  },
  mounted() {
    this.headerResize()
    // вешаем обработчик resize на хлебные крошки при монтировании компонента на страницу
    window.addEventListener('resize', this.headerResize)
  },
  destroyed() {
    // и снимаем обработчик при размонтировании компонента
    window.removeEventListener('resize', this.headerResize)
  }
}
</script>

<style lang="scss" scoped>

</style>