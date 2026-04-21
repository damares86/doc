<?php 

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class AccountRoles extends Common{

    public $table = "accountsRoles" ;
    public $account_id ;
    public $role_id ;
    public $redirect ;


    public function showAccountRolesId(){

        $query = "SELECT role_id
                FROM " .$this->prx. $this->table . "
                WHERE account_id = :account_id
                ORDER BY role_id ASC";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":account_id",$this->account_id);
        
        $stmt->execute();
        
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

    
        if($stmt){
            return $this->role_id = $row['role_id'];
        } else {
            return false ;
        }
        
    }
    
    
    public function showRolesAccountId(){

        $query = "SELECT account_id
                    FROM " .$this->prx. $this->table . "
                    WHERE role_id = :role_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":role_id",$this->role_id);
        
        $stmt->execute();

        if($stmt){
            return $stmt ;
        } else {
            return false ;
        }
        
    }

    public function countRoleAccounts(){
    
        $query = "SELECT id FROM ".$this->prx.$this->table."
                 WHERE role_id = :role_id";
    
        $stmt = $this->conn->prepare( $query );

        $stmt->bindParam(":role_id",$this->role_id);
        $stmt->execute();
    
        $num = $stmt->rowCount();
    
        return $num;
    }
    
}

?>