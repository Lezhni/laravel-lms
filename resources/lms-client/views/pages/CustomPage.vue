<template>
  <div class="w-100">

    <breadcrumbs
        :links="[{link: '/', text: 'Главная'}]"
        :active="content.name"
    />

    <scrolled-content>
      <b-container class="pt-5 pb-5">
        <PageTitle :title="content.name"/>

        <content-block
            :content="content.content"
        />

      </b-container>

      <footer-block/>
    </scrolled-content>
  </div>
</template>

<script>
import PageTitle from "../../components/UI/PageTitle";
import ContentBlock from "../../components/UI/ContentBlock";
import ScrolledContent from "../../components/layouts/ScrolledContent";
import Breadcrumbs from "../../components/UI/Breadcrumbs";
import FooterBlock from "../../components/FooterBlock";
import axios from "axios";

export default {
  components: {PageTitle, ContentBlock, ScrolledContent, Breadcrumbs, FooterBlock},
  computed: {
    content() {
      return this.$store.getters["getCustomPageContent"]
    },
  },
  methods: {
    setData(page) {
      this.$store.dispatch('setCustomPageData', page)
      this.$store.dispatch('setContentLoading', false)
    }
  },
  beforeRouteEnter(to, from, next) {
    // Метод жизненного цикла beforeRouteEnter отрабатывает до смены url
    // Сперва идет запрос на сервер, получаем данные, и только после метод next()
    // позволяет сменить роут и страница рендерится с уже готовыми данными
    const getPage = async () => {
      await axios.get(`/api/pages/${to.params.alias}`).then(res => {
        next(vm => vm.setData(res.data))
      }).catch(error => {
        next('404')
        eventBus.$emit('errorMess', error.response.data.message, error.response.status);
      })
    }
    getPage()
  },
}
</script>

<style scoped>

</style>