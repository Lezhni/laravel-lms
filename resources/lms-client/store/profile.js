// хранилище информации store.
// если state подключен через getDefaultState -
// его можно легко вернуть к базовому состоянию методом Object.assign(state, getDefaultState())

const getDefaultState = () => {
    return {
        citiesArray: [],
        countriesArray: [],
        selectCountry: null,
        selectCity: null,
        student: {},
        badges: {
            checked: [
                {id: 0, text: "HR"},
                {id: 1, text: "Excel"},
                {id: 2, text: "PR и реклама"},
            ],
            all: [
                {id: 0, text: "HR"},
                {id: 1, text: "Excel"},
                {id: 2, text: "PR и реклама"},
                {id: 3, text: "продажи"},
                {id: 4, text: "CRM-маркетинг"},
                {id: 5, text: "Digital"},
                {id: 6, text: "Keynote"},
                {id: 7, text: "Product management"},
                {id: 8, text: "контент"},
                {id: 9, text: "IT"},
            ]
        }
    }
}

export default {
    state: getDefaultState(),
    mutations: {
        checkingBadge(state, badge) {
            state.badges.checked.push(badge)
        },
        unCheckingBadge(state, badge) {
            state.badges.checked.splice(state.badges.checked.indexOf(badge), 1)
        },
        setCountriesData(state, data) {
            state.countriesArray = data
        },
        setCitiesData(state, data) {
            state.citiesArray = data
        },
        setCountriesValue(state, value) {
          state.selectCountry = value
        },
        setCitiesValue(state, value) {
            state.selectCity = value
        },
        setStudentData(state, data) {
            state.student = data

            if (data.country) {
                state.selectCountry = data.country
            }
        },
    },
    actions: {
        checkingBadge({commit}, badge) {
            commit('checkingBadge', badge)
        },
        unCheckingBadge({commit}, badge) {
            commit('unCheckingBadge', badge)
        },
        setCountriesData({commit}, data) {
            commit('setCountriesData', data)
        },
        setCitiesData({commit}, data) {
            commit('setCitiesData', data)
        },
        setCountriesValue({commit}, data) {
            commit('setCountriesValue', data)
        },
        setCitiesValue({commit}, val) {
            commit('setCitiesValue', val)
        },
        setStudentData({commit}, data) {
            commit('setStudentData', data)
        },
    },
    getters: {
        getCheckedBadges(state) {
            return state.badges.checked
        },
        getUnCheckedBadges(state) {
            return state.badges.all.filter(badge => !state.badges.checked.find(item => item.id === badge.id))
        },
        getCountries(state) {
            return state.countriesArray
        },
        getCountriesValue(state) {
            return state.selectCountry
        },
        getCitiesValue(state) {
            return state.selectCity
        },
        getCities(state) {
            return state.citiesArray
        },
        getProfileStudent(state) {
            return state.student
        }
    }
}