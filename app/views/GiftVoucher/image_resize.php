<?php

function image_resize($src, $dst, $width, $height, $crop = 0) {

    if (!list($w, $h) = getimagesize($src)) {
        return "Unsupported picture type!";
    }

    $type = strtolower(substr(strrchr($src, "."), 1));
    if ($type == 'jpeg') {
        $type = 'jpg';
    }
    switch ($type) {
        case 'bmp': $img = imagecreatefromwbmp($src);
            break;
        case 'gif': $img = imagecreatefromgif($src);
            break;
        case 'jpg': $img = imagecreatefromjpeg($src);
            break;
        case 'png': $img = imagecreatefrompng($src);
            break;
        default : return "Unsupported picture type!";
    }

    // resize
    if ($crop) {
        if ($w < $width or $h < $height) {
            return "Picture is too small!";
        }
        $ratio = max($width / $w, $height / $h);
        $h = $height / $ratio;
        $x = ($w - $width / $ratio) / 2;
        $w = $width / $ratio;
    }
    else {
        if ($w < $width and $h < $height) {
            return "Picture is too small!";
        }
        $ratio = min($width / $w, $height / $h);
        $width = $w * $ratio;
        $height = $h * $ratio;
        $x = 0;
    }

    $new = imagecreatetruecolor($width, $height);

    // preserve transparency
    if ($type == "gif" or $type == "png") {
        imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
        imagealphablending($new, false);
        imagesavealpha($new, true);
    }

    imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

    switch ($type) {
        case 'bmp': imagewbmp($new, $dst);
            break;
        case 'gif': imagegif($new, $dst);
            break;
        case 'jpg': imagejpeg($new, $dst);
            break;
        case 'png': imagepng($new, $dst);
            break;
    }
    return true;
}
?>

Example that I use when uploading new images to the server.

This saves the original picture in the form:
original.type

and creates a new thumbnail:
100x100.type

<?php
$pic_type = strtolower(strrchr($picture['name'], "."));
$pic_name = "original$pic_type";
move_uploaded_file($picture['tmp_name'], $pic_name);
if (true !== ($pic_error = @image_resize($pic_name, "100x100$pic_type", 100, 100, 1))) {
    echo $pic_error;
    unlink($pic_name);
} else
    echo "OK!";
?>