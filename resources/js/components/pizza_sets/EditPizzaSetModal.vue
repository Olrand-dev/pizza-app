<template>

    <div class="container-fluid container-modal">
        <div class="row">

            <div class="col-12">
                <div class="card card-modal">

                    <div class="header">
                        <div class="row">
                            <div class="col-12">

                                <h4 class="title pull-left">Edit pizza set ID:{{ setData.id }}</h4>
                                <button class="btn btn-default pull-right" @click="$emit('close')">Close</button>

                            </div>
                        </div>
                    </div>

                    <div class="content">
                        <div class="row">
                            <div class="col-12">

                                <div class="col-md-12">
                                    <div class="row">

                                        <div class="col-12">
                                            <img v-if="!setImageChanged" class="set-details-image"
                                                 :src="setData.image_thumbs.w_600" alt="set image">
                                            <input v-if="setImageChanged" type="file" id="setImage"
                                                   ref="setImageFile"
                                                   @change="handleFileUpload">
                                            <span v-if="checkErr('image_file')" class="error">
                                                {{ getErr('image_file') }}
                                            </span>
                                        </div>

                                        <div class="col-12 mt-5">
                                            <button v-if="!setImageChanged" class="btn btn-default"
                                                    @click="changeImage">
                                                <span v-if="!setData.image"><i class="fa fa-plus"></i> Add Image</span>
                                                <span v-else><i class="fa fa-edit"></i> Change Image</span>
                                            </button>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-12 mt-15">

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" v-model="setEdit.name">
                                            <span v-if="checkErr('name')" class="error">
                                                {{ getErr('name') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pizzaBaseSelect">Base</label>
                                                    <select v-model="setEdit.base_id" class="form-control"
                                                            id="pizzaBaseSelect">
                                                        <option v-for="base in basesList" :key="base.id"
                                                                :value="base.id">
                                                            {{ base.name }}
                                                        </option>
                                                    </select>
                                                    <span v-if="checkErr('base_id')" class="error">
                                                        {{ getErr('base_id') }}
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="col-12 pizza-set-ingredients-list">

                                        <ingredients-list ref="ingList"
                                                          :errors-list="getErr('ingredients', true)"
                                                          :items-list="setEdit.ingredients"
                                                          :types="ingTypesList"
                                                          :ing-list="ingList"></ingredients-list>
                                        <span v-if="checkErr('ingredients')" class="error">
                                            {{ getErr('ingredients') }}
                                        </span>

                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-right">

                                        <button class="btn btn-success btn-fill btn-icon"
                                                @click="saveSet">
                                            <i v-if="saving" class="fa fa-spinner anim-rotate"></i>
                                            <i v-else class="fa fa-check"></i>
                                            Save
                                        </button>
                                        <button class="btn btn-warning btn-fill btn-icon"
                                                @click="cancelEdit">
                                            <i class="fa fa-ban"></i> Cancel
                                        </button>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

</template>


<style scoped>

    .set-details-image {
        max-width: 100%;
        max-height: 250px;
    }

    .pizza-set-ingredients-list {
        padding-left: 30px;
        margin-bottom: 20px;
    }

    .container-modal {
        max-height: 90vh;
        overflow: auto;
    }

</style>


<script>

    import Utils from '../../mixins/Utils';
    import Notify from '../../mixins/Notify';
    import Validation from '../../mixins/Validation';

    export default {

        data() {
            return {
                setImageChanged: false,
                setEdit: {},
                saving: false,
            }
        },

        props: [
            'set-data',
            'bases-list',
            'ing-types-list',
            'ing-list',
        ],

        mixins: [
            Utils,
            Notify,
            Validation
        ],

        created() {
            /*console.log('set data:', this.setData);
            console.log('bases list:', this.basesList);
            console.log('ing types list:', this.ingTypesList);
            console.log('ing list:', this.ingList);*/

            this.setEdit = this.clone(this.setData);
        },

        methods: {

            changeImage() {
                this.setImageChanged = true;
            },

            handleFileUpload() {
                this.setEdit.image_file = this.$refs.setImageFile.files[0];
            },

            saveSet() {
                let formData = new FormData();

                for (let prop in this.setEdit) {
                    let propData = this.setEdit[prop];

                    if (prop !== 'image_file' && this.isObject(propData)) {
                        propData = JSON.stringify(propData);
                    }
                    formData.append(prop, propData);
                }

                this.saving = true;

                axios.post('/pizza-sets/save',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        }
                    }
                ).then(function(response) {

                    this.notifySuccess('Pizza set ID:' + this.setEdit.id + ' successfully updated.');
                    this.clearErrors();
                    this.closeModal();

                }.bind(this))
                .catch(function(error) {

                    if (this.checkValidationErrors(error.response.data)) {
                        this.notifyError('Form validation error.', 1500);
                        this.saving = false;
                        return;
                    }
                    this.notifyError('Pizza set update error.');
                    this.closeModal();

                }.bind(this));
            },

            closeModal() {
                this.setEdit = {};
                this.$emit('close');
            },

            cancelEdit() {
                this.closeModal();
            },
        }
    }

</script>
