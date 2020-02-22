import EventService from "../services/EventService";

export const state = {
    loading: false,
    tickets: [],
    count: 0,
    editLabels: {},
    headers: [],
    pageLimit: 10,
    jsonLabels: {}
}

export const mutations = {
    SET_TICKETS(state, tickets) {
        state.tickets = tickets;
    },
    ADD_TICKET(state, ticket) {
        state.tickets.push(ticket);
    },
    SET_COUNT(state, count) {
        state.count = count;
    },
    SET_PAGE_LIMIT(state, limit) {
        state.pageLimit = limit;
    },
    SET_HEADERS(state, headers) {
        state.headers = headers;
    },
    SET_HEADER(state, header) {
        var obj = {};
        obj.text = header;
        obj.value = header;
        state.headers.push(obj);
    },
    SET_LABELS(state, labels) {
        state.editLabels = labels;
    },
    SET_LOADING(state, value) {
        state.loading = value;
    },
    SET_JSON_LABELS(state, labels) {
        state.jsonLabels = labels;
    }
}

export const actions = {
    fetchTickets({ commit }, { page, filter }) {
        commit('SET_LOADING', true);
        EventService.fetchRecords('HelpDesk', 'HelpDesk', filter, page)
            .then(response => {
                commit(
                    'SET_TICKETS',
                    response.data.result.records
                );
                commit(
                    'SET_COUNT',
                    parseInt(response.data.result.count)
                );
                commit(
                    'SET_PAGE_LIMIT',
                    parseInt(response.data.result.pageLimit)
                );
                commit(
                    'SET_HEADERS',
                    []
                );
                response.data.result.headers.forEach(function(header) {
                    commit(
                        'SET_HEADER',
                        header
                    );
                });
                var editLabels = response.data.result.editLabels;
                commit(
                    'SET_LABELS',
                    editLabels
                );
                commit('SET_LOADING', false);
                var jsonResult = {};
                for (var prop in editLabels) {
                    jsonResult[editLabels[prop]] = prop;
                }
                commit('SET_JSON_LABELS', jsonResult);
            })
            .catch(error => {
                /* eslint-disable no-console */
                console.log(error);
                /* eslint-enable no-console */
                commit('SET_LOADING', false);
            })
    }
}

export const getters = {
    getHeadersTickets: state => {
        let res = state.headers.map(function (value) {
            return value;
        });
        return res.filter(function (value) {
            return value.value != 'id';
        });
    }
}