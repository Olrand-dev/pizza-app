<template>

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card">
                <div class="content users-list">

                    <div class="row">
                        <div class="col-md-12">

                            <button class="btn btn-info btn-fill btn-sm new-user-btn"
                                    @click="openBox">
                                <i class="fas fa-user-plus"></i> Add New
                            </button>

                        </div>
                    </div>

                    <users-list-customers btn-class="btn-info"
                                          btn-icon-class="fa fa-edit"
                                          custom-list-class="customers"
                                          @on-button-click="editUser"></users-list-customers>

                </div>
            </div>

        </div>


        <div v-if="mode === 'add_new' || mode === 'update'" class="col-md-5">
            <div class="card">

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
                                <label>Name</label>
                                <input type="text" class="form-control" v-model="customerEdit.name">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control" v-model="customerEdit.phone">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control" v-model="customerEdit.email">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Address</label>
                                <textarea rows="5" class="form-control" v-model="customerEdit.address"></textarea>
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

                            <button v-if="mode === 'update'" class="btn btn-danger btn-fill btn-icon pull-right"
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

    import Utils from '../mixins/Utils';
    import Notify from '../mixins/Notify';
    import Pagination from '../mixins/Pagination';
    import DialogModal from "./DialogModal";

    const CustomerRef = {
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
            Pagination,
        ],

        created() {
            this.initCustomerData();
            this.getList();
        },

        methods: {

            initCustomerData() {
                this.customerEdit = this.clone(CustomerRef, true);
            },

            paginate(page) {
                this.page = page;
                this.getList();
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

            openBox() {
                this.mode = 'add_new';
                this.initCustomerData();
            },

            closeBox() {
                this.mode = 'list';
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
                .catch(function() {

                    let errorMsg = ((update) ? 'Update' : 'Add') + ' customer error.';

                    this.saving = false;
                    this.notifyError(errorMsg);

                }.bind(this));
            },

            editUser(item) {
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
