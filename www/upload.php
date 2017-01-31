<?php
$target_dir = "/opt/svg/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
////Check if image file is a actual image or fake image
// if(isset($_POST["submit"])) {
    // $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    // if($check !== false) {
        // echo "File is an image - " . $check["mime"] . ".";
        // $uploadOk = 1;
    // } else {
        // echo "File is not an image.";
        // $uploadOk = 0;
    // }
// }
// Check if file already exists
if (file_exists($target_file)) {
    echo "Die Datei existiert bereits.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Die Datei ist zu groß";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "csv" ) {
    echo "Nur csv-Dateien erlaubt.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Die Datei wurde leider nicht hochgeladen";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "Die Datei ". basename( $_FILES["fileToUpload"]["name"]). " wurde erfolgreich hochgeladen.";
    } else {
        echo "Es gab einen Fehler beim Upload der Datei";
    }
}
?>
