<template>
  <footer class="footer">
    <b-container>
      <b-row class="pt-4 pb-4">
        <b-col cols="12" md="4" lg="6" class="mb-3">
          <b-row>
            <b-col cols="12" sm="6" md="12" lg="6" class="d-flex justify-content-center justify-content-sm-start">
              <router-link to="/">
                <img class="footer-logo" src="/assets/images/logo-white.svg" alt="logo-white">
              </router-link>
            </b-col>
            <b-col cols="12" sm="6" md="12" lg="6" class="d-flex justify-content-center justify-content-sm-start">
              <div class="footer-links d-flex flex-column align-items-center align-items-sm-start" v-if="userName">
                <a href="https://tradings.world/catalog/" target="_blank">
                  Каталог курсов
                </a>

                <router-link to="/courses" custom v-slot="{ navigate, isActive }">
                  <a href="#" :active="isActive" @click="navigate" @keypress.enter="navigate">
                    Ваши курсы
                  </a>
                </router-link>

                <router-link to="/support" custom v-slot="{ navigate, isActive }">
                  <a href="#" :active="isActive" @click="navigate" @keypress.enter="navigate">
                    Вопросы и ответы
                  </a>
                </router-link>
              </div>
            </b-col>
          </b-row>
        </b-col>
        <b-col cols="12" md="8" lg="6">
          <div class="footer-copyright text-center text-md-left">
            <p>Согласно федеральному закону "О рынке ценных бумаг" от 22.04.1996 N 39-ФЗ ООО “МИР” не осуществляет брокерскую деятельность и не оказывает услуги Форекс дилинга. Компания осуществляет деятельность только по предоставлению консультационных услуг по вопросам финансового посредничества. Вся информация на сайте, финансовые инструменты и торговые стратегии являются высокорискованными и могут привести как к частичной, так и к полной потере денежных средств. Если Вы используете ее для принятия инвестиционных решений и совершения операций на финансовом рынке, то делаете это на свой страх и риск, а также принимаете на себя ответственность за любые последствия таких решений. ООО “МИР” (сайт <a href="https://tradings.world">https://tradings.world</a>) не дает никаких гарантий, что пользование услугами принесет Заказчику финансовое достижение и немедленных результатов. Регистрируясь на Сайте и оплачивая курс либо иные услуги Исполнителя, Заказчик подтверждает свое безоговорочное согласие со всеми условиями <a target="_blank" href="https://tradings.world/files/oferta.pdf">Договора оферты</a> и ознакомлен с <a target="_blank" href="https://tradings.world/files/politikakonf.pdf">Пользовательским соглашением</a>.</p>
            <p class="footer-copyright__label">© tradings.world, 2021 - {{ currentYear }}. Все права защищены.</p>
          </div>
        </b-col>
      </b-row>
    </b-container>
  </footer>
</template>

<script>
export default {
  data() {
    return {
      currentYear: new Date().getFullYear(),
    }
  },
  computed: {
    userName() {
      return this.$store.getters['getAuthUserName']
    },
  }
}
</script>

<style scoped>

</style>