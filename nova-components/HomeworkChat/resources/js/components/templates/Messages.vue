<template>
  <div class="w-full mb-8">
    <div
        class="message-box"
        v-for="item in messages"
        :key="item.id"
        :class="item.sender.id === messages[0].sender.id ? 'student-mess' : 'teacher-mess'"
    >
      <div class="message-block">
        <div v-html="item.message" class="mb-2"></div>
        <div
            v-for="(attachment, index) in item.attachments"
            :key="index"
            class="message-block-attachments mb-2"
        >
          <a :href="attachment.link" download class="message-block-attachments-link">
            Файл: <span class="message-block-attachments-file">({{attachment.filename}})</span>
          </a>
        </div>
        <span class="message-description">
          {{ item.sender.name }} / {{ item['created_at'] | filterDate }}
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    messages: {
      type: Array,
      required: false
    }
  },
  filters: {
    filterDate(date) {
      return new Date(date).toLocaleDateString('ru-RU', {
        year: "numeric",
        month: "numeric",
        day: "numeric"
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.student-mess {
  display: flex;
  justify-content: flex-start;

  .message {
    &-block {
      border-top-left-radius: 15px;
      border-bottom-right-radius: 15px;
      border-top-right-radius: 15px;
      background-color: #f4f7fa;

      &-attachments {
        &-link {
          text-decoration: none;
          color: #4099de;
          transition: color .3s;

          &:hover {
            color: darken(#4099de, 20%);

            .message-block-attachments-file {
              color: darken(#4099de, 20%)
            }
          }
        }
        &-file {
          font-size: 10px;
          color: #7c858e;
          transition: color .3s;
        }
      }
    }

    &-description {
      left: 5px;
    }

  }
}

.teacher-mess {
  display: flex;
  justify-content: flex-end;

  .message {
    &-block {
      border-top-left-radius: 15px;
      border-bottom-left-radius: 15px;
      border-top-right-radius: 15px;
      background-color: #f6f4ee;
      text-align: right;
    }

    &-description {
      right: 5px;
    }
  }
}

.message {
  &-box {
    position: relative;
  }

  &-block {
    max-width: 80%;
    border: 1px solid #bacad6;
    padding: 20px;
    color: #3c4b5f;
    box-shadow: 0 2px 4px 0 rgb(0 0 0 / 5%);
  }

  &-description {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    font-style: italic;
    color: #7c858e;
  }
}

.student-mess, .teacher-mess {
  &:not(:last-child) {
    margin-bottom: 20px;
  }
}
</style>