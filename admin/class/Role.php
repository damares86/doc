<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Role extends Common{

    public $table = "roles" ;
    public $rolename ;
    public $redirect ;

    public function showRolenameById(){
        $query = "SELECT rolename
                FROM ".$this->table."
                WHERE id = :id";

       $stmt = $this->conn->prepare($query);

       $stmt->bindParam(":id",$this->id) ;

       $stmt->execute() ;
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
       
       if($stmt){
           return $this->rolename = $row['rolename'];
       } else {
           return false ;
       }
    
    }

    public function showIdByRolename(){
        $query = "SELECT id
                FROM ".$this->table."
                WHERE rolename = :rolename";

       $stmt = $this->conn->prepare($query);

       $stmt->bindParam(":rolename",$this->rolename) ;

       $stmt->execute() ;
    //    $row = $stmt->fetch(PDO::FETCH_ASSOC);
       
       if($stmt){
           return $stmt;
       } else {
           return false ;
       }
    
    }

    public function roleExists(){
        // query to check if email exists
        $query = "SELECT *
        FROM " .$this->prx. $this->table . "
        WHERE rolename = :rolename
        LIMIT 0,1";
        
        // prepare the query
        $stmt = $this->conn->prepare( $query );
        
        // bind given email value
        $stmt->bindParam(':rolename', $this->rolename);
        
        // execute the query
        $stmt->execute();
        
        // get number of rows
        $num = $stmt->rowCount();
    
        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
            return true ;
        }else{
            return false ;
        }

    }

}

?>