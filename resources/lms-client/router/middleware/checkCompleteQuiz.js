export default function middlewareAuth ({ next, store, nextMiddleware }){
    return nextMiddleware()
}