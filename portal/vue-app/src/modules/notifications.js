export const state = {
    notification: false,
    message: ''
}

export const mutations = {
    SET_NOTIFICATION(state, value) {
        state.notification = value;
    },
    SET_MESSAGE(state, message) {
        state.message = message;
    },
    SET_NOTIFICATION_TRUE(state) {
        state.notification = true;
    },
}

export const getters = {
    getMessage (state) {
        return state.message;
    }
}