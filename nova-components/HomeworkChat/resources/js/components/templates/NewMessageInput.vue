<template>
  <form
      @submit="submitMessage"
      class="flex flex-col"
  >
    <input id="x" type="hidden" name="content" ref="inpMess" required>
    <trix-editor
        placeholder="Введите текст сообщения"
        class="mb-8"
        input="x"
        ref="trix"
    ></trix-editor>
    <button
        type="submit"
        class="btn btn-default btn-primary self-end"
    >
      Отправить
    </button>
  </form>
</template>

<script>
export default {
  props: {
    getData: {
      type: Function,
      required: true
    },
    field: {
      type: Object,
      required: true
    }
  },
  methods: {
    submitMessage(e) {
      e.preventDefault()

      const value = this.$refs.inpMess.value

      if(value !== '') {
        Nova.request().post('/nova-vendor/homework-chat/messages', {
          message: value,
          grade_id: this.field['gradeId']
        }).then((res) => {
          this.getData()
          this.$refs.inpMess.value = ""
          this.$refs.trix.textContent = ""
          Nova.success(res.data.message)
        }).catch(e => {
          // Nova.error(e.response.data.message)
        })
      }
    }
  }
}
</script>

<style lang="scss" scoped>

</style>