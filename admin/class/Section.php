<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Section extends Common{

    public $table_parent = "sectionParent";
    public $table_child = "sectionChild";
    public $parent_id ;
    public $link ;
    public $label ;
    public $icon ;
    public $show_menu ;

        
    public function countChild($id){
    
        $query = "SELECT id FROM ".$this->prx.$this->table_child."
                 WHERE parent_id = :id";
    
        $stmt = $this->conn->prepare( $query );

        $stmt->bindParam(":id",$id);
        $stmt->execute();
    
        $num = $stmt->rowCount();
    
        return $num;
    }
    
    public function insertParent(){

        $query = "INSERT INTO " .$this->prx. $this->table_parent."
        SET link = :link,
        label = :label,
        icon = :icon"; 
        
        $stmt = $this->conn->prepare( $query );
    
        $stmt->bindParam(":link", $this->link);
        $stmt->bindParam(":label", $this->label);
        $stmt->bindParam(":icon", $this->icon);
    
        if($stmt->execute()){
            return true ;
        }else{
            return false ;
        }
        
    }

    public function insertChild(){

        $query = "INSERT INTO " .$this->prx. $this->table_child."
        SET link = :link,
        label = :label,
        icon = :icon,
        parent_id = :parent_id,
        show_menu = :show_menu"; 
        
        $stmt = $this->conn->prepare( $query );
    
        $stmt->bindParam(":link", $this->link);
        $stmt->bindParam(":label", $this->label);
        $stmt->bindParam(":icon", $this->icon);
        $stmt->bindParam(":parent_id", $this->parent_id);
        $stmt->bindParam(":show_menu", $this->show_menu);
    
        if($stmt->execute()){
            return true ;
        }else{
            return false ;
        }
        
    }

    function showByLink($link, $table){

        $query = "SELECT *
            FROM " .$this->prx. $table."
            WHERE link = :link";   
    
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(":link",$link);
    
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ;
    }
    
    function showById($table){

        $query = "SELECT *
            FROM " .$this->prx. $table."
            WHERE id = :id";   
    
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(":id",$this->id);
    
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ;
    }

    function showAllChild(){

        $query = "SELECT *
            FROM " .$this->prx. $this->table_child."
            WHERE parent_id = :parent_id
            ORDER BY id ASC"; 

        $stmt = $this->conn->prepare( $query );
    
        $stmt->bindParam(":parent_id", $this->parent_id);
    
        $stmt->execute();
    
        return $stmt;
    }
    

    function deleteByLink($table){
        
        $query = "DELETE FROM " .$this->prx. $table. " WHERE link = :link";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":link", $this->link);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

}

?>