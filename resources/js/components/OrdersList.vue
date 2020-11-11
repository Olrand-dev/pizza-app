<template>

    <div id="ordersList" class="row">

        <div class="col-md-12">
            <div class="row">

                <div class="col-md-10">

                </div>

                <div class="col-md-2 pull-right text-right">

                    <button v-if="mode === 'list'" class="btn btn-info btn-fill btn-icon"
                            @click="openBox">
                        <i class="fa fa-plus"></i> New Order
                    </button>
                    <button v-if="mode === 'add_new'" class="btn btn-default btn-icon"
                            @click="closeBox">
                        <i class="fa fa-times"></i> Close
                    </button>

                </div>

            </div>
        </div>

        <transition name="slide-down">

            <div v-if="order" v-show="mode === 'add_new'" class="col-md-12 mt-10">
                <div class="row">

                    <div class="col-md-6">

                        <div class="col-12"></div>
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

                </div>
            </div>

        </transition>

    </div>

</template>


<style scoped lang="scss">

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

                order: {},
                ordersList: [],
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
            //this.getList();
        },

        methods: {

            initEmptyOrder() {
                this.order = this.clone(OrderRef, true);
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

                }.bind(this));
            },

            /*getList(resetPage = false) {
                this.listUpdating = true;
                if (resetPage) this.page = 1;

                axios.get(
                    '/orders/get-list',
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

                }.bind(this));
            },*/

            modalSelectCustomer() { //todo: добавить компонент выбора покупателя заказа

                this.$modal.show(
                    SelectOrderCustomerModal,
                    {
                        'modal-data': {
                            onConfirm: function (id) { console.log(id);
                                this.order.customer_id = id;
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

            /*addNewSet() {
                let formData = new FormData();

                for (let prop in this.pizzaSet) {
                    let propData = this.pizzaSet[prop];

                    if (prop !== 'image_file' && this.isObject(propData)) {
                        propData = JSON.stringify(propData);
                    }
                    formData.append(prop, propData);
                }

                this.saving = true;

                axios.post('/orders/add-new',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        }
                    }
                ).then(function(response) {

                    this.notifySuccess('Pizza set ID:' + response.data + ' successfully added.');
                    this.closeBox();
                    this.getList();

                }.bind(this))
                .catch(function() {

                    this.notifyError('Add pizza set error.');

                }.bind(this));
            },*/

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
