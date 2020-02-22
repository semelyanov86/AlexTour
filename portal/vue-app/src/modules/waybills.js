import EventService from "../services/EventService";

export const state = {
    loading: false,
    records: [],
    count: 0,
    editLabels: {},
    headers: [],
    pageLimit: 10,
    jsonLabels: {},
    createDateFilter: []
}

export const mutations = {
    SET_WAYBILLS(state, records) {
        state.records = records;
    },
    ADD_WAYBILL(state, record) {
        state.records.push(record);
    },
    SET_WAYBILL_COUNT(state, count) {
        state.count = count;
    },
    SET_WAYBILL_PAGE_LIMIT(state, limit) {
        state.pageLimit = limit;
    },
    SET_WAYBILL_HEADERS(state, headers) {
        state.headers = headers;
    },
    SET_WAYBILL_HEADER(state, header) {
        var obj = {};
        obj.text = header;
        obj.value = header;
        state.headers.push(obj);
    },
    SET_WAYBILL_LABELS(state, labels) {
        state.editLabels = labels;
    },
    SET_LOADING(state, value) {
        state.loading = value;
    },
    SET_WAYBILL_JSON_LABELS(state, labels) {
        state.jsonLabels = labels;
    },
    SET_CREATE_DATE_FILTER(state, value) {
        state.createDateFilter = value;
    }
}

export const actions = {
    fetchWaybills({ commit }, { page, filter }) {
        commit('SET_LOADING', true);
        EventService.fetchRecords('WayBill', 'WayBill', filter, page)
            .then(response => {
                if (response.data.result.count == null) {
                    commit('SET_NOTIFICATION_TRUE');
                    commit('SET_MESSAGE', 'No data available');
                    commit('SET_LOADING', false);
                } else {
                    if (!response.data.result.code) {
                        commit(
                            'SET_WAYBILLS',
                            response.data.result.records
                        );
                        commit(
                            'SET_WAYBILL_COUNT',
                            parseInt(response.data.result.count)
                        );
                        commit(
                            'SET_WAYBILL_PAGE_LIMIT',
                            parseInt(response.data.result.pageLimit)
                        );
                        commit(
                            'SET_WAYBILL_HEADERS',
                            []
                        );
                        response.data.result.headers.forEach(function(header) {
                            commit(
                                'SET_WAYBILL_HEADER',
                                header
                            );
                        });
                        commit(
                            'SET_WAYBILL_HEADER',
                            'Actions'
                        );
                        var editLabels = response.data.result.editLabels;
                        commit(
                            'SET_WAYBILL_LABELS',
                            editLabels
                        );
                        commit('SET_LOADING', false);
                        var jsonResult = {};
                        for (var prop in editLabels) {
                            jsonResult[editLabels[prop]] = prop;
                        }
                        commit('SET_WAYBILL_JSON_LABELS', jsonResult);
                    } else {
                        commit('SET_NOTIFICATION_TRUE');
                        commit('SET_MESSAGE', response.data.result.message);
                    }
                }

            })
            .catch(error => {
                commit('SET_NOTIFICATION_TRUE');
                commit('SET_MESSAGE', error.message);
                commit('SET_LOADING', false);
            })
    }
}

export const getters = {
    getHeaders: state => {
        let res = state.headers.map(function (value) {
            if (value.value == 'Дата добавления накладной') {
                value.filter = function (val) {
                    const originalDate = new Date(val);
                    if (state.createDateFilter.length > 0) {
                        const startDate = new Date(state.createDateFilter[0]);
                        const endDate = new Date(state.createDateFilter[1]);
                        return originalDate > startDate && originalDate < endDate;
                    } else {
                        return true;
                    }
                }
            }
            return value;
        });
        return res.filter(function (value) {
            return value.value != 'id';
        });
    }
}