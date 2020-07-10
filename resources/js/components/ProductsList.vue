<template>

    <div id="productsList" class="container">
        <div class="row">

            <div class="col-md-12">

                <div class="col-12">

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
                

                <transition name="slide-down">

                    <div v-show="mode === 'add_new'" class="col-12">
                        <div class="row">

                            <div class="col-md-7">
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

                            <div class="col-md-5">
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


            <div class="col-12 table-responsive table-full-width">
                <table class="table table-hover table-striped">

                    <thead>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Cost</th>
                        <th>Weight</th>
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

                page: 1,
                perPage: 10,
                sort: 'name_desc',
                byType: 'all',

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

            getProdTypesList() {

                axios.get(
                    '/products/get-prod-types-list'
                ).then(function(response) {

                    this.productTypesList = response.data;

                }.bind(this));
            },

            getProdsList() {

                axios.get(
                    '/products/get-prods-list'
                ).then(function(response) { 
                    
                    //console.log(response.data);
                    this.prodsList = response.data;

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
