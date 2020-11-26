<template>

    <div id="ordersList" class="row">

        <div class="col-md-12">
            <div class="row">

                <div class="col-md-12">

                    <div class="btn-box" v-if="mode === 'list'">

                        <button class="btn btn-info btn-fill btn-icon"
                                @click="openBox">
                            <i class="fa fa-plus"></i> New Order
                        </button>

                    </div>

                    <div class="btn-box" v-if="mode === 'add_new' || mode === 'edit'">

                        <button class="btn btn-success btn-fill btn-icon"
                                @click="onSave">
                            <i v-if="saving" class="fa fa-spinner anim-rotate"></i>
                            <i v-else class="fa fa-check"></i>
                            Save
                        </button>
                        <button class="btn btn-warning btn-fill btn-icon"
                                @click="onCancel">
                            <i class="fa fa-ban"></i> Cancel
                        </button>

                    </div>

                    <div class="btn-box" v-if="mode === 'show'">

                        <div class="btn-box">
                            <button class="btn btn-primary btn-fill btn-icon"
                                    @click="closeOrder">
                                <i class="fa fa-arrow-left"></i> Back
                            </button>
                        </div>

                        <div v-if="
                            orderSelected.status &&
                            !inArray(orderSelected.status.slug, [
                                'delivery', 'delivered', 'completed', 'archived', 'declined'
                            ])
                        " class="btn-box">
                            <button class="btn btn-info btn-fill btn-icon"
                                    @click="edit">
                                <i class="fa fa-edit"></i> Edit Order
                            </button>
                        </div>

                    </div>


                    <!--Order Status Manage-->
                    <div class="btn-box" v-if="orderSelected.status && mode === 'show'">

                        <div class="btn-box" v-if="
                            orderSelected.status.slug === 'new'
                        ">

                            <button class="btn order-status-btn btn-fill btn-icon"
                                    @click="setOrderStatus('accepted')">
                                <i class="fa fa-arrow-right"></i> Accept
                            </button>

                        </div>

                        <div class="btn-box" v-if="
                            orderSelected.status.slug === 'accepted'
                        ">

                            <button class="btn order-status-btn btn-fill btn-icon"
                                    @click="setOrderStatus('cooking')">
                                <i class="fa fa-arrow-right"></i> Cooking
                            </button>

                        </div>

                        <div class="btn-box" v-if="
                            orderSelected.status.slug === 'cooking'
                        ">

                            <button class="btn order-status-btn btn-fill btn-icon"
                                    @click="setOrderStatus('ready')">
                                <i class="fa fa-arrow-right"></i> Ready
                            </button>

                        </div>

                        <div class="btn-box" v-if="
                            orderSelected.status.slug === 'ready'
                        ">

                            <button class="btn order-status-btn btn-fill btn-icon"
                                    @click="setOrderStatus('delivery')">
                                <i class="fa fa-arrow-right"></i> Delivery
                            </button>

                        </div>

                        <div class="btn-box" v-if="
                            orderSelected.status.slug === 'delivery'
                        ">

                            <button class="btn order-status-btn btn-fill btn-icon"
                                    @click="setOrderStatus('delivered')">
                                <i class="fa fa-arrow-right"></i> Delivered
                            </button>

                        </div>

                        <div class="btn-box" v-if="
                            orderSelected.status.slug === 'delivered'
                        ">

                            <button class="btn btn-success btn-fill btn-icon"
                                    @click="setOrderStatus('completed')">
                                <i class="fa fa-check"></i> Completed
                            </button>

                        </div>

                        <div class="btn-box" v-if="
                            orderSelected.status.slug === 'completed'
                        ">

                            <button class="btn btn-default btn-fill btn-icon"
                                    @click="setOrderStatus('archived')">
                                <i class="fas fa-archive"></i> Archive
                            </button>

                        </div>

                        <div class="btn-box" v-if="
                            !inArray(orderSelected.status.slug, [
                                'new', 'declined', 'archived'
                            ])
                        ">

                            <button class="btn btn-warning btn-fill btn-icon"
                                    @click="setOrderStatus('declined')">
                                <i class="fa fa-ban"></i> Decline
                            </button>

                        </div>

                        <div class="btn-box" v-if="
                            orderSelected.status.slug === 'declined'
                        ">

                            <button class="btn btn-danger btn-fill btn-icon"
                                    @click="modalDelete(orderSelected.id)">
                                <i class="fa fa-trash"></i> Delete Order
                            </button>

                        </div>

                    </div>

                </div>

            </div>
        </div>


        <div id="edit-order-box">
            <div v-if="mode === 'show' || mode === 'edit'" class="col-md-12 mt-10">

                <div class="col-md-12">
                    <h3 v-if="mode === 'show'">Order ID:{{ orderSelected.id }}</h3>
                    <h3 v-if="mode === 'edit'">Order ID:{{ orderSelected.id }} edit</h3>
                </div>

                <div class="col-md-12">
                    <h4>Customer Data</h4>
                </div>

                <div class="col-md-12">
                    <order-customer-data :data="orderSelected.customer"></order-customer-data>
                </div>

                <div v-if="mode === 'edit'" class="col-md-12 mb-20 customer-change">

                    <button class="btn btn-default btn-icon"
                            @click="modalSelectCustomer">
                        <span>
                            <i class="fa fa-user-edit"></i> Change Customer
                        </span>
                    </button>

                </div>

                <div v-if="orderSelected.comments[0]" class="col-md-12">
                    <div class="form-group">
                        <label>Customer comment</label>
                        <textarea rows="5" class="form-control" :readonly="mode === 'show'"
                                  v-model="orderSelected.comments[0].content"></textarea>
                    </div>
                </div>

                <div class="col-md-12 ing-header">
                    <h4>Ingredients</h4>
                </div>

                <order-ingredients ref="orderIngEdit"
                                   :mode="mode"
                                   :order-pizza-sets="orderSelected.pizza_sets"
                                   :order-add-prods="orderSelected.products"
                                   :pizza-sets-list="pizzaSetsList"
                                   :add-prods-list="addProductsList"
                                   @on-ing-list-change="setOrderData"></order-ingredients>

            </div>
        </div>


        <transition name="slide-down">

            <div v-if="objLength(order) > 0" v-show="mode === 'add_new'" class="col-md-12 mt-10">
                <div class="row new-order-box">

                    <div class="col-md-12">
                        <h4>Customer Data</h4>
                    </div>

                    <div class="col-md-6">

                        <div v-if="objLength(order.customer_data) > 0" class="col-12">

                            <order-customer-data :data="order.customer_data"></order-customer-data>

                        </div>

                        <div class="col-12 customer-change">

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

                    <order-ingredients ref="orderIngNew"
                                       :mode="mode"
                                       :order-pizza-sets="order.pizza_sets"
                                       :order-add-prods="order.products"
                                       :pizza-sets-list="pizzaSetsList"
                                       :add-prods-list="addProductsList"
                                       @on-ing-list-change="setOrderData"></order-ingredients>

                </div>

            </div>

        </transition>


        <div v-if="mode === 'list' || mode === 'add_new'" class="col-md-12 orders-list">

            <div class="row orders-navigate">

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


            <div class="row">

                <div id="boxed-list" class="col-12">
                    <div class="row list">

                        <div v-for="(item, index) in ordersList" :key="item.id" class="col-md-12 box boxed-list-box">

                            <div class="col-md-12 data-top">

                                <div class="col-md-4 name-block">

                                    <span class="text-muted order-id">
                                        #{{ item.id }}
                                    </span>
                                    <span class="data-line customer-name">
                                        <i class="fas fa-user"></i>
                                        {{ item.customer.name }}
                                    </span>

                                </div>

                                <div class="col-md-3 data-block">

                                    <span class="data-line">
                                        <i class="fas fa-phone-alt"></i>
                                        {{ item.customer.phone }}
                                    </span>
                                    <span class="data-line">
                                        <i class="fas fa-table"></i>
                                        {{ item.ordered_at }}
                                    </span>
                                    <span v-if="item.last_updated_at !== item.ordered_at" class="data-line">
                                        <i class="fas fa-edit"></i>
                                        {{ item.last_updated_at }}
                                    </span>

                                </div>

                                <div class="col-md-2 add-block">

                                    <span class="data-line">
                                        <i class="fas fa-weight-hanging"></i>
                                        {{ item.weight }} g.
                                    </span>
                                    <span class="data-line">
                                        <i class="fas fa-dollar-sign"></i>
                                        {{ item.cost }}
                                    </span>

                                </div>

                                <div class="col-md-1 text-center">
                                    <span class="order-status-label" :class="'status-' + item.status.slug">
                                        {{ item.status.name }}
                                    </span>
                                </div>

                                <div class="col-md-2 text-right">

                                    <button class="btn btn-primary btn-sm order-btn open-order-btn"
                                            @click="openOrder(item.id)">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button v-if="
                                        item.status &&
                                        !inArray(item.status.slug, [
                                            'delivery', 'delivered', 'completed', 'archived', 'declined'
                                        ])
                                    " class="btn btn-info btn-sm order-btn open-order-btn"
                                            @click="openOrder(item.id, true)">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                </div>

                            </div>

                            <div class="col-md-12 address-block">
                                    <span class="data-line customer-address">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ item.customer.address }}
                                    </span>
                            </div>

                        </div>

                    </div>
                </div>

                <div v-if="ordersList.length === 0" class="col-12 text-center">
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

</template>


<style scoped lang="scss">

    @import '../../../sass/variables';
    @import '../../../sass/mixins';

    .new-order-box {

        h4 {
            margin-top: 15px;
        }

        .ing-header {
            margin-bottom: 0;
            margin-top: 30px;
        }
    }

    .btn-box {
        margin-top: 3px;
    }

    .order-status-btn {
        &.btn-fill {
            background-color: $purple;
            border-color: $purple;

            &:hover {
                background-color: $dark-purple;
                border-color: $dark-purple;
            }

            &:active:focus {
                border-color: $darker-purple;
            }
        }
    }


    .customer-change {

        @include maxw(990) {
            margin-bottom: 15px;
        }
    }

    .orders-list {

        margin-top: 20px;

        .name-block {

            @include maxw(990) {
                margin-bottom: 12px;

                .customer-name {
                    font-size: 18px;
                }
            }
        }

        .data-block {

            @include maxw(990) {
                margin-bottom: 12px;
            }
        }

        .add-block {

            @include maxw(990) {
                margin-bottom: 12px;
            }
        }

        .address-block {

            @include maxw(990) {
                margin-top: 40px;
            }
        }

        .update-list-btn {
            position: relative;
            top: 28px;

            @include maxw(990) {
                top: 0;
            }
        }

        .order-btn {
            position: relative;
            top: 4px;

            @include maxw(990) {
                top: 30px;
            }
            @include minmaxw(991,1200) {
                top: 57px;
            }
        }

        .customer-name {
            display: inline-block !important;
            font-size: 16px;
        }

        .customer-address {
            font-size: 15px;
        }

        .order-id {
            display: inline-block;
            margin-right: 5px;
        }

        .order-status-label {
            color: white;
            background-color: $s-soft-gray;
            padding: 8px 14px;
            border-radius: 6px;
            position: relative;
            top: 10px;

            &.status-new {
                background-color: $blue;
            }
            &.status-accepted {
                background-color: $cyan;
            }
            &.status-cooking {
                background-color: $purple;
            }
            &.status-ready {
                background-color: $red;
            }
            &.status-delivery {
                background-color: $orange;
            }
            &.status-delivered {
                background-color: $yellow;
                color: $s-dark-gray;
            }
            &.status-declined {
                background-color: $pink;
            }
            &.status-completed {
                background-color: $green;
            }
            &.status-archived {
                background-color: $s-soft-gray;
            }
        }
    }

</style>

<script>

    import Utils from '../../mixins/Utils';
    import Notify from '../../mixins/Notify';
    import Pagination from '../../mixins/Pagination';
    import SelectOrderCustomerModal from "./SelectOrderCustomerModal";
    import DialogModal from "../DialogModal";

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
                orderSelected: {},
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
                if (this.hop(this.$refs, 'orderIngNew')) {
                    this.$refs.orderIngNew.clearData();
                }
            },

            paginate(page) {
                this.page = page;
                this.getList();
            },

            onSave() {
                switch (this.mode) {
                    case 'add_new': {
                        this.addNew();
                        break;
                    }
                    case 'edit': {
                        this.saveOrder();
                        break;
                    }
                }
            },

            onCancel() {
                switch (this.mode) {
                    case 'add_new': {
                        this.closeBox();
                        break;
                    }
                    case 'edit': {
                        this.closeOrder();
                        break;
                    }
                }
            },

            openBox() {
                this.mode = 'add_new';
            },

            closeBox() {
                this.mode = 'list';
                this.initEmptyOrder();
            },

            openOrder(id, toEdit = false) {
                const editBox = document.getElementById('edit-order-box');

                axios.get(
                    '/orders/get-order-data',
                    {
                        params: {
                            id
                        },
                    }
                ).then(function(response) {

                    let data = response.data;
                    console.log(data);
                    this.mode = (toEdit) ? 'edit' : 'show';
                    this.orderSelected = data;

                    this.$smoothScroll({
                        scrollTo: editBox,
                    });

                }.bind(this));
            },

            edit() {
                this.mode = 'edit';
            },

            closeOrder() {
                this.mode = 'list';
                this.orderSelected = {};
            },

            saveOrder() {
                this.saving = true;

                axios.post('/orders/save',
                    this.orderSelected
                ).then(function(response) {

                    this.notifySuccess('Order ID:' + this.orderSelected.id + ' successfully saved.');
                    this.closeOrder();
                    this.saving = false;
                    this.getList();

                }.bind(this))
                .catch(function() {

                    this.saving = false;
                    this.notifyError('Save order error.');

                }.bind(this));
            },

            setOrderStatus(status) {

                this.$modal.show(
                    DialogModal,
                    {
                        'modal-data': {
                            header: `Change order status`,
                            text: `Order status will be changed to "${status}".`,
                            onConfirm: function () {

                                this.saving = true;

                                axios.post('/orders/set-status',
                                    {
                                        order_id: this.orderSelected.id,
                                        status
                                    }
                                ).then(function(response) {

                                    this.notifySuccess('Order status successfully updated.');
                                    this.closeOrder();
                                    this.saving = false;
                                    this.getList();

                                }.bind(this))
                                .catch(function() {

                                    this.saving = false;
                                    this.notifyError('Set order status error.');

                                }.bind(this));

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
                let target = (this.mode === 'add_new') ? 'order' : 'orderSelected';

                this[target].cost = data.cost;
                this[target].weight = data.weight;
                this[target].pizza_sets = this.clone(data.pizza_sets, true);
                this[target].products = this.clone(data.products, true);
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
                    //console.log(this.ordersList);
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
                                let copy = this.clone(item, true);

                                if (this.mode === 'add_new') {
                                    this.order.customer_data = copy;
                                    this.order.customer_id = copy.id;
                                } else {
                                    this.orderSelected.customer = copy;
                                }
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
                //console.log(this.order);
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

            modalDelete(id) {

                this.$modal.show(
                    DialogModal,
                    {
                        'modal-data': {
                            header: `Delete order ID:${id}`,
                            text: 'Order will be deleted.',
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
                    '/orders/delete',
                    {
                        params: {
                            id
                        },
                    }
                ).then(function(response) {

                    this.notifySuccess(`Order ID:${id} successfully deleted.`);
                    this.closeOrder();
                    this.getList();

                }.bind(this))
                .catch(function() {

                    this.notifyError('Delete order error.');

                }.bind(this));
            }
        }
    }
</script>
