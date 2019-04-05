//check if the elemnt is in view
function isScrolledIntoView(el) {
    let rect = el.getBoundingClientRect();
    let elemTop = rect.top;
    let elemBottom = rect.bottom;

    return elemTop < window.innerHeight && elemBottom >= 0;
}


//get all our required elements
let trickCardList = document.querySelector('#trick-card-list');
let scrollToTricks = document.querySelector('#scroll-to-tricks');
let scrollToTop = document.querySelector('#scroll-to-top');

//reset all the animation classes
function resetAnimation() {
    scrollToTricks.classList.remove('slideInDown', 'slideOutUp');
    scrollToTop.classList.remove('slideInUp', 'slideOutDown');
}

//check if we have all te required elements, no need to fire the scroll if not
if (trickCardList && scrollToTop && scrollToTricks) {

    //adding our animate classes, requires animate.css
    scrollToTricks.classList.add('animated', 'slideInDown');
    scrollToTop.classList.add('animated');

    window.onscroll = function () {
        if (isScrolledIntoView(trickCardList)) {
            // initial show as is hidden by default
            scrollToTop.style.display = 'block';

            resetAnimation();
            scrollToTricks.classList.add('slideOutUp');
            scrollToTop.classList.add('slideInUp');
        } else {
            resetAnimation();
            scrollToTricks.classList.add('slideInDown');
            scrollToTop.classList.add('slideOutDown');
        }
    };
}

