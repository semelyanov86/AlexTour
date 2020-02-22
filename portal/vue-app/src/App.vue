<template>
  <v-app>
    <navigation-bar v-if="$route.path != '/login'"></navigation-bar>
    <v-content>
      <router-view :key="$route.fullPath"></router-view>
    </v-content>
    <Footer v-if="$route.path != '/login'"></Footer>
  </v-app>
</template>

<script>

  import NavigationBar from "@/components/NavigationBar";
  import { mapState } from 'vuex';
  import Footer from "@/components/Footer";
  import EventService from "./services/EventService";

export default {
  name: 'App',

  components: {
    NavigationBar, Footer,
  },

  data: () => ({

  }),

  created() {
    const self = this;
    EventService.getLanguage()
    .then(response => {
      const lang = response.data.result;
      self.setLocale(lang);
    })
    .catch(error => {
      /* eslint-disable no-console */
      console.log(error);
      /* eslint-enable no-console */
    });
    this.$store.dispatch('fetchCompanyTitle');
    this.$store.dispatch('fetchModules');
  },

  computed: mapState(['companyTitle']),

  methods: {
    setLocale(locale){
      import(`@/translations/${locale}.json`).then((msgs) => {
        this.$i18n.setLocaleMessage(locale, msgs);
        this.$i18n.locale = locale;
      })
      .catch(error => {
        /* eslint-disable no-console */
        console.log(error);
        /* eslint-enable no-console */
      })
    }
  }
};
</script>
