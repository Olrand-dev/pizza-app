<template>

    <div class="container-fluid container-modal">
        <div class="row">

            <div class="col-12">
                <div class="card card-modal">

                    <div class="header">
                        <div class="row">
                            <div class="col-12">

                                <h4 class="title pull-left">{{ modalData.header }}</h4>
                                <button class="btn btn-default pull-right" @click="$emit('close')">Close</button>

                            </div>
                        </div>
                    </div>

                    <div class="content">
                        <div class="row">

                            <div class="col-12 modal-text">

                                <p>{{ modalData.text }}</p>

                            </div>

                            <div class="col-12 modal-btns">

                                <button class="btn btn-success btn-fill btn-icon" @click="confirmEvent">
                                    <i class="fa fa-check"></i> Ok
                                </button>
                                <button class="btn btn-warning btn-fill btn-icon" @click="$emit('close')">
                                    <i class="fa fa-ban"></i> Cancel
                                </button>

                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

</template>


<style scoped>

    .modal-btns {
        margin-top: 20px;
    }

</style>


<script>

export default {

    props: [
        'modal-data'
    ],

    methods: {

        confirmEvent() {
            this.modalData.onConfirm();
            this.$emit('close');
        }
    }
}

</script>
