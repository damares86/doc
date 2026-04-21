<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

require __DIR__ . "/coreConfig.php";

// check if there's a page to delete

if (filter_input(INPUT_GET, "idToDel")) {

    // gestire il discorso del colore usato

    $idToDel = filter_input(INPUT_GET, "idToDel");

    $mc->table = 'mc_galleries';
    $mc->id = $idToDel;

    if ($mc->delete('id')) {

        // $error = '' ;
        // if(!$mc->rmdir_recursive('../../uploads/gallery/g_'.$idToDel)){
        //     $error = '&err=galleryImgDelFail' ;
        // }
        // header("Location: ../index.php?p=allGalleries&msg=galleryDelSucc$error");
        
        $mc->rmdir_recursive('../../uploads/gallery/g_'.$idToDel);
        
        header("Location: ../index.php?p=allGalleries&msg=galleryDelSucc");
        exit;
    } else {
        header("Location: ../index.php?p=allGalleries&err=galleryDelFail");
        exit;
    }
}else if(filter_input(INPUT_GET, "imgToDel")){

    $imgToDel = filter_input(INPUT_GET, "imgToDel");
    $idGallery = filter_input(INPUT_GET, "idGallery");

    if(!unlink('../../uploads/gallery/g_'.$idGallery.'/'.$imgToDel)){
        header("Location: ../index.php?p=editGallery&idToMod=$idGallery&err=imgDelErr");
        exit;
    }else{
        header("Location: ../index.php?p=editGallery&idToMod=$idGallery&msg=imgDelSucc");
        exit;
    }

}

$operation = filter_input(INPUT_POST, "operation");

// check if there's a gallery to edit or add

if ($operation == 'add') {

    // insert gallery in db
    $mc->table = 'mc_galleries';
    $gallery_name = filter_input(INPUT_POST, 'gallery_name'); 
    $mc->gallery_name = $gallery_name ;
    $error = 0;
    if (!$mc->insert(['gallery_name'])) {
        header("Location: ../index.php?p=allGalleries&err=galleryAddFail");
        exit;
    } else {

        $mc->table = 'mc_galleries' ;
        $stmt = $mc->showAllLimitDesc('id',1);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        $last_id = $row['id'];

        for ($i = 0; $i < count($_FILES['myfile']['name']); $i++) {

            if ($_FILES['myfile']['size'][$i] > 0) {

                $filename = $_FILES['myfile']['name'][$i];
                $mc->filename = $filename;
                $mc->inputFileName = $_FILES['myfile']['tmp_name'][$i];
                // $mc->label =  $gallery_name . '_'.$i ;
                $mc->path = "../../uploads/gallery/g_$last_id/";
                $mc->origin = filter_input(INPUT_POST, "origin");

                $mc->operation = filter_input(INPUT_POST, "operation");

                if (!$mc->uploadFile()) {
                    $error++;
                }
            }

            $error_msg = '';
            if ($error > 0) {
                $error_msg = '&err=errFileImg';
            }
        }
        header("Location: ../index.php?p=allGalleries&msg=galleryAddSucc$error_msg");
        exit;
    }
} else if ($operation == 'edit') {

  $gallery_name = filter_input(INPUT_POST, 'gallery_name');
  $old_gallery_name = filter_input(INPUT_POST, 'old_gallery_name');
  $gallery_id = filter_input(INPUT_POST, 'id_gallery');

  if ($gallery_name != $old_gallery_name) {
      $mc->table = 'mc_galleries';
      $mc->gallery_name = $gallery_name;
      $mc->id = $gallery_id ;
      $error_db = 0;
      if (!$mc->update(['gallery_name'],'id')) {
          $error_db++;
      }
  }

  $error_img = 0 ;
  
  if($_FILES['myfile']){

      for ($i = 0; $i < count($_FILES['myfile']['name']); $i++) {
          
          if ($_FILES['myfile']['size'][$i] > 0) {
              
              $filename = $_FILES['myfile']['name'][$i];
              $mc->filename = $filename;
              $mc->inputFileName = $_FILES['myfile']['tmp_name'][$i];
          // $mc->label =  $gallery_name . '_'.$i ;
          $mc->path = "../../uploads/gallery/g_$gallery_id/";
          $mc->origin = filter_input(INPUT_POST, "origin");
          
          $mc->operation = filter_input(INPUT_POST, "operation");
          
          if (!$mc->uploadFile()) {
              $error_img++;
            }
        }
    }

      $error_msg = '';
      if ($error_img > 0) {
          $error_msg .= '&err=errFileImg';
        }
    }
      if ($error_db > 0) {
          $error_msg .= '&err=errGalleryName';
      }
  header("Location: ../index.php?p=editGallery&idToMod=$gallery_id&msg=galleryEditSucc$error_msg");
  exit;
} else {
    header("Location: ../index.php?p=allGalleries&err=noPost");
    exit;
}
