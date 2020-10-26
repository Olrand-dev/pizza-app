<template>

    <div class="container-fluid container-modal">
        <div class="row">

            <div class="col-12">
                <div class="card card-modal">

                    <div class="header">
                        <div class="row">
                            <div class="col-12">

                                <h4 class="title pull-left">Edit product ID:{{ prodData.id }}.</h4>
                                <button class="btn btn-default pull-right" @click="$emit('close')">Close</button>

                            </div>
                        </div>
                    </div>

                    <div class="content">
                        <div class="row">
                            <div class="col-12">

                                <div class="col-md-12">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <img v-if="!prodImageChanged" class="prod-details-image" 
                                                :src="prodData.image_thumbs.w_600" alt="prod image">
                                            <input v-if="prodImageChanged" type="file" id="productImage" ref="prodImageFile"
                                                @change="handleFileUpload">
                                        </div>

                                        <div class="col-md-12 mt-5">
                                            <button v-if="!prodImageChanged" class="btn btn-default" @click="changeImage">
                                                <i class="fa fa-edit"></i> Change
                                            </button>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-12 mt-15">
                                    
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" v-model="prodEdit.name">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="row">

                                            <div class="col-md-8">

                                                <div class="form-group">
                                                    <label for="prodTypesSelect">Type</label>
                                                    <select v-model="prodEdit.type_id" class="form-control" 
                                                        id="prodTypesSelect">
                                                        <option v-for="type in prodTypesList" :key="type.id"
                                                            :value="type.id">
                                                            {{ type.name }}
                                                        </option>
                                                    </select>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="row">

                                            <div class="col-md-3">

                                                <div class="form-group">
                                                    <label>Cost ($)</label>
                                                    <input type="number" step="0.01" min="0.01" 
                                                        class="form-control" v-model="prodEdit.cost">
                                                </div>

                                            </div>
                                            <div class="col-md-3">

                                                <div class="form-group">
                                                    <label>Weight (g)</label>
                                                    <input type="number" step="1" min="1" 
                                                        class="form-control" v-model="prodEdit.weight">
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-12">

                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea rows="5" class="form-control" v-model="prodEdit.description"></textarea>
                                        </div>

                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-right">

                                        <button class="btn btn-success btn-fill btn-icon"
                                            @click="saveProd"> 
                                            <i class="fa fa-check"></i> Save
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

    .prod-details-image {
        max-width: 100%;
        max-height: 250px;
    }

    .container-modal {
        max-height: 90vh;
        overflow: auto;
    }

</style>


<script>

    import Utils from '../mixins/Utils';
    import Notify from '../mixins/Notify';

    export default {

        data() {
            return {
                prodImageChanged: false,
                prodEdit: {},
            }
        },

        props: [
            'prod-data',
            'prod-types-list',
        ],

        mixins: [
            Utils,
            Notify
        ],

        created() {
            this.prodEdit = this.clone(this.prodData);
            this.prodEdit.image_changed = false;
        },

        methods: {

            changeImage() {
                this.prodImageChanged = true;
                this.prodEdit.image_changed = true;
            },

            handleFileUpload() {
                this.prodEdit.imageFile = this.$refs.prodImageFile.files[0];
            },

            saveProd() {
                let formData = new FormData();

                for (let prop in this.prodEdit) {
                    formData.append(prop, this.prodEdit[prop]);
                }

                axios.post('/products/save-product',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        }
                    }
                ).then(function(response) {
                    
                    this.notifySuccess('Product ID:' + this.prodEdit.id + ' successfully updated.');
                    this.closeModal();

                }.bind(this))
                .catch(function() {
                    
                    this.notifyError('Product update error.');
                    this.closeModal();
                    
                }.bind(this));
            },

            closeModal() {
                this.prodEdit = {};
                this.$emit('close');
            },

            cancelEdit() {
                this.closeModal();
            },
        }
    }

</script>