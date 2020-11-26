/**
 * Laravel form validation response handle mixin
 */
export default {

    data() {
        return {
            errors: {},
            subItemsFields: [],
        }
    },

    methods: {

        checkValidationErrors(response) {
            this.clearErrors();

            if (response.hasOwnProperty('message') && response.message === 'The given data was invalid.') {
                let errors = (response.hasOwnProperty('errors')) ? response.errors : null;
                if (!errors) return false;

                this.handleSubItemErrors(errors);
                this.errors = errors;
                //console.log(this.errors);
                return true;
            }
            return false;
        },

        handleSubItemErrors(errList) {
            for (let err in errList) {
                let parts = err.split('.');

                if (parts.length !== 1) {
                    let fieldName = parts[0];
                    let index = parts[1];
                    let subItemField = parts[2];

                    if (!errList.hasOwnProperty(fieldName)) {
                        errList[fieldName] = [];
                        this.subItemsFields.push(fieldName);
                    }
                    if (errList[fieldName][index] === undefined) errList[fieldName][index] = {};
                    errList[fieldName][index][subItemField] = errList[err];
                    delete (errList[err]);
                }
            }
        },

        checkErr(name, sub = false) {
            let subIndex = this.subItemsFields.indexOf(name);
            let subCheck = (sub) ? subIndex > -1 : subIndex === -1;

            return this.errors.hasOwnProperty(name) && subCheck;
        },

        getErr(name, sub = false) {
            let err = '';
            if (this.checkErr(name, sub)) {
                err = (sub) ? this.errors[name] : this.errors[name][0];
            }
            return err;
        },

        checkSubItemErr(index, name) {
            return this.errors[index] !== undefined && this.errors[index].hasOwnProperty(name);
        },

        getSubItemErr(index, name) {
            return this.errors[index][name][0] || '';
        },

        clearErrors() {
            this.errors = {};
            this.subItemsFields = [];
        }
    }
}
