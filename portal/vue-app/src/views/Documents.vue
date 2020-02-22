<template>
    <v-container fluid>
        <v-card>
            <v-card-title>
                {{module.label}}
                <v-spacer></v-spacer>
                <div class="my-2">
                    <v-btn color="primary" @click="create = true" v-show="createSupported">{{$t('LBL_CREATE_DOCUMENT')}}</v-btn>
                </div>
                <v-spacer></v-spacer>
                <v-text-field
                        v-model="search"
                        append-icon="mdi-search"
                        label="Search"
                        single-line
                        hide-details
                ></v-text-field>
            </v-card-title>
            <v-data-table
                    :headers="getHeaders"
                    :items="records.records"
                    :search="search"
                    :loading="records.loading"
                    loading-text="Loading... Please wait"
                    footer-props.items-per-page-text="Количество записей на страницу"
                    @click:row="selectEntity($event)"
                    :style="{ cursor: 'pointer'}"
            >
                <template v-slot:item.Actions="{ item }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn color="primary" v-on="on" v-on:click.stop="downloadDocument(item)" v-show="item.documentExists">{{$t('Download')}}</v-btn>
                        </template>
                        <span>{{$t('Change password')}}</span>
                    </v-tooltip>
                </template>
            </v-data-table>
        </v-card>
        <v-row justify="center">
            <v-dialog v-model="create" persistent max-width="600px">
                <add-document v-on:closeDialog="create = false"></add-document>
            </v-dialog>
        </v-row>
        <notification></notification>
    </v-container>
</template>
<script>
    import {mapState} from "vuex";
    import AddDocument from "@/components/AddDocument";
    import Notification from "@/components/Notification";

    export default {
        name: "Index",
        components: {
            Notification,
            AddDocument
        },
        data: () => ({
            search: '',
            create: false,
            moduleName: 'Documents'
        }),
        created() {
            this.$store.dispatch('getModuleDescription', 'Documents');
            this.$store.dispatch('fetchRecords', {module: 'Documents', label: 'Documents', page: 0, filter: {}});
        },
        methods: {
            selectEntity(event) {
                if (this.viewSupported) {
                    const id = event.id;
                    const name = 'DocumentShow';
                    this.$router.push({ name: name, params: { id: id } })
                }
            },
            downloadDocument(item) {
                window.location.href = 'index.php?module='+this.moduleName+'&api=DownloadFile&recordId='+item.id;
            }
        },
        computed: {
            createSupported() {
                if (this.module.name) {
                    return this.$store.getters.isRecordCreatable(this.module.name);
                } else {
                    return false;
                }
            },
            viewSupported() {
                if (this.module.name) {
                    return this.$store.getters.isRecordViewable(this.module.name);
                } else {
                    return false;
                }
            },
            getHeaders() {
                let headers = this.records.headers.filter(value => value.value != 'id');
                headers.push({
                    text: 'Actions',
                    value: 'Actions'
                });
               return headers;
            },
            ...mapState(['module', 'records'])
        },
    }
</script>

<style scoped>

</style>