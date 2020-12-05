/**
 * User authorization permissions mixin
 */
export default {

    data() {
        return {
            permissions: {},
        }
    },

    methods: {

        p(pSlug) {
            return this.permissions[pSlug];
        },

        getPermissionsList(model) {

            axios.post('/get-permissions-list',
                {
                    model
                }
            ).then(function (response) {

                console.log(response.data);
                this.permissions = response.data;

            }.bind(this))
            .catch(function () {

                this.notifyError('Get permissions list error.');

            }.bind(this));
        }
    }
}
