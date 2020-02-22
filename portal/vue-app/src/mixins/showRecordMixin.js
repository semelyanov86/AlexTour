export default {
    data() {
        return {
            message: '',
            notification: false,
            dialog: false,
            edit: false
        }
    },
    methods: {
        addNotify(message) {
            this.$store.commit('SET_NOTIFICATION', true);
            this.$store.commit('SET_MESSAGE', message);
        },
    },
    computed: {
        getFieldName() {
            return this.record.record.identifierName ? this.record.record.identifierName.label : this.$t('Name');
        },
        editSupported() {
            return this.$store.getters.isRecordEditable(this.module.name);
        },
    }
}