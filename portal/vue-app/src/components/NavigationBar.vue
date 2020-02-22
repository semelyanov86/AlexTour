<template>
    <div>
        <v-navigation-drawer app temporary v-model="drawer">
            <v-list-item>
                <v-list-item-content>
                    <v-list-item-title class="title">
                        {{companyTitle}} {{$t('Portal')}}
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

            <v-list
                    dense
                    nav
            >
                <v-list-item
                        v-for="item in menus"
                        :key="item.title"
                        :to="{name: item.name}"
                        link
                        :exact="item.name == 'home'"
                >

                    <v-list-item-content>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>

            <v-divider></v-divider>

            <v-list
                    dense
                    nav
            >
                <v-list-item :to="{name: 'profile'}" link>
                    <v-list-item-content>
                        <v-list-item-title>{{$t('Profile information')}}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-on:click="change = true" link>
                    <v-list-item-content>
                        <v-list-item-title>{{$t('Change password')}}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-on:click="doLogout()" link>
                    <v-list-item-content>
                        <v-list-item-title>{{$t('Do logout')}}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

            </v-list>

        </v-navigation-drawer>
        <v-app-bar 
        :collapse="windowSize()"
        app 
        color="primary" 
        dark
        >
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>{{companyTitle}} {{$t('Portal')}}</v-toolbar-title>
            <v-toolbar-items class="pl-12">
               <router-link v-for="item in menus" :key="item.title" :to="{name: item.name}" v-slot="{ href, route, navigate, isActive, isExactActive }">
                    <v-btn text :href="href" @click="navigate" :input-value="checkActive(route, isActive)" :exact="item.name == 'home'">{{ item.title }}</v-btn>
                </router-link>
            </v-toolbar-items>

            <v-spacer></v-spacer>

            <template v-if="$vuetify.breakpoint.smAndUp">
                <!-- Не понятно пока, нужен ли общий поиск в меню, когда у нас есть поиск в таблицах
                <v-btn icon>
                    <v-icon>mdi-magnify</v-icon>
                </v-btn>
                -->
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon :to="{name: 'profile'}">
                            <v-icon>mdi-account-circle</v-icon>
                        </v-btn>
                    </template>
                    <span>{{$t('Profile information')}}</span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" v-on:click="change = true" icon>
                            <v-icon>mdi-account-key</v-icon>
                        </v-btn>
                    </template>
                    <span>{{$t('Change password')}}</span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" v-on:click="doLogout()" icon>
                            <v-icon>mdi-account-child-circle</v-icon>
                        </v-btn>
                    </template>
                    <span>{{$t('Do logout')}}</span>
                </v-tooltip>

            </template>
        </v-app-bar>
        <v-row justify="center">
            <v-dialog v-model="change" persistent max-width="600px">
                <change-password @close="change = false"></change-password>
            </v-dialog>
        </v-row>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import ChangePassword from "./ChangePassword";

    export default {
        name: "NavigationBar",
        components: {
           ChangePassword
        },
        data: () => ({
            drawer: false,
            change: false
        }),
        methods: {
           doLogout() {
               window.location.href = 'index.php?module=Portal&view=Logout';
           },
           windowSize() {
               if (window.screen.width < 800) {
                return true;
               }
           },
            checkActive(route, isActive) {
               if (route.name == 'home') {
                   return this.$route.name === 'home';
               }
                if (isActive) {
                    return true;
                } else {
                    const newName = route.name + '-show';
                    return this.$route.name === newName;
                }
            }
        },
        computed: mapState(['menus', 'companyTitle'])
    }
</script>

<style scoped>

</style>