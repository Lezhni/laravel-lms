import axios from "axios";
// хранилище информации store.
// если state подключен через getDefaultState -
// его можно легко вернуть к базовому состоянию методом Object.assign(state, getDefaultState())

const getDefaultState = () => {
    return {
        user: {
            avatar: "",
            name: '',
            is_admin: false
        },
        loginForm: {
            email: '',
            password: ''
        },
        authenticated: false,
        sendEmail: '',
        sendNewPassword: {
            password: '',
            confirmPassword: ''
        },
        superPermission: {
            id: '',
            isSuper: false
        }
    }
}

export default {
    state: getDefaultState(),
    mutations: {
        setAuthUser(state, pay) {
            state.user = pay
            state.authenticated = true
        },
        setResetUser(state) {
            state.user = {}
            state.authenticated = false
        },
        setAuth(state, auth) {
            this.state.authenticated = auth
        },
        setUserEmail(state, email) {
            state.loginForm.email = email
        },
        setUserPassword(state, password) {
            state.loginForm.password = password
        },
        setSendEmail(state, email) {
            state.sendEmail = email
        },
        setNewPassword(state, password) {
            state.sendNewPassword.password = password
        },
        setNewPasswordConfirm(state, confirmPassword) {
            state.sendNewPassword.confirmPassword = confirmPassword
        },
        setSuperPermissionChange(state, permission) {
            state.superPermission = permission
        },
    },
    actions: {
        async setAuthLogin({commit}, user) {
            return await axios.post('/api/login', user)
        },
        setAuthUser({commit}, pay) {
            commit('setAuthUser', pay)
        },
        setResetUser({commit}) {
            commit('setResetUser')
        },
        setAuth({commit}, auth) {
            commit('setAuth', auth)
        },
        setUserEmail({commit}, email) {
            commit('setUserEmail', email)
        },
        setUserPassword({commit}, password) {
            commit('setUserPassword', password)
        },
        setSendEmail({commit}, email) {
            commit('setSendEmail', email)
        },
        setNewPassword({commit}, password) {
            commit('setNewPassword', password)
        },
        setNewPasswordConfirm({commit}, confirmPassword) {
            commit('setNewPasswordConfirm', confirmPassword)
        },
        setSuperPermissionChange({commit}, value) {
            commit('setSuperPermissionChange', value)
        },
    },
    getters: {
        getAuthUser(state) {
            return state.user
        },
        getAuthUserName(state) {
            return state.user.name
        },
        getAvatar(state) {
          return state.user.avatar
        },
        getUserEmail(state) {
            return state.loginForm.email
        },
        getUserPassword(state) {
            return state.loginForm.password
        },
        getSendEmail(state) {
            return state.sendEmail
        },
        getNewPassword(state) {
            return state.sendNewPassword.password
        },
        getNewPasswordConfirm(state) {
            return state.sendNewPassword.confirmPassword
        },
        getSuperPermissionChange(state) {
            return state.user.is_admin
        }
    }
}