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
                       v-on:click="openEditPopup()"
                       v-show="editSupported"
                       :disabled="record ? !record.module.isStatusEditable : true"
                >
                    {{$t('LBL_EDIT_TICKET')}}
                </v-btn>
                <v-btn class="mx-2" color="primary" v-on:click="dialog = true">{{$t('LBL_ATTACH_DOCUMENT')}}</v-btn>
                <v-btn class="ml-2" color="success" v-on:click="solveTicket()">{{$t('LBL_MARK_SOLVED')}}</v-btn>
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
        <v-dialog
                v-model="dialog"
                width="500"
        >
            <add-document
                    @addDocument="addDocument($event)"
                    @closeDialog="dialog = false"
                    @addNotification="addNotify($event)"
                    :related="related"
            ></add-document>
        </v-dialog>
        <v-dialog v-model="edit" persistent max-width="600px">
            <create-page @close="edit = false" :id="id" :formStartValues="fieldStartValues"></create-page>
        </v-dialog>
        <notification></notification>
    </div>
</template>

<script>
    import EventService from "@/services/EventService";
    import { mapState } from 'vuex';
    import AddDocument from "@/components/AddDocument";
    import CreatePage from "@/components/CreatePage";
    import Notification from "@/components/Notification";
    import RelatedTabsShow from "../components/RelatedTabsShow";
    import showRecordMixin from "../mixins/showRecordMixin";

    export default {
        mixins: [showRecordMixin],
        name: "TicketShow",
        props: ['id'],
        components: {AddDocument, CreatePage, Notification, RelatedTabsShow},
        data() {
            return {
                ticket: {},
                related: {module: 'HelpDesk', id: this.id},
                fieldStartValues: {}
            }
        },
        created() {
            this.$store.dispatch('fetchRelatedModules', this.related);
            this.$store.dispatch('fetchRecordModule', this.related);
            this.$store.dispatch('getModuleDescription', 'HelpDesk');
            this.$store.dispatch('fetchBlocksFields', this.related);
        },
        computed: {
            ...mapState(['record', 'module'])
        },
        methods: {
            addDocument(file) {
                var record = file.data.result.record;
                var index = this.getIndexByName('Documents');
                if (index >= 0) {
                    record.main = true;
                    this.$store.commit('ADD_RELATED_RECORD', [record, index]);
                    this.$store.commit('ADD_RELATED_RECORD_MAIN', [record, index]);
                }
            },
            getIndexByName(name) {
                return this.record.relatedModules.findIndex(module => module.name == name);
            },
            openEditPopup() {
                var editLabels = this.record.editLabels;
                for (var prop in editLabels) {
                    if (prop != 'id' && prop != 'assigned_user_id') {
                        var fieldName = editLabels[prop];
                        this.fieldStartValues[prop] = this.getPicklistValue(prop, this.record.record[fieldName]);
                    }
                }
                this.edit = true;
            },
            getPicklistValue(name, val) {
                var fields = this.module.fields;
                var curField;
                var finalValue = null;
                for (var prop in fields) {
                    if (fields[prop].name == name) {
                        curField = fields[prop].type;
                    }
                }
                if (curField && curField.picklistValues) {
                    curField.picklistValues.forEach(function(value) {
                        if (val == value.label) {
                            finalValue = value.value;
                        }
                    });
                } else {
                    return val;
                }
                if (finalValue) {
                    return finalValue;
                } else {
                    return val;
                }
            },
            solveTicket() {
                const self = this;
                EventService.closeTicket(this.id)
                    .then(() => {
                        self.addNotify(self.$t('LBL_TICKET_CLOSED'));
                        self.$store.commit('CLOSE_TICKET');
                    })
                    .catch(error => {
                        self.addNotify(this.$t('There was an error in closing ticket: ' + error.message));
                        /* eslint-disable no-console */
                        console.log(error);
                        /* eslint-enable no-console */
                    });
            },
            identifierName() {
                var identifierNameLabel = this.record.record.identifierName ? this.record.record.identifierName.label : '';
                var identifierNameValue = this.record.record[identifierNameLabel] ? this.record.record[identifierNameLabel] : '';
                return identifierNameValue;
            }
        }
    }
</script>

<style scoped>

</style>