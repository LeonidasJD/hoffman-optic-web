//STICY BUTTON TO TOP START
var stickyButton = document.getElementById('sticky-btn');

stickyButton.addEventListener('click', function(){
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});
//STICY BUTTON TO TOP END