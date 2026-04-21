<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Mc extends Common
{

    public $page_name;
    public $no_del;
    public $link_to_file;
    public $layout;
    public $header;
    public $header_media;
    public $use_page_name;
    public $use_name;
    public $use_desc;
    public $counter;
    public $color;
    public $quote;
    public $author;
    public $title;
    public $content;
    public $page_id;
    public $popup_cat_id;
    public $category;
    public $name;
    public $value;
    public $label;
    public $email;
    public $gallery_name;
    public $filename;
    public $path;
    public $origin;
    public $inputFileName;
    public $operation;

    public function uploadFile()
    {

        if ($this->filename) {
            $target_directory = $this->path;
            $target_file = $target_directory . $this->filename;
            
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
            $file_upload_error_messages = "";

            $allowed_file_types = array("png", "jpg", "jpeg", "JPG", "gif", "pdf", "doc", "docx", "zip", "mp3");
            if (!in_array($file_type, $allowed_file_types)) {
                header("Location: ../index.php?p=" . $this->origin . "&err=formatErr");
                exit;
            }

            if (file_exists($target_file)) {
                rename($target_file, $target_file . '_old');
                // $file_upload_error_messages.="File already exists";
            }

            // make sure the 'uploads' folder exists
            // if not, create it
            if (!is_dir($target_directory)) {
                $oldmask = umask(0);
                mkdir($target_directory, 0777, true);
                umask($oldmask);
            } else {
                $oldmask = umask(0);
                chmod($target_directory, 0777);
                umask($oldmask);
            }

            // the physical file on a temporary uploads directory on the server
            $file = $this->inputFileName;

            if (move_uploaded_file($file, $target_file)) {
                chmod($target_file,0777);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
