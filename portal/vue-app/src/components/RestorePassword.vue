<template>

        <v-card>
            <v-card-title>
                <span class="headline">{{$t('Reset Password')}}</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                        <v-form ref="resetPasswdForm" v-model="formValidity">
                            <v-row>
                                <v-text-field :label="$t('Email')" type="email" required v-model="email" :rules="emailRules"></v-text-field>
                            </v-row>
                        </v-form>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeDialog()">{{$t('Close')}}</v-btn>
                <v-btn color="blue darken-1" text @click="restorePassword()" :disabled="!formValidity">{{$t('Restore')}}</v-btn>
            </v-card-actions>
            <v-dialog
                    v-model="loading"
                    hide-overlay
                    persistent
                    width="300"
            >
                <v-card
                        color="primary"
                        dark
                >
                    <v-card-text>
                        {{$t('Please stand by')}}
                        <v-progress-linear
                                indeterminate
                                color="white"
                                class="mb-0"
                        ></v-progress-linear>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </v-card>
</template>

<script>
    import EventService from "@/services/EventService";
    export default {
        name: "RestorePassword",
        data: () => ({
            loading: false,
            email: '',
            emailRules: [
                v => !!v || this.$t('Email is required'),
                v => v.indexOf('@') !== 0 || this.$t('Email should have a username'),
                v => v.includes('@') || this.$t('Email should include a symbol'),
                v => v.indexOf('.') - v.indexOf('@') > 1 || this.$t('Email should contain a valid domain'),
                v => v.indexOf('.') <= v.length - 3 || this.$t('Email should contain a valid domain extension')
            ],
            formValidity: false
        }),
        methods: {
            closeDialog() {
                this.$refs.resetPasswdForm.reset();
                this.$emit('close');
            },
            restorePassword() {
                var self = this;
                this.loading = true;
                EventService.restorePassword(this.email)
                .then(response => {
                    if (response.data.result.result.code) {
                        self.$store.commit('SET_NOTIFICATION_TRUE');
                        self.$store.commit('SET_MESSAGE', response.data.result.result.message);
                    } else {
                        self.$emit('close');
                        self.$refs.resetPasswdForm.resetValidation();
                        self.$store.commit('SET_NOTIFICATION_TRUE');
                        self.$store.commit('SET_MESSAGE', response.data.result.result);
                    }
                    self.loading = false;
                })
                .catch(error => {
                    self.loading = false;
                    self.$store.commit('SET_NOTIFICATION_TRUE');
                    self.$store.commit('SET_MESSAGE', error.message);

                })
            }
        },
        computed: {
        }
    }
</script>

<style scoped>

</style>