<template>

    <paginate
        v-model="curPage"
        :page-count="pagesCount"
        :page-range="pagesRange"
        :margin-pages="marginPages"
        :click-handler="onClick"
        :prev-text="prevText"
        :next-text="nextText"
        container-class="pagination"
        page-class="page-item"
        prev-class="prev-page-btn"
        next-class="next-page-btn"
        break-view-class="bv"
    ></paginate>

</template>


<style lang="scss">

    @import './../../sass/variables';

    .pagination {

        a:focus, span:focus {
            outline: none;
        }

        li {
            a, a:focus, a:hover, span, span:focus, span:hover {
                padding: 9px 16px;
                color: $s-dark;
            }
        }

        .active {
            a, a:focus, a:hover, span, span:focus, span:hover {
                background-color: $s-light-blue;
                border-color: $s-light-gray;
            }
        }
    }

</style>


<script>

    export default {

        data() {
            return {
                pageValue: 1,
                marginPages: 1,
                prevText: this.prev || 'Prev.',
                nextText: this.next || 'Next',
            }
        },

        props: [
            'page', 'pages-count', 'click-handler', 'range', 'prev', 'next',
        ],

        computed: {

            curPage: {
                get() {
                    return this.page;
                },
                set(val) {
                    this.pageValue = val;
                }
            },

            pagesRange() {
                let range = this.range || 3;
                return parseInt(range);
            }
        },

        methods: {

            onClick() {
                this.$emit('click-handler', this.pageValue);
            }
        }
    }

</script>
