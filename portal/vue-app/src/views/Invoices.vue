<template>
    <v-container fluid>
        <v-card>
            <v-card-title>
                {{module.label}}
                <v-spacer></v-spacer>
                <div class="my-2">
                    <v-btn color="primary"  v-show="createSupported" @click="create = true">{{$t('LBL_CREATE_INVOICE')}}</v-btn>
                </div>
                <v-spacer></v-spacer>
                <json-excel
                        class   = "btn btn-default"
                        :data   = "records.records"
                        :fields = "records.jsonLabels"
                        worksheet = "My Worksheet"
                        name    = "Invoices.xls">
                    <v-btn color="primary">{{$t('LBL_EXPORT_INVOICES')}}</v-btn>

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
                    :headers="$store.getters.getHeadersInvoices"
                    :items="records.records"
                    :search="search"
                    :loading="records.loading"
                    loading-text="Loading... Please wait"
                    footer-props.items-per-page-text="Количество записей на страницу"
                    @click:row="selectRecord($event)"
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
        name: 'Invoices',
        components: {
            Notification,
            JsonExcel, CreatePage
        },

        data: () => ({
            search: '',
            create: false,
        }),
        methods: {
            selectRecord(event) {
                if (this.viewSupported) {
                    var id = event.id;
                    this.$router.push({ name: 'invoice-show', params: { id: id } })
                }
            }
        },
        created() {
            this.$store.dispatch('getModuleDescription', 'Invoice');
            this.$store.dispatch('fetchRecords', {module: 'Invoice', label: 'Invoice', page: 0, filter: {}});
        },
        computed: {
            ...mapState(['module', 'records'])
        },
    };
</script>
