// Настройка кастомного скролла, трогать с нежностью и лаской, отвечает за все кастомные скроллы

const config = {
    ops: {
        vuescroll: {
            mode: 'native',
            wheelScrollDuration: 0,
            locking: true
        },
        scrollPanel: {
            easing: 'easeInOutQuad',
            speed: 300
        },
        rail: {

        },
        bar: {
            background: '#5775B9',
            keepShow: true
        },
    },
    name: 'vue-scroll'
}

export default config