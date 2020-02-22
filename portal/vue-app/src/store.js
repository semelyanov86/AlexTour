import Vue from 'vue';
import Vuex from 'vuex';
import EventService from "@/services/EventService";
import * as tickets from '@/modules/tickets.js';
import * as record from '@/modules/record.js';
import * as records from '@/modules/records.js';
import * as notifications from '@/modules/notifications.js';
import * as profile from '@/modules/profile.js';
Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        tickets, record, records, notifications, profile
    },
    state: {
        menus: [
            { title: 'Домой', name: 'home' },
        ],
        modules: {},
        companyTitle: '',
        module: {}
    },
    mutations: {
        ADD_COMPANY_TITLE(state, event) {
            state.companyTitle = event;
        },
        ADD_MODULE_DESCRIPTION(state, event) {
            state.module = event;
        },
        ADD_MODULE(state, event) {
            if (event.name != 'ProjectTask' && event.name != 'ProjectMilestone') {
                state.menus.push(event);
            }
        },
        SET_MODULES(state, payload) {
            state.modules = payload;
        },
        SET_REFERENCE_ITEMS(state, payload) {
            state.module.fields[payload.id].items = payload.items;
        }
    },
    actions: {
        fetchCompanyTitle({commit}) {
            EventService.fetchCompanyTitle()
                .then(response => {
                    commit('ADD_COMPANY_TITLE', response.data.result.result);
                })
                .catch(error => {
                    /* eslint-disable no-console */
                    console.log(error.response);
                    /* eslint-enable no-console */
                })
        },
        getModuleDescription({commit}, module) {
            EventService.describeModule(module)
                .then(response => {
                    if (!response.data.result.code) {
                        commit('ADD_MODULE_DESCRIPTION', response.data.result['describe']);
                    } else {
                        commit('SET_NOTIFICATION_TRUE');
                        commit('SET_MESSAGE', response.data.result.message);
                    }
                })
                .catch(error => {
                    commit('SET_NOTIFICATION_TRUE');
                    commit('SET_MESSAGE', error.message);
                    /* eslint-disable no-console */
                    console.log(error);
                    /* eslint-enable no-console */
                })
        },
        fetchModules({commit}) {
            EventService.fetchModules()
                .then(response => {
                    var result = response.data.result.moduleInfo;
                    commit('SET_MODULES', result);
                    for (var name in result) {
                        var res = {
                            title: result[name].uiLabel,
                            'name': result[name].name
                        };
                        commit('ADD_MODULE', res);
                    }
                })
                .catch(error => {
                    commit('SET_NOTIFICATION_TRUE');
                    commit('SET_MESSAGE', error.message);
                    /* eslint-disable no-console */
                    console.log(error);
                    /* eslint-enable no-console */
                })
        }
    },
    getters: {
      isRecordCreatable: state => moduleName => {
          if (state.modules[moduleName]) {
              return state.modules[moduleName].create == '1';
          } else {
              return false;
          }
      },
      isRecordEditable: state => moduleName => {
          if (state.modules[moduleName]) {
              return state.modules[moduleName].edit == '1';
          } else {
              return false;
          }
      },
        isRecordViewable: state => moduleName => {
            if (state.modules[moduleName]) {
                return state.modules[moduleName].recordvisibility == '1';
            } else {
                return false;
            }
        },
        getFieldNumber: state => fieldName => {
          let result = -1;
          for (let prop in state.module.fields) {
              if (state.module.fields[prop].name == fieldName) {
                  return prop;
              }
          }
          return result;
        },
        getModules: state => {
            /*let result = {};
            for (let module in state.modules) {
                if (module != 'ProjectTask' || module != 'ProjectMilestone') {
                    result[module] = state.modules[module];
                }
            }*/
            return state.modules;
        }
    }
})