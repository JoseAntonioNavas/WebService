
<?php
 
class Valida{

    function validateEmail($email){

        if (filter_var($email, FILTER_VALIDATE_EMAIL) && empty($email) == false) {
            
            return true;

        }else{

            return false;
        }
        
    }

    function isBlank($var){

        if($var == "" || $var == null){
            return true;
        }else{
            return false;
        }
    }

 
    

}


?>