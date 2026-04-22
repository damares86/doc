<?php 


##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################


class Account extends Common{

    public $table = "accounts" ;
    public $username ;
    public $email ;
    public $password ;
    public $avatar ;
    public $last_login ;
    public $token ;
    public $expDate ;
    public $auth_token ;
    public $details ;
    public $details_opt ;

    public function getPswTmpData(){
        
        $query="SELECT * FROM 
        ".$this->prx."password_reset_temp
        WHERE token = :token AND email = :email
        LIMIT 0,1";

        $stmt = $this->conn->prepare( $query );

        $stmt->bindParam(":token",$this->token) ;
        $stmt->bindParam(":email",$this->email) ;

        $stmt->execute();

        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    public function getPswTmpDataByEmail(){
        
        $query="SELECT * FROM 
        ".$this->prx."password_reset_temp
        WHERE email = :email
        LIMIT 0,1";
        
        $stmt = $this->conn->prepare( $query );

        $stmt->bindParam(":email",$this->email) ;

        $stmt->execute();

        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    public function getExpDate(){
        $query = "SELECT *
        FROM ".$this->prx."password_reset_temp
        WHERE email = :email
        LIMIT 0,1";
  
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':email', $this->email);
        
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $this->expDate = $row['expDate'];
    }

    public function getLastLogin(){

        $query="SELECT * FROM 
        ".$this->prx.$this->table."
        ORDER BY last_login DESC
        LIMIT 3";

        $stmt = $this->conn->prepare($query);
        
        $stmt->execute();

        return $stmt ;

    }
    

}

?>