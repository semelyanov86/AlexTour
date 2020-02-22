<template>
    <v-snackbar
            v-model="notifications.notification"
    >
        {{ notifications.message }}
        <v-btn
                color="pink"
                text
                @click="closeNotify()"
        >
            {{$t('Close')}}
        </v-btn>
    </v-snackbar>
</template>

<script>
    import { mapState } from 'vuex';
    export default {
        name: "Notification",
        methods: {
            closeNotify() {
                this.$store.commit('SET_NOTIFICATION', false);
            }
        },
        computed: mapState(['notifications']),
    }
</script>

<style scoped>

</style>