Nova.booting((Vue, router, store) => {
    Vue.component('index-tab-navigator', require('./components/IndexField'))
    Vue.component('detail-tab-navigator', require('./components/DetailField'))
    Vue.component('form-tab-navigator', require('./components/FormField'))
})
