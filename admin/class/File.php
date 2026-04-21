<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class File extends Common{

    public $table = "files" ;
    public $filename ;
    public $filename_orig ;
    public $label ;
    public $inputFileName ;
    public $path = "../uploads" ;
    public $origin ;

    public function uploadFile(){

        if($this->filename){
            $target_directory = $this->path ;
            $target_file = $target_directory . $this->filename;
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
            $file_upload_error_messages="";
            
            $allowed_file_types=array("png","jpg","jpeg","JPG","gif","pdf", "doc", "docx", "zip","mp3");
            if(!in_array($file_type, $allowed_file_types)){
                header("Location: ../index.php?p=".$this->origin."&err=formatErr");
		        exit;
            }
            
            if(file_exists($target_file)){
                rename($target_file,$target_file.'_old');
               // $file_upload_error_messages.="File already exists";
            }
            
            // make sure the 'uploads' folder exists
            // if not, create it
            if(!is_dir($target_directory)){
                $oldmask = umask(0);
                mkdir($target_directory, 0777, true);
                umask($oldmask);
            }else{
                $oldmask = umask(0);
                chmod($target_directory, 0777);
                umask($oldmask);
            }
            
            if(empty($file_upload_error_messages)){  
                // the physical file on a temporary uploads directory on the server
                $file = $this->inputFileName;
                
				if(move_uploaded_file($file, $target_file)) {
                    
                    $oldmask = umask(0);
                    chmod($target_file, 0777);
                    umask($oldmask);
                    $query="";
                    if($this->operation=="add"){
            
                    $query = "INSERT INTO
                                " .$this->prx. $this->table . "
                            SET
                            filename = :filename,
                            label = :label";
                            
                        }else if($this->operation=="edit"){
                            $query = "UPDATE
                            " .$this->prx. $this->table . "
                            SET
                            filename = :filename,
                            label = :label
                            WHERE 
                            id = :id";
                        }
                        // prepare the query
                        $stmt = $this->conn->prepare($query);
                        // bind the values
                        $stmt->bindParam(':filename', $this->filename);
                        $stmt->bindParam(':label', $this->label);
                        if($this->operation=="edit"){
                            $stmt->bindParam(':id', $this->id);
                        }
                        // execute the query, also check if query was successful
                        if($stmt->execute()){
                        return true;
                    }else{
                        $this->showError($stmt);
                        return false;
                    }
				
                } else {
                    echo "Failed to upload file.";
                    return false;
                }   
        	}
        }
 
    }

    public function countFile(){
    
        $query = "SELECT id FROM ".$this->prx.$this->table."
                 WHERE filename = :filename";
    
        $stmt = $this->conn->prepare( $query );

        $stmt->bindParam(":filename",$this->filename);
        $stmt->execute();
    
        $num = $stmt->rowCount();
    
        return $num;
    }

    public function showIdByFilename(){
        $query = "SELECT id
                FROM ".$this->table."
                WHERE filename = :filename";

       $stmt = $this->conn->prepare($query);

       $stmt->bindParam(":filename",$this->filename_orig) ;

       $stmt->execute() ;
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
       if($row){
           return $this->id = $row['id'];
       } else {
           return false ;
       }
    
    }
    
    public function showFilenameById(){
        $query = "SELECT filename
                FROM ".$this->table."
                WHERE id = :id";

       $stmt = $this->conn->prepare($query);

       $stmt->bindParam(":id",$this->id) ;

       $stmt->execute() ;
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
       
       if($row){
           return $this->id = $row['filename'];
       } else {
           return false ;
       }
    
    }

}

?>