<template>
  <div class="flex flex-row">
    <div class="px-8 py-6 w-1/5">
      <span class="inline-block text-80 pt-2 leading-tight mb-2">
        Сообщения слева - студент, сообщения справа - преподаватели
      </span>
    </div>

    <div class="custom-form-messages flex flex-col border-b border-40 p-8 w-1/2">
      <messages :messages="messages"/>
      <new-message-input :getData="getData" :field="field"/>
    </div>
  </div>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'
import Messages from "./templates/Messages";
import NewMessageInput from "./templates/NewMessageInput";

// Nova.request().post();

export default {
  data() {
    return {
      messages: []
    }
  },
  mixins: [FormField, HandlesValidationErrors],
  components: {Messages, NewMessageInput},
  props: ['resourceName', 'resourceId', 'field'],

  created() {
    this.getData()
  },
  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    async getData() {
     await Nova.request().get(`/nova-vendor/homework-chat/messages`,
          {params: {grade: this.field['gradeId']}}
      ).then(res => {
        this.messages = res.data.messages
      })
    },
    setInitialValue() {
      this.value = this.field.value || ''
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, this.value || '')
    },
  },
}
</script>
