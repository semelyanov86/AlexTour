<template>
    <div class="px-3">
        <v-row>
            <v-col cols="4">
                <h1 class="pl-8 title">{{ $t('LBL_CURRENT_TICKET') }} {{ record ? identifierName() : '' }}</h1>
            </v-col>
            <v-col class="text-right">
                <v-btn
                        class="mx-2"
                        color="primary"
                        v-on:click="edit = true"
                        v-show="editSupported"
                >
                    {{$t('LBL_EDIT_TICKET')}}
                </v-btn>
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <v-card>
                    <div v-for="(fieldsName, blockName) in record.blocks" v-bind:key="blockName">

                        <v-row class="pl-8">
                            <v-col class="title">{{blockName}}</v-col>
                        </v-row>

                        <div v-for="(fieldName, id) in fieldsName" v-bind:key="id">

                            <div v-for="(fieldLabel, fieldName2) in record.editLabels" v-bind:key="fieldName2">

                                <div v-if="fieldName == fieldName2">

                                    <v-row class="pl-8">
                                        <v-col cols="4">{{fieldLabel}}</v-col>
                                        <v-col>{{record.record[fieldLabel]}}</v-col>
                                    </v-row>

                                </div>
                            </div>

                        </div>

                    </div>
                </v-card>
            </v-col>

            <v-col cols="6">
                <related-tabs-show :id="id"></related-tabs-show>
            </v-col>
        </v-row>

        <v-dialog v-model="edit" persistent max-width="600px">
            <create-page @close="edit = false" :id="id" :formStartValues="fieldStartValues"></create-page>
        </v-dialog>
        <notification></notification>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import CreatePage from "@/components/CreatePage";
    import Notification from "@/components/Notification";
    import RelatedTabsShow from "@/components/RelatedTabsShow";
    import showRecordMixin from "../mixins/showRecordMixin";

    export default {
        mixins: [showRecordMixin],
        name: "EntityShow",
        props: ['id'],
        components: {CreatePage, Notification, RelatedTabsShow},
        data() {
            return {
                moduleName: this.$route.name.split('-')[0],
                entity: {},
                related: {module: this.$route.name.split('-')[0], id: this.id},
                fieldStartValues: {}
            }
        },
        computed: {
            ...mapState(['record', 'module'])
        },
        methods: {
            identifierName() {
                var identifierNameLabel = this.record.record.identifierName ? this.record.record.identifierName.label : '';
                var identifierNameValue = this.record.record[identifierNameLabel] ? this.record.record[identifierNameLabel] : '';
                return identifierNameValue;
            }
        },
        created() {
            this.$store.dispatch('getModuleDescription', this.moduleName);
            this.$store.dispatch('fetchRelatedModules', this.related);
            this.$store.dispatch('fetchRecordModule', this.related);
            this.$store.dispatch('fetchBlocksFields', this.related);
        },
    }
</script>

<style scoped>

</style>