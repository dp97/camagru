<?php
session_start();
require 'test_input.php';

if ( isset($_POST['imgData']) && isset($_POST['filter']) ) {
    $imageData = test_input( $_POST['imgData'] );
    $filter = test_input( $_POST['filter'] );

    $image = substr($imageData, strpos( $imageData, ",") + 1 );
    $encodedData = str_replace(' ', '+', $image);
    $unencodedData = base64_decode($encodedData);

    editImage($unencodedData, $filter);
    exit;
}

function editImage($image, $filter) {
    // Load the stamp and the photo to apply the watermark to
    $stamp = imagecreatefrompng('../'. $filter);
    $im = imagecreatefromstring($image);

    // Copy the stamp image onto our photo using the margin offsets and the photo
    // width to calculate positioning of the stamp.
    imagecopy($im, $stamp, 0, 0, 0, 0, imagesx($stamp), imagesy($stamp));

    // Buffering output to get image data.
    ob_start();
        imagepng($im);
        $imageData = ob_get_contents();
    ob_end_clean();

    // Encode to base64
    $im64 = base64_encode($imageData);

    // Save image to DataBase.
    saveToDatabase($im64);

    // sending back the image.
    echo $im64;

    imagedestroy($im);
    imagedestroy($stamp);
}

function saveToDatabase($im) {
    // Connect to Database.
    include('../config/DBConnect.php');

    // Make request to upload image to Database.
    $sql = "INSERT INTO " . $DB_NAME . "." . $IMG_TABLE . " (image, owner) VALUES ('$im', '". $_SESSION['username'] ."')";

    $conn->exec( $sql );
    $conn = null;
}
?>
