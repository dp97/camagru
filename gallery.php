<?PHP
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Gallery</title>
        <META charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <LINK href="https://fonts.googleapis.com/css?family=ABeeZee" rel="stylesheet" />
        <LINK REL="stylesheet" TYPE="text/css" HREF="/stylesheets/styles.css" />
    </head>
    <body>
        <HEADER class="site-header">
            <NAV>
                <A ID="title-button" HREF="/">Camagru</A>
                <DIV ID="to-right">
                    <?PHP
                        if ( isset($_SESSION['username']) ) {
                            echo '<A CLASS="tabs" HREF="/am/logout.php">Logout</A>';
                        } else {
                            echo '<A class="tabs" HREF="/am/login.php">Log In</A>';
                        }
                    ?>
                </DIV>
            </NAV>
        </HEADER>
        <button id='testing' hidden>test</button>

        <DIV id="gal-container" class="gallery-container">

            <?PHP
            getImagesFromDB();
            ?>

        </DIV>

        <!-- The Modal -->
        <div id="myModal" class="modal">
          <!-- Modal content -->
          <div class="modal-content">

            <span class="close">&times;</span>
            <img height=240 width=320 id="modal-image"></img>

            <div class="ld-area-container">
                <div id="like-container">
                    <A onclick="setLike()" id="like-btn" >
                        &#x1F44D; <span id="like-btn-txt">Like</span>
                    </A>
                    <p id="likes-count"></p>
                </div>

                <div id="del-container" onclick="deleteImage()">
                    <button type="button" id="delete-btn">Delete</button>
                </div>
            </div>

            <form method="post" autocomplete="off" action="lib/uploadComment.php">
                <input hidden id="photoID" type="text" value name="photoID"/>
                <input hidden id="howner" type="text" value name="owner"/>
                <textarea required id="comment-area" value="" name="comment" maxlength="255" placeholder="write your comment!" wrap="hard" rows="6" cols="50"></textarea>
                <?PHP
                    if ($_SESSION['username']) {
                        echo '<input class="tabs comment-button" type="submit" value="Submit" />';
                    } else {
                        echo '<input disabled class="tabs comment-button" type="submit" value="Submit" />';
                    }
                ?>
            </form>
          </div>
        </div>

        <FOOTER class="site-footer">
            <DIV id="footer-content">
                <P>© Copyright 2017, dpetrov, Inc. All rights reserved.</P>
            </DIV>
        </FOOTER>

        <script>
            // Get modal
            var modal = document.getElementById("myModal");

            // span element that close modal
            var span = document.getElementsByClassName('close')[0];

            // when user press on image, open modal
            function openImage(loggedUser, image, owner, likes, photoID) {
                // checking if user is the owner to enable delete button.
                if ( loggedUser != owner ) {
                    document.getElementById('delete-btn').style.visibility = "hidden";
                } else {
                    document.getElementById('delete-btn').style.visibility = "visible"
                }

                // Setting photoID
                document.getElementById('photoID').value = photoID;
                // Setting owner.
                document.getElementById('howner').value = owner;

                document.getElementById('likes-count').textContent = likes;
                modal.style.display = "block";
                document.getElementById('modal-image').src = "data:image/png;base64, " + image;
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

            // Ajax function for deleting a image.
            function deleteImage() {
                var photoID = document.getElementById('photoID').value;
                var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        location.reload(true);
                    }
                }
                xhttp.open("GET", "/lib/deleteImage.php?photoID=" + photoID, true);
                xhttp.send();
            }

            // Ajax function for like button.
            function setLike() {
                var photoID = document.getElementById('photoID').value;
                var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        if (this.responseText == "Forbidden.") {
                            return;
                        }
                        console.log(this.responseText);

                        var lc = document.getElementById('likes-count');

                        lc.innerHTML = parseInt(lc.innerHTML) + 1;

                        // Updating likes..
                        document.getElementById( photoID ).innerHTML = lc.innerHTML;
                    }
                };
                xhttp.open("GET", "/lib/updateLikeCounter.php?photoID="+ photoID, true);
                xhttp.send();
            }
            var from = 10;
            // Load more images..
			window.onscroll = function() {
				if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                    var ajax = new XMLHttpRequest();

                    ajax.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            var images = JSON.parse(this.responseText);

                            var i;
                            var gc = document.getElementById('gal-container');

                            for (i = 0; i < images.length; i++) {
                                addImage(images[i], gc);
                            }
                            from += 6;
                        }
                    };
                    ajax.open('GET', '/lib/loadMoreImages.php?from=' + from, true);
                    ajax.send();
                }
            };

            document.getElementById('testing').onclick = function() {
                var ajax = new XMLHttpRequest();

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var images = JSON.parse(this.responseText);

                        var i;
                        var gc = document.getElementById('gal-container');

                        for (i = 0; i < images.length; i++) {
                            addImage(images[i], gc);
                        }
                        from += 6;
                    }
                };
                ajax.open('GET', '/lib/loadMoreImages.php?from=' + from, true);
                ajax.send();
            };

            function addImage(images, gc) {
                var div = document.createElement('div');
                var p = document.createElement('p');
                var img = document.createElement('img');

                var image = images['image'];
                var owner = images['owner'];
                var likes = images['likes'];
                var id =  images['photoID'];
                var comments = images['c_comments'];

                div.className = "photo-div";
                div.onclick = function() {
                    openImage('<?PHP echo $_SESSION['username'];?>', image, owner, likes, id);
                }

                p.id = "photo-info";
                p.innerHTML = "owner: <span ID='owner'>"+ owner +"</span> comments: <span ID='comments'>"+ comments +"</span> likes: <span ID='"+ id +"' >"+ likes +"</span>";

                img.className = "gallery-image";
                img.src = "data:image/png;base64," + image;

                div.appendChild(p);
                div.appendChild(img);
                gc.appendChild(div);
            }
        </script>

    </body>
</html>

<?PHP

function getImagesFromDB() {
    // Connect to Database.
    include('config/DBConnect.php');

    // Logged user.
    $current_user = NULL;
    if ( isset($_SESSION['username']) ) {
        $current_user = $_SESSION['username'];
    }

    // Make request to upload image to Database.
    $sql = "SELECT photoID, image, owner, likes, c_comments
			FROM db_main.images
			ORDER BY date DESC
            LIMIT 10;";

    foreach ($conn->query( $sql ) as $row) {
        echo "<DIV class=\"photo-div\" onclick=\"openImage( '$current_user', '".$row['image']."', '".$row['owner']."', '".$row['likes']."', '". $row['photoID'] ."')\" >";
        echo "
                <p ID='photo-info'>
                    owner: <span ID='owner'>". $row['owner'] ."</span>
                    comments: <span ID='comments'>". $row['c_comments'] ."</span>
                    likes: <span ID='". $row['photoID'] ."' >". $row['likes'] ."</span>
                </p>
                <img class='gallery-image' src='data:image/png;base64, ". $row['image'] ."'/>
            </DIV>
        ";
    }

    $conn = null;
}
?>
