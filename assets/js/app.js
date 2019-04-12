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

const addImageHolder = document.querySelector('#new_trick_type_form_images');




const addImageElement = returnElementFromProto(addImageHolder);

let deleteButton = document.createElement('a');
deleteButton.setAttribute('class', 'btn waves-effect waves-light deleteButton');

deleteButton.insertAdjacentHTML('beforeend','<i class="material-icons">delete</i>');

addImageElement.appendChild(deleteButton);

const showButton = document.createElement('a');
showButton.setAttribute('class', 'btn waves-effect waves-light');
showButton.innerText = 'Add image';
showButton.addEventListener('click', function(){
    addImageHolder.appendChild(addImageElement.cloneNode(true));
    let addImageDeleteButtons = document.querySelectorAll('#new_trick_type_form_images .deleteButton');
    for(let i=0; i<addImageDeleteButtons.length; i++){
        addImageDeleteButtons[i].addEventListener('click', function(){
            deleteFormRow(this);
        });
    }
    console.log(addImageDeleteButtons);
});

addImageHolder.appendChild(showButton);

function returnElementFromProto(holder){
    let wrapper= document.createElement('div');
    wrapper.innerHTML= holder.dataset.prototype;
    return wrapper.firstChild;
}

function deleteFormRow(self){
    console.log('here', self);
    self.parentNode.parentNode.removeChild(self.parentNode);
}