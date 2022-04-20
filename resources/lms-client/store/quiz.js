// хранилище информации store.
// если state подключен через getDefaultState -
// его можно легко вернуть к базовому состоянию методом Object.assign(state, getDefaultState())

import {shuffle} from '../assets/js/helpers'

const getDefaultState = () => {
    return {
        quiz: {
            questions: []
        },
        currentQuiz: 0,
    }
}

export default {
    state: getDefaultState(),
    mutations: {
        resetQuizStore(state) {
            Object.assign(state, getDefaultState())
        },
        setLessonQuiz(state, data) {
            data.quiz.questions.map((question, idx) => {
                question.selected = []
                question.completed = false
                question.isCorrect = false
                question.index = idx
                return shuffle(question.answers)
            })

            state.quiz = data.quiz
        },
        setUpdateCurrentQuiz(state, val) {
            state.currentQuiz = val
        },
        setIncCurrentQuiz(state) {
            if (state.currentQuiz + 1 !== state.quiz.questions.length) {
                state.currentQuiz += 1
            }
        },
        setDecCurrentQuiz(state) {
            if (state.currentQuiz !== 0) {
                state.currentQuiz = state.currentQuiz - 1
            }
        },
    },
    actions: {
        resetQuizStore({commit}) {
            commit('resetQuizStore')
        },
        setLessonQuiz({commit}, data) {
            commit('setLessonQuiz', data)
        },
        setUpdateCurrentQuiz({commit}, val) {
            commit('setUpdateCurrentQuiz', val)
        },
        setIncCurrentQuiz({commit}) {
            commit('setIncCurrentQuiz')
        },
        setDecCurrentQuiz({commit}) {
            commit('setDecCurrentQuiz')
        },
    },
    getters: {
        getLessonQuiz(state) {
            return state.quiz
        },
        getCurrentQuiz(state) {
            return state.currentQuiz
        },
    }
}