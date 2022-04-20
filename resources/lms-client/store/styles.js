// хранилище информации store.
// если state подключен через getDefaultState -
// его можно легко вернуть к базовому состоянию методом Object.assign(state, getDefaultState())

// Самый сложный и не стабильный функционал - расчет высоты шапки и хлебных крошек для последующего расчета
// фиксированной высоты контейнера для кастомного скролла

export default {
    state: {
        headerHeight: 81,
        breadcrumbsHeight: 49
    },
    mutations: {
        setBreadcrumbsHeight(state, value) {
            state.breadcrumbsHeight = value
        },
        setHeaderHeight(state, value) {
            state.headerHeight = value
        },
    },
    actions: {
        setBreadcrumbsHeight({commit}, value) {
            commit('setBreadcrumbsHeight', value)
        },
        setHeaderHeight({commit}, value) {
            commit('setHeaderHeight', value)
        },
    },
    getters: {
        getBreadcrumbsHeight(state) {
            return state.breadcrumbsHeight
        },
        getHeaderHeight(state) {
            return state.headerHeight
        },
    }
}