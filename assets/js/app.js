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

var prevScrollpos = window.pageYOffset;
var scrollToTricks = document.getElementById("scroll-to-tricks")
window.onscroll = function() {
    var currentScrollPos = window.pageYOffset;
    if (prevScrollpos > currentScrollPos) {
        document.getElementById("scroll-to-tricks").style.top = "0";
    } else {
        scrollToTricks.offsetTop = scrollToTricks.offsetTop -1;

    }
    prevScrollpos = currentScrollPos;
}