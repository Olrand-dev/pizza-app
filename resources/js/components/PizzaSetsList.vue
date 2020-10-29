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

                            <div class="col-md-5">
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
                                                    <select v-model="pizzaSet.baseId" class="form-control"
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

                            <div class="col-md-7">

                                <div class="row pizza-set-ingredients-box">

                                    <div class="col-md-12">
                                        <label>Ingredients</label>
                                    </div>

                                    <div v-if="pizzaSet.ingredients.length > 0" class="col-md-12">

                                        <div v-for="(prod, index) in pizzaSet.ingredients" :key="index" class="row">
                                            <div class="col-md-12 ingredient-box">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Type</label>
                                                        <select v-model="prod.typeId" class="form-control">
                                                            <option v-for="type in pizzaIngTypesList" :key="type.id"
                                                                    :value="type.id">
                                                                {{ type.name }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label>Product</label>
                                                        <select v-model="prod.prodId" class="form-control" :disabled="prod.typeId === 0">
                                                            <option v-for="prod in pizzaIngredientsList[prod.typeId]" :key="prod.id"
                                                                    :value="prod.id">
                                                                {{ prod.name }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Q-ty</label>
                                                        <input type="number" step="1" min="1"
                                                               v-model="prod.quantity" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-1 text-right">
                                                    <button class="btn btn-danger btn-sm ing-del-btn"
                                                            @click="deleteIngredient(index)">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-12">
                                        <button class="btn btn-info btn-fill btn-icon"
                                                @click="addIngredient">
                                            <i class="fa fa-plus"></i> Add Ingredient
                                        </button>
                                    </div>

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


<style scoped lang="scss">

    @import './../../sass/variables';

    .pizza-set-ingredients-box {
        padding-left: 55px;
    }

    .ingredient-box {
        border: 1px solid $s-light-gray;
        padding: 0;
        border-radius: 6px;
        position: relative;
        right: 15px;
        margin-bottom: 6px;
    }

    .ing-del-btn {
        position: relative;
        top: 28px;
        right: 12px;
    }

</style>


<script>

    import Utils from '../mixins/Utils';
    import Notify from '../mixins/Notify';
    import Sortable from '../mixins/Sortable';
    import Pagination from '../mixins/Pagination';
    import LightBox from 'vue-image-lightbox';
    //import EditProductModal from './EditProductModal';

    const PizzaSetRef = {
        name: '',
        baseId: 0,
        ingredients: [],
        imageFile: '',
    };

    const ingredientRef = {
        typeId: 0,
        prodId: 0,
        quantity: 1,
    };

    export default {

        data() {
            return {
                mode: 'list',
                updating: true,

                tableHeaders: [
                    'ID', 'Image', 'Name', 'Type', 'Cost', 'Weight',
                ],
                sortableHeaders: {
                    'ID': 'id', 'Name': 'name', 'Cost': 'cost', 'Weight': 'weight',
                },
                selectedSortableHeader: 'Name',
                sortField: 'name',

                pizzaSet: {},
                pizzaBasesList: [],
                pizzaIngTypesList: [],
                pizzaIngredientsList: [],
                setsList: [],
                lbData: [],
            }
        },

        mixins: [
            Utils,
            Notify,
            Pagination,
            Sortable,
        ],

        components: {
            LightBox,
        },

        created() {
            this.initEmptySet();
            this.getIngredientsList();
            //this.getSetsList();
        },

        methods: {

            initEmptySet() {
                this.pizzaSet = this.clone(PizzaSetRef, true);
            },

            sortableHeaderClickHandler() {
                this.getSetsList();
            },

            getIngredientsList() {

                axios.get(
                    '/pizza-sets/get-prods-list'
                ).then(function(response) {

                    let data = response.data; console.log(data);
                    this.pizzaBasesList = data.bases_list;
                    this.pizzaIngTypesList = data.ing_types_list;
                    this.pizzaIngredientsList = data.ingredients_list;

                }.bind(this));
            },

            addIngredient() {
                let ingredient = this.clone(ingredientRef);
                this.pizzaSet.ingredients.push(ingredient);
            },

            deleteIngredient(index) {
                this.removeByIndex(this.pizzaSet.ingredients, index);
                console.log(index);
            },

            getSetsList() {
                this.updating = true;

                axios.get(
                    '/pizza-sets/get-list',
                    {
                        params: {
                            page: this.page,
                            per_page: this.perPage,
                            sort_field: this.sortField,
                            sort_dir: this.sortDirection,
                        },
                    }
                ).then(function(response) {

                    console.log(response.data);
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
                console.log(this.pizzaSet);

                for (let prop in this.pizzaSet) {
                    let propData = this.pizzaSet[prop];

                    if (prop !== 'imageFile' && this.isObject(propData)) {
                        propData = JSON.stringify(propData);
                    }
                    formData.append(prop, propData);
                }

                axios.post('/pizza-sets/add-new',
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

            /*editProdModal(id) {

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
            },*/

            /*prodDetailsModal(id) {

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
            },*/

            /*deleteProdModal(id) {

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
            },*/

            deleteSet(id) {

                axios.get(
                    '/pizza-sets/delete',
                    {
                        params: {
                            id
                        },
                    }
                ).then(function(response) {

                    this.$modal.hide('dialog');
                    this.notifySuccess(`Pizza set ID:${id} successfully deleted.`);
                    this.getSetsList();

                }.bind(this))
                .catch(function() {

                    this.$modal.hide('dialog');
                    this.notifyError('Delete pizza set error.');

                }.bind(this));
            },

            handleFileUpload() {
                this.pizzaSet.imageFile = this.$refs.pizzaSetImageFile.files[0];
            },

            openGallery(index) {
                this.$refs.lightbox.showImage(index);
            }
        }
    }
</script>
