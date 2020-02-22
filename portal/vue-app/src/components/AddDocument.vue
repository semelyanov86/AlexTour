<template>
    <v-card>
        <v-card-title
                class="headline grey lighten-2"
                primary-title
        >
            {{$t("Add Document")}}
        </v-card-title>

        <v-card-text>
            <v-file-input id="file" ref="file" :loading="loading" :label="$t('Upload Your File')"  v-on:change="onChangeFileUpload($event)"></v-file-input>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                    text
                    @click="uploadAttachment()"
            >
                {{$t("Download")}}
            </v-btn>
            <v-btn
                    color="primary"
                    text
                    @click="closeDialog()"
            >
                {{$t("Cancel")}}
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    import EventService from "../services/EventService";

    export default {
        name: "AddDocument",
        props: {
            related: {
                type: Object,
                default: function () {
                    return {}
                }
            },
        },
        data: () => ({
            file: '',
            loading: false,
        }),
        methods: {
            uploadAttachment() {
                var self = this;
                this.loading = true;

                EventService.addDocument(this.file, this.related).then(function(data){
                    self.$emit('addDocument', data);
                    self.loading = false;
                    self.file = '';
                    self.$store.commit('SET_NOTIFICATION', true);
                    self.$store.commit('SET_MESSAGE', self.$t('Document added successfully'));
                    self.$emit('closeDialog');
                })
                    .catch(function(error){
                        self.loading = false;
                        self.$store.commit('SET_NOTIFICATION', true);
                        self.$store.commit('SET_MESSAGE', self.$t('There was an error in adding document') + error.message);
                    });
            },
            onChangeFileUpload(e) {
                this.file = e;
            },
            closeDialog() {
                this.$emit('closeDialog')
            }
        }
    }
</script>

<style scoped>

</style>