<template>
    <v-container>
        <v-card>
            <v-card-title>
                {{module.label}}
            </v-card-title>
            <v-text-field
                    v-model="search"
                    append-icon="mdi-search"
                    :label="$t('Search')"
                    single-line
                    hide-details
            ></v-text-field>
            <v-data-table
                    :headers="headers"
                    :items="records.records"
                    :search="search"
                    :custom-filter="customFilter"
                    :loading="records.loading"
                    :single-expand="singleExpand"
                    :expanded.sync="expanded"
                    item-key="id"
                    :loading-text="$t('Loading')"
                    hide-default-header
                    hide-default-footer
                    class="elevation-1"
                    show-expand
            >
                <template v-slot:expanded-item="{ headers }">
                    <td :colspan="headers.length">{{expanded[0] ? expanded[0].faq_answer : ''}}</td>
                </template>
            </v-data-table>
        </v-card>
    </v-container>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: 'Faq',

        data: () => ({
            search: '',
            expanded: [],
            singleExpand: true,
            headers: [{
                text: 'Name',
                value: 'question'
            }],
            selectedId: null
        }),
        methods: {
            customFilter(items, search, item) {
                /* eslint-disable no-console */
                console.log(items);
                /* eslint-enable no-console */
                return item.question.toLowerCase().indexOf(search.toLowerCase()) !== -1 || item.faq_answer.toLowerCase().indexOf(search.toLowerCase()) !== -1;
            }
        },
        created() {
            this.$store.dispatch('getModuleDescription', 'Faq');
            this.$store.dispatch('fetchRecords', {module: 'Faq', label: 'Faq', filter: {"page": 0}, page: 0});
        },
        computed: mapState(['module', 'records']),
    };
</script>
