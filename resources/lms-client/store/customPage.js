// хранилище информации store.
// если state подключен через getDefaultState -
// его можно легко вернуть к базовому состоянию методом Object.assign(state, getDefaultState())

const getDefaultState = () => {
    return {
        content: {
            name: ''
        },
    }
}

export default {
    state: getDefaultState(),
    mutations: {
        setCustomPageData(state, data) {
            state.content = data.page
        },
    },
    actions: {
        setCustomPageData({commit}, data) {
            commit('setCustomPageData', data)
        },
    },
    getters: {
        getCustomPageContent(state) {
            return state.content
        },
    }
}