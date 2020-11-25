/**
 * Laravel form validation response handle mixin
 */
export default {

    data() {
        return {
            errors: {},
        }
    },

    methods: {

        checkValidationErrors(response) {
            if (response.hasOwnProperty('message') && response.message === 'The given data was invalid.') {
                this.errors = (response.hasOwnProperty('errors')) ? response.errors : {};
                return true;
            }
            return false;
        },

        checkErr(name) {
            return this.errors.hasOwnProperty(name);
        },

        getErr(name) {
            return (this.checkErr(name)) ? this.errors[name][0] : '';
        },

        clearErrors() {
            this.errors = {};
        }
    }
}
