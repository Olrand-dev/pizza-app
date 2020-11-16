<template>

    <div class="row">

        <div class="col-md-12">
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control"
                       v-model="data.name" readonly>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Phone</label>
                <input type="text" class="form-control"
                       v-model="data.phone" readonly>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control"
                       v-model="data.user.email" readonly>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Address</label>
                <textarea rows="5" class="form-control"
                          v-model="data.address" readonly></textarea>
            </div>
        </div>

    </div>

</template>


<style scoped>

</style>


<script>

    export default {

        props: [
            'data',
        ],
    }

</script>
