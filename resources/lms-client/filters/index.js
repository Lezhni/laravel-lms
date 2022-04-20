import Vue from 'vue'

// Фильтры для работы в компонентах
// Применяются {{ someString | nameFilter }}

Vue.filter('formatDate', (str) => {
    return new Date(str).toLocaleDateString('ru-RU', {
        year: "numeric",
        month: "numeric",
        day: "numeric",
        hour: "numeric",
        minute: "numeric"
    })
})

Vue.filter('formatDateShort', (str) => {
    return new Date(str).toLocaleDateString('ru-RU', {
        month: "numeric",
        day: "numeric"
    })
})

Vue.filter('pluralize', (num, array) => {
    const pluralize = (forms, val) => {
        const cases = [2, 0, 1, 1, 1, 2];
        return forms[(val % 100 > 4 && val % 100 < 20) ? 2 : cases[(val % 10 < 5) ? val % 10 : 5]];
    }

   return pluralize(array, num)
})

Vue.filter('slicer', (str, length) => {
    return str.length <= length ? str : str.slice(0, length) + '...'
})

Vue.filter('implode', (array, property) => {
    return array.map(item => item[property]).join(', ')
})

Vue.filter('replaceNameFile', (name, link) => {
    return name + '.' + link.split('.').reverse()[0]
})