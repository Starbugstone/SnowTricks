const viewTrickImagesButton = document.querySelector('#view-trick-images');
const trickImagesContainer = document.querySelector('#trick-images');

//add a fast animate.css animation then remove once finished
function animateCSS(element, animationName, callback) {
    const node = document.querySelector(element)
    node.classList.add('animated', 'faster', animationName)

    function handleAnimationEnd() {
        node.classList.remove('animated', animationName)
        node.removeEventListener('animationend', handleAnimationEnd)

        if (typeof callback === 'function') callback()
    }

    node.addEventListener('animationend', handleAnimationEnd)
}

if(viewTrickImagesButton && trickImagesContainer){
    viewTrickImagesButton.addEventListener('click', function(){

        //we are using the hide-on-small-only class to toggle the visability
        //this enables a navigator resize and not lose functionality with shady JS
        if(trickImagesContainer.classList.contains('hide-on-small-only')){
            //images are hidden, now show
            trickImagesContainer.classList.remove('hide-on-small-only');
            animateCSS('#trick-images', 'slideInUp');


            viewTrickImagesButton.innerHTML = '<i class="material-icons left">cancel</i>Close photos';
        }else{
            //hide images on mobile
            trickImagesContainer.classList.add('hide-on-small-only');


            viewTrickImagesButton.innerHTML = '<i class="material-icons left">insert_photo</i>View photos';

        }
    });
}
