<template>

    <div id="ordersList" class="row">

        <div class="col-md-12">
            <div class="row">

                <div class="col-md-12">

                    <div v-if="mode === 'list'">
                        <button class="btn btn-info btn-fill btn-icon"
                                @click="openBox">
                            <i class="fa fa-plus"></i> New Order
                        </button>
                    </div>

                    <div v-if="mode === 'add_new'">
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

        <transition name="slide-down">

            <div v-if="order" v-show="mode === 'add_new'" class="col-md-12 mt-10">
                <div class="row new-order-box">

                    <div class="col-md-12">
                        <h4>Customer Data</h4>
                    </div>

                    <div class="col-md-6">

                        <div v-if="objLength(order.customer_data) > 0" class="col-12">

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control"
                                               v-model="order.customer_data.name" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control"
                                               v-model="order.customer_data.phone" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control"
                                               v-model="order.customer_data.user.email" readonly>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea rows="5" class="form-control"
                                                  v-model="order.customer_data.address" readonly></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-12">

                            <button class="btn btn-default btn-icon"
                                    @click="modalSelectCustomer">
                                <span v-if="order.customer_id === 0">
                                    <i class="fa fa-user"></i> Select Customer
                                </span>
                                <span v-else>
                                    <i class="fa fa-user-edit"></i> Change Customer
                                </span>
                            </button>

                        </div>

                    </div>

                    <div v-if="order.customer_id > 0" class="col-md-6">
                        <div class="form-group">
                            <label>Customer comment</label>
                            <textarea rows="5" class="form-control"
                                      v-model="order.customer_comment"></textarea>
                        </div>
                    </div>

                    <div class="col-md-12 ing-header">
                        <h4>Ingredients</h4>
                    </div>

                    <order-ingredients ref="orderIng"
                                       :order-pizza-sets="order.pizza_sets"
                                       :order-add-prods="order.products"
                                       :pizza-sets-list="pizzaSetsList"
                                       :add-prods-list="addProductsList"
                                       @on-ing-list-change="setOrderData"></order-ingredients>

                </div>

            </div>

        </transition>


        <div class="col-md-12 orders-list">

            <div class="col-md-3">
                <div class="form-group">
                    <label for="roleFilter">Show by status</label>
                    <select v-model="byStatus" class="form-control" id="roleFilter">
                        <option value="0">All</option>
                        <option v-for="status in orderStatusesList" :key="status.id"
                                :value="status.id">
                            {{ status.name }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label for="findBySelect">Find by</label>
                    <select v-model="findBy" class="form-control" id="findBySelect">
                        <option value="name">Customer name</option>
                        <option value="phone">Customer phone</option>
                        <option value="address">Customer address</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="findQueryField">Search</label>
                    <input type="text" class="form-control" id="findQueryField"
                           v-model="findQuery">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="sortSelect">Sort</label>
                    <select v-model="sort" class="form-control" id="sortSelect">
                        <option value="created_at@desc">Order date - newest</option>
                        <option value="created_at@asc">Order date - oldest</option>
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

    </div>

</template>


<style scoped lang="scss">

    .new-order-box {

        h4 {
            margin-top: 15px;
        }

        .ing-header {
            margin-bottom: 0;
            margin-top: 30px;
        }
    }


    .orders-list {

        margin-top: 20px;

        .update-list-btn {
            position: relative;
            top: 28px;
        }
    }

</style>


<script>

    import Utils from '../mixins/Utils';
    import Notify from '../mixins/Notify';
    import Pagination from '../mixins/Pagination';
    import SelectOrderCustomerModal from "./SelectOrderCustomerModal";

    const OrderRef = {
        customer_id: 0,
        customer_data: {},
        customer_comment: '',
        pizza_sets: [],
        products: [],
        cost: 0,
        weight: 0,
    };

    export default {

        data() {
            return {
                mode: 'list',
                listUpdating: true,
                saving: false,

                byStatus: 0,
                findBy: 'name',
                findQuery: '',
                sort: 'created_at@desc',

                order: {},
                ordersList: [],
                orderStatusesList: [],
                pizzaSetsList: [],
                addProductsList: [],
            }
        },

        mixins: [
            Utils,
            Notify,
            Pagination,
        ],

        created() {
            this.initEmptyOrder();
            this.getDataLists();
            this.getList();
        },

        methods: {

            initEmptyOrder() {
                this.order = this.clone(OrderRef, true);
                if (this.hop(this.$refs, 'orderIng')) {
                    this.$refs.orderIng.clearData();
                }
            },

            paginate(page) {
                this.page = page;
                this.getList();
            },

            openBox() {
                this.mode = 'add_new';
            },

            closeBox() {
                this.mode = 'list';
                this.initEmptyOrder();
            },

            getDataLists() {

                axios.get(
                    '/orders/get-data-lists',
                ).then(function(response) {

                    let data = response.data;
                    //console.log(data);return;

                    this.pizzaSetsList = data.pizza_sets_list;
                    this.addProductsList = data.add_prods_list;
                    this.orderStatusesList = data.order_statuses_list;

                }.bind(this));
            },

            setOrderData(data) {
                this.order.cost = data.cost;
                this.order.weight = data.weight;
            },

            getList(resetPage = false) {
                this.listUpdating = true;
                if (resetPage) this.page = 1;

                let sortData = this.sort.split('@');
                let sortField = sortData[0];
                let sortDir = sortData[1];

                axios.get(
                    '/orders/get-list',
                    {
                        params: {
                            page: this.page,
                            per_page: this.perPage,
                            by_status: this.byStatus,
                            find_by: this.findBy,
                            find_query: this.findQuery,
                            sort_field: sortField,
                            sort_dir: sortDir,
                        },
                    }
                ).then(function(response) {

                    let data = response.data;

                    this.ordersList = JSON.parse(data.items);
                    console.log(this.ordersList);
                    this.pagesCount = data.pages_count;

                    this.listUpdating = false;

                }.bind(this))
                .catch(function() {

                    this.notifyError('Load orders list error.');

                }.bind(this));
            },

            modalSelectCustomer() {

                this.$modal.show(
                    SelectOrderCustomerModal,
                    {
                        'modal-data': {
                            onConfirm: function (item) {
                                this.order.customer_data = this.clone(item, true);
                                this.order.customer_id = item.id;
                            }.bind(this),
                        }
                    },
                    {
                        adaptive: true,
                        height: 'auto',
                    },
                    {
                        'before-close': event => {
                        }
                    }
                );
            },

            addNew() {
                console.log(this.order);
                this.saving = true;

                axios.post('/orders/add-new',
                    this.order
                ).then(function(response) {

                    this.notifySuccess('Order ID:' + response.data + ' successfully added.');
                    this.closeBox();
                    this.saving = false;
                    this.getList();

                }.bind(this))
                .catch(function() {

                    this.saving = false;
                    this.notifyError('Add order error.');

                }.bind(this));
            },

            /*deleteItem(id) {

                axios.get(
                    '/orders/delete',
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
