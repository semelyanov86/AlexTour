<template>
    <v-card
            class="mx-auto"
            max-width="544"
            outlined
    >
        <v-dialog
                v-model="dialog"
                width="500"
        >
            <add-document @addDocument="addDocument($event)" @closeDialog="dialog = false" @addNotification="addNotify($event)"></add-document>
        </v-dialog>
        <v-list-item three-line>
            <v-list-item-content>
                <div class="overline mb-4">{{$t("What would you like to do ?")}}</div>
                <v-list-item-title class="headline mb-1">{{$t("Popular Activities")}}</v-list-item-title>
                <v-card-text>
                           <p class="display-1 text--primary">
                                {{$t("Documents")}}
                            </p>
                           <div class="my-2">
                               <v-btn color="primary" v-on:click="dialog = true">{{$t("LBL_ADD_DOCUMENT")}}</v-btn>
                           </div>
                           <p class="display-1 text--primary">
                               {{$t("Tickets")}}
                           </p>
                           <div class="my-2">
                               <v-btn v-on:click="createTicket()">{{$t("LBL_CREATE_TICKET")}}</v-btn>
                           </div>
                           <div class="my-2">
                              <v-btn color="error" :to="{ name: 'HelpDesk', params: { onlyOpen: true } }">{{$t("LBL_OPEN_TICKETS")}}</v-btn>
                           </div>
                </v-card-text>
            </v-list-item-content>

        </v-list-item>
        <notification></notification>
    </v-card>
</template>

<script>
    import AddDocument from "../AddDocument";
    import Notification from "../Notification";

    export default {
        name: "ActionWidget",
        components: {AddDocument, Notification},
        props: {
           create: {
               type: Boolean,
               required: true
           }
        },
        data: () => ({
            drawer: false,
            dialog: false,
            loading: false,
            message: '',
            notification: false
        }),
        methods: {
            createTicket() {
                this.$emit('createTicket');
            },
            addDocument(data) {
                this.$emit('addDocument', data);
            },
        }
    }
</script>

<style scoped>

</style>