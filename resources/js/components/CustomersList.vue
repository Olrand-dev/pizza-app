<template>

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card">
                <div class="content">

                        <div class="row">

                            <div class="col-md-11">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="findBySelect">Find by</label>
                                        <select v-model="findBy" class="form-control" id="findBySelect">
                                            <option value="name">Name</option>
                                            <option value="phone">Phone</option>
                                            <option value="address">Address</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="findQueryField">Search</label>
                                        <input type="text" class="form-control" id="findQueryField"
                                               v-model="findQuery">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sortSelect">Sort</label>
                                        <select v-model="sort" class="form-control" id="sortSelect">
                                            <option value="name_desc">Name A-Z</option>
                                            <option value="name_asc">Name Z-A</option>
                                            <option value="date_desc">Register date - newest</option>
                                            <option value="date_asc">Register date - oldest</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <button class="btn btn-default btn-sm top-panel-btn update-list-btn"
                                            @click="getList">
                                        <i class="fas fa-sync-alt" :class="{ 'anim-rotate': listUpdating }"></i>
                                    </button>
                                </div>

                            </div>

                            <div class="col-md-1">
                                <button class="btn btn-info btn-fill btn-sm top-panel-btn new-user-btn"
                                        @click="openBox">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-12">

                            </div>

                            <div v-if="customersList.length === 0" class="col-12 text-center">
                                <span class="no-items">No items to show.</span>
                            </div>

                        </div>

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
                                <input type="text" class="form-control" v-model="customer.name">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control" v-model="customer.phone">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control" v-model="customer.email">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Address</label>
                                <textarea rows="5" class="form-control" v-model="customer.address"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <button class="btn btn-success btn-fill btn-icon"
                                    @click="addNew">
                                <i v-if="saving" class="fa fa-spinner anim-rotate"></i>
                                <i v-else class="fa fa-check"></i>
                                Save
                            </button>
                            <button class="btn btn-warning btn-fill btn-icon"
                                    @click="closeBox">
                                <i class="fa fa-ban"></i> Cancel
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

</template>


<style scoped lang="scss">

    .top-panel-btn {
        position: relative;
        top: 28px;
    }

    .new-user-btn {
        right: 14px;
    }

    .update-list-btn {
        min-width: 35px;
    }

</style>


<script>

    import Utils from '../mixins/Utils';
    import Notify from '../mixins/Notify';
    import Pagination from '../mixins/Pagination';

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
                sort: 'name_desc',

                customer: {},
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
            //this.getList();
        },

        methods: {

            initCustomerData() {
                this.customer = this.clone(CustomerRef, true);
            },

            paginate(page) {
                this.page = page;
                this.getList();
            },

            getList(resetPage = false) {
                this.listUpdating = true;
                if (resetPage) this.page = 1;

                /*axios.get(
                    '/customers/get-list',
                    {
                        params: {
                            page: this.page,
                            per_page: this.perPage,
                            sort_field: this.sortField,
                            sort_dir: this.sortDirection,
                        },
                    }
                ).then(function(response) {

                    //console.log(response.data);return;
                    let data = response.data;

                    this.setsList = JSON.parse(data.items);
                    this.pagesCount = data.pages_count;

                    this.listUpdating = false;

                }.bind(this))
                .catch(function() {

                    this.notifyError('Load orders list error.');

                }.bind(this));*/
            },

            openBox() {
                this.mode = 'add_new';
            },

            closeBox() {
                this.mode = 'list';
                this.initCustomerData();
            },

            addNew() {
                this.saving = true;

                axios.post('/customers/add-new',
                    this.customer
                ).then(function(response) {

                    this.notifySuccess('Cutomer ID:' + response.data + ' successfully added.');
                    this.closeBox();
                    this.saving = false;
                    //this.getList();

                }.bind(this))
                .catch(function() {

                    this.saving = false;
                    this.notifyError('Add customer error.');

                }.bind(this));
            },

            /*modalDelete(id) {

                this.$modal.show(
                    'dialog',
                    {
                        title: 'Delete pizza set',
                        text: `Pizza set with ID:${id} will be deleted.`,
                        buttons: [
                            {
                                title: 'Ok',
                                handler: () => {
                                    this.deleteSet(id);
                                },
                            },
                            {
                                title: 'Cancel',
                                handler: () => {
                                    this.$modal.hide('dialog');
                                },
                            },
                        ],
                    }
                );
            },*/

            /*deleteItem(id) {

                axios.get(
                    '/customers/delete',
                    {
                        params: {
                            id
                        },
                    }
                ).then(function(response) {

                    this.$modal.hide('dialog');
                    this.notifySuccess(`Order ID:${id} successfully deleted.`);
                    this.getList();

                }.bind(this))
                .catch(function() {

                    this.$modal.hide('dialog');
                    this.notifyError('Delete order error.');

                }.bind(this));
            }*/
        }
    }
</script>
