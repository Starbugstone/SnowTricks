const addImageHolder = document.querySelector('#trick_type_form_images');

if(addImageHolder){
    const showButton = document.createElement('a');
    showButton.setAttribute('class', 'btn waves-effect waves-light');
    showButton.innerText = 'Add image';
    showButton.addEventListener('click', function(){

        let addImageElement = getElementFromProto(addImageHolder);

        addImageElement.appendChild(createDeleteButton());

        addImageHolder.appendChild(addImageElement.cloneNode(true));
        let addImageDeleteButtons = document.querySelectorAll('#trick_type_form_images .deleteButton');
        for(let i=0; i<addImageDeleteButtons.length; i++){
            addImageDeleteButtons[i].addEventListener('click', function(){
                deleteFormRow(this);
            });
        }
    });

    addImageHolder.appendChild(showButton);
}


const addVideoHolder = document.querySelector('#trick_type_form_videos');

if(addVideoHolder){
    const showVideoButton = document.createElement('a');
    showVideoButton.setAttribute('class', 'btn waves-effect waves-light');
    showVideoButton.innerText = 'Add video';
    showVideoButton.addEventListener('click', function(){

        let addVideoElement = getElementFromProto(addVideoHolder);

        addVideoElement.appendChild(createDeleteButton());

        addVideoHolder.appendChild(addVideoElement.cloneNode(true));
        let addVideoDeleteButtons = document.querySelectorAll('#trick_type_form_videos .deleteButton');
        for(let i=0; i<addVideoDeleteButtons.length; i++){
            addVideoDeleteButtons[i].addEventListener('click', function(){
                deleteFormRow(this);
            });

            let elems = document.querySelectorAll('select');
            if (elems.length > 0) {
                M.FormSelect.init(elems);
            }

        }
    });

    addVideoHolder.appendChild(showVideoButton);
}




function createDeleteButton(){
    const deleteButton = document.createElement('a');
    deleteButton.setAttribute('class', 'btn waves-effect waves-light deleteButton');

    deleteButton.insertAdjacentHTML('beforeend','<i class="material-icons">delete</i>');

    return deleteButton;
}

function getElementFromProto(holder){
    let elem = holder.dataset.prototype;
    let index = holder.dataset.index;
    if(index === undefined){
        index = 0;
    }
    holder.dataset.index = +index + 1;
    console.log(index);
    elem = elem.replace(/__name__/g, index);

    let wrapper= document.createElement('div');
    wrapper.innerHTML= elem;
    return wrapper.firstChild;
}

function deleteFormRow(self){
    console.log('here', self);
    self.parentNode.parentNode.removeChild(self.parentNode);
}