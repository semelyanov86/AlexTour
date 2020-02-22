export default {
    computed: {
        createSupported() {
            return this.$store.getters.isRecordCreatable(this.module.name);
        },
        viewSupported() {
            return this.$store.getters.isRecordViewable(this.module.name);
        }
    }
}