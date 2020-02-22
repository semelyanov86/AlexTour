<template>
    <v-card
            class="mx-auto"
            :loading="loading"
            outlined
    >
        <v-list-item three-line>
            <v-list-item-content>
                <div class="overline mb-4">{{$t("Recent Documents")}}</div>
                <v-list-item-title class="headline mb-1">{{$t("Last 5 entities")}}</v-list-item-title>
                <v-card-text class=".headline text--primary">
                    <div v-if="loading">
                        <p>
                            {{$t('Loading Data')}}
                        </p>
                    </div>
                    <div v-else-if="entities.length < 1">
                        <p>
                            {{$t('No records found')}}
                        </p>
                    </div>
                    <div v-else>
                        <div v-for="entity in entities" :key="entity.id">
                            <v-list-item>
                              <v-list-item-content>
                                <v-list-item-title> <a :href="'index.php?module=Documents&api=DownloadFile&recordId=' + entity.id">{{entity.label}}</a>
                                </v-list-item-title>
                              </v-list-item-content>
                            </v-list-item>
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
        name: "RecentDocuments",
        data: () => ({
            drawer: false
        }),
    }
</script>

<style scoped>

</style>