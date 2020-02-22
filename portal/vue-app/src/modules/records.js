import EventService from "../services/EventService";

export const state = {
    loading: false,
    records: [],
    count: 0,
    editLabels: {},
    headers: [],
    pageLimit: 10
}

export const mutations = {
    SET_RECORDS(state, records) {
        state.records = records;
    },
    SET_COUNT_RECORDS(state, count) {
        state.count = count;
    },
    SET_PAGE_LIMIT_RECORDS(state, limit) {
        state.pageLimit = limit;
    },
    SET_HEADERS_RECORDS(state, headers) {
        state.headers = headers;
    },
    SET_HEADER_RECORD(state, header) {
        var obj = {};
        obj.text = header;
        obj.value = header;
        state.headers.push(obj);
    },
    SET_HEADER_NULL(state) {
        state.headers = [];
    },
    SET_LABELS_RECORDS(state, labels) {
        state.editLabels = labels;
    },
    SET_LOADING(state, value) {
        state.loading = value;
    },
}

export const actions = {
    fetchRecords({ commit }, { module, label, page, filter }) {
        commit('SET_LOADING', true);
        EventService.fetchRecords(module, label, filter, page)
            .then(response => {
                /*if (response.data.result.count == null) {
                    commit('SET_NOTIFICATION_TRUE');
                    commit('SET_MESSAGE', 'No data available');
                    commit('SET_LOADING', false);
                } else {*/
                    if (!response.data.result.code) {
                        commit(
                            'SET_RECORDS',
                            response.data.result.records
                        );
                        commit(
                            'SET_COUNT_RECORDS',
                            parseInt(response.data.result.count)
                        );
                        commit(
                            'SET_PAGE_LIMIT_RECORDS',
                            parseInt(response.data.result.pageLimit)
                        );
                        commit('SET_HEADER_NULL');
                        if (response.data.result.headers) {
                            response.data.result.headers.forEach(function(header) {
                                commit(
                                    'SET_HEADER_RECORD',
                                    header
                                );
                            });
                        }
                        var editLabels = response.data.result.edits;
                        if (editLabels) {
                            commit(
                                'SET_LABELS_RECORDS',
                                editLabels
                            );
                        }
                    }  else {
                        commit('SET_NOTIFICATION_TRUE');
                        commit('SET_MESSAGE', response.data.result.message);
                    }
                // }

                commit('SET_LOADING', false);
            })
            .catch(error => {
                /* eslint-disable no-console */
                console.log(error);
                /* eslint-enable no-console */
                commit('SET_NOTIFICATION_TRUE');
                commit('SET_MESSAGE', error.message);
                commit('SET_LOADING', false);
            })
    }
}

export const getters = {
    getHeadersInvoices: state => {
        let res = state.headers.map(function (value) {
            return value;
        });
        return res.filter(function (value) {
            return value.value != 'id';
        });
    },
    getHeadersWithoutId: state => {
        return state.headers.filter(value => value.value != 'id');
    }
}