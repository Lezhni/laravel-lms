export default function middlewareNotAuth ({ next }){
    // проверка авторизации для страницы login

    if (auth.check()) {
        next({
            path: '/courses',
        });

        return;
    }
    return next()
}