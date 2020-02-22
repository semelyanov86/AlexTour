import EventService from "../services/EventService";

export default {
    data() {
        return {
            comment: null
        }
    },
    methods: {
        sendComment() {
            var commentParams = {};
            commentParams.commentcontent = this.comment;
            commentParams.parentId = '';
            commentParams.related_to = this.id;
            var tabArr = this.tab.split('-');
            var key = tabArr[1];
            var self = this;
            EventService.addCommentPost(commentParams)
                .then(response => {
                    if (!response.data.result.code && response.data.result.code != 0) {
                        var result = response.data.result;
                        result.main = true;
                        self.$store.commit('SET_NOTIFICATION', true);
                        self.$store.commit('SET_MESSAGE', self.$t('Comment send successfully'));
                        this.comment = '';
                        this.$store.commit('ADD_RELATED_RECORD', [result, key]);
                        this.$store.commit('ADD_RELATED_RECORD_MAIN', [result, key]);
                    } else {
                        self.$store.commit('SET_NOTIFICATION_TRUE');
                        self.$store.commit('SET_MESSAGE', response.data.result.message);
                    }

                })
                .catch(error => {
                    self.$store.commit('SET_NOTIFICATION', true);
                    self.$store.commit('SET_MESSAGE', this.$t('There was an error in sending comment: ' + error.message));
                    /* eslint-disable no-console */
                    console.log(error);
                    /* eslint-enable no-console */
                });
        },
    }
}