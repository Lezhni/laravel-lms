<template>
  <b-modal
      id="calendar-modal"
      centered
      hide-footer
      title="Расписание занятий"
  >
    <calendar
        :attributes='attributes'
        class="calendar"
    >
      <template #day-popover="{ day, format, masks, attributes }">
        <vue-scroll>
          <div class="calendar-popup-wrapper">

              <div
                  v-for="attr in attributes"
                  :key="attr.key"
                  @click="redirectToLesson(attr.customData.link)"
                  class="calendar-popup-item"
              >
                <div class="calendar-popup-item__date">
                  <h6 class="calendar-popup-item__date--day m-0">
                    {{
                      new Date(attr.customData.date).toLocaleString("ru", {
                        month: 'numeric',
                        day: 'numeric',
                        timezone: 'UTC'
                      })
                    }}
                  </h6>
                  <span class="calendar-popup-item__date--time">
                  {{
                      new Date(attr.customData.date).toLocaleString("ru", {
                        hour: 'numeric',
                        minute: 'numeric',
                        timezone: 'UTC'
                      })
                    }}
                </span>
                </div>
                <div class="calendar-popup-item__title">
                  <h6 class="calendar-popup-item__title--name mb-1">
                    {{ attr.customData.title | slicer(20) }}
                  </h6>
                  <p v-if="attr.customData.teacher" class="calendar-popup-item__title--teacher mb-2">{{ attr.customData.teacher }}</p>
                  <span class="calendar-popup-item__title--link">
                  {{ attr.customData.link.title | slicer(30) }}
                </span>
                </div>
              </div>

          </div>
        </vue-scroll>
      </template>
    </calendar>
  </b-modal>
</template>

<script>
// Второй по сложности компонент приложения. Метод attributes принимает массив всех заданий, перебирает их
// и создает новый массив нужно структуры

export default {
  computed: {
    todos() {
      return this.$store.getters["getTodos"]
    },
    attributes() {
      return [
        ...this.todos.map(todo => ({
          dates: new Date(todo.date),
          date: todo.date,
          highlight: {
            color: 'orange',
            fillMode: 'solid',
            class: 'calendar-date-highlight',
            contentClass: 'calendar-date-item',
          },
          popover: {
            label: todo.description,
            visibility: 'hover'
          },
          customData: todo,
        })),
      ];
    },
  },
  methods: {
    redirectToLesson(link) {
      // Метод перехода на страницу нужного задания
      this.$router.push(`/course/${link.url.course_id}/lesson/${link.url.lesson_id}`)
      this.$bvModal.hide('calendar-modal')
    }
  }
}
</script>

<style lang="scss" scoped>
.vc-container {
  width: 100%;
}

.vc-popover-content {
  border-left: 10px solid red;
  border-radius: 10px;
}
</style>