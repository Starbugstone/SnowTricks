const addImageHolder = document.querySelector('#trick_type_form_images');

if(addImageHolder){
    addImageHolder.insertAdjacentElement('beforeend', createShowButton(addImageHolder, 'add Image'));
}

const addVideoHolder = document.querySelector('#trick_type_form_videos');

if(addVideoHolder){
    addVideoHolder.insertAdjacentElement('beforeend', createShowButton(addVideoHolder, 'add video'));
}

function createShowButton(holder, text){
    const showButton = document.createElement('a');
    showButton.setAttribute('class', 'btn waves-effect waves-light');
    showButton.innerText = text;
    showButton.addEventListener('click', function(){

        let addElement = getElementFromProto(holder);

        addElement.appendChild(createDeleteButton());

        holder.insertAdjacentElement('beforebegin', addElement);

        // Initialising the materialize select
        let elems = document.querySelectorAll('select');
        if (elems.length > 0) {
            M.FormSelect.init(elems);
        }
    });

    return showButton;
}

function createDeleteButton(){
    const deleteButton = document.createElement('a');
    deleteButton.setAttribute('class', 'btn waves-effect waves-light red deleteButton my-3');

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

    elem = elem.replace(/__name__/g, index);

    let wrapper= document.createElement('div');
    wrapper.innerHTML= elem;
    return wrapper.firstChild;
}

function deleteFormRow(self){
    console.log('here', self);
    self.parentNode.parentNode.removeChild(self.parentNode);
}