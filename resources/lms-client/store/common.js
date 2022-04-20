// хранилище информации store.
// если state подключен через getDefaultState -
// его можно легко вернуть к базовому состоянию методом Object.assign(state, getDefaultState())

const getDefaultState = () => {
    return {
        isLoading: false,
        contentLoading: true,
        menuOpen: false,
        isOpenCalendar: false,
        fullSidebar: false
    }
}

export default {
    state: getDefaultState(),
    mutations: {
        setIsLoading(state, isLoading) {
            state.isLoading = isLoading
        },
        setContentLoading(state, value) {
            state.contentLoading = value
        },
        setToggleMenu(state) {
            state.menuOpen = !state.menuOpen
        },
        setFullSidebar(state, value) {
            state.fullSidebar = value
        },
    },
    actions: {
        setIsLoading({commit}, isLoading) {
            commit('setIsLoading', isLoading)
        },
        setContentLoading({commit}, value) {
            commit('setContentLoading', value)
        },
        setToggleMenu({commit}) {
            commit('setToggleMenu')
        },
        setFullSidebar({commit}, value) {
            commit('setFullSidebar', value)
        },
    },
    getters: {
        getIsLoading(state) {
            return state.isLoading
        },
        getContentLoading(state) {
            return state.contentLoading
        },
        getMenuOpen(state) {
            return state.menuOpen
        },
        getIsOpenCalendar(state) {
            return state.isOpenCalendar
        },
        getFullSidebar(state) {
            return state.fullSidebar
        },
    }
}