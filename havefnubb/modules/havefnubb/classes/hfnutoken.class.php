<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnutoken {

    /*
    *setHfnutoken 
    * define a token
    */
    function setHfnutoken() {
        $_SESSION['hfnutoken'] = md5(time().session_id());
    }

    /*
    *getHfnutoken 
    * get a token
    * @return $token string the token
    */    
    function getHfnutoken() {
        return $token = $_SESSION['hfnutoken'];
    }
    
    /*
    *checkHfnutoken 
    * verify the validity of the token
    * @param $tkn string of the token
    */    
    function checkHfnutoken($tkn) {   
        if (empty($tkn) || $_SESSION['hfnutoken'] != $tkn)
        {            
            die("FATAL ERROR : invalid datas !");
        }
        
        unset($_SESSION['hfnutoken']);
        return true;
   }      
}
?>