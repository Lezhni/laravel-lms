import axios from "axios";

export default async function middlewareAuth({to, next, store, nextMiddleware}) {
    // проверка авторизации для страниц

    if (!auth.check()) {
        next({
            path: '/login',
        });

        return;
    } else {
        // если проверка пройдена и user авторизован - получаем user и записываем в store

        store.dispatch('setAuthUser', auth.getUser())
        store.dispatch('setContentLoading', true)
        await axios('/api/profile')
            .then(res => {

                const newUser = {
                    avatar: res.data.student['avatarUrl'],
                    created_at: res.data.student.created_at,
                    email: res.data.student.email,
                    id: res.data.student.id,
                    name: res.data.student.name,
                    is_admin: res.data.student.is_admin,
                }

                auth.changeUser(newUser)

                store.dispatch('setAuthUser', newUser)
            }).catch(e => {
                if (e.response.status === 401) {
                    auth.logout()

                    next({
                        path: '/login',
                    });
                }
            })


        await axios('/api/notifications').then(res => {
            store.dispatch('setNotifications', res.data.notifications)
        })
            .finally(() => {

            })
    }

    return nextMiddleware()
}