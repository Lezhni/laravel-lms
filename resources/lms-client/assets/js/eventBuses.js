import Vue from 'vue'
import app from '../../main.js'

const eventBus = new Vue()
window.eventBus = eventBus

// обработка кастомных событий. switch/case обрабатывает статусы ошибок и выводит уведомления пользователю
// используется в компонентах и доступен в глобальном объекте.
// Пример - eventBus.$emit('errorMess', 'Профиль обновлен', res.status);

eventBus.$on('errorMess', (message, errorStatus) => {
    switch (errorStatus) {
        case 200:
            app.$bvToast.toast(message, {
                title: 'Успешно',
                toaster: 'b-toaster-bottom-right',
                solid: true,
                variant: 'success',
                appendToast: true
            })

            app.$store.dispatch('setContentLoading', false)
            break
        case 403:
        case 404:
            app.$bvToast.toast(message, {
                title: 'Ошибка',
                toaster: 'b-toaster-bottom-right',
                solid: true,
                variant: 'danger',
                appendToast: true
            })

            app.$store.dispatch('setContentLoading', false)
            break
        case 422:
            app.$bvToast.toast(message, {
                title: 'Ошибка',
                toaster: 'b-toaster-bottom-right',
                solid: true,
                variant: 'warning',
                appendToast: true
            })

            app.$store.dispatch('setContentLoading', false)
            break


    }
});

eventBus.$on('sendNotify', (message, variant) => {
    app.$bvToast.toast(message, {
        title: 'Новое уведомление',
        toaster: 'b-toaster-bottom-right',
        solid: true,
        variant: variant,
        appendToast: true
    })
});