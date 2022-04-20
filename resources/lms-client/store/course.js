// хранилище информации store.
// если state подключен через getDefaultState -
// его можно легко вернуть к базовому состоянию методом Object.assign(state, getDefaultState())

const getDefaultState = () => {
    return {
        course: {
            lessons: []
        }
    }
}

export default {
    state: getDefaultState(),
    mutations: {
        setCourseData(state, data) {
            state.course = data.course
        },
        setResetCourse(state) {
            Object.assign(state, getDefaultState())
        },
    },
    actions: {
        setCourseData({commit}, data) {
            commit('setCourseData', data)
        },
        setResetCourse({commit}) {
            commit('setResetCourse')
        },
    },
    getters: {
        getCourseInfo(state) {
            return state.course
        },
        getCourseLessons(state) {
            return state.course.lessons
        },
    }
}