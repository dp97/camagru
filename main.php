<?PHP
session_start();

// Simple security check.
if (isset($_SESSION["username"])) {
    echo "<P>Hi, ". $_SESSION['username'];
} else {
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<HTML>
    <HEAD>
        <TITLE>Camagru</TITLE>
        <META charset="UTF-8">
        <META name="viewport" content="width=device-width, initial-scale=1.0" />
        <LINK href="https://fonts.googleapis.com/css?family=ABeeZee" rel="stylesheet" />
        <LINK REL="stylesheet" TYPE="text/css" HREF="/stylesheets/styles.css" />
    </HEAD>
    <BODY>

        <HEADER class="site-header">
            <NAV>
                <A ID="title-button" HREF="/main.php">Camagru</A>
                <DIV ID="to-right">
                    <A CLASS="tabs" HREF="/gallery.php">Gallery</A>
                    <A CLASS="tabs" HREF="/am/logout.php">Logout</A>
                    <A CLASS="tabs" HREF="/am/settings.php">Settings</A>
                </DIV>
            </NAV>
        </HEADER>

        <DIV CLASS="application-container">

            <DIV class="main-container">
                <DIV CLASS="super-album-container">
                    <?php
                        loadPosableImages();
                    ?>
                </DIV>

                <DIV class="camera-container">

                    <DIV id="preview" class="camera">
                        <video id="video" width="320" height="240" style="object-fit: contain;" autoplay>
                            <p>Your browser does not support the video tag.</p>
                        </video>
                    </DIV>

                    <canvas ID="canvas" width=320 height=240 style="display:none;"></canvas>

                    <DIV class="camera">
                        <img id="selectedImage" style="position: absolute;" width="320" height="240"/>
                    </DIV>

                    <DIV id='take-picture-button-container'>
                        <button disabled id="take-picture-button">Take Picture</button>

                        <form id="uploadImage">
                            <input type="file" id="imageSelect" name="photo" accept="image/*" required/>
                            <button type="submit" id="uploadButton">Upload</submit>
                        </form>
                    </DIV>

                </DIV>

            </DIV>

            <DIV id="album-tainer" CLASS="album-container">
                <?PHP
                    getUserPhotos();
                ?>
            </DIV>

        </DIV>

        <FOOTER class="site-footer">
            <DIV id="footer-content">
                <P>© Copyright 2017, dpetrov. All rights reserved.</P>
            </DIV>
        </FOOTER>
        <script type="text/javascript" src="/scripts/video.js"></script>
    </BODY>
</HTML>

<?PHP

function loadPosableImages() {
    $path = "images/super-images/";
    $images = glob($path . "*.png");

    foreach ($images as $image) {
        echo "<IMG onclick=\"selectImage('". $image ."', this)\" class='super-album-images' style='border: 2px dashed transparent;' src='$image' />";
    }
}

function getUserPhotos() {
    // Cnnect to database.
    require 'config/DBConnect.php';

    // Preparing the request.
    $sql = "SELECT image FROM db_main.images WHERE owner = '". $_SESSION['username'] ."' ORDER BY date DESC;";
    // Preparing the request.
    $state = $conn->prepare( $sql );
    // Executing the request.
    $state->execute();
    // Fetching the result...
    $result = $state->fetchAll();
    // Disconnecting..
    $conn = NULL;

    foreach ($result as $photo) {
        $src = "data:image/png;base64, " . $photo['image'];
        echo "<img class='album-images' src='$src' />";
    }
}
?>
