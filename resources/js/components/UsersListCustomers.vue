<template>

    <div id="users-list" class="row" :class="customListClass">

        <div class="col-12">

            <div class="col-md-3">
                <div class="form-group">
                    <label for="findBySelect">Find by</label>
                    <select v-model="findBy" class="form-control" id="findBySelect">
                        <option value="name">Name</option>
                        <option value="phone">Phone</option>
                        <option value="address">Address</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="findQueryField">Search</label>
                    <input type="text" class="form-control" id="findQueryField"
                           v-model="findQuery">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="sortSelect">Sort</label>
                    <select v-model="sort" class="form-control" id="sortSelect">
                        <option value="name@desc">Name A-Z</option>
                        <option value="name@asc">Name Z-A</option>
                        <option value="created_at@desc">Register date - newest</option>
                        <option value="created_at@asc">Register date - oldest</option>
                    </select>
                </div>
            </div>

            <div class="col-md-1">
                <button class="btn btn-default btn-sm top-panel-btn update-list-btn"
                        @click="getList(true)">
                    <i class="fas fa-sync-alt" :class="{ 'anim-rotate': listUpdating }"></i>
                </button>
            </div>

        </div>

        <div class="col-12">
            <div class="row">

                <div class="col-12">
                    <div class="row user-box-list">

                        <div v-for="(item, index) in list" :key="item.id" class="col-md-12 box user-box">

                            <div class="col-md-12 user-data-top">

                                <div class="col-md-5">
                                    <span class="user-data-line user-name">
                                        <i class="fas fa-user"></i>
                                        {{ item.name }}
                                    </span>
                                </div>

                                <div class="col-md-5">

                                    <span class="user-data-line">
                                        <i class="fas fa-phone-alt"></i>
                                        {{ item.phone }}
                                    </span>
                                    <span class="user-data-line">
                                        <i class="fas fa-at"></i>
                                        {{ item.user.email }}
                                    </span>
                                    <span class="user-data-line">
                                        <i class="fas fa-table"></i>
                                        {{ item.registered_at }}
                                    </span>

                                </div>

                                <div class="col-md-2 text-right">
                                    <button class="btn btn-success btn-sm" :class="btnClass" @click="onButtonClick(index)">
                                        <i :class="btnIconClass"></i>
                                    </button>
                                </div>

                            </div>

                            <div class="col-md-12">
                                    <span class="user-data-line user-address">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ item.address }}
                                    </span>
                            </div>

                        </div>

                    </div>
                </div>

                <div v-if="list.length === 0" class="col-12 text-center">
                    <span class="no-items">No items to show.</span>
                </div>

                <div class="col-12">

                    <div class="col-md-10">

                        <pagination :page="page" :pages-count="pagesCount" @click-handler="paginate"
                                    range="5"></pagination>

                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="perPageSelect">Per page</label>
                            <select v-model="perPage" class="form-control" id="perPageSelect"
                                    @change="getList(true)">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

</template>


<style scoped>

</style>


<script>

    import Utils from "../mixins/Utils";
    import Notify from '../mixins/Notify';
    import Pagination from "../mixins/Pagination";

    export default {

        data() {
            return {
                listUpdating: false,

                findBy: 'name',
                findQuery: '',
                sort: 'name@desc',

                list: [],
            }
        },

        mixins: [
            Utils,
            Notify,
            Pagination,
        ],

        props: [
            'btn-class',
            'btn-icon-class',
            'custom-list-class',
        ],

        created() {
            this.getList();
        },

        methods: {

            paginate(page) {
                this.page = page;
                this.getList();
            },

            onButtonClick(index) {
                let item = this.list[index];
                this.$emit('on-button-click', item);
                this.$emit('close');
            },

            getList(resetPage = false) {

                this.listUpdating = true;
                if (resetPage) this.page = 1;

                let sortData = this.sort.split('@');
                let sortField = sortData[0];
                let sortDir = sortData[1];

                axios.get(
                    '/customers/get-list',
                    {
                        params: {
                            page: this.page,
                            per_page: this.perPage,
                            find_by: this.findBy,
                            find_query: this.findQuery,
                            sort_field: sortField,
                            sort_dir: sortDir,
                        },
                    }
                ).then(function(response) {

                    let data = response.data;

                    this.list = JSON.parse(data.items);
                    //console.log(this.list);
                    this.pagesCount = data.pages_count;

                    this.listUpdating = false;

                }.bind(this))
                .catch(function() {

                    this.notifyError('Load customers list error.');

                }.bind(this));
            }
        }
    }

</script>
