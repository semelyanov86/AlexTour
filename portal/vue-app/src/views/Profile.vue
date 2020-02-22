<template>
    <v-container fluid class="grey lighten-5">
        <v-row>
            <v-col cols="12" md="6">
                <v-card>
                    <v-card-title
                            class="headline"
                            v-text="$t('Profile Information')"
                    ></v-card-title>
                         <v-card-text class="body-1">
                            <v-row>
                                    <v-col>{{$t('lastname')}}</v-col>
                                    <v-col>{{profile.customerDetails.lastname}}</v-col>
                            </v-row>
                            <v-row>
                                    <v-col>{{$t('firstname')}}</v-col>
                                    <v-col>{{profile.customerDetails.firstname}}</v-col>
                            </v-row>
                            <v-row>
                                    <v-col>{{$t('Email')}}</v-col>
                                    <v-col>{{profile.customerDetails.email}}</v-col>
                            </v-row>
                            <v-row>
                                    <v-col>{{$t('phone')}}</v-col>
                                    <v-col>{{profile.customerDetails.phone}}</v-col>
                            </v-row>
                        </v-card-text>
                </v-card>
            </v-col>
           <v-col cols="12" md="6">
                <v-card>
                    <v-card-title
                                    class="headline"
                                    v-text="$t('Company Info')"
                            ></v-card-title>
                            <v-card-text class="body-1">
                                <v-row>
                                        <v-col>{{$t('organizationname')}}</v-col>
                                        <v-col>{{profile.companyInfo.organizationname}}</v-col>
                                </v-row>
                                <v-row>
                                        <v-col>{{$t('phone')}}</v-col>
                                        <v-col>{{profile.companyInfo.phone}}</v-col>
                                </v-row>
                                <v-row>
                                        <v-col>{{$t('fax')}}</v-col>
                                        <v-col>{{profile.companyInfo.fax}}</v-col>
                                </v-row>
                                <v-row>
                                        <v-col>{{$t('website')}}</v-col>
                                        <v-col>{{profile.companyInfo.website}}</v-col>
                                </v-row>
                            </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <v-row>
           <v-col cols="12" md="6">
                <v-card>
                 <v-card-title
                        class="headline"
                        v-text="$t('Company Information')"
                ></v-card-title>
                <v-card-text class="body-1">
                    <v-row>
                            <v-col>{{$t('accountname')}}</v-col>
                            <v-col>{{profile.companyDetails.accountname}}</v-col>
                    </v-row>
                    <v-row>
                            <v-col>{{$t('Email')}}</v-col>
                            <v-col>{{profile.companyDetails.email1}}</v-col>
                    </v-row>
                    <v-row>
                            <v-col>{{$t('phone')}}</v-col>
                            <v-col>{{profile.companyDetails.phone}}</v-col>
                    </v-row>
                    <v-row>
                            <v-col>{{$t('website')}}</v-col>
                            <v-col>{{profile.companyDetails.website}}</v-col>
                    </v-row>
                </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                <v-card>
                    <v-card-title
                                    class="headline"
                                    v-text="$t('User Information')"
                            ></v-card-title>
                            <v-card-text class="body-1">
                                <v-row>
                                        <v-col>{{$t('firstname')}}</v-col>
                                        <v-col>{{profile.userInfo.first_name}}</v-col>
                                </v-row>
                                <v-row>
                                        <v-col>{{$t('lastname')}}</v-col>
                                        <v-col>{{profile.userInfo.last_name}}</v-col>
                                </v-row>
                                <v-row>
                                        <v-col>{{$t('Email')}}</v-col>
                                        <v-col>{{profile.userInfo.email1}}</v-col>
                                </v-row>
                                <v-row>
                                        <v-col>{{$t('phone')}}</v-col>
                                        <v-col>{{profile.userInfo.phone_work}}</v-col>
                                </v-row>
                            </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "Profile",
        created() {
            this.$store.dispatch('fetchProfileData');
        },
        computed: {
            getContactImage() {
                return `data:${this.profile.customerDetails.imagetype};base64,${this.profile.customerDetails.imagedata}`;
            },
            getAccountImage() {
                return `data:${this.profile.companyDetails.imagetype};base64,${this.profile.companyDetails.imagedata}`;
            },
            ...mapState(['profile'])
        },
    }
</script>

<style scoped>

</style>