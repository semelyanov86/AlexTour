<template>
  <v-container fluid>
    <v-card>
      <v-card-title>
        {{module.label}}
        <v-spacer></v-spacer>
        <div class="my-2">
          <v-btn color="primary" @click="create = true" v-show="createSupported">{{$t('LBL_CREATE_TICKET')}}</v-btn>
        </div>
        <v-spacer></v-spacer>
        <json-excel
                class   = "btn btn-default"
                :data   = "tickets.tickets"
                :fields = "tickets.jsonLabels"
                worksheet = "My Worksheet"
                name    = "Tickets.xls">
          <v-btn color="primary">{{$t('LBL_EXPORT_TICKETS')}}</v-btn>

        </json-excel>
        <v-spacer></v-spacer>
        <v-text-field
                v-model="search"
                append-icon="mdi-search"
                :label="$t('Search')"
                single-line
                hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table
              :headers="$store.getters.getHeadersTickets"
              :items="tickets.tickets"
              :search="search"
              :loading="tickets.loading"
              loading-text="Loading... Please wait"
              footer-props.items-per-page-text="Количество записей на страницу"
              @click:row="selectTicket($event)"
              :style="{ cursor: 'pointer'}"
              :footer-props="{
                itemsPerPageText: $t('Items per page'),
                pageText: '{0}-{1} ' + $t('of') + ' {2}'
              }"
      ></v-data-table>
    </v-card>
      <v-row justify="center">
        <v-dialog v-model="create" persistent max-width="600px">
          <create-page @close="create = false"></create-page>
        </v-dialog>
      </v-row>
    <notification></notification>
  </v-container>
</template>

<script>
  import { mapState } from 'vuex';
  import JsonExcel from 'vue-json-excel';
  import CreatePage from "@/components/CreatePage";
  import Notification from "@/components/Notification";
  import getPermissionsInfo from "../mixins/getPermissionsInfo";

export default {
  mixins: [getPermissionsInfo],
  props: {
    onlyOpen: {
      type: Boolean,
      required: false,
      default: false
    },
  },
  name: 'Tickets',
  components: {
    Notification,
    JsonExcel, CreatePage
  },

  data: () => ({
    search: '',
    create: false,
  }),
  methods: {
    selectTicket(event) {
      if (this.viewSupported) {
        var id = event.id;
        this.$router.push({ name: 'ticket-show', params: { id: id } })
      }
    }
  },
  created() {
    this.$store.dispatch('getModuleDescription', 'HelpDesk');
    let filter = {};
    if (this.onlyOpen) {
      filter = {"ticketstatus":"Open"};
    }
    this.$store.dispatch('fetchTickets', {page: 0, filter: filter});
  },
  computed: {
    ...mapState(['module', 'tickets']),
  }
};
</script>
