// хранилище информации store.
// если state подключен через getDefaultState -
// его можно легко вернуть к базовому состоянию методом Object.assign(state, getDefaultState())

import {useSound} from '@vueuse/sound'
import messageSound from '../assets/files/coins-swoosh.mp3'

const getDefaultState = () => {
    return {
        isShowNotifications: false,
        addedMessage: false,
        noReadMessage: false,
        notifications: []
    }
}

export default {
    state: getDefaultState(),
    mutations: {
        setIsShowNotifications(state) {
            state.isShowNotifications = !state.isShowNotifications
        },
        setCloseNotifications(state) {
            state.isShowNotifications = false
        },
        setCancelAddedMessage(state) {
            state.addedMessage = false
        },
        setNotifications(state, notifications) {
            state.notifications = notifications
            state.noReadMessage = false

            notifications.forEach(notification => {
                if (!notification.read) state.noReadMessage = true
            })
        },
        setToggleReadNotifications(state, id) {
            state.noReadMessage = false

            state.notifications.some(notification => {
                if (id === notification.id) {
                    notification.read = true
                    return true
                }
            })

            state.notifications.forEach(notification => {
                if (!notification.read) state.noReadMessage = true
            })
        },
        setToggleReadNotificationsAll(state) {
            state.noReadMessage = false

            state.notifications.map(notification => {
                notification.read = true
                return notification
            })

            state.notifications.forEach(notification => {
                if (!notification.read) state.noReadMessage = true
            })
        },
        setAddNewNotification(state, newItem) {
            state.notifications.unshift({
                'created_at': new Date().toISOString(),
                'data': {
                    'link': newItem.link,
                    'message': newItem.message
                },
                'id': newItem.id,
                'read': false
            })

            state.addedMessage = true
            state.noReadMessage = true

            // new Audio(messageSound).play()

            eventBus.$emit('sendNotify', newItem.message, 'info');
        },
    },
    actions: {
        setIsShowNotifications({commit}) {
            commit('setIsShowNotifications')
        },
        setCloseNotifications({commit}) {
            commit('setCloseNotifications')
        },
        setNotifications({commit}, notifications) {
            commit('setNotifications', notifications)
        },
        setToggleReadNotifications({commit}, id) {
            commit('setToggleReadNotifications', id)
        },
        setToggleReadNotificationsAll({commit}) {
            commit('setToggleReadNotificationsAll')
        },
        setAddNewNotification({commit}, newItem) {
            commit('setAddNewNotification', newItem)
        },
        setCancelAddedMessage({commit}) {
            commit('setCancelAddedMessage')
        },
    },
    getters: {
        getIsShowNotifications(state) {
            return state.isShowNotifications
        },
        getNotifications(state) {
            return state.notifications
        },
        getAddedMessage(state) {
            return state.addedMessage
        },
        getNoReadMessage(state) {
            return state.noReadMessage
        },
    }
}
