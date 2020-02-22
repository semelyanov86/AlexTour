import EventService from "../services/EventService";
import loadMore from "./loadMore";
import parseObjectRelatedResult from "./parseObjectRelatedResult";
import Vue from 'vue';

export const state = {
    loading: false,
    relatedModules: [],
    record: {},
    editLabels: {},
    module: {},
    relatedRecords: [],
    relatedRecordsMain: [],
    blocks: {}
}

export const mutations = {
    SET_RELATED(state, related) {
        state.relatedModules = related;
    },
    SET_RECORD(state, record) {
        state.record = record;
    },
    ADD_RECORD(state, ticket) {
        state.tickets.push(ticket);
    },
    SET_LABELS(state, labels) {
        state.editLabels = labels;
    },
    SET_MODULE(state, module) {
        state.module = module;
    },
    SET_LOADING(state, value) {
        state.loading = value;
    },
    SET_RELATED_RECORDS(state, records) {
        // state.relatedRecords = [...state.relatedRecords, ...records];
        // state.relatedRecords[records[1]] = records[0];
        Vue.set(state.relatedRecords, records[1], records[0]);
    },
    SET_RELATED_RECORDS_MAIN(state, records) {
        // state.relatedRecordsMain = [...state.relatedRecordsMain, ...records];
        // state.relatedRecordsMain.push(records);
        Vue.set(state.relatedRecordsMain, records[1], records[0]);
    },
    ADD_RELATED_RECORD(state, data) {
        state.relatedRecords[data[1]].unshift(data[0]);
    },
    ADD_RELATED_RECORD_MAIN(state, data) {
        state.relatedRecordsMain[data[1]].unshift(data[0]);
    },
    LOAD_RECORDS(state, payload) {
        // state.relatedRecordsMain[payload[1]] = [...state.relatedRecordsMain[payload[1]], ...payload[0]];
        // var res = state.relatedRecordsMain;
        // Vue.set(state, 'relatedRecordsMain', res);
        Vue.set( state.relatedRecordsMain, payload[1], [...state.relatedRecordsMain[payload[1]], ...payload[0]]);
    },
    CLOSE_TICKET(state) {
        Vue.set(state.record, 'Статус', 'Closed');
        Vue.set(state.module, 'isStatusEditable', false);
    },
    LOAD_BLOCKS(state, payload) {
        state.blocks = payload;
    }
}

export const actions = {
    fetchRelatedModules({commit}, { module, id }) {
        EventService.fetchRelated(module)
            .then(response => {
                var relatedModules = response.data.result;
                commit('SET_RELATED', relatedModules);
                relatedModules.forEach(function(related, index) {
                    if (related.value == 1) {
                        EventService.fetchRelatedRecords(id, module, related.name)
                            .then(response => {
                                var result;
                                if(related && related.name == 'ModComments') {
                                    if(response.data.result.comments && response.data.result.comments !== null) {
                                        result = response.data.result.comments.map(function (comment, index) {
                                            if (index < 3) {
                                                Vue.set(comment, 'main', true);
                                                // comment.main = true;
                                            } else {
                                                Vue.set(comment, 'main', false);
                                                // comment.main = false;
                                            }
                                            return comment;
                                        });
                                    } else {
                                        result = [];
                                    }

                                } else {
                                    if(response.data.result.records && response.data.result.records !== null) {
                                        var records = response.data.result.records;
                                        if (!Array.isArray(records)) {
                                            result = parseObjectRelatedResult(records);
                                        } else {
                                            result = records.map(function (record, index) {
                                                if (index < 3) {
                                                    if (record) {
                                                        Vue.set(record, 'main', true);
                                                        record.main = true;
                                                    }
                                                } else {
                                                    if (record) {
                                                        Vue.set(record, 'main', false);
                                                        record.main = false;
                                                    }
                                                }
                                                return record;
                                            });
                                        }
                                    } else {
                                        result = [];
                                    }

                                }
                                if (!result) {
                                    result = [];
                                }
                                commit('SET_RELATED_RECORDS', [result, index]);
                                var filterres = result.filter(function(record){
                                    if (record) {
                                        return record.main === true;
                                    } else {
                                        return false;
                                    }
                                });
                                commit('SET_RELATED_RECORDS_MAIN', [filterres, index]);
                            })
                            .catch(error => {
                                /* eslint-disable no-console */
                                console.log(error);
                                /* eslint-enable no-console */
                            });
                    }
                });
            })
            .catch(error => {
                /* eslint-disable no-console */
                console.log(error);
                /* eslint-enable no-console */
            })
    },
    fetchRecordModule({commit}, {module, id}) {
        commit('SET_LOADING', true);
        EventService.fetchRecord(module, id)
            .then(response => {
                if (!response.data.result.code && response.data.result.code != 0) {
                    if (response.data.result.HelpDesk) {
                        commit('SET_MODULE', response.data.result.HelpDesk);
                    } else if(response.data.result.WayBill) {
                        commit('SET_MODULE', response.data.result.WayBill);
                    }
                    commit('SET_LABELS', response.data.result.editLabels);
                    commit('SET_RECORD', response.data.result.record);
                    commit('SET_LOADING', false);
                } else {
                    commit('SET_NOTIFICATION_TRUE');
                    commit('SET_MESSAGE', response.data.result.message);
                }

            })
            .catch(error => {
                /* eslint-disable no-console */
                console.log(error);
                /* eslint-enable no-console */
            })
    },
    loadRecords({commit, getters}, payload) {
        let res = getters.getRecordsFilter;
        if (res[payload].length > 0) {
            var loadRes = loadMore(res[payload]);
            commit('LOAD_RECORDS', [loadRes, payload]);
        }
    },
    fetchBlocksFields({commit}, {module, id}) {
        EventService.fetchBlocks(module, id)
            .then(response => {
                commit('LOAD_BLOCKS', response.data.result.record);
            })
            .catch(error => {
                /* eslint-disable no-console */
                console.log(error);
                /* eslint-enable no-console */
            })
    }
}

export const getters = {
    getRecordsFilter(state) {
        var res = state.relatedRecords.map(function (record) {
            return record.filter(mes => {
                if (mes) {
                    return mes.main === false;
                } else {
                    return false;
                }
            });
        });
        return res;
    },
    getRecordsMain (state) {
        return state.relatedRecordsMain;
    }
}