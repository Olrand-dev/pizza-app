/**
 * Sortable table columns mixin
 */
export default {

    data() {
        return {
            sortableHeaders: {},
            selectedSortableHeader: '',
            sortField: '',
            sortDirection: 'desc',
        }
    },

    methods: {

        onTableHeaderClick(header) {

            if (this.hop(this.sortableHeaders, header)) {

                if (header !== this.selectedSortableHeader) {
                    this.selectedSortableHeader = header;
                    this.sortDirection = 'desc';
                } else {

                    if (this.sortDirection === 'desc') {
                        this.sortDirection = 'asc';
                    } else {
                        this.sortDirection = 'desc';
                    }
                }

                this.sortField = this.sortableHeaders[header];
                this.sortableHeaderClickHandler();
            }
        },

        sortableHeaderClickHandler() {

        }
    }
}
