<template>
  <section class="support">

    <breadcrumbs
        :links="[{link: '/', text: 'Главная'}]"
        active="Поддержка слушателей"
    />

    <scrolled-content>
      <b-container :fluid="false">
        <b-row class="pt-5 pb-5">
          <b-col>
            <div class="support-accordion accordion" role="tablist">
              <b-card no-body class="support-card mb-1" v-for="category in categories" :key="category.id">
                <b-card-header header-class="support-header" header-tag="header" class="p-0" role="tab">
                  <b-button class="text-left support-tab" block v-b-toggle="`accordion-${category.id}`"
                            variant="light">
                    {{ category.name }}
                  </b-button>
                </b-card-header>

                <b-collapse :id="`accordion-${category.id}`" accordion="my-accordion" role="tabpanel">
                  <b-card-body>
                    <b-card-text v-for="page in category.pages" :key="page.id">
                      <a :href="`/pages/${page.alias}`" target="_blank">{{ page.name }}</a>
                    </b-card-text>
                  </b-card-body>
                </b-collapse>
              </b-card>

            </div>
          </b-col>
        </b-row>
      </b-container>

      <footer-block/>
    </scrolled-content>
  </section>
</template>

<script>
import ScrolledContent from "../components/layouts/ScrolledContent";
import Breadcrumbs from "../components/UI/Breadcrumbs";
import FooterBlock from "../components/FooterBlock";
import axios from "axios";

export default {
  components: {ScrolledContent, Breadcrumbs, FooterBlock},
  computed: {
    categories() {
      return this.$store.getters['getCategories']
    }
  },
  methods: {
    setData(pages) {
      this.$store.dispatch('setSupportsData', pages)
      this.$store.dispatch('setContentLoading', false)
    }
  },
  beforeRouteEnter(to, from, next) {
    // Метод жизненного цикла beforeRouteEnter отрабатывает до смены url
    // Сперва идет запрос на сервер, получаем данные, и только после метод next()
    // позволяет сменить роут и страница рендерится с уже готовыми данными
    const getCourses = async () => {
      await axios.get('/api/pages').then(res => {
        next(vm => vm.setData(res.data))
      }).catch(error => {
        eventBus.$emit('errorMess', error.response.data.message, error.response.status);
      })
    }
    getCourses()
  },
}
</script>

<style scoped>

</style>