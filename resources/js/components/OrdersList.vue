<template>

    <div id="ordersList" class="container">
        <div class="row">

            <div class="col-12">

                <div class="row">
                    <div class="col-md-12">

                        <div v-if="mode === 'list'">
                            <button class="btn btn-info btn-fill btn-icon"
                                    @click="openBox">
                                <i class="fa fa-plus"></i> Add New
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


                <transition name="slide-down">

                    <div v-show="mode === 'add_new'" class="col-12 mt-10">
                        <div class="row">

                            <!--<div class="col-md-5">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" v-model="pizzaSet.name">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pizzaBaseSelect">Base</label>
                                                    <select v-model="pizzaSet.base_id" class="form-control"
                                                        id="pizzaBaseSelect">
                                                        <option v-for="base in pizzaBasesList" :key="base.id"
                                                                :value="base.id">
                                                            {{ base.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Image (.jpg, .jpeg, .png)</label>
                                                    <input type="file" id="pizzaSetImage" ref="pizzaSetImageFile"
                                                           @change="handleFileUpload">
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>-->

                        </div>
                    </div>

                </transition>

            </div>


            <!--<div class="col-12 mt-10">
                <div class="row">

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

                    <div v-if="listUpdating" class="col-md-1">
                        <span class="list-update top">
                            <i class="fa fa-spinner anim-rotate"></i>
                        </span>
                    </div>

                </div>
            </div>


            <div v-if="setsList.length > 0" class="col-12 mt-15 table-responsive table-full-width">
                <table class="table table-hover table-striped sortable">

                    <thead>
                        <th v-for="(header,index) in tableHeaders" :key="index"
                            :class="{ 'sortable': hop(sortableHeaders, header) }" @click="onTableHeaderClick(header)">
                            {{ header }}
                            <i v-if="header === selectedSortableHeader && sortDirection === 'desc'" class="fa fa-sort-down"></i>
                            <i v-if="header === selectedSortableHeader && sortDirection === 'asc'" class="fa fa-sort-up"></i>
                        </th>
                        <th class="text-center">Actions</th>
                    </thead>

                    <tbody>
                        <tr v-for="(item,index) in setsList" :key="item.id">
                            <td>{{ item.id }}</td>
                            <td>
                                <img class="pizza-set-image gallery-image" :src="item.image_url"
                                    alt="pizza set image" @click="openGallery(index)">
                            </td>
                            <td>{{ item.name }}</td>
                            <td>${{ item.cost }}</td>
                            <td>{{ item.weight }} g.</td>

                            <td class="text-center">

                                <button class="btn btn-info btn-sm"
                                    @click="editSetModal(item.id)">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <button class="btn btn-danger btn-sm"
                                    @click="deleteSetModal(item.id)">
                                    <i class="fa fa-trash"></i>
                                </button>

                            </td>
                        </tr>
                    </tbody>

                </table>

                <LightBox ref="lightbox" :media="lbData" :show-light-box="false"
                    :show-caption="false" :show-thumbs="false"></LightBox>

                <v-dialog />

            </div>
            <div v-if="setsList.length === 0" class="col-12 text-center mt-25">
                <span class="no-items">No items to show.</span>
            </div>


            <div class="col-12">
                <div class="row">
                    <div class="col-md-12">

                        <pagination ref="pagin1" :page="page" :pages-count="pagesCount" @click-handler="paginate"
                                    range="5"></pagination>

                    </div>
                </div>
            </div>-->

        </div>
    </div>

</template>


<style scoped lang="scss">



</style>


<script>

    import Utils from '../mixins/Utils';
    import Notify from '../mixins/Notify';
    import Sortable from '../mixins/Sortable';
    import Pagination from '../mixins/Pagination';
    //import EditSetModal from './EditPizzaSetModal';

    const OrderRef = {

    };

    export default {

        data() {
            return {
                mode: 'list',
                listUpdating: true,
                saving: false,

                tableHeaders: [
                    'ID', 'Image', 'Name', 'Cost', 'Weight',
                ],
                sortableHeaders: {
                    'ID': 'id', 'Name': 'name', 'Cost': 'cost', 'Weight': 'weight',
                },
                selectedSortableHeader: 'Name',
                sortField: 'name',

                order: {},
                ordersList: [],
            }
        },

        mixins: [
            Utils,
            Notify,
            Pagination,
            Sortable,
        ],

        created() {
            this.initEmptyOrder();
            this.getList();
        },

        methods: {

            initEmptyOrder() {
                this.order = this.clone(OrderRef, true);
            },

            sortableHeaderClickHandler() {
                this.getList();
            },

            paginate(page) {
                this.page = page;
                this.getList();
            },

            getList(resetPage = false) {
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
            },

            openBox() {
                this.mode = 'add_new';
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

            closeBox() {
                this.mode = 'list';
                this.initEmptyOrder();
            },

            /*editSetModal(id) {

                let order = this.getItemById(this.ordersList, id);

                this.$modal.show(
                    EditSetModal,
                    {
                        'set-data': set,
                        'bases-list': this.pizzaBasesList,
                        'ing-types-list': this.pizzaIngTypesList,
                        'ing-list': this.pizzaIngredientsList,
                    },
                    {
                        adaptive: true,
                        height: 'auto',
                    },
                    {
                        'before-close': event => {
                            this.getList();
                        }
                    }
                );
            },

            deleteSetModal(id) {

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

            deleteItem(id) {

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
            }
        }
    }
</script>
