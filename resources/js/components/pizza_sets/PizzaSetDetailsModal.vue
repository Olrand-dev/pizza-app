<template>

    <div class="container-fluid container-modal">
        <div class="row">

            <div class="col-12">
                <div class="card card-modal">

                    <div class="header">
                        <div class="row">
                            <div class="col-12">

                                <h4 class="title pull-left">Pizza set ID:{{ setData.id }} details.</h4>
                                <button class="btn btn-default pull-right" @click="$emit('close')">Close</button>

                            </div>
                        </div>
                    </div>

                    <div class="content">
                        <div class="row">
                            <div class="col-12">

                                <div class="col-md-6">
                                    <img class="set-details-image"
                                         :src="getImageThumb(setData.image_thumbs)" alt="prod image">
                                </div>

                                <div class="col-md-6">
                                    <table class="table table-hover">
                                        <tbody>

                                        <tr>
                                            <th>Name</th>
                                            <td>{{ setData.name }}</td>
                                        </tr>
                                        <tr v-if="p('uiElemPizzaSetDataCost')">
                                            <th>Cost</th>
                                            <td>${{ setData.cost }}</td>
                                        </tr>
                                        <tr>
                                            <th>Weight</th>
                                            <td>{{ setData.weight }} g.</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12">

                                    <h4>Ingredients</h4>

                                    <table class="table table-hover">

                                        <thead>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Q-ty</th>
                                        </thead>

                                        <tbody>

                                        <tr v-for="item in setData.products" :key="item.id">
                                            <td>{{ item.name }}</td>
                                            <td>{{ item.type_name }}</td>
                                            <td>{{ item.connection.quantity }}</td>
                                        </tr>

                                        </tbody>
                                    </table>
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
    }

</style>


<script>

    import Utils from '../../mixins/Utils';
    import Permissions from '../../mixins/Permissions';

    export default {

        data() {
            return {
                permissions: this.permissionsList,
            }
        },

        props: [
            'set-data',
            'permissions-list',
        ],

        mixins: [
            Utils,
            Permissions,
        ],

        watch: {

            permissionsList(val) {
                this.permissions = val;
            }
        }
    }

</script>
