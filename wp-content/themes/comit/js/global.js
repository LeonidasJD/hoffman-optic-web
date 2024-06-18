//STICY BUTTON BACK TO TOP START
var stickyButtonWrappers = document.getElementsByClassName('sticky-button-wrapper');
var stickyButton = document.getElementById('sticky-btn');


stickyButton.addEventListener('click', function(){
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});


window.addEventListener('scroll', function(){
    if(window.scrollY > 400){
       for (stickyButtonWrapper of stickyButtonWrappers){
        stickyButtonWrapper.style.visibility = 'visible';
        stickyButtonWrapper.style.opacity = '1';
       }
    }else{
        for (stickyButtonWrapper of stickyButtonWrappers){
            stickyButtonWrapper.style.visibility = 'hidden';
            stickyButtonWrapper.style.opacity = '0';
           }
    }
});


//STICY BUTTON BACK TO TOP END




