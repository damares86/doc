<?php 

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Auth extends Common{

    public $table = "accounts";
    public $username;
    public $password;
    public $email;
    public $avatar;
    public $last_login;
    public $token;
    public $expDate;
    public $auth_token ;


    public function emailExists(){
        
        // query to check if email exists
        $query = "SELECT *
        FROM " .$this->prx. $this->table . "
        WHERE email = ?
        LIMIT 0,1";
    
        // prepare the query
        $stmt = $this->conn->prepare( $query );
    
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));
    
        // bind given email value
        $stmt->bindParam(1, $this->email);
    
        // execute the query
        $stmt->execute();
    
        // get number of rows
        $num = $stmt->rowCount();
    
        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
    
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // assign values to object properties
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->password = $row['password'];
            $this->email = $row['email'];
            $this->avatar = $row['avatar'];
            $this->last_login = $row['last_login'];
    
            // return true because email exists in the database
            return true;
        }
    
        // return false if email does not exist in the database
        return false;
        }


    public function updateLog($time){

        $query="UPDATE 
        " .$this->prx. $this->table . "
            SET last_login=:last_login 
            WHERE id = :id";

        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(':last_login', $time);
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()){
            return true;

        }else{
            $this->showError($stmt);
            return false;
        }

    }

    public function checkCookie(){
        $query="SELECT * FROM ".$this->table."
        WHERE id = :id AND auth_token = :auth_token";
        

        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':auth_token', $this->auth_token);
        
        $stmt->execute();

        $num = $stmt->rowCount();
    
        return $num;
        
    }

}
?>