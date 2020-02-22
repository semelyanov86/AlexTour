<template>

        <v-card>
            <v-card-title>
                <span class="headline">{{$t('Change Password')}}</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                        <v-form ref="changePasswdForm" v-model="formValidity">
                            <v-row>
                                <v-text-field :label="$t('Old Password')" type="password" required v-model="oldPassword" :rules="oldRules"></v-text-field>
                            </v-row>
                            <v-row>
                                <v-text-field :label="$t('New Password')" type="password" required v-model="newPassword" :rules="newRules"></v-text-field>
                            </v-row>
                            <v-row>
                                <v-text-field :label="$t('Confirm New Password')" type="password" required v-model="confirmPassword" :rules="[minConfirmRule, passwordConfirmationRule]"></v-text-field>
                            </v-row>
                        </v-form>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeDialog()">{{$t('Close')}}</v-btn>
                <v-btn color="blue darken-1" text @click="savePassword()" :disabled="!formValidity">{{$t('Save')}}</v-btn>
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
        name: "ChangePassword",
        data: () => ({
            loading: false,
            oldPassword: '',
            newPassword: '',
            confirmPassword: '',
            oldRules: [
                v => !!v || this.$t('Old password is required'),
                v => (v && v.length > 3) || this.$t('Old password must be more than 3 characters'),
            ],
            newRules: [
                v => !!v || this.$t('New password is required'),
                v => (v && v.length > 6) || this.$t('New password must be more than 6 characters'),
            ],
            formValidity: false
        }),
        methods: {
            closeDialog() {
                this.$refs.changePasswdForm.reset();
                this.$emit('close');
            },
            savePassword() {
                var self = this;
                this.loading = true;
                EventService.changePassword({
                    confirmPassword: self.confirmPassword,
                    newPassword: self.newPassword,
                    oldPassword: self.oldPassword
                })
                .then(response => {
                    if (response.data.result.result.code) {
                        self.$store.commit('SET_NOTIFICATION_TRUE');
                        self.$store.commit('SET_MESSAGE', response.data.result.result.message);
                    } else {
                        self.$emit('close');
                        self.$refs.changePasswdForm.resetValidation();
                        self.$store.commit('SET_NOTIFICATION_TRUE');
                        self.$store.commit('SET_MESSAGE', response.data.result.result);
                        window.location.href = "index.php?module=Portal&view=Logout";
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
            passwordConfirmationRule() {
                return () => (this.newPassword === this.confirmPassword) || this.$t('LBL_PASSWORDS_DOES_NOT_MATCH')
            },
            minConfirmRule() {
                return () => (!!this.confirmPassword) || 'Confirm password is required'
            }
        }
    }
</script>

<style scoped>

</style>