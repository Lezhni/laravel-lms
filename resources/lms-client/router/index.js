import Vue from "vue";
import VueRouter from "vue-router";
import store from '../store/index';
import middlewarePipeline from "./middlewarePipeline";

Vue.use(VueRouter);

// Подключение с Lazy routing

const Login = () => import('../views/Login.vue')
const Courses = () => import('../views/Courses.vue')
const Course = () => import('../views/Course.vue')
const Profile = () => import('../views/Profile.vue')
const Support = () => import('../views/Support.vue')
const CourseInfo = () => import('../views/course/CourseInfo.vue')
const CourseLesson = () => import('../views/course/CourseLesson.vue')
const CourseQuiz = () => import('../views/course/CourseQuiz.vue')
const CourseQuizComplete = () => import('../views/course/CourseQuizComplete.vue')
const CourseQuizResults = () => import('../views/course/CourseQuizResults.vue')
const CourseSchoolwork = () => import('../views/course/CourseSchoolwork.vue')
const CourseLessonList = () => import('../views/course/CourseLessonList.vue')
const SendEmail = () => import('../views/password/SendEmail.vue')
const NewPassword = () => import('../views/password/NewPassword.vue')
const CustomPage = () => import('../views/pages/CustomPage.vue')
const NotFound = () => import('../views/NotFound.vue')

import middlewareAuth from './middleware/middlewareAuth'
import middlewareNotAuth from './middleware/middlewareNotAuth'
import checkCompleteQuiz from './middleware/checkCompleteQuiz'

// Настройка роутинга. При смене URL в тэг <router-view/> компонента App выводится указанный
// Middleware - защитники роутов, отрабатывают ДО смены роута, тчо дает возможность определения, пускать/не пускать.

const index = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/password/send-email',
            name: 'send-email',
            component: SendEmail,
        },
        {
            path: '/password/reset/:token',
            name: 'reset',
            component: NewPassword,
        },
        {
            path: '/pages/:alias',
            name: 'custom-page',
            component: CustomPage,
            meta: {
                middleware:  [
                    middlewareAuth
                ]
            },
        },
        {
            path: '/404',
            name: '404',
            component: NotFound,
            meta: {
                middleware:  [
                    middlewareAuth
                ]
            },
        },
        {
            path: '/',
            name: 'main',
            redirect: '/login',
            meta: {
                middleware:  [
                    middlewareAuth
                ]
            },
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                middleware: [
                    middlewareNotAuth
                ]
            },
        },
        {
            path: '/support',
            name: 'support',
            component: Support,
            meta: {
                middleware: [
                    middlewareAuth
                ]
            },
        },
        {
            path: '/courses',
            name: 'courses',
            component: Courses,
            meta: {
                middleware: [
                    middlewareAuth
                ]
            },
        },
        {
            path: '/course/:id',
            component: Course,
            children: [
                {
                    path: '/',
                    component: CourseInfo,
                    name: 'course-info',
                    meta: {
                        middleware: [
                            middlewareAuth
                        ]
                    },
                },
                {
                    path: 'lesson/:lesson',
                    name: 'course-lesson',
                    component: CourseLesson,
                    meta: {
                        middleware: [
                            middlewareAuth
                        ]
                    },
                },
                {
                    path: 'lesson/:lesson/quiz/:quiz',
                    name: 'course-quiz',
                    component: CourseQuiz,
                    meta: {
                        middleware: [
                            middlewareAuth,
                            checkCompleteQuiz
                        ]
                    },
                },
                {
                    path: 'lesson/:lesson/quiz/:quiz/complete',
                    name: 'course-quiz-complete',
                    component: CourseQuizComplete,
                    meta: {
                        middleware: [
                            middlewareAuth
                        ]
                    },
                },
                {
                    path: 'lesson/:lesson/quiz/:quiz/results',
                    name: 'course-quiz-results',
                    component: CourseQuizResults,
                    meta: {
                        middleware: [
                            middlewareAuth
                        ]
                    },
                },
                {
                    path: 'lesson/:lesson/schoolwork',
                    name: 'course-schoolwork',
                    component: CourseSchoolwork,
                    meta: {
                        middleware: [
                            middlewareAuth
                        ]
                    },
                }
            ]
        },
        {
            path: '/course/:id/lessons',
            name: 'course-lessons',
            component: CourseLessonList,
            meta: {
                middleware: [
                    middlewareAuth
                ]
            },
        },
        {
            path: '/profile',
            name: 'profile',
            component: Profile,
            meta: {
                middleware: [
                    middlewareAuth
                ]
            },
        },
        {
            path: '*',
            name: 'some',
            component: NotFound,
            meta: {
                middleware:  [
                    middlewareAuth
                ]
            },
        }
    ]
})

index.beforeEach((to, from, next) => {
    const startMiddleware = () => {
        if (!to.meta.middleware) {
            return next()
        }
        const middleware = to.meta.middleware

        const context = {
            to,
            from,
            next,
            store
        }
        return middleware[0]({
            ...context,
            nextMiddleware: middlewarePipeline(context, middleware, 1)
        })
    }

    startMiddleware()
})

export default index
