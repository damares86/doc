<?php 

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class RolesSection extends Common {

    public $table = "rolesSection" ;
    public $section_id ;
    public $role_id ;



function insertRoleSection(){
    
    $query = "INSERT INTO " .$this->prx. $this->table."
    SET 
    section_id = :section_id,
    role_id = :role_id"; 
    
    $stmt = $this->conn->prepare( $query );

    $stmt->bindParam(":section_id", $this->section_id);
    $stmt->bindParam(":role_id", $this->role_id);
   
    if($stmt->execute()){
        return true ;
    }else{
        return false ;
    }
    
}


function showAllPermission(){

    $query = "SELECT *
        FROM " .$this->prx. $this->table."
        WHERE role_id = :role_id
        ORDER BY id ASC"; 
// print_r($query);
// exit;
    $stmt = $this->conn->prepare( $query );

    $stmt->bindParam(":role_id", $this->role_id);

    $stmt->execute();

    return $stmt;
}



}

?>
