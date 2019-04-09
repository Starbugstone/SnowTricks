/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');
console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
//

//all the materialize calls
require('./materializeInitialisers.js');

//our ajax library calls
require('./axiosCalls.js');

//the scroll button functions
require('./scrollButtons.js');


let addImageHolder = document.querySelector('#new_trick_type_form_images');
let addImageHtml = addImageHolder.dataset.prototype;

// let addImageHtmlCode = new DOMParser().parseFromString(addImageHtml, 'text/html');
// addImageHolder.appendChild(addImageHtml);
console.log(addImageHtml);
addImageHolder.insertAdjacentHTML('beforeend', addImageHtml);