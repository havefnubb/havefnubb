<?php
/**
* @package    jelix
* @subpackage db
* @author     Croes Gérald, Laurent Jouanneau
* @contributor Laurent Jouanneau, Gwendal Jouannic, Julien Issler
* @copyright  2001-2005 CopixTeam, 2005-2006 Laurent Jouanneau
* @copyright  2008 Gwendal Jouannic
* @copyright  2008 Julien Issler
*
* This class was get originally from the Copix project (CopixDbTools, CopixDbConnection, Copix 2.3dev20050901, http://www.copix.org)
* Some lines of code are still copyrighted 2001-2005 CopixTeam (LGPL licence).
* Initial authors of this Copix classes are Gerald Croes and Laurent Jouanneau,
* and this class was adapted/improved for Jelix by Laurent Jouanneau
*
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * Description of a field of a table
 * @package  jelix
 * @subpackage db
 * @see jDbTools::getFieldList
 */
 class jDbFieldProperties {
 
    /**
     * native type of the field
     * @var string
     */
    public $type;

    /**
     * unified type of the field
     * @var string
     */
    public $unifiedtype;

    /**
     * field name
     * @var string
     */
    public $name;

    /**
     * says if the field can be null or not
     * @var boolean
     */
    public $notNull=true;

    /**
     * says if the field is the primary key
     * @var boolean
     */
    public $primary=false;

    /**
     * says if the field is auto incremented
     * @var boolean
     */
    public $autoIncrement=false;

    /**
     * default value
     * @var string
     */
    public $default='';

    /**
     * says if there is a default value
     * @var boolean
     */
    public $hasDefault = false;

    public $length = 0;
    
     /**
     * if there is a sequence
     * @var string
     */
    public $sequence = false;
    
    public $unsigned = false;
    
    public $minLength = null;
    
    public $maxLength = null;
    
    public $minValue = null;
    
    public $maxValue = null;
}

/**
 * Provides utilities methods for a database
 * @package  jelix
 * @subpackage db
 */
abstract class jDbTools {

    public $trueValue = '1';

    public $falseValue = '0';
    
    /**
    * the database connector
    * @var jDbConnection
    */
    protected $_conn;

    /**
    *
    */
    function __construct( $connector){
        $this->_conn = $connector;
    }

    protected $unifiedToPhp = array(
        'boolean'=>'boolean',
        'integer'=>'integer',
        'float'=>'float',
        'double'=>'float',
        'numeric'=>'numeric',
        'decimal'=>'decimal',
        'date'=>'string',
        'time'=>'string',
        'datetime'=>'string',
        'year'=>'string',
        'char'=>'string',
        'varchar'=>'string',
        'text'=>'string',
        'blob'=>'string',
        'binary'=>'string',
        'varbinary'=>'string',
    );
    
    protected $typesInfo = array();


    /**
     * get informations about the given SQL type
     * @param string $nativeType the SQL type
     * @return array an array which contains characteristics of the type
     *        array ( 'nativetype', 'corresponding unifiedtype', minvalue, maxvalue, minlength, maxlength)
     * minvalue, maxvalue, minlength, maxlength can be null.
     * @since 1.1.1
    */
    public function getTypeInfo($nativeType) {
        if(isset($this->typesInfo[$nativeType])) {
            $r = $this->typesInfo[$nativeType];
        }
        else 
            $r = $this->typesInfo['varchar'];
        $r[] = ($nativeType == 'serial' || $nativeType == 'autoincrement' || $nativeType == 'bigautoincrement');
        return $r;
    }

    /**
     * return the PHP type corresponding to the given unified type
     * @param string $unifiedType
     * @return string the php type
     * @since 1.1.1
    */
    public function unifiedToPHPType($unifiedType) {
        if(isset($this->unifiedToPhp[$unifiedType])) {
            return $this->unifiedToPhp[$unifiedType];
        }
        throw new Exception('bad unified type name:'.$unifiedType);
    }

    /**
     * @param string $unifiedType  the unified type name
     * @param string $value        the value
     * @return string  the php value corresponding to the type
     * @since 1.1.1
    */
    public function stringToPhpValue($unifiedType, $value, $checkNull = false) {
        if($checkNull && ($value === null ||strtolower($value)=='null'))
            return null;
        switch($this->unifiedToPHPType($unifiedType)) {
            case 'boolean':
                return ($this->getBooleanValue($value) == $this->trueValue);
            case 'integer':
                return intval($value);
            case 'float':
                return floatval($value);
            case 'numeric':
            case 'decimal':
                if(is_numeric($value))
                    return $value;
                else
                    return floatval($value);
            default:
                return $value;
        }
    }

    /**
     * @param string $unifiedType  the unified type name
     * @param mixed $value        the value
     * @return string  the value which is ready to include a SQL query string
     * @since 1.1.1
    */
    public function escapeValue($unifiedType, $value, $checkNull = false, $toPhpSource = false) {
        if($checkNull && ($value === null ||strtolower($value)=='null'))
            return 'NULL';
        switch($this->unifiedToPHPType($unifiedType)) {
            case 'boolean':
                return $this->getBooleanValue($value);
            case 'integer':
                return (string)intval($value);
            case 'float':
                return (string)doubleval($value);
            case 'numeric':
            case 'decimal':
                if(is_numeric($value))
                    return $value;
                else
                    return (string)floatval($value);
            default:
                if ($toPhpSource) {
                    if(strpos($value,"'") !== false){
                        return '\'.$this->_conn->quote(\''.str_replace('\'','\\\'',$value).'\').\'';
                    }else{
                        return "\\'".$value."\\'";
                    }
                }
                elseif ($this->_conn)
                    return $this->_conn->quote($value);
                else
                    return "'".addslashes($value)."'";
        }
    }

    /**
     * @param string|boolean $value a value which is a boolean
     * @return string the string value representing a boolean in SQL
     * @since 1.1.1
    */
    public function getBooleanValue($value) {
      if(is_string($value))
          $value = strtolower($value);
      if ($value =='true' || $value === true || $value =='1'|| $value=='t')
          return $this->trueValue;
      else
          return $this->falseValue;
    }

    /**
     * enclose the field name
     * @param string $fieldName the field name
     * @return string the enclosed field name
     * @since 1.1.1
     */
    public function encloseName($fieldName){
        return $fieldName;
    }

    /**
    * returns the table list
    */
    abstract public function getTableList ();

    /**
    * return the field list of a given table
    * @return array  array of jDbFieldProperties
    */
    abstract public function getFieldList ($tableName);

    /**
     * regular expression to detect comments and end of query
     */
    protected $dbmsStyle = array('/^\s*#/', '/;\s*$/');

    public function execSQLScript ($file) {

        $lines = file($file);
        $cmdSQL = '';
        $nbCmd = 0;

        $style = $this->dbmsStyle;

        foreach ((array)$lines as $key=>$line) {
            if ((!preg_match($style[0],$line))&&(strlen(trim($line))>0)) { // la ligne n'est ni vide ni commentaire
               //$line = str_replace("\\'","''",$line);
               //$line = str_replace($this->scriptReplaceFrom, $this->scriptReplaceBy,$line);

                $cmdSQL.=$line;

                if (preg_match($style[1],$line)) {
                    //Si on est à la ligne de fin de la commande on l'execute
                    // On nettoie la commande du ";" de fin et on l'execute
                    $cmdSQL = preg_replace($style[1],'',$cmdSQL);
                    $this->_conn->query ($cmdSQL);
                    $nbCmd++;
                    $cmdSQL = '';
                }
            }
        }
        return $nbCmd;
   }

}
