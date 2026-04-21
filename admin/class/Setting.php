<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Setting extends Common{

    public $table = "settings" ;
    public $name ;
    public $value ;

    function showByName(){

        $query = "SELECT *
            FROM " .$this->prx. $this->table."
            WHERE name = :name
            ORDER BY id ASC"; 
            
        $stmt = $this->conn->prepare( $query );
        
        $stmt->bindParam(":name", $this->name);
    
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row;
    }
    
    function updateValue(){


        $query = "UPDATE " .$this->prx. $this->table."
        SET  value = :value  WHERE name = :name"; 
        
        $stmt = $this->conn->prepare( $query );

        $stmt->bindParam(":value", $this->value);
        $stmt->bindParam(":name", $this->name);

        if($stmt->execute()){
            return true ;
        }else{
            return false ;
        }
        
    }


}

?>