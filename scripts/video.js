
function startVideo() {

    var video = document.getElementById('video');

    navigator.getUserMedia = navigator.getUserMedia ||
                            navigator.webkitGetUserMedia ||
                            navigator.mozGetUserMedia;

    // request camera from user.
    if (navigator.getUserMedia) {
        navigator.getUserMedia({video: true}, handleVideo, handleError);
    }

    // set to tag video src to camera stream.
    function handleVideo(stream) {
        video.src = window.URL.createObjectURL(stream);
    }

    // In case of an error, print on console.
    function handleError(err) {
        console.log('Camagru: Stream not available' + err + '.');
    }

    var canvas = document.getElementById('canvas');
    var context = canvas.getContext("2d");

    // Set event listener when button is pressed.
    document.getElementById('take-picture-button').addEventListener("click", takePicture);

    // Draw image on canvas.
    function takePicture() {
        context.drawImage(document.getElementById('video'), 0, 0, 320, 240);
        savePicture();
    }

    // Clear drawed image from canvas. Just in case!!!
    function clearCanvas() {
        context.clearRect(0, 0, canvas.width, canvas.height);
    }

    // Save image on server for PHP to edit it and save on DataBase.
    function savePicture() {
        var img = canvas.toDataURL();
        var filter = document.getElementById('selectedImage').getAttribute('src');
        var ajax = new XMLHttpRequest();

        ajax.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // add image to album.
                var album = document.getElementById('album-tainer');
                var newImg = document.createElement('img');
                newImg.className = "album-images";
                newImg.src = "data:image/png;base64," + this.responseText;;
                album.insertAdjacentElement('afterbegin', newImg);
            }
        };
        ajax.open("POST", "/lib/edit-image.php", true);
        ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax.send("imgData=" + img + "&filter=" + filter);
    }
}

// Set up our event listener to run the startup process
// once loading is complete.
window.addEventListener('load', startVideo, false);

var selectedElem = null;

function selectImage(image, elem) {
    if (elem.style.border != "2px dashed transparent") {
        // DE-Select
        document.getElementById('take-picture-button').disabled = true;
        elem.style.border = "2px dashed transparent";
        selectedElem = null;
    } else {
        // Select
        elem.style.border = "2px dashed #2F2F2F";
        document.getElementById('selectedImage').src = image;
        document.getElementById('take-picture-button').disabled = false;

        if (selectedElem != null) {
            selectedElem.style.border = "2px dashed transparent";
        }
        selectedElem = elem;
    }
}

// User upload image handle
var form = document.getElementById('uploadImage');
var imageSelect = document.getElementById('imageSelect');
var uploadButton = document.getElementById('uploadButton');

form.onsubmit = function(event) {
    event.preventDefault();

    // Update button text.
    uploadButton.innerHTML = "Uploading...";

    // Get image from input.
    var image = imageSelect.files[0];

    // Creating a new formData object for ajax.
    var formData = new FormData();

    // adding image to data
    formData.append("user-image", image, "user-image.png");

    // setup the request.
    var ajax = new XMLHttpRequest();
    // open the connection
    ajax.open('POST', '/am/user-upload.php', true);

    ajax.onload = function() {
        if (this.readyState == 4 && this.state == 200) {
            uploadButton.innerHTML = "Upload";

        }

        // Replace video stream with image.
        var preview = document.getElementById('preview');
        var newImg = document.createElement('img');
        var video = document.getElementById('video');
        preview.removeChild(video);

        newImg.id = "video";
        newImg.setAttribute("style", "object-fit: contain;width: 320px;height: 240px;");
        newImg.src = "data:image/png;base64, " + this.responseText;;
        preview.insertAdjacentElement('afterbegin', newImg);

        // Update button text.
        uploadButton.innerHTML = "Upload";
    };
    // Send data..
    ajax.send(formData);
}
