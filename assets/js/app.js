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
//

//the show hide images on mobile view
require('./showTrickImages.js');

//all the materialize calls
require('./materializeInitialisers.js');

//our ajax library calls
require('./axiosCalls.js');

//the scroll button functions
require('./scrollButtons.js');

//the JS to create the symfony sub forms
require('./symfonyCollectionType');



