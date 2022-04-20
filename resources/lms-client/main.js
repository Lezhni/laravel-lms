// Основной файл приложения. Входная точка. В данном файле импортируются все библиотеки и конфигурации
// для последующего использования в компонентах

// в конце данного файла экспортируется app для последующего использования компонента приложения
// в файлах /assets/js/

import Vue from 'vue'
import App from './App.vue' // Основной входной контейнер приложения, все компоненты выведены в нем
import router from './router'
import store from './store'
import './filters'

import Echo from "laravel-echo"
import Pusher from "pusher-js"
import axios from "axios";
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import Embed from 'v-video-embed'
import VueQuillEditor from 'vue-quill-editor'
import Calendar from 'v-calendar/lib/components/calendar.umd'
import DatePicker from 'v-calendar/lib/components/date-picker.umd'

import Auth from './assets/js/auth.js';
import vueScroll from 'vuescroll';
import vueScrollConfig from './assets/js/vuescroll.config.js' // кастомный скролл, настраивается в отдельном файле
import echoConfig from './assets/js/echo.config.js' // настройка подключения уведомлений (sockets)

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'
import './assets/js/eventBuses' // настройка тоннеля слушателя. Доступен в глобальном объекте window в каждом компоненте
import './assets/scss/main.scss'

Vue.component('calendar', Calendar)
Vue.component('date-picker', DatePicker)

Vue.use(vueScroll, vueScrollConfig);
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(Embed);
Vue.use(VueQuillEditor)

window.axios = axios;
window.Pusher = Pusher;
window.Echo = new Echo(echoConfig);
window.auth = new Auth();

Vue.config.productionTip = false

const app = new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app') // вывод приложения на страницу, файл /resources/views/template.blade.php

export default app


