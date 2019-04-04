import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// axios.get('https://jsonplaceholder.typicode.com/todos/1')
// axios.get('/ajax')
//     .then(res => console.log(res.data))
//     .catch(err => console.error(err));


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
        .then(function(){
            primaryImageLoader.style.display = "none";
        });
}

// ----------------------------------
// load more Home page
// ----------------------------------

var loadMoreHomePage = document.querySelector('#home-page-load-more');
if (loadMoreHomePage){
    loadMoreHomePage.addEventListener('click', function (e) {
        e.preventDefault();
        loadMoreTricks(loadMoreHomePage);
    })
}

function loadMoreTricks(linkElement){
    let url = linkElement.href;
    let trickCardList = document.querySelector('#trick-card-list');

    //TODO: take care of the different pages.
    axios.get(url)
        .then(function(res){
            console.log(res.data);
            if(res.data.nextPage === 0){
                let template = document.createElement('div');
                template.innerHTML = '<p>No more Tricks</p>';
                linkElement.replaceWith(template);
            }
            loadMoreHomePage.href = res.data.nextPageUrl;
            trickCardList.insertAdjacentHTML('beforeend',res.data.render);
        })
        .catch(function(err){
            console.error(err);
        })
    ;
}
