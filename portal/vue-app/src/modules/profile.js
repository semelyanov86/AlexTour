import EventService from "../services/EventService";


export const state = {
    loading: false,
    companyDetails: {},
    customerDetails: {},
    companyInfo: {},
    userInfo: {}
}

export const mutations = {
    SET_COMPANY_DETAILS(state, payload) {
        state.companyDetails = payload;
    },
    SET_CUSTOMER_DETAILS(state, payload) {
        state.customerDetails = payload;
    },
    SET_COMPANY_INFO(state, payload) {
        state.companyInfo = payload;
    },
    SET_USER_INFO(state, payload) {
        state.userInfo = payload;
    }
}

export const actions = {
    fetchProfileData({commit}) {
        commit('SET_LOADING', true);
        EventService.fetchProfile()
            .then(response => {
                var relatedData = response.data.result;
                commit('SET_COMPANY_DETAILS', relatedData.company_details);
                commit('SET_CUSTOMER_DETAILS', relatedData.customer_details);
                commit('SET_COMPANY_INFO', relatedData.company_info);
                commit('SET_USER_INFO', relatedData.user_info);
                commit('SET_LOADING', false);
            })
            .catch(error => {
                /* eslint-disable no-console */
                console.log(error);
                /* eslint-enable no-console */
                commit('SET_LOADING', false);
            })
    }
}