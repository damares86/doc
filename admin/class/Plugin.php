<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Plugin extends Common{

    public $table = "plugins" ;
    public $pluginname ;
    public $description ;
    public $installed ;
    public $active ;
    
    public function showPluginnameById(){
        $query = "SELECT pluginname
                FROM ".$this->table."
                WHERE id = :id";

       $stmt = $this->conn->prepare($query);

       $stmt->bindParam(":id",$this->id) ;

       $stmt->execute() ;
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
       
       if($row){
           return $this->id = $row['pluginname'];
       } else {
           return false ;
       }
    
    }

    public function isActive(){
        $query = "SELECT active
                FROM ".$this->table."
                WHERE pluginname = :pluginname";

       $stmt = $this->conn->prepare($query);

       $stmt->bindParam(":pluginname",$this->pluginname) ;

       $stmt->execute() ;
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
       
       if($row){
           return $row['active'];
       } else {
           return false ;
       }
    
    }


}

?>