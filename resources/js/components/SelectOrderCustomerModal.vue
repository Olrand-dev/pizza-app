<template>

    <div class="container-fluid container-modal">
        <div class="row">

            <div class="col-12">
                <div class="card card-modal">

                    <div class="header">
                        <div class="row">
                            <div class="col-12">

                                <h4 class="title pull-left">Select order customer.</h4>
                                <button class="btn btn-default pull-right" @click="$emit('close')">Close</button>

                            </div>
                        </div>
                    </div>

                    <div class="content">

                        <users-list-customers btn-class="btn-success"
                                              btn-icon-class="fa fa-check"
                                              custom-list-class="order-customers"
                                              @on-button-click="onSelect"></users-list-customers>

                    </div>

                </div>
            </div>

        </div>
    </div>

</template>


<style scoped lang="scss">

    .features {

    }

</style>


<script>

    export default {

        props: [
            'modal-data'
        ],

        methods: {

            onSelect(item) {
                this.modalData.onConfirm(item.id);
                this.$emit('close');
            }
        }
    }

</script>
