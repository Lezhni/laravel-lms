import IndexField from "./components/IndexField";
import FormField from "./components/FormField";

Nova.booting((Vue, router, store) => {
  Vue.component('index-homework-chat', IndexField)
  Vue.component('detail-homework-chat', FormField)
  Vue.component('form-homework-chat', FormField)
})
