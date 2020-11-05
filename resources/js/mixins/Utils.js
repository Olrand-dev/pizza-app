/**
 * Mixin with common used methods
 */
export default {

    methods: {

        inArray(needle, haystack, strict = true) {

            let found = false;
            let key;

            for (key in haystack) {
                if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
                    found = true;
                    break;
                }
            }

            return found;
        },

        getItemById(list, id) {
            return list.filter((_item) => _item.id === id)[0];
        },

        removeByIndex(arr, index) {
            arr.splice(index, 1);
        },

        isObject(obj) {
            return obj !== null && typeof obj === 'object'
        },

        objLength(obj) {
            return Object.keys(obj).length;
        },

        hop(obj, prop) {
            if (!this.isObject(obj)) return false;
            return obj.hasOwnProperty(prop);
        },

        clone(obj, deep = false) {
            if (!this.isObject(obj)) return;

            return (deep) ? jQuery.extend(true, {}, obj) : jQuery.extend({}, obj);
        },

        checkType(val, type) {
            switch (type) {
                case 'string':
                case 'number':
                case 'boolean':
                case 'object': return typeof val === type;
                case 'array': return Array.isArray(val);
            }
        }
    }
};
