<template>
  <div class="w-100">

    <breadcrumbs
        :links="[{link: '/', text: 'Главная'}]"
        :active="courseInfo.name"
    />

    <scrolled-content>
      <b-container :fluid="true" class="pb-4">
        <div class="course px-0 px-md-3">

          <mobile-links/>

          <b-row class="pt-3">
            <b-col cols="12" md="6" class="mb-3 mb-md-0">
              <div class="course-title__main d-flex align-items-center">
                <div class="course-title__main-avatar">
                  <img :src="courseInfo.image" alt="anna">
                </div>
                <div class="course-title__main-descr d-flex flex-column justify-content-between">
                  <div class="course-title__main-name h4 md-h5">{{ courseInfo.name }}</div>
                  <div class="course-title__main-author-name" v-if="courseInfo['teacher']">
                    {{ courseInfo['teacher'].name }}
                  </div>
                </div>
              </div>
            </b-col>
            <b-col cols="12" md="6">
              <courses-item-description-date
                  icon="clarity_clock-solid.svg?v=1"
                  text="Период проведения:"
                  :started_at="courseInfo['started_at']"
                  :finished_at="courseInfo['finished_at']"
                  class="mb-2"
              />
              <courses-item-description
                  icon="lessons.svg?v=1"
                  text="Занятий:"
                  :value="courseInfo.lessons.length"
              />
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <hr class="mb-4 mt-4">
            </b-col>
          </b-row>

          <content-block
              :content="courseInfo.content"
          />

          <attachments-categories
              v-if="courseInfo.attachmentsCategories && courseInfo.attachmentsCategories.length !== 0"
              :categories="courseInfo.attachmentsCategories"
          />

        </div>
      </b-container>

      <footer-block/>
    </scrolled-content>
  </div>
</template>

<script>
import ContentBlock from "../../components/UI/ContentBlock";
import CoursesItemDescription from "../../components/UI/CoursesItemDescription";
import ScrolledContent from "../../components/layouts/ScrolledContent";
import Breadcrumbs from "../../components/UI/Breadcrumbs";
import CoursesItemDescriptionDate from "../../components/UI/CoursesItemDescriptionDate";
import PageTitle from "../../components/UI/PageTitle";
import FooterBlock from "../../components/FooterBlock";
import MobileLinks from "../../components/UI/MobileLinks";
import AttachmentsCategories from "../../components/UI/AttachmentsCategories";

export default {
  components: {
    ContentBlock,
    CoursesItemDescription,
    ScrolledContent,
    Breadcrumbs,
    CoursesItemDescriptionDate,
    PageTitle,
    FooterBlock,
    MobileLinks,
    AttachmentsCategories
  },
  computed: {
    courseInfo() {
      return this.$store.getters["getCourseInfo"]
    },
  },
  mounted() {
    this.$store.dispatch('setContentLoading', false)
  }
}
</script>

<style lang="scss" scoped>
.course-info-content {
  .block-image {
    width: auto;
    max-width: 100%;
  }
}
</style>