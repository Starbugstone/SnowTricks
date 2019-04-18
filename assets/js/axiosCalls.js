import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// ----------------------------------
// Set primary image in edit
// ----------------------------------

var images = document.querySelectorAll('.js-set-primary-image');
if (images.length > 0) {
    for (var i = 0; i < images.length; i++) {
        let image = images[i];
        image.addEventListener('click', function (e) {
            e.preventDefault();
            setPrimaryImage(image);
        });
    }
}


function setPrimaryImage(image) {

    let primaryImageLoader = document.querySelector('#primary-image-preloader');
    let primaryImage = document.querySelector('#trick-primary-image');

    primaryImageLoader.style.display = "block";

    axios.get(image.href)
        .then(function (res) {
            let data = res.data;
            if (data.isCarousel === "false") {
                //reset all the other images
                for (var i = 0; i < images.length; i++) {
                    images[i].classList.remove('primary-trick-image');
                }
            }//todo: hook into the carousel to add images

            if (data.isPrimary) {
                image.classList.add('primary-trick-image');
                primaryImage.src = data.image;
            } else {
                image.classList.remove('primary-trick-image');
                primaryImage.src = data.defaultPrimaryImage;
            }
            M.toast({html: 'primary image updated'});
        })
        .catch(function (err) {
            M.toast({html: 'Error getting image: ' + err});
            console.error(err);
        })
        .then(function () {
            primaryImageLoader.style.display = "none";
        });
}

// ----------------------------------
// load more button
// ----------------------------------

var loadMoreElement = document.querySelector('#load-more');
if (loadMoreElement) {
    loadMoreElement.addEventListener('click', function (e) {
        e.preventDefault();
        loadMoreFunction(loadMoreElement);
    })
}

function loadMoreFunction(linkElement) {
    let url = linkElement.href;
    let CardList = document.querySelector('#card-list');

    linkElement.classList.add('pulse');

    axios.get(url)
        .then(res=>{
            if (res.data.nextPage === 0) {
                let template = document.createElement('div');
                template.innerHTML = '<p>No more elements to be loaded</p>';
                linkElement.replaceWith(template);
            }
            loadMoreElement.href = res.data.nextPageUrl;
            CardList.insertAdjacentHTML('beforeend', res.data.render);

        })
        .catch(err=>{
            console.error(err);
            linkElement.text = "Error";
            linkElement.classList.add('red');
        })
        .then(final=>{
            linkElement.classList.remove('pulse');
        })
    ;
}

// ----------------------------------
// Edit the comments
// ----------------------------------

//This is ugly, should be adding some eventlisteners, would need to call from the onload
Window.prototype.addEditForm =  function(e, linkElement){
    e.preventDefault();

    let commentId = linkElement.dataset.commentid;
    let url = linkElement.href;

    let commentButtons = document.querySelector('#comment-buttons-'+commentId);

    if(commentButtons.dataset.status === 'read'){

        commentButtons.dataset.status = 'ajax';

        linkElement.classList.add('pulse');

        axios.get(url)
            .then(res=>{
                let commentId = linkElement.dataset.commentid;
                let elem = res.data.render;
                elem = elem.replace(/comment_type_form_comment/g, 'comment_type_form_comment-'+commentId); //adding the ID to the form

                var parser = new DOMParser();
                var wrapper = parser.parseFromString(elem, "text/html");

                showForm(commentId, wrapper.getRootNode().body)
                commentButtons.dataset.status = "read";
            })
            .catch(err=>{
                console.error(err);
                commentButtons.dataset.status = 'error';
                linkElement.classList.add('red');
            })
            .then(final=>{
                linkElement.classList.remove('pulse');
                commentButtons.dataset.status = 'edit';
                M.textareaAutoResize(document.querySelector('#comment_type_form_comment-'+commentId));
                M.updateTextFields();//initialise the text fields to avoid the label over input text
            })
        ;
    }



};
Window.prototype.removeEditForm =  function(e, linkElement){
    e.preventDefault();
    let commentId = linkElement.dataset.commentid;

    let commentButtons = document.querySelector('#comment-buttons-'+commentId);

    if(commentButtons.dataset.status === 'edit'){
        showComment(commentId);
        commentButtons.dataset.status = 'read';
    }


};

function showForm(commentId, form){
    let commentReadBlock = document.querySelector('#comment-text-'+commentId);
    let commentFormElement = document.querySelector('#comment-form-'+commentId);
    let commentAddFormButton = document.querySelector('#comment-add-form-button-'+commentId);
    let commentRemoveFormButton = document.querySelector('#comment-remove-form-button-'+commentId);

    commentReadBlock.style.display = 'none';
    commentAddFormButton.style.display = 'none';
    commentRemoveFormButton.style.display = 'inline-block';

    commentFormElement.appendChild(form);
}

function showComment(commentId){
    let commentReadBlock = document.querySelector('#comment-text-'+commentId);
    let commentFormElement = document.querySelector('#comment-form-'+commentId);
    let commentAddFormButton = document.querySelector('#comment-add-form-button-'+commentId);
    let commentRemoveFormButton = document.querySelector('#comment-remove-form-button-'+commentId);

    commentReadBlock.style.display = 'block';
    commentAddFormButton.style.display = 'inline-block';
    commentRemoveFormButton.style.display = 'none';

    //deleating the form
    while (commentFormElement.firstChild) {
        commentFormElement.removeChild(commentFormElement.firstChild);
    }

}
