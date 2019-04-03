import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// axios.get('https://jsonplaceholder.typicode.com/todos/1')
axios.get('/ajax')
    .then(res => console.log(res.data))
    .catch(err => console.error(err));

var images = document.querySelectorAll('.js-set-primary-image');
for(var i=0; i<images.length; i++){
    let image = images[i];
    image.addEventListener('click', function(e){
        e.preventDefault();
        // console.log(image.href);
        setPrimaryImage(image);
    });
}

function setPrimaryImage(image){

    let primaryImageLoader = document.querySelector('#primary-image-preloader');
    let primaryImage = document.querySelector('#trick-primary-image');

    primaryImageLoader.style.display = "block";

    axios.get(image.href)
        .then(function(res){
            let data = res.data;
            if(data.isCarousel==="false"){
                //reset all the other images
                for(var i=0; i<images.length; i++){
                    images[i].classList.remove('primary-trick-image');
                }
            }//todo: hook into the carousel to add images

            if(data.isPrimary){
                image.classList.add('primary-trick-image');
                primaryImage.src = data.image;
            }else{
                image.classList.remove('primary-trick-image');
                primaryImage.src = data.defaultPrimaryImage;
            }
            primaryImageLoader.style.display = "none";
        })
        .catch(function(err){
            primaryImageLoader.style.display = "none";
            console.error(err);
        });


}