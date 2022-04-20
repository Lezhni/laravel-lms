<template>
  <div id="app" @click="clicked">
    <Header/>
    <router-view/>
    <overlay/>
    <calendar/>

    <notifications/>
  </div>
</template>

<script>
// Основной входной контейнер приложения
// В компонентах будут расписаны сложные нюансы работы
import Header from "./components/Header";
import Overlay from "./components/UI/Overlay";
import Calendar from "./components/modals/Calendar";
import Notifications from "./components/modals/Notifications";

export default {
  components: {Header, Overlay, Calendar, Notifications},
  computed: {
    isVisibleSidebar() {
      return this.$route.name !== 'courses'
    },
    isShowNotifications() {
      return this.$store.getters['getIsShowNotifications']
    },
    user() {
      return this.$store.getters['getAuthUser']
    }
  },
  methods: {
    clicked(e) {
      if (this.isShowNotifications) {
        if (!e.target.closest('.notifications') && !e.target.closest('.navbar-bell')) {
          this.$store.dispatch('setCloseNotifications')
        }
      }
    }
  },
  async mounted() {
    if (auth.check()) {
      // подключение к серверу уведомлений

      Echo.private('student.' + this.user.id)
          .notification(async (notification) => {

            await this.$store.dispatch('setAddNewNotification', notification)

            // спец-метод для андроид платформы
            if (window.hasOwnProperty('Android')) {
              await Android.showNotification(notification.message)
            }
          });
    }
  },
}
</script>

<style>

</style>
