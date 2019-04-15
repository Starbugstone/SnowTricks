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

//This is ugly, should be adding some eventlisteners, would need to call from the onload
Window.prototype.addEditForm =  function(e, linkElement){
    e.preventDefault();
    let commentId = linkElement.dataset.commentid;
    let url = linkElement.href;

    axios.get(url)
        .then(res=>{
            console.log(res.data.render);
        })
    ;

    console.log("called add "+commentId+' '+ url,linkElement);
};
Window.prototype.removeEditForm =  function(e, linkElement){
    e.preventDefault();
    let commentId = linkElement.dataset.commentid;


    console.log("called remove "+commentId,linkElement);
};

