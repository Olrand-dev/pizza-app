<template>

    <div class="row pizza-set-ingredients-list">

        <div class="col-md-12">
            <label>Ingredients</label>
        </div>

        <div v-if="items.length > 0" class="col-md-12">

            <div v-for="(prod, index) in items" :key="index" class="row">
                <div class="col-md-12 ingredient-box">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Type</label>
                            <select v-model="prod.typeId" class="form-control">
                                <option v-for="type in types" :key="type.id"
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
                                <option v-for="prod in ingList[prod.typeId]" :key="prod.id"
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

</template>


<style lang="scss">

    @import './../../sass/variables';

    .pizza-set-ingredients-list {
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

    import Utils from "../mixins/Utils";

    const ingredientRef = {
        typeId: 0,
        prodId: 0,
        quantity: 1,
    };

    export default {

        data() {
            return {
                items: this.itemsList,
            }
        },

        mixins: [
            Utils,
        ],

        props: [
            'items-list', 'types', 'ing-list',
        ],

        methods: {

            addIngredient() {
                let ingredient = this.clone(ingredientRef);
                this.items.push(ingredient);
            },

            deleteIngredient(index) {
                this.removeByIndex(this.items, index);
            },
        }
    }

</script>
