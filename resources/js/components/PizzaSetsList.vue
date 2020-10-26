<template>

    <div id="pizzaSetsList" class="container">
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
                                @click="addNewSet"> 
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

                    <div v-show="mode === 'add_new'" class="col-12 mt-10">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" v-model="pizzaset.name">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pizzaBaseSelect">Base</label>
                                                    <select v-model="pizzaset.baseId" class="form-control" 
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
                                                    <input type="file" id="pizzasetImage" ref="pizzasetImageFile"
                                                        @change="handleFileUpload">
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row pizzaset-products-box">

                                            <!-- <div class="col-md-12">
                                                
                                            </div> -->

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea rows="5" class="form-control" v-model="pizzaset.description"></textarea>
                                </div>
                               
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                        </div>
                    </div>

                </transition>

            </div>


            <!-- <div class="col-12 mt-10">
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="prodTypeFilter">Show by type</label>
                            <select v-model="byType" class="form-control" id="prodTypeFilter" @change="getProdsList">
                                <option value="0">all</option>
                                <option v-for="type in prodTypesList" :key="type.id"
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


            <div v-if="prodsList.length > 0" class="col-12 mt-15 table-responsive table-full-width">
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
                        <tr v-for="(prod,index) in prodsList" :key="prod.id">
                            <td>{{ prod.id }}</td>
                            <td>
                                <img class="prod-image gallery-image" :src="prod.image_url" 
                                    alt="prod image" @click="openGallery(index)">
                            </td>
                            <td>{{ prod.name }}</td>
                            <td>{{ prod.type.name }}</td>
                            <td>${{ prod.cost }}</td>
                            <td>{{ prod.weight }} g.</td>

                            <td class="text-center">

                                <button class="btn btn-info btn-sm"
                                    @click="editProdModal(prod.id)"> 
                                    <i class="fa fa-edit"></i>
                                </button>

                                <button v-if="prod.description !== ''" class="btn btn-default btn-sm"
                                    @click="prodDetailsModal(prod.id)"> 
                                    <i class="fa fa-info"></i>
                                </button>

                                <button class="btn btn-danger btn-sm"
                                    @click="deleteProdModal(prod.id)"> 
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
            <div v-if="prodsList.length === 0" class="col-12 text-center mt-25">
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
            </div> -->
            
        </div>
    </div>

</template>


<script>

    import Utils from '../mixins/Utils';
    import Notify from '../mixins/Notify';
    import LightBox from 'vue-image-lightbox';
    //import ProductDetailsModal from './ProductDetailsModal';
    //import EditProductModal from './EditProductModal';

    const PizzaSetRef = {
        name: '',
        baseId: 1,
        description: '',
        products: [],
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

                pizzaset: {},
                pizzaBasesList: [],
                pizzaProductsList: [],
                setsList: [],
                lbData: [],
            }
        },

        mixins: [
            Utils,
            Notify,
        ],

        components: {
            LightBox,
        },

        created() {
            this.initEmptySet();
            this.getPizzaProdsList();
            //this.getSetsList();
        },

        methods: {

            initEmptySet() {
                this.pizzaset = this.clone(PizzaSetRef);
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
                    this.getSetsList();
                }
            },

            getPizzaProdsList() {

                axios.get(
                    '/pizza-sets/get-prods-list'
                ).then(function(response) {

                    let data = response.data;
                    this.pizzaBasesList = data.bases_list;
                    this.pizzaProductsList = data.prods_list;

                }.bind(this));
            },

            getSetsList() {
                this.updating = true;

                axios.get(
                    '/pizza-sets/get-sets-list', 
                    {
                        params: {
                            page: this.page,
                            per_page: this.perPage,
                            sort_field: this.sortField,
                            sort_dir: this.sortDirection,
                        },
                    }
                ).then(function(response) { 
                    
                    //console.log(response.data);
                    let data = response.data;
                    let prods = JSON.parse(data.items);

                    let _lbData = [];
                    prods.forEach(function(prod) {
                        _lbData.push({
                            thumb: prod.image_url,
                            src: prod.image_url,
                        });
                    });
                    this.lbData = _lbData;

                    this.prodsList = prods;
                    this.pagesCount = data.pages_count;

                    this.updating = false;

                }.bind(this))
                .catch(function() {

                    this.notifyError('Load pizza sets list error.');

                }.bind(this));
            },

            openBox() {
                this.mode = 'add_new';
            },

            addNewSet() {
                let formData = new FormData();

                for (let prop in this.pizzaset) {
                    formData.append(prop, this.pizzaset[prop]);
                }

                axios.post('/pizza-sets/add-new-set',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        }
                    }
                ).then(function(response) {
                    
                    this.notifySuccess('Pizza set ID:' + response.data + ' successfully added.');
                    this.closeBox();
                    this.getSetsList();

                }.bind(this))
                .catch(function() {
                    
                    this.notifyError('Add pizza set error.');
                    
                }.bind(this));
            },

            closeBox() {
                this.mode = 'list';
                this.initEmptySet();
            },

            getPizzaSetById(id) {
                return this.setsList.filter((_item) => _item.id === id)[0];
            },

            editProdModal(id) {

                let set = this.getPizzaSetById(id);

                this.$modal.show(
                    EditProductModal,
                    {
                        'prod-data': set,
                        'prod-types-list': this.prodTypesList,
                    },
                    {
                        adaptive: true,
                        height: 'auto',
                    },
                    { 
                        'before-close': event => {
                            this.getSetsList();
                        } 
                    }
                );
            },

            prodDetailsModal(id) {

                let prod = this.getPizzaSetById(id);

                this.$modal.show(
                    ProductDetailsModal,
                    {
                        'prod-data': prod
                    },
                    {
                        adaptive: true,
                        height: 'auto',
                    }
                );
            },

            deleteProdModal(id) {
                
                this.$modal.show(
                    'dialog',
                    {
                        title: 'Delete product',
                        text: `Product with ID:${id} will be deleted.`,
                        buttons: [
                            {
                                title: 'Ok',
                                handler: () => {
                                    this.deleteProd(id);
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

            deleteProd(id) {
                
                axios.get(
                    '/products/delete-prod',
                    {
                        params: {
                            id
                        },
                    }
                ).then(function(response) {

                    this.$modal.hide('dialog');
                    this.notifySuccess(`Product ID:${id} successfully deleted.`);
                    this.getSetsList();

                }.bind(this))
                .catch(function() {

                    this.$modal.hide('dialog');
                    this.notifyError('Delete product error.');

                }.bind(this));
            },

            handleFileUpload() {
                this.pizzaset.imageFile = this.$refs.prodImageFile.files[0];
            },

            openGallery(index) {
                this.$refs.lightbox.showImage(index);
            }
        }
    }
</script>
