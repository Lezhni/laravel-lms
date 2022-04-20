// хранилище информации store.
// если state подключен через getDefaultState -
// его можно легко вернуть к базовому состоянию методом Object.assign(state, getDefaultState())

const getDefaultState = () => {
    return {
        activeCourses: [],
        pastCourses: [],
        searchStr: ''
    }
}

export default {
    state: getDefaultState(),
    mutations: {
        setCoursesData(state, data) {
            state.activeCourses = data.activeCourses
            state.pastCourses = data.pastCourses
        },
        setSearchStr(state, value) {
            state.searchStr = value
        },
    },
    actions: {
        setCoursesData({commit}, data) {
            commit('setCoursesData', data)
        },
        setSearchStr({commit}, value) {
            commit('setSearchStr', value)
        },
    },
    getters: {
        getActiveCourses(state) {
            // return state.searchStr ? state.activeCourses.filter(course => {
            //     return course.name.toLowerCase().includes(state.searchStr.toLowerCase())
            // }) : state.activeCourses
            return state.searchStr !== '' ? [...state.activeCourses, ...state.pastCourses].filter(course => {
                return course.name.toLowerCase().includes(state.searchStr.toLowerCase())
            }) : state.activeCourses
        },
        getPastCourses(state) {
            // return state.searchStr ? state.pastCourses.filter(course => {
            //     return course.name.toLowerCase().includes(state.searchStr.toLowerCase())
            // }) : state.pastCourses
            return state.searchStr !== '' ? [...state.activeCourses, ...state.pastCourses].filter(course => {
                return course.name.toLowerCase().includes(state.searchStr.toLowerCase())
            }) : state.pastCourses
        },
        getSearchStr(state) {
            return state.searchStr
        },
    }
}