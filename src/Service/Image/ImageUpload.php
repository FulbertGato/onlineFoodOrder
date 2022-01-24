<?php
namespace App\Service\Image;

class ImageUpload {

    public function imageNamegen($image){
        $fichier = md5(uniqid()) . '.' . $image->guessExtension();
    }
    
}