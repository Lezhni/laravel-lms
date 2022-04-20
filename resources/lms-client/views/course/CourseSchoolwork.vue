<template>
  <div class="w-100 pb-5">

    <breadcrumbs
        :links="[{link: '/', text: 'Главная'}, {link: `/course/${courseInfo.id}`, text: courseInfo.name}]"
        :active="schoolwork.lesson.name"
    />

    <scrolled-content>
      <b-container :fluid="true" class="pb-4">
        <div class="course-schoolwork px-0 px-md-3">

          <mobile-links/>

          <b-row class="pt-4">
            <b-col>
              <page-title :title="schoolwork.lesson.name" :bordered="true"/>
            </b-col>
          </b-row>

          <b-row>
            <b-col>
              <hr class="mb-4 mt-4">
            </b-col>
          </b-row>

          <content-block
              :content="schoolwork.content"
          />

          <b-row class="mb-4">
            <b-col>
              <content-comment
                  v-for="message in schoolwork.messages"
                  :message="message"
                  :key="message.id"
              />
            </b-col>
          </b-row>

          <b-row class="mb-3">
            <b-col>
              <quill-editor
                  ref="myQuillEditor"
                  v-model="content"
                  :options="editorOption"
              />
            </b-col>
          </b-row>

          <b-row>
            <b-col class="d-flex justify-content-between flex-column flex-sm-row">
              <input
                  placeholder="отредактируй меня"
                  type="file"
                  @change="previewFiles"
                  class="mb-3 mb-sm-0 w-100"
                  multiple
                  ref="inputFiles"
              >
              <b-button
                  variant="warning"
                  @click="quillSubmit"
              >Отправить
              </b-button>
            </b-col>
          </b-row>
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
import ContentComment from "../../components/UI/content/ContentComment";
import ScrolledContent from "../../components/layouts/ScrolledContent";
import Breadcrumbs from "../../components/UI/Breadcrumbs";
import FooterBlock from "../../components/FooterBlock";
import MobileLinks from "../../components/UI/MobileLinks";

export default {
  components: {ContentBlock, ContentComment, PageTitle, ScrolledContent, Breadcrumbs, FooterBlock, MobileLinks},
  data() {
    return {
      files: [],
      maxScore: 25,
      score: 14,
      content: '',
      editorOption: {
        modules: {
          toolbar: [
            ['bold', 'italic', 'underline', 'strike'],
            [{'header': 1}, {'header': 2}],
            [{'list': 'ordered'}, {'list': 'bullet'}],
            ['link'],
            ['clean']
          ]
        },
        placeholder: 'Оставьте комментарий',
      }
    }
  },
  computed: {
    schoolwork() {
      return this.$store.getters["getSchoolwork"]
    },
    courseInfo() {
      return this.$store.getters["getCourseInfo"]
    },
  },
  methods: {
    submitSubmit(e) {

    },
    previewFiles(event) {
      this.files = event.target.files
    },
    quillSubmit() {
      const data = new FormData();

      for(let key in this.files) {
        if (this.files.hasOwnProperty(key)) {
          data.append("attachments[]", this.files[key])
        }
      }

      data.append("message", this.content)

      axios.post(`/api/course/${this.$route.params.id}/lesson/${this.$route.params.lesson}/homework/message`, data)
          .then(() => {
            this.files = []
            this.content = ''
            this.$refs.inputFiles.value = ''
            this.setDataReload()
          })
          .catch(error => {
            for (let field in error.response.data.errors) {
              if (error.response.data.errors.hasOwnProperty(field)) {
                eventBus.$emit('errorMess', error.response.data.errors[field][0], error.response.status);
              }
            }
          })
    },
    setData(schoolwork) {
      this.$store.dispatch('setSchoolwork', schoolwork)
      this.$store.dispatch('setContentLoading', false)
    },
    async setDataReload() {
      await axios.get(`/api/course/${this.$route.params.id}/lesson/${this.$route.params.lesson}/homework`).then(res => {
        this.$store.dispatch('setSchoolwork', res.data)
      })
    }
  },
  beforeRouteEnter(to, from, next) {
    // Метод жизненного цикла beforeRouteEnter отрабатывает до смены url
    // Сперва идет запрос на сервер, получаем данные, и только после метод next()
    // позволяет сменить роут и страница рендерится с уже готовыми данными
    const getSchoolwork = async () => {
      await axios.get(`/api/course/${to.params.id}/lesson/${to.params.lesson}/homework`).then(res => {
        next(vm => vm.setData(res.data))
      })
    }
    getSchoolwork()
  },
  beforeRouteUpdate(to, from, next) {
    // Метод жизненного цикла beforeRouteUpdate при обновлении страницы
    // Сперва идет запрос на сервер, получаем данные, и только после метод next()
    // позволяет сменить роут и страница рендерится с уже готовыми данными
    axios.get(`/api/course/${to.params.id}/lesson/${to.params.lesson}/homework`).then(res => {
      this.$store.dispatch('setSchoolwork', res.data)
      this.$store.dispatch('setContentLoading', false)
      next()
    }).catch(error => {
      eventBus.$emit('errorMess', error.response.data.message, error.response.status);
    })

  },
}
</script>

<style scoped>

</style>