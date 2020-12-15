<template>

    <div id="productsList" class="container">
        <div class="row">

            <div class="col-12">

                <div class="row">
                    <div class="col-md-12">

                        <div v-if="p('uiButtonAddNew') && mode === 'list'">
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

                            <div class="col-md-6">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Name *</label>
                                            <input type="text" class="form-control" v-model="product.name">
                                            <span v-if="checkErr('name')" class="error">
                                                {{ getErr('name') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="prodTypesSelect">Type *</label>
                                                    <select v-model="product.type_id" class="form-control"
                                                            id="prodTypesSelect">
                                                        <option v-for="type in prodTypesList" :key="type.id"
                                                                :value="type.id">
                                                            {{ type.name }}
                                                        </option>
                                                    </select>
                                                    <span v-if="checkErr('type_id')" class="error">
                                                        {{ getErr('type_id') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Cost ($) *</label>
                                                    <input type="number" step="0.01" min="0.01"
                                                           class="form-control" v-model="product.cost">
                                                    <span v-if="checkErr('cost')" class="error">
                                                        {{ getErr('cost') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Weight (g) *</label>
                                                    <input type="number" step="1" min="1"
                                                           class="form-control" v-model="product.weight">
                                                    <span v-if="checkErr('weight')" class="error">
                                                        {{ getErr('weight') }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Image (.jpg, .jpeg, .png)</label>
                                                    <input type="file" id="productImage" ref="prodImageFile"
                                                           @change="handleFileUpload">
                                                    <span v-if="checkErr('image_file')" class="error">
                                                        {{ getErr('image_file') }}
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea rows="5" class="form-control" v-model="product.description"></textarea>
                                    <span v-if="checkErr('description')" class="error">
                                        {{ getErr('description') }}
                                    </span>
                                </div>

                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                        </div>
                    </div>

                </transition>

            </div>


            <div class="col-12 mt-10">
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="prodTypeFilter">Show by type</label>
                            <select v-model="byType" class="form-control" id="prodTypeFilter"
                                    @change="getList(true)">
                                <option value="0">all</option>
                                <option v-for="type in prodTypesList" :key="type.id"
                                        :value="type.id">
                                    {{ type.name }}
                                </option>
                            </select>
                        </div>
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

                    <div v-if="listUpdating" class="col-md-1">
                        <span class="list-update top">
                            <i class="fa fa-spinner anim-rotate"></i>
                        </span>
                    </div>

                </div>
            </div>


            <div v-if="prodsList.length > 0" class="col-12 mt-15 table-responsive table-full-width">
                <table class="table table-hover table-striped sortable">

                    <thead>
                    <th v-for="(header,index) in tableHeaders" :key="index"
                        :class="{ 'sortable': hop(sortableHeaders, header) }" @click="onTableHeaderClick(header)">
                        {{ header }}
                        <i v-if="header === selectedSortableHeader && sortDirection === 'desc'"
                           class="fa fa-sort-down"></i>
                        <i v-if="header === selectedSortableHeader && sortDirection === 'asc'"
                           class="fa fa-sort-up"></i>
                    </th>
                    <th class="text-center">Actions</th>
                    </thead>

                    <tbody>
                    <tr v-for="(item,index) in prodsList" :key="item.id">
                        <td>{{ item.id }}</td>
                        <td>
                            <img class="prod-image gallery-image" :src="item.image_thumbs.w_300"
                                 alt="prod image" @click="openGallery(index)">
                        </td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.type.name }}</td>
                        <td>${{ item.cost }}</td>
                        <td>{{ item.weight }} g.</td>

                        <td class="text-center">

                            <button v-if="p('uiButtonEdit')" class="btn btn-info btn-sm"
                                    @click="modalEdit(item.id)">
                                <i class="fa fa-edit"></i>
                            </button>

                            <button v-if="p('uiButtonDetails') && item.description !== ''" class="btn btn-default btn-sm"
                                    @click="modalDetails(item.id)">
                                <i class="fa fa-info"></i>
                            </button>

                            <button v-if="p('uiButtonDelete')" class="btn btn-danger btn-sm"
                                    @click="modalDelete(item.id)">
                                <i class="fa fa-trash"></i>
                            </button>

                        </td>
                    </tr>
                    </tbody>

                </table>

                <LightBox ref="lightbox" :media="lbData" :show-light-box="false"
                          :show-caption="false" :show-thumbs="false"></LightBox>

                <v-dialog/>

            </div>
            <div v-if="prodsList.length === 0" class="col-12 text-center mt-25">
                <span class="no-items">No items to show.</span>
            </div>


            <div class="col-12">
                <div class="row">
                    <div class="col-md-12">

                        <pagination ref="pagin1" :page="page" :pages-count="pagesCount" @click-handler="paginate"
                                    range="5"></pagination>

                    </div>
                </div>
            </div>

        </div>
    </div>

</template>


<style scoped lang="scss">

    .prod-image {
        max-height: 70px;
    }

</style>


<script>

    import Utils from '../../mixins/Utils';
    import Notify from '../../mixins/Notify';
    import Validation from '../../mixins/Validation';
    import Pagination from '../../mixins/Pagination';
    import Permissions from '../../mixins/Permissions';
    import Sortable from '../../mixins/Sortable';
    import LightBox from 'vue-image-lightbox';
    import ProductDetailsModal from './ProductDetailsModal';
    import EditProductModal from './EditProductModal';

    const ProductRef = {
        id: 0,
        name: '',
        cost: 1.0,
        weight: 100,
        description: '',
        type_id: 1,
        image_file: '',
    };

    export default {

        data() {
            return {
                mode: 'list',
                listUpdating: true,
                saving: false,

                tableHeaders: [
                    'ID', 'Image', 'Name', 'Type', 'Cost', 'Weight',
                ],
                sortableHeaders: {
                    'ID': 'id', 'Name': 'name', 'Cost': 'cost', 'Weight': 'weight',
                },
                selectedSortableHeader: 'Name',
                sortField: 'name',
                byType: 0,

                product: {},
                prodTypesList: [],
                prodsList: [],
                lbData: [],
            }
        },

        mixins: [
            Utils,
            Notify,
            Validation,
            Pagination,
            Permissions,
            Sortable,
        ],

        components: {
            LightBox,
        },

        created() {
            this.initProdData();
            this.getProdTypesList();
            this.getList();
            this.getPermissionsList('product');
        },

        methods: {

            initProdData() {
                this.product = this.clone(ProductRef);
            },

            sortableHeaderClickHandler() {
                this.getList();
            },

            getProdTypesList() {

                axios.get(
                    '/products/get-types-list'
                ).then(function(response) {

                    this.prodTypesList = response.data;

                }.bind(this));
            },

            getList(resetPage = false) {
                this.listUpdating = true;
                if (resetPage) this.page = 1;

                axios.get(
                    '/products/get-list',
                    {
                        params: {
                            page: this.page,
                            per_page: this.perPage,
                            sort_field: this.sortField,
                            sort_dir: this.sortDirection,
                            by_type: this.byType,
                        },
                    }
                ).then(function(response) {

                    //console.log(response.data);
                    let data = response.data;
                    let prods = JSON.parse(data.items);

                    let _lbData = [];
                    prods.forEach(function(prod) {
                        _lbData.push({
                            thumb: prod.image_thumbs.w_300,
                            src: prod.image_url,
                        });
                    });
                    this.lbData = _lbData;

                    this.prodsList = prods;
                    this.pagesCount = data.pages_count;

                    this.listUpdating = false;

                }.bind(this))
                .catch(function() {

                    this.notifyError('Load products list error.');

                }.bind(this));
            },

            openBox() {
                this.mode = 'add_new';
            },

            closeBox() {
                this.mode = 'list';
                this.clearErrors();
                this.initProdData();
            },

            addNew() {
                let formData = new FormData();

                for (let prop in this.product) {
                    formData.append(prop, this.product[prop]);
                }

                this.saving = true;

                axios.post('/products/add-new',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        }
                    }
                ).then(function(response) {

                    this.notifySuccess('Product ID:' + response.data + ' successfully added.');
                    this.clearErrors();
                    this.closeBox();
                    this.saving = false;
                    this.getList();

                }.bind(this))
                .catch(function(error) {

                    this.saving = false;
                    if (this.checkValidationErrors(error.response.data)) {
                        this.notifyError('Form validation error.', 1500);
                        return;
                    }
                    this.notifyError('Add product error.');

                }.bind(this));
            },

            modalEdit(id) {

                let prod = this.getItemById(this.prodsList, id);

                this.$modal.show(
                    EditProductModal,
                    {
                        'prod-data': prod,
                        'prod-types-list': this.prodTypesList,
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

            modalDetails(id) {

                let prod = this.getItemById(this.prodsList, id);

                this.$modal.show(
                    ProductDetailsModal,
                    {
                        'prod-data': prod
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

            modalDelete(id) {

                this.$modal.show(
                    'dialog',
                    {
                        title: 'Delete product',
                        text: `Product with ID:${id} will be deleted.`,
                        buttons: [
                            {
                                title: 'Ok',
                                handler: () => {
                                    this.deleteItem(id);
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
            },

            deleteItem(id) {

                axios.get(
                    '/products/delete',
                    {
                        params: {
                            id
                        },
                    }
                ).then(function(response) {

                    this.$modal.hide('dialog');

                    let data = response.data;
                    if (data.status && data.status === 'error') {
                        this.notifyError(data.message);
                        return;
                    }

                    this.notifySuccess(`Product ID:${id} successfully deleted.`);
                    this.getList();

                }.bind(this))
                .catch(function() {

                    this.$modal.hide('dialog');
                    this.notifyError('Delete product error.');

                }.bind(this));
            },

            handleFileUpload() {
                this.product.image_file = this.$refs.prodImageFile.files[0];
            },

            openGallery(index) {
                this.$refs.lightbox.showImage(index);
            }
        }
    }
</script>
