
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
// window.Materialize = require('materialize-css');

window.select2 = require('select2');

window.moment = require('moment');
window.moment.locale('es');
require('moment-countdown');
require('moment-timezone');

window.Clipboard = require('clipboard');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JaTuvaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));
//
// const app = new Vue({
//     el: '#app'
// });

$(document).ready(function(){
    $('.parallax').parallax();
    $('.modal').modal();
    $('.tooltipped').tooltip({delay: 50});
    $(".button-collapse").sideNav();
    new Clipboard('.btn');
    $('.dropdown-button-extend').dropdown({
            constrainWidth: false // Does not change width of dropdown to that of the activator
        }
    );
    $('.material-select').material_select();
});

new Clipboard('.copy');