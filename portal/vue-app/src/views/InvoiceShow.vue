<template>
    <div class="px-3">
        <v-row>
            <v-col cols="4">
                <h1 class="pl-8 title">{{ $t('LBL_CURRENT_INVOICE') }} {{ record ? identifierName() : '' }}</h1>
            </v-col>
            <v-col class="text-right">
                        <v-btn color="primary" v-on:click="openEditPopup()" v-show="editSupported" :disabled="isLocked">{{$t('LBL_EDIT_INVOICE')}}</v-btn>
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
    import RelatedTabsShow from "../components/RelatedTabsShow";
    import showRecordMixin from "../mixins/showRecordMixin";

    export default {
        mixins: [showRecordMixin],
        name: "InvoiceShow",
        props: ['id'],
        components: { CreatePage, Notification, RelatedTabsShow},
        data() {
            return {
                ticket: {},
                related: {module: 'Invoice', id: this.id},
                fieldStartValues: {}
            }
        },
        created() {
            this.$store.dispatch('fetchRelatedModules', this.related);
            this.$store.dispatch('fetchRecordModule', this.related);
            this.$store.dispatch('getModuleDescription', 'Invoice');
            this.$store.dispatch('fetchBlocksFields', this.related);
        },
        computed: {
            isLocked() {
                return false;
            },
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