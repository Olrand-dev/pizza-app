<template>

    <div class="container-fluid container-modal">
        <div class="row">

            <div class="col-12">
                <div class="card card-modal">

                    <div class="header">
                        <div class="row">
                            <div class="col-12">

                                <h4 class="title pull-left">Product ID:{{ prodData.id }} details.</h4>
                                <button class="btn btn-default pull-right" @click="$emit('close')">Close</button>

                            </div>
                        </div>
                    </div>

                    <div class="content">
                        <div class="row">
                            <div class="col-12">

                                <div class="col-md-6">
                                    <img class="prod-details-image" :src="prodData.image_thumbs.w_600" alt="prod image">
                                </div>

                                <div class="col-md-6">
                                    <table class="table table-hover">
                                        <tbody>

                                        <tr>
                                            <th>Name</th>
                                            <td>{{ prodData.name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Type</th>
                                            <td>{{ prodData.type.name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Cost</th>
                                            <td>${{ prodData.cost }}</td>
                                        </tr>
                                        <tr>
                                            <th>Weight</th>
                                            <td>{{ prodData.weight }} g.</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12 mt-10">
                                    <p class="text-muted">

                                        {{ prodData.description }}

                                    </p>
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
    }

</style>


<script>

    export default {

        props: [
            'prod-data'
        ],
    }

</script>
