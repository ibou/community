/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
// require('../scss/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
//  var $ = require('jquery');
// import $ from 'jquery'; 
global.$ = global.jQuery = $;
import select2 from 'select2';
//Hook up select2 to jQuery 
import 'bootstrap/js/dist/tooltip';
import 'bootstrap/js/dist/alert';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/modal';

// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');
$(document).ready(function () {
    console.log("Hello,", new Date());
    // $('select').select2();
    
});
