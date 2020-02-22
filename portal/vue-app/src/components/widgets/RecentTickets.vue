<template>
    <v-card
            class="mx-auto"
            :loading="loading"
            outlined
    >
        <v-list-item three-line>
            <v-list-item-content>
                <div class="overline mb-4">{{$t("Recent Helpdesk")}}</div>
                <v-list-item-title class="headline mb-1">{{$t("Last 5 entities")}}</v-list-item-title>
                <v-card-text>
                    <div v-if="loading">
                        <p class="display-1 text--primary">
                            {{$t('Loading Data')}}
                        </p>
                    </div>
                    <div v-else-if="entities.length < 1">
                        <p class="display-1 text--primary">
                            {{$t('No records found.')}}
                        </p>
                    </div>
                    <div v-else>
                        <div v-for="entity in entities" :key="entity.id">
                            <router-link :to="{ name: 'ticket-show', params: {id: entity.id} }">
                                <p class="display-1 text--primary">
                                    {{entity.label}}
                                </p>
                            </router-link>
                            <div>{{$t('Ticket Status')}} : {{entity.statuslabel}}</div>
                            <div class="text--primary">
                                {{$t('Description')}} : {{entity.description}}
                            </div>
                        </div>
                    </div>
                </v-card-text>
            </v-list-item-content>

        </v-list-item>

    </v-card>
</template>

<script>
    export default {
        props: {
           entities: {
               type: Array,
               default: function () {
                   return []
               }
           },
            loading: {
               type: Boolean,
                default: true
            }
        },
        name: "RecentTickets",
        data: () => ({
            drawer: false
        }),
    }
</script>

<style scoped>

</style>