import axios from 'axios';
// import VueAxios from 'vue-axios';

const apiClient = axios.create({
    // baseURL: 'http://localhost',
    withCredentials: false,
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json'
    }
});

export default {
    getRecentRecords() {
        var params = {
            api: 'FetchRecentRecords',
            module: 'Portal'
        };
        return apiClient.get('index.php', {
            params: params
        });
    },
    doLogin(query) {
        var params = {
            api: 'Login',
            module: 'Portal',
            q: query
        };
        return apiClient.get('index.php', {
            params: params
        });
    },
    getLanguage() {
        var params = {
            api: 'Language',
            module: 'Portal'
        };
        return apiClient.get('index.php', {
            params: params
        });
    },
    fetchCompanyTitle() {
        var params = {
            api: 'FetchCompanyTitle',
            module: 'Portal'
        };
        return apiClient.get('index.php', {
            params: params
        });
    },
    describeModule(module) {
        var params = {
            api: 'DescribeModule',
            language: 'ru_ru',
            module: module
        };
        return apiClient.get('index.php', {
            params: params
        });
    },
    fetchRecords(name, label, filter, page) {
        var query = {};
        query.mode = 'all';
        query.page = page;
        var params = {
            api: 'FetchRecords',
            filter: filter,
            label: name,
            module: label,
            q: query
        };
        return apiClient.get('index.php', {
            params: params
        });
    },
    fetchRelated(module) {
        var params = {
            api: 'FetchRelatedModules',
            'module': module
        };
        return apiClient.get('index.php', {
            params: params
        });
    },
    fetchRelatedRecords(id, parent, module) {
        var params = {};
        if (module == 'History') {
            params = {
                api: 'FetchHistory',
                id: id,
                module: parent,
                page: 0
            };
            return apiClient.get('index.php', {
                params: params
            });
        } else {
            params = {
                api: 'FetchRelatedRecords',
                id: id,
                module: parent,
                page: 0,
                relatedModule: module
            };
            if (module == 'Documents') {
                params.relatedModuleLabel = 'Documents';
            }
            return apiClient.get('index.php', {
                params: params
            });
        }
    },
    fetchRecord(module, id) {
        var params = {
            api: 'FetchRecord',
            'module': module,
            id: id
        };
        return apiClient.get('index.php', {
            params: params
        });
    },
    fetchBlocks(module, id) {
        var params = {
            api: 'FetchBlocks',
            'module': module,
            id: id
        };
        return apiClient.get('index.php', {
            params: params
        });
    },
    addCommentPost(comment) {
        var params = {
            api: 'AddComment',
            module: 'ModComments',
            comment: comment
        };
        return apiClient.post('index.php', params);
    },
    saveRecord(module, record, id) {
        if (module == 'WayBill') {
            delete record.createdtime;
            delete record.wemben;
            delete record.wsendercontact;
        } else if(module == 'HelpDesk') {
            delete record.assigned_user_id;
        }
        var params = {
            api: 'SaveRecord',
            module: module,
            record: record
        };
        if (id && id != '') {
            params.recordId = id;
        }
        return apiClient.post('index.php', params);
    },
    addDocument(file, related) {
        let formData = new FormData();
        formData.append('file', file);
        formData.append('module', 'Documents');
        formData.append('api', 'UploadAttachment');
        if (related.id) {
            formData.append('parentId', related.id);
        }
        return apiClient.post('index.php',
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        )
    },
    closeTicket(id) {
        var params = {
            api: 'SaveRecord',
            module: "HelpDesk",
            record: {ticketstatus: "Closed"},
            recordId: id
        };
        return apiClient.post('index.php', params);
    },
    fetchModules() {
        var params = {
            api: 'FetchModules',
            module: 'Portal'
        };
        return apiClient.get('index.php', {
            params: params
        });
    },
    changePassword(obj) {
        var params = {
            api: "ChangePassword",
            module: "Portal",
            record: obj
        };
        return apiClient.post('index.php', params);
    },
    restorePassword(email) {
        var params = {
            api: "ForgotPassword",
            module: "Portal",
            email: email
        };
        return apiClient.post('index.php', params);
    },
    fetchProfile() {
        var params = {
            api: 'FetchProfile',
            module: 'Portal'
        };
        return apiClient.get('index.php', {
            params: params
        });
    },
    fetchAutocomplete(parent, child, key) {
        var params = {
            api: 'SearchAutocomplete',
            module: 'Portal',
            parent: parent,
            child: child,
            key: key
        };
        return apiClient.get('index.php', {
            params: params
        });
    }
};