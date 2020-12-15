/**
 * Pagination mixin
 */
export default {

    data() {
        return {
            page: 1,
            pagesCount: 1,
            perPage: 10,
        }
    },

    methods: {

        paginate(page) {
            this.page = page;
            this.getList();
        },

        checkPagesCount() {
            if (this.pagesCount > this.page) {
                this.page = this.pagesCount;
                this.paginate(this.page);
            }
        }
    }
}
