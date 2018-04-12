<?PHP
session_start();

// Simple security check.
if (!isset($_SESSION["username"])) {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image = $_FILES['user-image']['tmp_name'];

$img = fopen($image, "r");
print_r(base64_encode(fread($img, filesize($image))));
fclose($img);
    //echo "<img src='$image' />";
}
?>
