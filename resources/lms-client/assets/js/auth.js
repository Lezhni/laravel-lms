class Auth {
    // Класс авторизации, доступен в глобальном объекте

    constructor() {
        this.token = window.localStorage.getItem('token');
        let userData = window.localStorage.getItem('user');
        this.user = userData ? JSON.parse(userData) : null;

        if (this.token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token;
            Echo.connector.pusher.config.auth.headers['Authorization'] = 'Bearer ' + this.token;
        }
    }

    login (token, user) {

        window.localStorage.setItem('token', token);
        window.localStorage.setItem('user', JSON.stringify(user));

        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        Echo.connector.pusher.config.auth.headers['Authorization'] = 'Bearer ' + token;

        this.token = token;
        this.user = user;

        eventBus.$emit('userLoggedIn', this.user);
    }

    changeUser (user) {
        window.localStorage.setItem('user', JSON.stringify(user));

        this.user = user;
    }

    logout() {
        this.token = null;
        this.user = null;
        window.localStorage.removeItem('token');
        window.localStorage.removeItem('user');
    }

    getUser() {
        return this.user
    }

    check () {
        return !!this.token;
    }
}

export default Auth;