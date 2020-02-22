<template>
    <v-container>
        <v-row class="mx-1">
         <h1>{{$t("Welcome to")}} {{$t("Portal")}}</h1>
         </v-row>

         <v-row>
            <v-col>
                <action-widget @createTicket="create = true" :create="create" @addDocument="addDocument($event)"></action-widget>
            </v-col>
            <v-col>
                <recent-documents :entities="documents" :loading="documentsLoading"></recent-documents>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12">
                 <recent-tickets :entities="helpdesk" :loading="helpdeskLoading"></recent-tickets>
            </v-col>
        </v-row>

         <v-dialog v-model="create" persistent max-width="600px">
                <create-page @close="create = false"></create-page>
        </v-dialog>
        <notification></notification>
    </v-container>
</template>

<script>
    import RecentTickets from "@/components/widgets/RecentTickets";
    import RecentDocuments from "@/components/widgets/RecentDocuments";
    import ActionWidget from "@/components/widgets/ActionWidget";
    import EventService from "@/services/EventService";
    import CreatePage from "@/components/CreatePage";
    import Notification from "@/components/Notification";

    export default {
        name: "Home",
        components: {Notification, RecentTickets, RecentDocuments, ActionWidget, CreatePage},
        data: () => ({
            drawer: false,
            helpdesk: [],
            helpdeskLoading: true,
            faq: [],
            faqLoading: true,
            documents: [],
            documentsLoading: true,
            create: false
        }),
        methods: {
            addDocument(e) {
                if (e.data.success) {
                    this.receiveData();
                }
            },
            receiveData() {
                var that = this;
                EventService.getRecentRecords()
                    .then(function(response) {
                        that.loading = false;
                        var result = response.data.result;
                        if(!response.data.result.code && response.data.result.code != 0) {
                            if (result.records) {
                                if (result.records.HelpDesk !== undefined && result.records.HelpDesk !== '') {
                                    that.helpdesk = result.records.HelpDesk;
                                }
                                if (result.records.Faq !== undefined && result.records.Faq !== '') {
                                    that.faq = result.records.Faq;
                                }
                                if (result.records.Documents !== undefined && result.records.Documents !== '') {
                                    that.documents = result.records.Documents;
                                }
                                that.helpdeskLoading = false;
                                that.faqLoading = false;
                                that.documentsLoading = false;
                            } else {
                                that.$store.commit('SET_NOTIFICATION_TRUE');
                                that.$store.commit('SET_MESSAGE', 'No data available');
                                that.helpdeskLoading = false;
                                that.faqLoading = false;
                                that.documentsLoading = false;
                            }
                        } else {
                            that.$store.commit('SET_NOTIFICATION_TRUE');
                            that.$store.commit('SET_MESSAGE', response.data.result.message);
                        }

                    })
            }
        },
        created() {
            this.receiveData();
            this.$store.dispatch('getModuleDescription', 'HelpDesk');
        }
    }
</script>

<style scoped>

</style>