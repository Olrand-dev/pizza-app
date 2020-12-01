<template>

    <div class="row">

        <div class="col-md-12">
            <label>Ingredients *</label>
        </div>

        <div v-if="items.length > 0" class="col-md-12">

            <div v-for="(prod, index) in items" :key="index" class="row">
                <div class="col-md-12 box ingredient-box">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Type</label>
                            <select v-model="prod.type_id" class="form-control" @change="onChangeList">
                                <option v-for="type in types" :key="type.id"
                                        :value="type.id">
                                    {{ type.name }}
                                </option>
                            </select>
                            <span v-if="checkSubItemErr(index, 'type_id')" class="error">
                                {{ getSubItemErr(index, 'type_id') }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Product</label>
                            <select v-model="prod.prod_id" class="form-control" :disabled="prod.type_id === 0"
                                    @change="onChangeList">
                                <option v-for="prod in ingList[prod.type_id]" :key="prod.id"
                                        :value="prod.id">
                                    {{ prod.name }}
                                </option>
                            </select>
                            <span v-if="checkSubItemErr(index, 'prod_id')" class="error">
                                {{ getSubItemErr(index, 'prod_id') }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Q-ty</label>
                            <input type="number" step="1" min="1"
                                   v-model="prod.quantity" class="form-control" @change="onChangeList">
                            <span v-if="checkSubItemErr(index, 'quantity')" class="error">
                                {{ getSubItemErr(index, 'quantity') }}
                            </span>
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


<style scoped lang="scss">

    @import '../../../sass/variables';
    @import '../../../sass/mixins';

    .ingredient-box {
        position: relative;
        right: 15px;
        margin-bottom: 6px;
    }

    .ing-del-btn {
        position: relative;
        top: 28px;
        right: 20px;

        @include maxw(990) {
            top: -8px;
        }
    }

</style>


<script>

    import Utils from "../../mixins/Utils";
    import Validation from "../../mixins/Validation";

    const ingredientRef = {
        type_id: 0,
        prod_id: 0,
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
            Validation,
        ],

        props: [
            'items-list', 'types', 'ing-list', 'errors-list',
        ],

        watch: {

            errorsList(val) {
                this.errors = val;
            }
        },

        methods: {

            addIngredient() {
                let ingredient = this.clone(ingredientRef);
                this.items.push(ingredient);
                this.onChangeList();
            },

            deleteIngredient(index) {
                this.removeByIndex(this.items, index);
                this.onChangeList();
            },

            onChangeList() {
                this.$emit('on-change-list', this.items);
            }
        }
    }

</script>
