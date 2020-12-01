<template>

    <div class="container">

        <div class="row">

            <div class="col-md-6 ing-list">

                <div class="row">

                    <div class="col-md-12 ing-box-label">
                        <label>Pizza Sets *</label>
                    </div>

                    <div v-if="pizza_sets.length > 0" class="col-md-12">

                        <div v-for="(set, index) in pizza_sets" :key="index" class="row">
                            <div class="col-md-12 box ingredient-box">

                                <div :class="{ 'col-md-6': mode !== 'show', 'col-md-8': mode === 'show' }">
                                    <div class="form-group">
                                        <label>Set</label>

                                        <input v-if="mode === 'show'" type="text" class="form-control"
                                               :value="getItemById(pizzaSetsList, set.id).name" readonly>

                                        <select v-else v-model="set.id" class="form-control" @change="updateTotal">
                                            <option v-for="option in pizzaSetsList" :key="option.id"
                                                    :value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                        <span v-if="checkSubItemErr(index, 'id', 'pizza_sets')" class="error">
                                            {{ getSubItemErr(index, 'id', 'pizza_sets') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Q-ty</label>
                                        <input type="number" step="1" min="1" :readonly="mode === 'show'"
                                               v-model="set.quantity" class="form-control" @change="updateTotal">
                                        <span v-if="checkSubItemErr(index, 'quantity', 'pizza_sets')" class="error">
                                            {{ getSubItemErr(index, 'quantity', 'pizza_sets') }}
                                        </span>
                                    </div>
                                </div>

                                <div v-if="mode !== 'show'" class="col-md-2 text-right">
                                    <button class="btn btn-danger btn-sm ing-del-btn"
                                            @click="deletePizzaSet(index)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div v-if="mode === 'edit' || mode === 'add_new'" class="col-md-12">
                        <button class="btn btn-info btn-fill btn-icon"
                                @click="addPizzaSet">
                            <i class="fa fa-plus"></i> Add Pizza Set
                        </button>
                        <span v-if="checkErr('pizza_sets')" class="error">
                            {{ getErr('pizza_sets') }}
                        </span>
                    </div>

                </div>


                <div class="row">

                    <div class="col-md-12 ing-box-label">
                        <label>Additional Products</label>
                    </div>

                    <div v-if="products.length > 0" class="col-md-12">

                        <div v-for="(prod, index) in products" :key="index" class="row">
                            <div class="col-md-12 box ingredient-box">

                                <div :class="{ 'col-md-6': mode !== 'show', 'col-md-8': mode === 'show' }">
                                    <div class="form-group">
                                        <label>Product</label>

                                        <input v-if="mode === 'show'" type="text" class="form-control"
                                               :value="getItemById(addProdsList, prod.id).name" readonly>

                                        <select v-else v-model="prod.id" class="form-control" @change="updateTotal">
                                            <option v-for="option in addProdsList" :key="option.id"
                                                    :value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                        <span v-if="checkSubItemErr(index, 'id', 'products')" class="error">
                                            {{ getSubItemErr(index, 'id', 'products') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Q-ty</label>
                                        <input type="number" step="1" min="1" :readonly="mode === 'show'"
                                               v-model="prod.quantity" class="form-control" @change="updateTotal">
                                        <span v-if="checkSubItemErr(index, 'quantity', 'products')" class="error">
                                            {{ getSubItemErr(index, 'quantity', 'products') }}
                                        </span>
                                    </div>
                                </div>

                                <div v-if="mode !== 'show'" class="col-md-2 text-right">
                                    <button class="btn btn-danger btn-sm ing-del-btn"
                                            @click="deleteAddProd(index)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div v-if="mode === 'edit' || mode === 'add_new'" class="col-md-12">
                        <button class="btn btn-info btn-fill btn-icon"
                                @click="addProd">
                            <i class="fa fa-plus"></i> Add Product
                        </button>
                        <span v-if="checkErr('products')" class="error">
                            {{ getErr('products') }}
                        </span>
                    </div>

                </div>

            </div>


            <div class="col-md-4 order-total">
                <div class="row">

                    <div class="col-12">
                        <label>Order Total</label>
                    </div>

                    <div class="col-md-12 box data">
                        <span><b>Cost:</b> ${{orderCost}}</span>
                        <span><b>Weight:</b> {{orderWeight}} g.</span>
                    </div>

                </div>
            </div>

            <div class="col-md-12">
                <hr>
            </div>

        </div>

    </div>

</template>


<style scoped lang="scss">

    @import '../../../sass/variables';
    @import '../../../sass/mixins';

    .ing-list {
        margin-bottom: 15px;
    }

    .ingredient-box {
        position: relative;
        right: 8px;
        margin-bottom: 9px;
        margin-left: 15px;
    }

    .ing-box-label {
        margin-top: 20px;
    }

    .ing-del-btn {
        position: relative;
        top: 21px;
        right: 20px;
        margin-top: 6px;

        @include maxw(990) {
            top: -8px;
        }
    }

    .order-total {

        margin-left: 35px;
        margin-top: 19px;

        span {
            display: block;
        }

        .box {
            padding: 10px;
            font-size: 16px;
            color: $s-dark-gray;
        }

        .data {
            max-width: 200px;
        }
    }

</style>


<script>

    import Utils from "../../mixins/Utils";
    import Validation from "../../mixins/Validation";

    const pizzaSetRef = {
        id: 0,
        quantity: 1,
    };

    const addProdRef = {
        id: 0,
        quantity: 1,
    };

    export default {

        data() {
            return {
                pizza_sets: this.orderPizzaSets,
                products: this.orderAddProds,
                orderCost: 0,
                orderWeight: 0,
            }
        },

        mixins: [
            Utils,
            Validation,
        ],

        props: [
            'mode',
            'order-pizza-sets',
            'order-add-prods',
            'pizza-sets-list',
            'add-prods-list',
            'errors-list',
            'sub-items-fields-list',
        ],

        watch: {

            errorsList(val) {
                this.errors = val;
            },

            subItemsFieldsList(val) {
                this.subItemsFields = val;
            }
        },

        created() {
            this.updateTotal();
        },

        methods: {

            updateTotal() {
                this.orderCost = 0;
                this.orderWeight = 0;

                let addToTotal = function (list, ing) {
                    if (ing.id === 0) return;
                    let item = this.getItemById(list, ing.id);

                    this.orderCost += item.cost * ing.quantity;
                    this.orderWeight += item.weight * ing.quantity;
                }.bind(this);

                this.pizza_sets.forEach(item => {
                    addToTotal(this.pizzaSetsList, item);
                });
                this.products.forEach(item => {
                    addToTotal(this.addProdsList, item);
                });

                this.$emit('on-ing-list-change', {
                    cost: this.orderCost,
                    weight: this.orderWeight,
                    pizza_sets: this.pizza_sets,
                    products: this.products,
                });
            },

            clearData() {
                this.pizza_sets = [];
                this.products = [];
                this.orderCost = 0;
                this.orderWeight = 0;
            },

            addPizzaSet() {
                let set = this.clone(pizzaSetRef);
                this.pizza_sets.push(set);
                this.updateTotal();
            },

            deletePizzaSet(index) {
                this.removeByIndex(this.pizza_sets, index);
                this.updateTotal();
            },

            addProd() {
                let prod = this.clone(addProdRef);
                this.products.push(prod);
                this.updateTotal();
            },

            deleteAddProd(index) {
                this.removeByIndex(this.products, index);
                this.updateTotal();
            },
        }
    }

</script>
