/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueLazyLoad from 'vue-lazyload';
import VModal from 'vue-js-modal';

Vue.use(VueLazyLoad);
Vue.use(VModal, { dialog: true });

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('products-list', require('./components/ProductsList.vue').default);
Vue.component('pizzasets-list', require('./components/PizzaSetsList.vue').default);
Vue.component('orders-list', require('./components/OrdersList.vue').default);
Vue.component('order-ingredients', require('./components/OrderIngredientsList.vue').default);
Vue.component('order-customer-data', require('./components/OrderCustomerData.vue').default);
Vue.component('customers-list', require('./components/CustomersList.vue').default);
Vue.component('users-list-customers', require('./components/UsersListCustomers.vue').default);
Vue.component('employees-list', require('./components/EmployeesList.vue').default);
Vue.component('pagination', require('./components/Pagination.vue').default);
Vue.component('ingredients-list', require('./components/PizzaSetIngredientsList.vue').default);
Vue.component('paginate', VuejsPaginate);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',

    data: {
        year: new Date().getFullYear(),
    }
});


$(document).ready(function(){

    /* $.notify({
        icon: 'pe-7s-gift',
        message: "Welcome to <b>Light Bootstrap Dashboard</b> - a beautiful freebie for every web developer."

    },{
        type: 'info',
        timer: 4000
    }); */

});
