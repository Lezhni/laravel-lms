// хранилище информации store.
// если state подключен через getDefaultState -
// его можно легко вернуть к базовому состоянию методом Object.assign(state, getDefaultState())

const getDefaultState = () => {
    return {
        lesson: {}
    }
}

export default {
    state: getDefaultState(),
    mutations: {
        setCourseLesson(state, data) {
            state.lesson = data.lesson
        }
    },
    actions: {
        setCourseLesson({commit}, data) {
            commit('setCourseLesson', data)
        }
    },
    getters: {
        getCourseLesson(state) {
            return state.lesson
        },
    }
}