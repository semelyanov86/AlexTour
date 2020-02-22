<template>
    <v-card>
        <v-card-title>
            <span class="headline">{{$t('LBL_NEW')}} {{module.label}}</span>
        </v-card-title>
        <v-card-text>
            <v-container>
                    <v-form ref="createRecordForm" v-model="formValidity">
                        <v-row v-for="(value, key) in module.fields"
                               :key="key">

                            <v-text-field
                                    v-if="value.type.name == 'string'"
                                    :label="value.mandatory ? value.label + ' *' : value.label"
                                    :name="value.name"
                                    :rules="formValidations[value.name]"
                                    v-model="formValues[value.name]"
                            ></v-text-field>

                            <v-text-field
                                    v-if="value.type.name == 'integer'"
                                    :label="value.mandatory ? value.label + ' *' : value.label"
                                    :name="value.name"
                                    :rules="formValidations[value.name]"
                                    v-model="formValues[value.name]"
                            ></v-text-field>

                            <v-text-field
                                    v-if="value.type.name == 'phone'"
                                    :label="value.mandatory ? value.label + ' *' : value.label"
                                    :name="value.name"
                                    :rules="formValidations[value.name]"
                                    v-model="formValues[value.name]"
                            ></v-text-field>

                            <v-text-field
                                    v-if="value.type.name == 'email'"
                                    :label="value.mandatory ? value.label + ' *' : value.label"
                                    :name="value.name"
                                    :rules="formValidations[value.name]"
                                    v-model="formValues[value.name]"
                            ></v-text-field>

                            <v-text-field
                                    v-if="value.type.name == 'currency'"
                                    :label="value.mandatory ? value.label + ' *' : value.label"
                                    :name="value.name"
                                    :rules="formValidations[value.name]"
                                    v-model="formValues[value.name]"
                                    suffix="руб."
                            ></v-text-field>

                            <v-menu
                              v-if="value.type.name == 'date'"
                              ref="menu1"
                              v-model="menu1"
                              :close-on-content-click="false"
                              transition="scale-transition"
                              offset-y
                              full-width
                              max-width="290px"
                              min-width="290px"
                            >
                              <template v-slot:activator="{ on }">
                                <v-text-field
                                  v-model="dateFormatted"
                                  :label="value.mandatory ? value.label + ' *' : value.label"
                                  persistent-hint
                                  prepend-icon="event"
                                  @blur="date = parseDate(dateFormatted)"
                                  v-on="on"
                                  readonly
                                ></v-text-field>
                              </template>
                              <v-date-picker v-model="date" no-title @input="menu1 = false"></v-date-picker>
                            </v-menu>

                            <v-select
                                    v-else-if="value.type.name == 'picklist'"
                                    :items="value.type.picklistValues"
                                    item-text="label"
                                    item-value="value"
                                    v-model="formValues[value.name]"
                                    :label="value.mandatory ? value.label + ' *' : value.label"
                                    :rules="formValidations[value.name]"
                                    :name="value.name"
                            ></v-select>

                            <v-textarea
                                    v-else-if="value.type.name == 'text'"
                                    :name="value.name"
                                    :label="value.mandatory ? value.label + ' *' : value.label"
                                    :rules="formValidations[value.name]"
                                    v-model="formValues[value.name]"
                            ></v-textarea>

                            <v-checkbox
                               v-else-if="value.type.name == 'boolean'"
                              :name="value.name"
                              :label="value.mandatory ? value.label + ' *' : value.label"
                            ></v-checkbox>
                            <v-autocomplete
                                        v-else-if="value.type.name == 'reference'"
                                        v-model="formValues[value.name]"
                                        :loading="isLoading"
                                        :items="value.items"
                                        :search-input.sync="searchRef"
                                        v-on:keypress="refName = value.name"
                                        item-text="name"
                                        item-value="id"
                                        cache-items
                                        flat
                                        hide-no-data
                                        hide-details
                                        :label="value.mandatory ? value.label + ' *' : value.label"
                            ></v-autocomplete>
                        </v-row>
                    </v-form>
            </v-container>
            <small>*{{$t('LBL_REQUIRED')}}</small>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="resetForm()">{{$t('Cancel')}}</v-btn>
            <v-btn color="blue darken-1" text @click="saveForm()" :disabled="!formValidity">{{$t('Save')}}</v-btn>
        </v-card-actions>
        <v-dialog
                v-model="loading"
                hide-overlay
                persistent
                width="300"
        >
            <v-card
                    color="primary"
                    dark
            >
                <v-card-text>
                    {{$t('Please stand by')}}
                    <v-progress-linear
                            indeterminate
                            color="white"
                            class="mb-0"
                    ></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
        <notification></notification>
    </v-card>
</template>

<script>
    import { mapState } from 'vuex';
    import EventService from "@/services/EventService";
    import Notification from "./Notification";

    export default {
        name: "CreatePage",
        components: {Notification},
        props: {
            id: {
                type: String,
                default: ''
            },
            formStartValues: {
                type: Object,
                // required: true,
                default: function () {
                    return {}
                }
            }
        },
        data() {
           return {
               loading: false,
               message: '',
               notification: false,
               formValues: {},
               formValidations: {},
               maxValues: 190,
               minValues: 5,
               formValidity: false,
               date: new Date().toISOString().substr(0, 10),
              dateFormatted: this.formatDate(new Date().toISOString().substr(0, 10)),
              menu1: false,
              searchRef: {},
               refName: null,
               isLoading: false
           }
        },
        created() {
            for (var prop in this.module.fields) {
                var name = this.module.fields[prop].name;
                var value = this.module.fields[prop].type.defaultValue ? this.module.fields[prop].type.defaultValue : this.module.fields[prop].default;
                var validation = [];
                if (this.module.fields[prop].mandatory) {
                    const rule =
                            v => !!v || this.$t('LBL_FIELD_REQUIRED');

                    validation.push(rule);
                }
                if (this.module.fields[prop].type.name != 'text') {
                    let rule;
                    if (this.module.fields[prop].type.name == 'currency') {
                        const regexp = /^\$?([0-9]{1,3} ([0-9]{3} )*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/;
                        rule = v => regexp.test(v) || this.$t('LBL_INVALID_CHARACTERS');
                    } else if (this.module.fields[prop].type.name == 'integer') {
                        const regexp = /^\d+$/;
                        rule = v => regexp.test(v) || this.$t('LBL_INVALID_CHARACTERS');
                    } else if (this.module.fields[prop].type.name == 'phone') {
                        const regexp = /^((\+7|7|8)+([0-9]){10})$/;
                        rule = v => regexp.test(v) || this.$t('LBL_INVALID_CHARACTERS');
                    } else {
                        rule =
                            v => (v || '').length <= this.maxValues ||
                                (this.$t('LBL_MAX_CHARACTERS') + this.maxValues);
                    }
                    validation.push(rule);
                } else {
/*                    const rule =
                        v => (v || '').length > this.minValues ||
                            (this.$t('LBL_MIN_CHARACTERS') + this.minValues);

                    validation.push(rule);*/
                }
                if (this.module.fields[prop].type.name == 'email') {
                    const rule =
                         value => {
            const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            return pattern.test(value) || 'Invalid e-mail.'
          }

                    validation.push(rule);
                }
                if (this.module.fields[prop].editable) {
                    this.$set(this.formValues, name, value);
                    this.$set(this.formValidations, name, validation);
                }
            }
        },
        mounted() {
            if (Object.keys(this.formStartValues).length !== 0) {
                let res = [];
                for (var curname in this.formStartValues) {
                    const fieldNumber = this.$store.getters.getFieldNumber(curname);
                    const field = this.module.fields[fieldNumber];
                    if(field.type.name == 'reference') {
                        res.push(curname);
                    }
                }
                this.formValues = this.formStartValues;
                if (res.length > 0) {
                    this.runAutocomplete(res);
                }
            }
        },
        watch: {
           formStartValues: function() {
               if (Object.keys(this.formStartValues).length !== 0) {
                   this.formValues = this.formStartValues;
               }
           },
            searchRef(val) {
                val && val !== this.select && this.querySelections(val)
            },
          date () {
            this.dateFormatted = this.formatDate(this.date)
        },
        },
        methods: {
            runAutocomplete(fields) {
                const self = this;
                fields.forEach(function(field) {
                    let curValue = self.formValues[field];
                    self.refName = field;
                    self.searchRef = curValue;
                    self.querySelections(curValue, true);
                })
            },
           resetForm() {
               this.$refs.createRecordForm.reset();
               this.$refs.createRecordForm.resetValidation();
               this.$emit('close');
           },
           saveForm() {
               var self = this;
               this.loading = true;
               EventService.saveRecord(this.module.name, this.formValues, this.id).then(function(response){
                   self.create = false;
                   self.loading = false;
                   if (response.data.result.code) {
                       self.$store.commit('SET_NOTIFICATION', true);
                       self.$store.commit('SET_MESSAGE', response.data.result.message);
                   } else {
                       self.$store.commit('SET_NOTIFICATION', true);
                       self.$store.commit('SET_MESSAGE', self.$t('Record added successfully'));
                       self.$emit('close');
                       if (!self.id || self.id == '') {
                           self.$refs.createRecordForm.reset();
                           self.$refs.createRecordForm.resetValidation();
                           if (self.module.name == 'HelpDesk') {
                               self.$store.commit('ADD_TICKET', response.data.result.record);
                               self.$router.push({ name: 'ticket-show', params: { id: response.data.result.record.id } })
                           } else {
                               self.$store.commit('ADD_RECORD', response.data.result.record);
                           }
                       } else {
                           self.$store.dispatch('fetchRecordModule', {module: self.module.name, id: self.id});
                       }
                   }
               })
                   .catch(function(error){
                       self.loading = false;
                       self.$store.commit('SET_NOTIFICATION', true);
                       self.$store.commit('SET_MESSAGE', self.$t('There was an error in adding record') + error.message);
                   });
           },
            querySelections(val, autoselect = false) {
                const self = this;
                this.isLoading = true;
                const fieldNumber = this.$store.getters.getFieldNumber(this.refName);
                const field = this.module.fields[fieldNumber];
                EventService.fetchAutocomplete(self.module.name, field.type.refersTo[0], val)
                .then(function(response) {
                    const data = response.data.result;
                    self.$store.commit('SET_REFERENCE_ITEMS', {'id': fieldNumber, 'items': data});
                    if (autoselect) {
                        self.formValues[self.refName] = data[0].id;
                    }
                })
                .catch(function(error) {
                    /* eslint-disable no-console */
                    console.log(error);
                    /* eslint-enable no-console */
                })
                .then(function() {
                    self.isLoading = false;
                })

            },
          formatDate (date) {
            if (!date) return null;

            const [year, month, day] = date.split('-');
            return `${day}-${month}-${year}`;
          },
          parseDate (date) {
            if (!date) {
                return null;
            }
            const [month, day, year] = date.split('/');
            return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
          },
        },
        computed: {
          computedDateFormatted () {
            return this.formatDate(this.date);
          },
      ...mapState(['module']),
      },  
    }
</script>

<style scoped>

</style>