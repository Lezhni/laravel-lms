// Входящий файл внутреннего store. Все отдельные контейнеры объединяются в один и становится доступен
// в каждом компоненте из контекста (this)

// Использование:
// запустить action - this.$store.dispatch('setCoursesData', courses)
// this.$store.dispatch метод принимает название сеттера и данные которые передаются в сеттер
// запустить getter - this.$store.getters["getSearchStr"]
// this.$store.getters принимает аргументом название геттера

import Vue from 'vue';
import Vuex from 'vuex';

import auth from './auth'
import common from './common'
import courses from './courses'
import course from './course'
import profile from './profile'
import lesson from './lesson'
import quiz from './quizes'
import calendar from './calendar'
import schoolwork from './schoolwork'
import support from './support'
import notifications from './notifications'
import customPage from "./customPage";
import styles from './styles'

Vue.use(Vuex);

export default new Vuex.Store({
    modules : {
        auth,
        common,
        courses,
        course,
        profile,
        lesson,
        quiz,
        calendar,
        schoolwork,
        support,
        notifications,
        customPage,
        styles
    }
})
