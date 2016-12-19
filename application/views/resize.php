<?php

//Maximize script execution time
ini_set('max_execution_time', 0);

$ImagesDirectory = 'application/images/artikels/'; //Source Image Directory End with Slash
$DestImagesDirectory = 'application/images/artikels/'; //Destination Image Directory End with Slash
$NewImageWidth = 1024; //New Width of Image
$NewImageHeight = 768; // New Height of Image
$Quality = 90; //Image Quality
//Open Source Image directory, loop through each Image and resize it.
if ($dir = opendir($ImagesDirectory)) {
    while (($file = readdir($dir)) !== false) {

        $imagePath = $ImagesDirectory . $file;
        $destPath = $DestImagesDirectory . $file;
        $checkValidImage = @getimagesize($imagePath);

        if (file_exists($imagePath) && $checkValidImage) { //Continue only if 2 given parameters are true
            //Image looks valid, resize.
            $error = resizeImage($imagePath, $destPath, $NewImageWidth, $NewImageHeight, $Quality);
            if ($error == "ok") {
                echo $file . ' resize Success!<br />';
                /*
                  Now Image is resized, may be save information in database?
                 */
            } else {
                echo $file . $error . ' <br />';
            }
        }
    }
    closedir($dir);
}

//Function that resizes image.
function resizeImage($SrcImage, $DestImage, $MaxWidth, $MaxHeight, $Quality) {
    list($iWidth, $iHeight, $type) = getimagesize($SrcImage);
    $ImageScale = min($MaxWidth / $iWidth, $MaxHeight / $iHeight);
    $NewWidth = ceil($ImageScale * $iWidth);
    $NewHeight = ceil($ImageScale * $iHeight);
    $NewCanves = imagecreatetruecolor($NewWidth, $NewHeight);

    if ($iWidth <= $MaxWidth && $iHeight <= $MaxHeight) {
        if (strtolower(image_type_to_mime_type($type)) == 'image/png') {
            $NewImage = imagecreatefrompng($SrcImage);
            // Resize Image
            if (imagecopyresampled($NewCanves, $NewImage, 0, 0, 0, 0, $NewWidth, $NewHeight, $iWidth, $iHeight)) {
                // copy file
                if (imagejpeg($NewCanves, $DestImage, $Quality)) {
                    imagedestroy($NewCanves);
                    return " was al klein ma is nu kleiner";
                }
            }
        } else {
            return " is al geresized!";
        }
    }

    switch (strtolower(image_type_to_mime_type($type))) {
        case 'image/jpeg':
            $NewImage = imagecreatefromjpeg($SrcImage);
            break;
        case 'image/png':
            $NewImage = imagecreatefrompng($SrcImage);
            break;
        case 'image/gif':
            $NewImage = imagecreatefromjpeg($SrcImage);
            break;
        default:
            return " kon niet worden geresized!";
    }

    // Resize Image
    if (imagecopyresampled($NewCanves, $NewImage, 0, 0, 0, 0, $NewWidth, $NewHeight, $iWidth, $iHeight)) {
        // copy file
        if (imagejpeg($NewCanves, $DestImage, $Quality)) {
            imagedestroy($NewCanves);
            return "ok";
        }
    }
}

?>