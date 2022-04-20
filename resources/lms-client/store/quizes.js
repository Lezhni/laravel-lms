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
        isCompleteQuiz: false,
        allQuestions: 0,
        correctQuestions: 0
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
                question.variant = "secondary-light"
                question.isCorrect = false
                question.completed = false
                question.idx = idx
                return shuffle(question.answers)
            })

            state.quiz = data.quiz
        },
        setIncCurrentQuiz(state) {
            state.quiz.questions[state.currentQuiz].completed = true

            if (state.currentQuiz + 1 !== state.quiz.questions.length) {
                state.currentQuiz += 1
            }
        },
        setDecCurrentQuiz(state) {
            if (state.currentQuiz !== 0) {
                state.currentQuiz = state.currentQuiz - 1
            }
        },
        setUpdateCurrentQuiz(state, val) {
            state.currentQuiz = val
        },
        setUpdateProgressQuiz(state, obj) {
            state.quiz.questions[obj.idxQuestion].variant = obj.isCorrect ? "success" : "danger"
            state.quiz.questions[obj.idxQuestion].isCorrect = obj.isCorrect
        },
        setIsCompleteQuiz(state, val) {
            state.isCompleteQuiz = val
        },
        setShortResultQuiz(state, data) {
            state.allQuestions = data.quiz.questions.length
            state.correctQuestions = data.quiz.questions.filter(item => {
                let result = true

                item.answers.forEach(answer => {
                    if (answer['is_correct'] && answer['is_correct'] !== answer['selected_by_student']) {
                        result = false
                    }
                })

                return result
            }).length
        },
    },
    actions: {
        resetQuizStore({commit}) {
            commit('resetQuizStore')
        },
        setLessonQuiz({commit}, data) {
            commit('setLessonQuiz', data)
        },
        setShortResultQuiz({commit}, data) {
            commit('setShortResultQuiz', data)
        },
        setIncCurrentQuiz({commit}) {
            commit('setIncCurrentQuiz')
        },
        setDecCurrentQuiz({commit}) {
            commit('setDecCurrentQuiz')
        },
        setUpdateCurrentQuiz({commit}, val) {
            commit('setUpdateCurrentQuiz', val)
        },
        setUpdateProgressQuiz({commit}, obj) {
            commit('setUpdateProgressQuiz', obj)
        },
        setIsCompleteQuiz({commit}, val) {
            commit('setIsCompleteQuiz', val)
        },
    },
    getters: {
        getLessonQuiz(state) {
            return state.quiz
        },
        getCurrentQuiz(state) {
            return state.currentQuiz
        },
        getIsCompleteQuiz(state) {
            return state.isCompleteQuiz
        },
        getSuccessQuestions(state) {
            return state.quiz.questions
        },
        getCompletedQuiz(state) {
            return state.quiz.questions.filter(question => !question.completed)
        },
        getAllQuestions(state) {
            return state.allQuestions
        },
        getCorrectQuestions(state) {
            return state.correctQuestions
        }
    }
}