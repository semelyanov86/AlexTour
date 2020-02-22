<template>
    <v-container fluid>
        <v-card>
            <v-card-title>
                {{module.label}}
                <v-spacer></v-spacer>
                <div class="my-2">
                    <v-btn color="primary" @click="create = true" v-show="createSupported">{{$t('LBL_CREATE_ENTITY')}}</v-btn>
                </div>
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
                    :headers="$store.getters.getHeadersWithoutId"
                    :items="records.records"
                    :search="search"
                    :loading="records.loading"
                    loading-text="Loading... Please wait"
                    @click:row="selectEntity($event)"
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
    import {mapState} from "vuex";
    import CreatePage from "@/components/CreatePage";
    import Notification from "@/components/Notification";

    export default {
        name: "Index",
        components: {
            Notification,
            CreatePage
        },
        data: () => ({
            search: '',
            create: false,
        }),
        created() {
            this.$store.dispatch('getModuleDescription', this.$route.name);
            this.$store.dispatch('fetchRecords', {module: this.$route.name, label: this.$route.name, page: 0, filter: {}});
        },
        methods: {
            selectEntity(event) {
                if (this.viewSupported) {
                    const id = event.id;
                    const name = this.$route.name + '-show';
                    this.$router.push({ name: name, params: { id: id } })
                }
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
            ...mapState(['module', 'records'])
        },
    }
</script>

<style scoped>

</style>