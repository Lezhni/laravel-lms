<template>
  <div class="w-100">

    <breadcrumbs
        :links="[{link: '/', text: 'Главная'}, {link: `/course/${courseInfo.id}`, text: courseInfo.name}]"
        :active="lesson.name"
    />

    <scrolled-content>
      <b-container :fluid="true" class="pb-4">
        <div class="course-lesson px-0 px-md-3">

          <mobile-links/>

          <b-row class="pt-4">
            <b-col>
              <page-title :title="lesson.name" :bordered="true"/>
            </b-col>
          </b-row>

          <b-row class="pt-3" v-if="lesson.teacher">
            <b-col class="d-flex align-items-center">
              <img :src="`/assets/images/icons/teacher.svg`" class="course-lesson-teacher-icon mr-2 mr-md-3" :alt="`Занятие проведет трейдер ${lesson.teacher.name}`" >
              <p class="course-lesson-teacher-name">Занятие проведет трейдер <b>{{ lesson.teacher.name }}</b></p>
            </b-col>
          </b-row>

          <b-row>
            <b-col>
              <hr class="mb-4 mt-4">
            </b-col>
          </b-row>

          <b-row class="mb-4">
            <b-col cols="12" lg="12" xl="9">
              <div class="course-lesson-video-wrapper" v-if="lesson['record_link']">
                <video-title :title="lesson['record_name']" fontSize="24px" :bordered="false"/>
                <video-embed v-if="lesson['record_type'] === 'player'"
                             :src="lesson['record_link']"></video-embed>
                <div v-else-if="lesson['record_type'] === 'iframe'"
                     class="iframe-wrapper">
                  <iframe :src="lesson['record_link']" allowfullscreen=""></iframe>
                </div>
              </div>
            </b-col>
          </b-row>

          <content-block
              :content="lesson.content"
          />

          <attachments-categories
              v-if="lesson.attachmentsCategories && lesson.attachmentsCategories.length !== 0"
              :categories="lesson.attachmentsCategories"
          />

        </div>
      </b-container>

      <footer-block/>
    </scrolled-content>
  </div>
</template>


<script>
import axios from "axios";
import ContentBlock from "../../components/UI/ContentBlock";
import PageTitle from "../../components/UI/PageTitle";
import VideoTitle from "../../components/UI/VideoTitle";
import ScrolledContent from "../../components/layouts/ScrolledContent";
import Breadcrumbs from "../../components/UI/Breadcrumbs";
import FooterBlock from "../../components/FooterBlock";
import MobileLinks from "../../components/UI/MobileLinks";
import AttachmentsCategories from "../../components/UI/AttachmentsCategories";

export default {
  components: {
    ContentBlock,
    PageTitle,
    ScrolledContent,
    Breadcrumbs,
    FooterBlock,
    VideoTitle,
    MobileLinks,
    AttachmentsCategories,
  },
  computed: {
    lesson() {
      return this.$store.getters["getCourseLesson"]
    },
    courseInfo() {
      return this.$store.getters["getCourseInfo"]
    },
  },
  methods: {
    setData(attachments) {
      this.$store.dispatch('setCourseLesson', attachments)
      this.$store.dispatch('setContentLoading', false)
    }
  },
  beforeRouteEnter(to, from, next) {
    const getAttachments = async () => {
      await axios.get(`/api/course/${to.params.id}/lesson/${to.params.lesson}`).then(res => {
        next(vm => vm.setData(res.data))
      }).catch(error => {
        eventBus.$emit('errorMess', error.response.data.message, error.response.status);
      })
    }
    getAttachments()
  },
  beforeRouteUpdate(to, from, next) {
    // Метод жизненного цикла beforeRouteEnter отрабатывает до смены url
    // Сперва идет запрос на сервер, получаем данные, и только после метод next()
    // позволяет сменить роут и страница рендерится с уже готовыми данными
    axios.get(`/api/course/${to.params.id}/lesson/${to.params.lesson}`).then(res => {
      this.$store.dispatch('setCourseLesson', res.data)
      this.$store.dispatch('setContentLoading', false)
      next()
    }).catch(error => {
      eventBus.$emit('errorMess', error.response.data.message, error.response.status);
    })
  }
}
</script>

<style lang="scss" scoped>

</style>