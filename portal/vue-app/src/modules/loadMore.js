import Vue from 'vue';

export default function loadMore(res) {
    const messages = [];
    if (res.length > 2) {
        for (let i = 0; i < 2; i++) {
            Vue.set(res[i], 'main', true);
            // res[i].main = true;
            messages.push(res[i]);
        }
    } else {
        for (let i = 0; i < res.length; i++) {
            Vue.set(res[i], 'main', true);
            // res[i].main = true;
            messages.push(res[i]);
        }
    }
    return messages;
}