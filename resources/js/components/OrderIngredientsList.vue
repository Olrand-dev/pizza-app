<template>

    <div class="container">

        <div class="row">

            <div class="col-md-6 ing-list">

                <div class="row">

                    <div class="col-md-12 ing-box-label">
                        <label>Pizza Sets</label>
                    </div>

                    <div v-if="pizza_sets.length > 0" class="col-md-12">

                        <div v-for="(set, index) in pizza_sets" :key="index" class="row">
                            <div class="col-md-12 box ingredient-box">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Set</label>
                                        <select v-model="set.id" class="form-control" @change="updateTotal">
                                            <option v-for="option in pizzaSetsList" :key="option.id"
                                                    :value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Q-ty</label>
                                        <input type="number" step="1" min="1"
                                               v-model="set.quantity" class="form-control" @change="updateTotal">
                                    </div>
                                </div>

                                <div class="col-md-2 text-right">
                                    <button class="btn btn-danger btn-sm ing-del-btn"
                                            @click="deletePizzaSet(index)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <button class="btn btn-info btn-fill btn-icon"
                                @click="addPizzaSet">
                            <i class="fa fa-plus"></i> Add Pizza Set
                        </button>
                    </div>

                </div>


                <div class="row">

                    <div class="col-md-12 ing-box-label">
                        <label>Additional Products</label>
                    </div>

                    <div v-if="add_prods.length > 0" class="col-md-12">

                        <div v-for="(prod, index) in add_prods" :key="index" class="row">
                            <div class="col-md-12 box ingredient-box">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Product</label>
                                        <select v-model="prod.id" class="form-control" @change="updateTotal">
                                            <option v-for="option in addProdsList" :key="option.id"
                                                    :value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Q-ty</label>
                                        <input type="number" step="1" min="1"
                                               v-model="prod.quantity" class="form-control" @change="updateTotal">
                                    </div>
                                </div>

                                <div class="col-md-2 text-right">
                                    <button class="btn btn-danger btn-sm ing-del-btn"
                                            @click="deleteAddProd(index)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <button class="btn btn-info btn-fill btn-icon"
                                @click="addProd">
                            <i class="fa fa-plus"></i> Add Product
                        </button>
                    </div>

                </div>

            </div>


            <div class="col-md-4 order-total">
                <div class="row">

                    <div class="col-12">
                        <label>Order Total</label>
                    </div>

                    <div class="col-md-12 box">
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

    @import './../../sass/variables';

    .ing-list {
        margin-bottom: 15px;
    }

    .ingredient-box {
        position: relative;
        right: 15px;
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
    }

</style>


<script>

    import Utils from "../mixins/Utils";

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
                add_prods: this.orderAddProds,
                orderCost: 0,
                orderWeight: 0,
            }
        },

        mixins: [
            Utils,
        ],

        props: [
            'order-pizza-sets',
            'order-add-prods',
            'pizza-sets-list',
            'add-prods-list',
        ],

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
                this.add_prods.forEach(item => {
                    addToTotal(this.addProdsList, item);
                });

                this.$emit('on-ing-list-change', {
                    cost: this.orderCost,
                    weight: this.orderWeight,
                });
            },

            clearData() {
                this.pizza_sets = [];
                this.add_prods = [];
                this.orderCost = 0;
                this.orderWeight = 0;
            },

            addPizzaSet() {
                let set = this.clone(pizzaSetRef);
                this.pizza_sets.push(set);
            },

            deletePizzaSet(index) {
                this.removeByIndex(this.pizza_sets, index);
                this.updateTotal();
            },

            addProd() {
                let prod = this.clone(addProdRef);
                this.add_prods.push(prod);
            },

            deleteAddProd(index) {
                this.removeByIndex(this.add_prods, index);
                this.updateTotal();
            },
        }
    }

</script>
