<template>
  <b-card no-body class="course-schoolwork-comment mb-3">
    <b-card-body>
      <b-card-text>
        <div class="course-schoolwork-comment__item">
          <div class="course-schoolwork-comment__item--avatar">
            <img :src="message.sender['avatarUrl'] || '/assets/images/empty_profile.webp'"
                 alt="empty_profile">
          </div>
          <div class="course-schoolwork-comment__item--block">
            <div class="course-schoolwork-comment__item--block--name">
              <h6>{{ message.sender.name }}</h6>
            </div>
            <div class="course-schoolwork-comment__item--block--content" v-html="message.message"></div>
            <div
                class="course-schoolwork-comment__item--block--content"
                v-for="attach in message.attachments"
            >
              <a :href="attach.link" target="_blank">{{ attach.filename }}</a>
            </div>
          </div>
        </div>
      </b-card-text>
    </b-card-body>
  </b-card>
</template>

<script>
export default {
  props: {
    message: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped>

</style>