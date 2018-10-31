/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
// require('../scss/app.scss');
 
// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// var $ = require('jquery');
// import $ from 'jquery';  
import 'bootstrap/js/dist/tooltip';
import 'bootstrap/js/dist/alert';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/modal';
 
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');
$(document).ready(function () {
    console.log("Jquery is ready now !!"); 
});
console.log('Hello Webpack Encore! Edit me in assets/js/app.js from logs ');
