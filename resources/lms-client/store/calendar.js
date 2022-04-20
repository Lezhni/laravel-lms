// хранилище информации store.
// если state подключен через getDefaultState -
// его можно легко вернуть к базовому состоянию методом Object.assign(state, getDefaultState())

const getDefaultState = () => {
    return {
        todos: []
    }
}

export default {
    state: getDefaultState(),
    mutations: {
        setCalendarData(state, data) {
            state.todos = data.events
        },
    },
    actions: {
        setCalendarData({commit}, data) {
            commit('setCalendarData', data)
        },
    },
    getters: {
        getTodos(state) {
            return state.todos
        },
    }
}