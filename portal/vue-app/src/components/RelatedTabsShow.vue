<template>
    <v-tabs
            v-model="tab"
            background-color="deep-purple accent-4"
            class="elevation-2"
            dark
            :centered="false"
            :grow="false"
            :vertical="false"
            :right="false"
    >

        <v-tabs-slider></v-tabs-slider>

        <v-tab
                v-for="(value, key) in record.relatedModules"
                :key="key"
                :href="`#tab-${key}`"
        >
            {{$t(value.name)}}
        </v-tab>

        <v-tab-item
                v-for="(value, key) in messages"
                :key="key"
                :value="'tab-' + key"
        >

            <v-card
                    flat
                    tile
            >

                <div v-if="record.relatedModules[key] && record.relatedModules[key].name == 'ModComments'">
                    <v-textarea
                            class="px-4"
                            name="comment"
                            :label="$t('Enter comment')"
                            v-model="comment"
                    ></v-textarea>

                    <v-card-actions :style="{ float: 'right'}">
                        <v-btn color="success" v-on:click="sendComment()">{{$t('LBL_SEND')}}</v-btn>
                    </v-card-actions>
                </div>

                <v-card-text v-if="!value || value.length < 1">{{ $t('LBL_NO_RECORDS') }}</v-card-text>
                <v-card-text v-else>
                    <div v-if="record.relatedModules[key] && record.relatedModules[key].name == 'ModComments'">
                        <v-list-item class="px-0" v-for="comment in value" :key="comment.id">
                            <v-list-item-content>
                                <v-list-item-title class="primary--text font-weight-regular">{{comment.source == "WEBSERVICE" ? comment.customer.label : comment.creator.label}}</v-list-item-title>
                                <v-list-item-subtitle class="caption">
                                    {{comment.createdtime}}
                                </v-list-item-subtitle>
                                {{comment.commentcontent}}
                            </v-list-item-content>
                        </v-list-item>
                    </div>
                    <div v-else-if="record.relatedModules[key] && record.relatedModules[key].name == 'History'">
                        <div v-for="(history, key) in value" :key="'history' + key">
                            <div v-for="(item, name) in history" :key="name">
                                <div v-if="typeof item == 'object'">
                                   <v-list-item v-if="item.updateStatus == 'updated'" class="px-0">
                                        <v-list-item-content>
                                            <v-list-item-title class="primary--text font-weight-regular">
                                                {{name}}
                                            </v-list-item-title>
                                            <v-list-item-subtitle class="font-weight-bold">
                                                {{ $t(item.updateStatus) }}
                                            </v-list-item-subtitle>
                                            <v-list-item-subtitle>
                                                {{ $t('LBL_FROM') }} "<strong>{{item.previous}}</strong>"
                                            </v-list-item-subtitle>
                                            <v-list-item-subtitle>
                                                {{ $t('LBL_TO') }} "<strong>{{item.current}}</strong>"
                                            </v-list-item-subtitle>
                                            <v-list-item-subtitle class="caption">
                                                {{history.modifiedtime}}
                                            </v-list-item-subtitle>
                                        </v-list-item-content>
                                        </v-list-item>
                                    <v-list-item v-else-if="name == 'id' && item.updateStatus == 'created' && typeof history.count == 'undefined'" class="px-0">
                                        <v-list-item-content>
                                            <v-list-item-title class="primary--text font-weight-regular">
                                                {{$t('Entity created')}}
                                            </v-list-item-title>
                                            <v-list-item-subtitle class="font-weight-bold">
                                                {{ $t(history.id.updateStatus) }}
                                            </v-list-item-subtitle>
                                            <v-list-item-subtitle class="caption">
                                                {{history.modifiedtime}}
                                            </v-list-item-subtitle>
                                            <v-list-item-subtitle class="caption">
                                                {{history.created.user}}
                                            </v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <v-list-item three-line v-for="record in value" :key="record.id">
                            <v-list-item-content>
                                <v-list-item-title>
                                     <a :href="'index.php?module=Documents&api=DownloadFile&recordId=' + record.id">{{record.filename}}</a>
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </div>
                </v-card-text>

                <v-card-actions :style="{ float: 'right'}">
                    <v-btn
                            large
                            :color="getColorBtn(key)"
                            @click="loadMore(key)"
                            :disabled="maxLength(key) === 0"
                    >
                        {{$t('LBL_LOAD_MORE')}}</v-btn>
                </v-card-actions>

            </v-card>

        </v-tab-item>

    </v-tabs>
</template>

<script>
    import {mapState} from "vuex";
    import sendCommentMixin from "../mixins/sendCommentMixin";

    export default {
        mixins: [sendCommentMixin],
        props: {
            id: {
                type: String,
                required: true
            }
        },
        name: "RelatedTabsShow",
        data() {
          return {
              tab: null,
          }
        },
        computed: {
            messages () {
                return this.$store.getters.getRecordsMain
            },
            ...mapState(['record', 'module'])
        },
        methods: {
            getColorBtn (key) {
                if (this.maxLength(key) === 0) {
                    return '';
                } else {
                    return 'primary';
                }
            },
            loadMore(key) {
                this.$store.dispatch('loadRecords', key);
            },
            maxLength (key) {
                return this.$store.getters.getRecordsFilter[key] ? this.$store.getters.getRecordsFilter[key].length : 0;
            },
        }
    }
</script>

<style scoped>

</style>