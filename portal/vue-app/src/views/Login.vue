<template>
    <v-container
            class="fill-height"
            fluid
    >
        <v-row
                align="center"
                justify="center"
        >
            <v-col
                    cols="12"
                    sm="8"
                    md="6"
            >
                <v-card class="elevation-12">
                    <v-toolbar
                            color="primary"
                            dark
                            flat
                    >
                        <v-toolbar-title>{{$t('Login form')}}</v-toolbar-title>

                    </v-toolbar>
                    <v-card-text>
                        <v-form v-model="valid" ref="form" validation v-on:submit.prevent="onSubmit()">
                            <v-text-field
                                    label="Email"
                                    name="username"
                                    prepend-icon="mdi-account-box"
                                    type="email"
                                    v-model="email"
                                    :rules="emailRules"
                            />
                            <v-text-field
                                    id="password"
                                    label="Password"
                                    name="password"
                                    prepend-icon="mdi-lock"
                                    append-icon="mdi-eye-off"
                                    :type="showPassword ? 'text' : 'password'"
                                    v-model="password"
                                    :rules="passwordRules"
                                    @click:append="showPassword = !showPassword"
                            />
                            <v-select
                                    :items="items"
                                    v-model="language"
                                    :label="$t('Choose Language')"
                                    prepend-icon="mdi-translate"
                            />
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn text color="primary" class="mr-4" @click="restore = true">{{$t('Reset Password')}}</v-btn>
                        <v-spacer />
                        <v-btn :disabled="!valid" v-on:click.prevent="onSubmit()" color="primary">{{$t('Login')}}</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="restore" persistent max-width="600px">
            <restore-password  @close="restore = false"></restore-password>
        </v-dialog>
        <notification></notification>
    </v-container>
</template>

<script>
    import EventService from "@/services/EventService";
    import RestorePassword from "@/components/RestorePassword";
    import Notification from "@/components/Notification";

    export default {
        name: "Login",
        components: {RestorePassword, Notification},
        data: () => ({
            email: '',
            password: '',
            restore: false,
            loginFailed: false,
            errorMessage: false,
            showPassword: false,
            language: 'ru',
            items: [
                'ru',
                'en',
            ],
            valid: false,
            emailRules: [
                v => !!v || this.$t('Email is required'),
                v => /.+@.+/.test(v) || this.$t('E-mail must be valid'),
            ],
            passwordRules: [
                v => !!v || 'Password is required',
                v => v.length >= 6 || 'Password must be equal or more 6 characters',
            ],
        }),
        methods: {
            onSubmit () {
                var q = {};
                q.username = this.email;
                q.password = this.password;
                q.language = this.language;
                var self = this;
                if(this.$refs.form.validate()) {
                    EventService.doLogin(q).then(function(response) {
                        if (response.data.result.error) {
                            self.loginFailed = true;
                            self.errorMessage = response.data.result.error.message;
                            self.$store.commit('SET_NOTIFICATION_TRUE');
                            self.$store.commit('SET_MESSAGE', response.data.result.error.message);
                        } else {
                            window.location.href = 'index.php';
                        }
                        })
                }
            }
        }
    }
</script>

<style scoped>

</style>