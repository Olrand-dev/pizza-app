<template>

    <div id="productsList" class="container">
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
                                @click="addNewProduct"> 
                                <i class="fa fa-check"></i> Save
                            </button>
                            <button class="btn btn-warning btn-fill btn-icon"
                                @click="closeBox"> 
                                <i class="fa fa-ban"></i> Cancel
                            </button>
                        </div>

                    </div>
                </div>
                

                <transition name="slide-down">

                    <div v-show="mode === 'add_new'" class="col-12">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" v-model="product.name">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="prodTypesSelect">Type</label>
                                                    <select v-model="product.typeId" class="form-control" 
                                                        id="prodTypesSelect">
                                                        <option v-for="type in productTypesList" :key="type.id"
                                                            :value="type.id">
                                                            {{ type.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Cost ($)</label>
                                                    <input type="number" step="0.01" min="0.01" 
                                                        class="form-control" v-model="product.cost">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Weight (g)</label>
                                                    <input type="number" step="1" min="1" 
                                                        class="form-control" v-model="product.weight">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Image (.jpg, .jpeg, .png)</label>
                                                    <input type="file" id="productImage" ref="prodImageFile"
                                                        @change="handleFileUpload">
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea rows="5" class="form-control" v-model="product.description"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </transition>

            </div>


            <div class="col-12">
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="prodTypeFilter">Show by type</label>
                            <select v-model="byType" class="form-control" id="prodTypeFilter" @change="getProdsList">
                                <option value="0">all</option>
                                <option v-for="type in productTypesList" :key="type.id"
                                    :value="type.id">
                                    {{ type.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="perPageSelect">Per page</label>
                            <select v-model="perPage" class="form-control" id="perPageSelect" @change="getProdsList">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>

                    <div v-if="updating" class="col-md-1">
                        <span class="list-update top">
                            <i class="fa fa-spinner anim-rotate"></i>
                        </span>
                    </div>

                </div>
            </div>


            <div v-if="prodsList.length > 0" class="col-12 table-responsive table-full-width">
                <table class="table table-hover table-striped sortable">

                    <thead>
                        <th v-for="(header,index) in tableHeaders" :key="index" 
                            :class="{ 'sortable': hop(sortableHeaders, header) }" @click="onTableHeaderClick(header)">
                            {{ header }}
                            <i v-if="header === selectedSortableHeader && sortDirection === 'desc'" class="fa fa-sort-down"></i>
                            <i v-if="header === selectedSortableHeader && sortDirection === 'asc'" class="fa fa-sort-up"></i>
                        </th>
                    </thead>

                    <tbody>
                        <tr v-for="prod in prodsList" :key="prod.id">
                            <td>{{ prod.id }}</td>
                            <td>
                                <img class="prod-image" :src="prod.image_url" alt="prod image">
                            </td>
                            <td>{{ prod.name }}</td>
                            <td>{{ prod.type.name }}</td>
                            <td>${{ prod.cost }}</td>
                            <td>{{ prod.weight }} g.</td>
                        </tr>
                    </tbody>
                    
                </table>
            </div>
            <div v-if="prodsList.length === 0" class="col-12 text-center">
                <span class="no-items">No items to show.</span>
            </div>


            <div class="col-12">
                <div class="row">
                    <div class="col-md-12">

                        <paginate
                            v-model="page"
                            :page-count="pagesCount"
                            :page-range="pageRange"
                            :margin-pages="marginPages"
                            :click-handler="getProdsList"
                            :prev-text="prevText"
                            :next-text="nextText"
                            container-class="pagination"
                            page-class="page-item"
                            prev-class="prev-page-btn"
                            next-class="next-page-btn"
                            break-view-class="bv"
                        ></paginate>

                    </div>
                </div>
            </div>
            
        </div>
    </div>

</template>


<script>

    import Utils from '../mixins/Utils';
    import Notify from '../mixins/Notify';

    const ProductRef = {
        name: '',
        cost: 1.0,
        weight: 100,
        description: '',
        typeId: 1,
        imageFile: '',
    };

    export default {

        data() {
            return {
                mode: 'list',
                updating: true,

                page: 1,
                pagesCount: 1,
                pageRange: 5,
                perPage: 10,
                marginPages: 1,
                prevText: 'Prev.',
                nextText: 'Next',

                tableHeaders: [
                    'ID', 'Image', 'Name', 'Type', 'Cost', 'Weight',
                ],
                sortableHeaders: {
                    'ID': 'id', 'Name': 'name', 'Cost': 'cost', 'Weight': 'weight',
                },
                selectedSortableHeader: 'Name',
                sortField: 'name',
                sortDirection: 'desc',
                byType: 0,

                product: {},
                productTypesList: [],
                prodsList: [],
            }
        },

        mixins: [
            Utils,
            Notify,
        ],

        created() {
            this.initEmptyProd();
            this.getProdTypesList();
            this.getProdsList();
        },

        methods: {

            initEmptyProd() {
                this.product = this.clone(ProductRef);
            },

            onTableHeaderClick(header) {

                if (this.hop(this.sortableHeaders, header)) {

                    if (header !== this.selectedSortableHeader) {
                        this.selectedSortableHeader = header;
                        this.sortDirection = 'desc';
                    } else {

                        if (this.sortDirection === 'desc') {
                            this.sortDirection = 'asc';
                        } else {
                            this.sortDirection = 'desc';
                        }
                    }

                    this.sortField = this.sortableHeaders[header];
                    this.getProdsList();
                }
            },

            getProdTypesList() {

                axios.get(
                    '/products/get-prod-types-list'
                ).then(function(response) {

                    this.productTypesList = response.data;

                }.bind(this));
            },

            getProdsList() {
                this.updating = true;

                axios.get(
                    '/products/get-prods-list', 
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

                    this.prodsList = JSON.parse(data.items);
                    this.pagesCount = data.pages_count;

                    this.updating = false;

                }.bind(this))
                .catch(function() {

                    this.notifyError('Load products list error.');

                }.bind(this));
            },

            openBox() {
                this.mode = 'add_new';
            },

            addNewProduct() {
                let formData = new FormData();

                for (let prop in this.product) {
                    formData.append(prop, this.product[prop]);
                }

                axios.post('/products/add-new-product',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        }
                    }
                ).then(function(response) {
                    
                    this.notifySuccess('Product ID:' + response.data + ' successfully added.');
                    this.closeBox();
                    this.getProdsList();

                }.bind(this))
                .catch(function() {
                    
                    this.notifyError('Add product error.');
                    
                }.bind(this));
            },

            closeBox() {
                this.mode = 'list';
                this.initEmptyProd();
            },

            handleFileUpload() {
                this.product.imageFile = this.$refs.prodImageFile.files[0];
            }
        }
    }
</script>
