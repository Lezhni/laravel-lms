<template>
  <transition name="slide-fade" v-if="isShowNotifications">
    <div class="notifications">
      <div class="notifications-box">
        <vue-scroll>
          <div class="notifications-wrapper">
            <div
                class="notifications-item"
                v-for="notification in notifications"
                :key="notification.id"
            >
              <a
                  :class="notification.read ? 'read' : 'no-read'"
                  class="notifications-item__link"
                  v-if="notification.data.link"
                  :href="notification.data.link"
                  target="_blank"
              >
                <div v-html="notification.data.message"></div>
              </a>
              <div
                  :class="notification.read ? 'read' : 'no-read'"
                  class="notifications-item__span"
                  v-html="notification.data.message"
                  v-else
              ></div>

              <span
                  v-if="!notification.read"
                  @click="notificationsRead(notification.id)"
                  class="btn-close"
              >прочитать</span>

              <span v-else class="btn-close">прочитано</span>
              <span class="date">{{notification['created_at'] | formatDate}}</span>
            </div>
          </div>
        </vue-scroll>
      </div>
      <button class="notifications-button" @click="notificationsReadAll">
        <span>отметить все</span>
      </button>
    </div>
  </transition>
</template>

<script>
import axios from "axios";

export default {
  computed: {
    isShowNotifications() {
      return this.$store.getters['getIsShowNotifications']
    },
    notifications() {
      return this.$store.getters['getNotifications']
    },
  },
  methods: {
    async notificationsRead(id) {
      await axios.post(`/api/notifications/${id}`, {'_method': 'PUT'})
          .then(() => {
            this.$store.dispatch('setToggleReadNotifications', id)
          })
    },
    async notificationsReadAll() {
      await axios.post(`/api/notifications`, {'_method': 'PUT'})
          .then(() => {
            this.$store.dispatch('setToggleReadNotificationsAll')
          })
    }
  }
}
</script>

<style lang="scss" scoped>
.slide-fade-enter-active {
  transition: all .3s ease;
}

.slide-fade-leave-active {
  transition: all .3s ease;
  //transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

.slide-fade-enter, .slide-fade-leave-to
  /* .slide-fade-leave-active до версии 2.1.8 */
{
  transform: translateX(10px);
  opacity: 0;
}

</style>