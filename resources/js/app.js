/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

//Components per al vue-tables-2
import {ServerTable} from 'vue-tables-2';
Vue.use(ServerTable, {}, false, 'bootstrap4', 'default');

//VUE HTTP RESOURCE
import VueResource from 'vue-resource'
Vue.use(VueResource);
//.VUE HTTP RESOURCE


//Importem en mou component StripeForm
import StripeForm from './components/StripeForm';
Vue.component('stripe-form', StripeForm);

//Importem un nou component Courses
import Courses from './components/Courses';
Vue.component('courses-list', Courses);

// Vue.component(
//     'courses-list',
//     require('./components/Courses.vue').default
// );


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    //identificador id='app' a la view "app.blade.php"
    el: '#app',
});
