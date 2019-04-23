const addImageHolder = document.querySelector('#trick_form_images');

if(addImageHolder){
    addImageHolder.insertAdjacentElement('afterend', createShowButton(addImageHolder, 'add Image'));

    //adding the delete button on existing elements
    const imageElements = document.querySelectorAll('#trick_form_images>div>div');
    imageElements.forEach(function(elem){
        elem.insertAdjacentElement('afterend', createDeleteButton(true));
    });
}

const addVideoHolder = document.querySelector('#trick_form_videos');

if(addVideoHolder){
    addVideoHolder.insertAdjacentElement('afterend', createShowButton(addVideoHolder, 'add video'));
    //adding the delete button on existing elements
    const imageElements = document.querySelectorAll('#trick_form_videos>div>div');
    imageElements.forEach(function(elem){
        elem.insertAdjacentElement('afterend', createDeleteButton(true));
    });
}

function createShowButton(holder, text){
    const showButton = document.createElement('a');
    showButton.setAttribute('class', 'btn waves-effect waves-light');
    showButton.innerText = text;
    showButton.addEventListener('click', function(){

        let addElement = getElementFromProto(holder);

        addElement.appendChild(createDeleteButton());

        holder.insertAdjacentElement('beforeend', addElement);

        // Initialising the materialize select
        let elems = document.querySelectorAll('select');
        if (elems.length > 0) {
            M.FormSelect.init(elems);
        }
    });

    return showButton;
}

// Creating the delete button, set the askConfirm to true for a delete confirmation
function createDeleteButton(askConfirm = false){
    const deleteButton = document.createElement('a');
    deleteButton.setAttribute('class', 'btn waves-effect waves-light red lighten-2 deleteButton my-3');

    if(askConfirm){
        deleteButton.dataset.askConfirm = "true";
    }

    deleteButton.insertAdjacentHTML('beforeend','<i class="material-icons">delete</i>');

    deleteButton.addEventListener('click', function(){
        deleteFormRow(this);
    });

    return deleteButton;
}

function getElementFromProto(holder){
    let elem = holder.dataset.prototype;
    let index = holder.dataset.index;

    if(index === undefined){
        index = 0;
    }
    holder.dataset.index = +index + 1;

    elem = elem.replace(/__name__/g, 'js-added-'+index);

    let wrapper= document.createElement('div');
    wrapper.innerHTML= elem;
    return wrapper.firstChild;
}

function deleteFormRow(self){

    let safeDelete = true;
    if(self.dataset.askConfirm){
        safeDelete = confirm("Are you sure you want to delete this element ?")
    }
    if(safeDelete){
        self.parentNode.parentNode.removeChild(self.parentNode);
    }

}