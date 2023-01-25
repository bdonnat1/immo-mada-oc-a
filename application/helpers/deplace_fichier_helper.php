<?php

function deplace_image($chemin_source,$chemin_destination,$last_id,$nom_image){

    if(file_exists(getcwd().$chemin_source.$nom_image)){
        $last_id = $last_id;
        // Creer le reprtoire si il n'existe pas
        if (!is_dir(getcwd().$chemin_destination)) {
            mkdir(getcwd().$chemin_destination, 0777, TRUE);
        }

        // recuperer l'extention du photo de l'etudiant
        $extension_photo = explode(".", $nom_image);
        $extension_photo_value = $extension_photo[1];
        // Renomer l'ancien fichier si exist
        if(file_exists(getcwd().$chemin_destination.'/'.utf8_decode($last_id).".".$extension_photo_value)){
            $file = getcwd().$chemin_destination.'/'.utf8_decode($last_id).".".$extension_photo_value;
            unlink($file);
        }

        // Deplacer le fichier du repertoire temporaire vers le dossier de l'etudiant
        rename(
            getcwd().$chemin_source.$nom_image,
            getcwd().$chemin_destination.'/'.utf8_decode($last_id).".".$extension_photo_value);


        $new_name_image =  utf8_decode($last_id).".".$extension_photo_value;
        return $new_name_image;
    }



    if(file_exists(getcwd().$chemin_destination."/".$nom_image)){
        $last_id = $last_id;
        // Creer le reprtoire si il n'existe pas
        if (!is_dir(getcwd().$chemin_destination)) {
            mkdir(getcwd().$chemin_destination, 0777, TRUE);
        }

        // recuperer l'extention du photo de l'etudiant
        $extension_photo = explode(".", $nom_image);
        $extension_photo_value = $extension_photo[1];
        // Renomer l'ancien fichier si exist
        if(file_exists(getcwd().$chemin_destination.'/'.utf8_decode($last_id).".".$extension_photo_value)){
            $file = getcwd().$chemin_destination.'/'.utf8_decode($last_id).".".$extension_photo_value;
            unlink($file);
        }

        rename(
            getcwd().$chemin_destination.'/'.$nom_image,
            getcwd().$chemin_destination.'/'.utf8_decode($last_id).".".$extension_photo_value);


        $new_name_image =  utf8_decode($last_id).".".$extension_photo_value;
        return $new_name_image;

    }
}
