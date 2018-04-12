// Get modal
var modal = document.getElementById("myModal");


// modal content image
var image = document.getElementById('modal-image');

// span element that close modal
var span = document.getElementsByClassName('close')[0];

// when user press on image, open modal
function openImage(image) {
    modal.style.display = "block";
    image.src = "data:image/png;base64, " + image;
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
};

// When the user clicks anywhere outside of the modal, close it
window.onclick = function() {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};