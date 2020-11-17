<template>

    <div class="row justify-content-center">

        <div id="boxed-list" class="col-md-7 users-list">

            <div class="card">
                <div class="content">

                    <div class="row">
                        <div class="col-md-12">

                            <button class="btn btn-info btn-fill btn-sm new-user-btn"
                                    @click="openBox">
                                <i class="fas fa-user-plus"></i> Add New
                            </button>

                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="roleFilter">Show by role</label>
                                <select v-model="byRole" class="form-control" id="roleFilter">
                                    <option value="0">all</option>
                                    <option v-for="role in rolesList" :key="role.id"
                                            :value="role.id">
                                        {{ role.name }}
                                    </option>
                                </select>
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
                                    @click="getList(true)">
                                <i class="fas fa-sync-alt" :class="{ 'anim-rotate': listUpdating }"></i>
                            </button>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-12">
                            <div class="row list">

                                <div v-for="(item, index) in employeesList" :key="item.id" class="col-md-12 box boxed-list-box">

                                    <div class="col-md-12 data-top">

                                        <div class="col-md-5">
                                            <span class="data-line user-name">
                                                <i class="fas fa-user-tie"></i>
                                                {{ item.name }}
                                            </span>
                                        </div>

                                        <div class="col-md-5">

                                            <span class="data-line">
                                                <i class="fas fa-user-cog"></i>
                                                {{ item.role.name }}
                                            </span>
                                            <span class="data-line">
                                                <i class="fas fa-phone-alt"></i>
                                                {{ item.phone }}
                                            </span>
                                            <span class="data-line">
                                                <i class="fas fa-at"></i>
                                                {{ item.user.email }}
                                            </span>
                                            <span class="data-line">
                                                <i class="fas fa-table"></i>
                                                {{ item.registered_at }}
                                            </span>

                                        </div>

                                        <div class="col-md-2 text-right">
                                            <button class="btn btn-info btn-sm" @click="editUser(index)">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </div>

                                    </div>

                                    <div class="col-md-12">
                                        <span class="data-line user-address">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ item.address }}
                                        </span>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div v-if="employeesList.length === 0" class="col-12 text-center">
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
                        <span v-if="mode === 'add_new'">Add New Employee</span>
                        <span v-if="mode === 'update'">Update Employee ID:{{ employeeEdit.id }}</span>
                    </h5>
                </div>

                <div class="content">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" v-model="employeeEdit.name">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="roleSelect">Role</label>
                                        <select v-model="employeeEdit.role_id" class="form-control" id="roleSelect">
                                            <option v-for="role in rolesList" :key="role.id"
                                                    :value="role.id">
                                                {{ role.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control" v-model="employeeEdit.phone">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control" v-model="employeeEdit.email">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Address</label>
                                <textarea rows="5" class="form-control" v-model="employeeEdit.address"></textarea>
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
                                    @click="modalDelete(employeeEdit.id)">
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

</style>


<script>

    import Utils from '../mixins/Utils';
    import Notify from '../mixins/Notify';
    import Pagination from '../mixins/Pagination';
    import DialogModal from "./DialogModal";

    const EmployeeRef = {
        name: '',
        role_id: 0,
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

                byRole: 0,
                sort: 'name@desc',

                employeeEdit: {},
                employeesList: [],
                rolesList: [],
            }
        },

        mixins: [
            Utils,
            Notify,
            Pagination,
        ],

        created() {
            this.initEmployeeData();
            this.getRolesList();
            this.getList();
        },

        methods: {

            initEmployeeData() {
                this.employeeEdit = this.clone(EmployeeRef, true);
            },

            paginate(page) {
                this.page = page;
                this.getList();
            },

            getRolesList() {

                axios.get(
                    '/employees/get-roles-list'
                ).then(function(response) {

                    this.rolesList = response.data;
                    //console.log(this.rolesList);

                }.bind(this));
            },

            getList(resetPage = false) {
                this.listUpdating = true;
                if (resetPage) this.page = 1;

                let sortData = this.sort.split('@');
                let sortField = sortData[0];
                let sortDir = sortData[1];

                axios.get(
                    '/employees/get-list',
                    {
                        params: {
                            page: this.page,
                            per_page: this.perPage,
                            by_role: this.byRole,
                            sort_field: sortField,
                            sort_dir: sortDir,
                        },
                    }
                ).then(function(response) {

                    let data = response.data;

                    this.employeesList = JSON.parse(data.items);
                    //console.log(this.employeesList);
                    this.pagesCount = data.pages_count;

                    this.listUpdating = false;

                }.bind(this))
                .catch(function() {

                    this.notifyError('Load employees list error.');

                }.bind(this));
            },

            openBox() {
                this.mode = 'add_new';
                this.initEmployeeData();
            },

            closeBox() {
                this.mode = 'list';
                this.initEmployeeData();
            },

            saveUser() {
                this.saving = true;
                let update = this.mode === 'update';

                let apiUrl = (update) ? '/employees/save' : '/employees/add-new';

                axios.post(apiUrl,
                    this.employeeEdit
                ).then(function(response) {

                    let id = (update) ? this.employeeEdit.id : response.data;
                    let successMsg = 'Employee ID:' + id + ' successfully ' +
                        ((update) ? 'updated.' : 'added.');

                    this.notifySuccess(successMsg);
                    this.closeBox();

                    this.initEmployeeData();
                    this.saving = false;
                    this.getList();

                }.bind(this))
                .catch(function() {

                    let errorMsg = ((update) ? 'Update' : 'Add') + ' employee error.';

                    this.saving = false;
                    this.notifyError(errorMsg);

                }.bind(this));
            },

            editUser(index) {
                let employeeData = this.employeesList[index];

                this.employeeEdit = {
                    id: employeeData.id,
                    name: employeeData.name,
                    role_id: employeeData.role_id,
                    email: employeeData.user.email,
                    phone: employeeData.phone,
                    address: employeeData.address,
                };
                this.mode = 'update';
            },

            modalDelete(id) {

                this.$modal.show(
                    DialogModal,
                    {
                        'modal-data': {
                            header: `Delete employee ID:${id}`,
                            text: 'Employee will be deleted.',
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
                    '/employees/delete',
                    {
                        params: {
                            id
                        },
                    }
                ).then(function(response) {

                    this.notifySuccess(`Employee ID:${id} successfully deleted.`);
                    this.closeBox();
                    this.getList();

                }.bind(this))
                .catch(function() {

                    this.notifyError('Delete employee error.');

                }.bind(this));
            }
        }
    }
</script>
