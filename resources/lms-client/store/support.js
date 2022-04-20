// хранилище информации store.
// если state подключен через getDefaultState -
// его можно легко вернуть к базовому состоянию методом Object.assign(state, getDefaultState())

const getDefaultState = () => {
    return {
        categories: []
    }
}

export default {
    state: getDefaultState(),
    mutations: {
        setSupportsData(state, data) {
            state.categories = data.categories
        },
    },
    actions: {
        setSupportsData({commit}, data) {
            commit('setSupportsData', data)
        },
    },
    getters: {
        getCategories(state) {
            return state.categories
        },
    }
}