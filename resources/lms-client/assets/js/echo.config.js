// Конфигурация подключения к серверу уведомлений

const config = {
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.PUSHER_CLUSTER,
    wsHost: window.location.hostname,
    wssPort: 6001,
    authEndpoint: '/api/broadcasting/auth',
    disableStats: true,
    encrypted: process.env.APP_ENV === 'production' ? null : true,
    wsPort: process.env.APP_ENV === 'production' ? null : 6001,
    forceTLS: process.env.APP_ENV === 'production' ? null : true,
    enabledTransports: process.env.APP_ENV === 'production' ? null : ['ws', 'wss']
}

export default config