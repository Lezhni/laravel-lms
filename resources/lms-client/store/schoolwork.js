// хранилище информации store.
// если state подключен через getDefaultState -
// его можно легко вернуть к базовому состоянию методом Object.assign(state, getDefaultState())

const getDefaultState = () => {
    return {
        schoolwork: {
            content: "",
            lesson: {
                id: null,
                name: ''
            }
        }
    }
}

export default {
    state: getDefaultState(),
    mutations: {
        setSchoolwork(state, data) {
            state.schoolwork = data['homework']
        },
        setResetSchoolwork(state) {
            Object.assign(state, getDefaultState())
        },
    },
    actions: {
        setSchoolwork({commit}, data) {
            commit('setSchoolwork', data)
        },
        setResetSchoolwork({commit}) {
            commit('setResetSchoolwork')
        },
    },
    getters: {
        getSchoolwork(state) {
            return state.schoolwork
        },
    }
}