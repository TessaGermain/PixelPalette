function triggerLike(event){
    if(event.target.classList.contains("notliked")){
        event.target.classList.remove("notliked");
        event.target.classList.add("liked");
        event.target.src = likedPath;
    }
    else if(event.target.classList.contains("liked")){
        event.target.classList.remove("liked");
        event.target.classList.add("notliked");
        event.target.src = notlikedPath;
    }
}

function scroll(){
    window.scrollTo({
        top: window.scrollY - 200,
        behavior: 'smooth' // Pour un défilement fluide
      });
      
}