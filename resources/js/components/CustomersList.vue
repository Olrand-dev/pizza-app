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
                                            <option value="name@desc">Name A-Z</option>
                                            <option value="name@asc">Name Z-A</option>
                                            <option value="created_at@desc">Register date - newest</option>
                                            <option value="created_at@asc">Register date - oldest</option>
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

                            <div class="col-12">
                                <div class="row user-box-list">

                                    <div v-for="(item, index) in customersList" :key="item.id" class="col-md-12 box user-box">

                                        <div class="col-md-12 user-data-top">

                                            <div class="col-md-6">
                                                <span class="user-data-line user-name">
                                                    <i class="fas fa-user"></i>
                                                    {{ item.name }}
                                                </span>
                                            </div>

                                            <div class="col-md-5">

                                                <span class="user-data-line">
                                                    <i class="fas fa-phone-alt"></i>
                                                    {{ item.phone }}
                                                </span>
                                                <span class="user-data-line">
                                                    <i class="fas fa-at"></i>
                                                    {{ item.user.email }}
                                                </span>
                                                <span class="user-data-line">
                                                    <i class="fas fa-table"></i>
                                                    {{ item.registered_at }}
                                                </span>

                                            </div>

                                            <div class="col-md-1 text-right">
                                                <button class="btn btn-info btn-sm" @click="editUser(index)">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </div>

                                        </div>

                                        <div class="col-md-12">
                                            <span class="user-data-line user-address">
                                                <i class="fas fa-map-marker-alt"></i>
                                                {{ item.address }}
                                            </span>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div v-if="customersList.length === 0" class="col-12 text-center">
                                <span class="no-items">No items to show.</span>
                            </div>

                            <div class="col-12">

                                <div class="col-md-10">

                                    <pagination :page="page" :pages-count="pagesCount" @click-handler="paginate"
                                                range="5"></pagination>

                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="perPageSelect">Per page</label>
                                        <select v-model="perPage" class="form-control" id="perPageSelect"
                                                @change="getList(true)">
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="15">15</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>
                                </div>

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


<style scoped lang="scss">

    @import './../../sass/variables';

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

    .user-box-list {

        padding-left: 15px;
        padding-right: 15px;
        margin: 20px 0;

        .user-box {

            padding: 10px;
            margin-bottom: 10px;

            .user-data-top {
                padding: 0;
                margin-bottom: 6px;
            }

            .user-data-line {
                display: block;
                color: $s-soft-gray;

                &.user-name {
                    font-size: 18px;
                }
                &.user-address {
                    font-size: 16px;
                }

                i {
                    color: $s-dark-gray;
                    margin-right: 5px;
                }
            }
        }
    }

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
                    let successMsg = 'Cutomer ID:' + id + ' successfully ' +
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

            editUser(index) {
                let customerData = this.customersList[index];

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
                        'data': {
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

                    this.notifySuccess(`Customer ID:${id} successfully deleted.`);
                    this.closeBox();
                    this.getList();

                }.bind(this))
                .catch(function() {

                    this.$modal.hide('dialog');
                    this.notifyError('Delete customer error.');

                }.bind(this));
            }
        }
    }
</script>
