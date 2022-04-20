<template>
  <scrolled-wrapper>
    <section class="courses">
      <div class="courses-wrapper">
        <b-container :fluid="false" class="pb-4">
          <page-title title="Мои курсы" :bordered="false" class="pt-4 pb-4"/>

          <b-row>
            <b-col>
              <b-card no-body class="courses-card">
                <b-form-input
                    class="courses-search"
                    v-model="searchStr"
                    size="lg"
                    placeholder="Поиск"
                ></b-form-input>
                <b-tabs card nav-wrapper-class="bg-transparent" pills>
                  <b-tab title="Текущие курсы" active title-item-class="courses-tab-button">
                    <CoursesCard
                        v-for="course in activeCourses"
                        :key="course.id"
                        :course="course"
                    />
                    <h4 class="text-center" v-if="activeCourses.length === 0">Курсов нет</h4>
                  </b-tab>
                  <b-tab title="Архив" title-item-class="courses-tab-button">
                    <CoursesCard
                        v-for="course in pastCourses"
                        :key="course.id"
                        :course="course"
                    />
                    <h4 class="text-center" v-if="pastCourses.length === 0">Курсов нет</h4>
                  </b-tab>
                </b-tabs>
              </b-card>
            </b-col>
          </b-row>
        </b-container>
      </div>
    </section>

    <footer-block/>
  </scrolled-wrapper>
</template>

<script>
import PageTitle from "../components/UI/PageTitle";
import CoursesCard from "../components/UI/CoursesCard";
import ScrolledWrapper from "../components/layouts/ScrolledWrapper";
import FooterBlock from "../components/FooterBlock";
import axios from 'axios'

export default {
  components: {PageTitle, CoursesCard, ScrolledWrapper, FooterBlock},
  data() {
    return {
      courses: {}
    }
  },
  computed: {
    activeCourses() {
      return this.$store.getters["getActiveCourses"]
    },
    pastCourses() {
      return this.$store.getters["getPastCourses"]
    },
    searchStr: {
      get() {
        return this.$store.getters["getSearchStr"]
      },
      set(value) {
        this.$store.dispatch('setSearchStr', value)
      }
    }
  },
  methods: {
    setData(courses) {
      this.$store.dispatch('setCoursesData', courses)
      this.$store.dispatch('setResetCourse')
      this.$store.dispatch('resetQuizStore')
      this.$store.dispatch('setResetSchoolwork')
      this.$store.dispatch('setContentLoading', false)
    }
  },
  beforeRouteEnter(to, from, next) {
    // Метод жизненного цикла beforeRouteEnter отрабатывает до смены url
    // Сперва идет запрос на сервер, получаем данные, и только после метод next()
    // позволяет сменить роут и страница рендерится с уже готовыми данными
    const getCourses = async () => {
      await axios.get('/api/dashboard').then(res => {
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