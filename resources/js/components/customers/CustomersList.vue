<template>

    <div class="row">

        <div class="col-md-7">

            <div class="card">
                <div class="content users-list">

                    <div class="row">
                        <div class="col-md-12">

                            <button v-if="p('uiButtonAddNew')" class="btn btn-info btn-fill btn-sm new-user-btn"
                                    @click="openBox">
                                <i class="fas fa-user-plus"></i> Add New
                            </button>

                        </div>
                    </div>

                    <users-list-customers btn-class="btn-info"
                                          btn-icon-class="fa fa-edit"
                                          custom-list-class="customers"
                                          :btn-permission="permissions.uiButtonEdit"
                                          @on-button-click="editUser"></users-list-customers>

                </div>
            </div>

        </div>


        <div id="edit-user-box" class="col-md-5">
            <div class="card" v-show="mode === 'add_new' || mode === 'update'">

                <div class="header">
                    <h5 class="title">
                        <span v-if="mode === 'add_new'">Add New Customer</span>
                        <span v-if="mode === 'update'">Update Customer ID:{{ customerEdit.id }}</span>
                    </h5>
                </div>

                <div class="content">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Name *</label>
                                <input type="text" class="form-control" v-model="customerEdit.name">
                                <span v-if="checkErr('name')" class="error">
                                    {{ getErr('name') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control" v-model="customerEdit.phone">
                                        <span v-if="checkErr('phone')" class="error">
                                            {{ getErr('phone') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email *</label>
                                        <input type="text" class="form-control" v-model="customerEdit.email">
                                        <span v-if="checkErr('email')" class="error">
                                            {{ getErr('email') }}
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Address</label>
                                <textarea rows="5" class="form-control" v-model="customerEdit.address"></textarea>
                                <span v-if="checkErr('address')" class="error">
                                    {{ getErr('address') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <button class="btn btn-success btn-fill btn-icon"
                                    @click="saveUser">
                                <i v-if="saving" class="fa fa-spinner anim-rotate"></i>
                                <i v-else class="fa fa-check"></i>
                                Save
                            </button>
                            <button class="btn btn-warning btn-fill btn-icon"
                                    @click="closeBox">
                                <i class="fa fa-ban"></i> Cancel
                            </button>

                            <button v-if="p('uiButtonDelete') && mode === 'update'"
                                    class="btn btn-danger btn-fill btn-icon pull-right"
                                    @click="modalDelete(customerEdit.id)">
                                <i class="fa fa-trash"></i> Delete user
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

</template>


<style scoped>

</style>


<script>

    import Utils from '../../mixins/Utils';
    import Notify from '../../mixins/Notify';
    import Validation from '../../mixins/Validation';
    import Pagination from '../../mixins/Pagination';
    import Permissions from '../../mixins/Permissions';
    import DialogModal from "../DialogModal";

    const CustomerRef = {
        id: 0,
        name: '',
        email: '',
        phone: '',
        address: '',
    };

    export default {

        data() {
            return {
                mode: 'list',
                listUpdating: true,
                saving: false,

                findBy: 'name',
                findQuery: '',
                sort: 'name@desc',

                customerEdit: {},
                customersList: [],
            }
        },

        mixins: [
            Utils,
            Notify,
            Validation,
            Pagination,
            Permissions,
        ],

        created() {
            this.initCustomerData();
            this.getList();
            this.getPermissionsList('customer');
        },

        methods: {

            initCustomerData() {
                this.customerEdit = this.clone(CustomerRef, true);
            },

            getList(resetPage = false) {
                this.listUpdating = true;
                if (resetPage) this.page = 1;

                let sortData = this.sort.split('@');
                let sortField = sortData[0];
                let sortDir = sortData[1];

                axios.get(
                    '/customers/get-list',
                    {
                        params: {
                            page: this.page,
                            per_page: this.perPage,
                            find_by: this.findBy,
                            find_query: this.findQuery,
                            sort_field: sortField,
                            sort_dir: sortDir,
                        },
                    }
                ).then(function(response) {

                    let data = response.data;

                    this.customersList = JSON.parse(data.items);
                    //console.log(this.customersList);
                    this.pagesCount = data.pages_count;

                    this.listUpdating = false;

                }.bind(this))
                .catch(function() {

                    this.notifyError('Load customers list error.');

                }.bind(this));
            },

            scrollToEditBox() {
                const editBox = document.getElementById('edit-user-box');
                this.$smoothScroll({
                    scrollTo: editBox,
                });
            },

            openBox() {
                this.scrollToEditBox();
                this.mode = 'add_new';
                this.initCustomerData();
            },

            closeBox() {
                this.mode = 'list';
                this.clearErrors();
                this.initCustomerData();
            },

            saveUser() {
                this.saving = true;
                let update = this.mode === 'update';

                let apiUrl = (update) ? '/customers/save' : '/customers/add-new';

                axios.post(apiUrl,
                    this.customerEdit
                ).then(function(response) {

                    let id = (update) ? this.customerEdit.id : response.data;
                    let successMsg = 'Customer ID:' + id + ' successfully ' +
                        ((update) ? 'updated.' : 'added.');

                    this.notifySuccess(successMsg);
                    this.closeBox();

                    this.initCustomerData();
                    this.saving = false;
                    this.getList();

                }.bind(this))
                .catch(function(error) {

                    this.saving = false;
                    if (this.checkValidationErrors(error.response.data)) {
                        this.notifyError('Form validation error.', 1500);
                        return;
                    }
                    let errorMsg = ((update) ? 'Update' : 'Add') + ' customer error.';
                    this.notifyError(errorMsg);

                }.bind(this));
            },

            editUser(item) {
                this.closeBox();
                this.scrollToEditBox();
                let customerData = item;

                this.customerEdit = {
                    id: customerData.id,
                    name: customerData.name,
                    email: customerData.user.email,
                    phone: customerData.phone,
                    address: customerData.address,
                };
                this.mode = 'update';
            },

            modalDelete(id) {

                this.$modal.show(
                    DialogModal,
                    {
                        'modal-data': {
                            header: `Delete customer ID:${id}`,
                            text: 'Customer will be deleted.',
                            onConfirm: function () {
                                this.deleteItem(id);
                            }.bind(this),
                        },
                    },
                    {
                        adaptive: true,
                        height: 'auto',
                    },
                    {
                        'before-close': event => {}
                    }
                );
            },

            deleteItem(id) {

                axios.get(
                    '/customers/delete',
                    {
                        params: {
                            id
                        },
                    }
                ).then(function(response) {

                    let data = response.data;
                    if (data.status && data.status === 'error') {
                        this.notifyError(data.message);
                        return;
                    }

                    this.notifySuccess(`Customer ID:${id} successfully deleted.`);
                    this.closeBox();
                    this.getList();

                }.bind(this))
                .catch(function() {

                    this.notifyError('Delete customer error.');

                }.bind(this));
            }
        }
    }

</script>
