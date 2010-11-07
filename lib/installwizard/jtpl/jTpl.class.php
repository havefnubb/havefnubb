<?php
/**
* @package     jelix
* @subpackage  jtpl
* @author      Laurent Jouanneau
* @contributor Dominique Papin
* @copyright   2005-2009 Laurent Jouanneau, 2007 Dominique Papin
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

/**
 * template engine
 * @package     jelix
 * @subpackage  jtpl
 */
class jTpl {

    const VERSION = '1.0pre.SERIAL';

    /**
     * all assigned template variables. 
     * It have a public access only for plugins. So you musn't use directly this property
     * except from tpl plugins.
     * See methods of jTpl to manage template variables
     * @var array
     */
    public $_vars = array ();

    /**
     * temporary template variables for plugins.
     * It have a public access only for plugins. So you musn't use directly this property
     * except from tpl plugins.
     * @var array
     */
    public $_privateVars = array ();

    /**
     * internal use
     * It have a public access only for plugins. So you musn't use directly this property
     * except from tpl plugins.
     * @var array
     */
    public $_meta = array();

    public function __construct () {
        $this->_vars['j_datenow'] = date('Y-m-d');
        $this->_vars['j_timenow'] = date('H:i:s');
    }

    /**
     * assign a value in a template variable
     * @param string|array $name the variable name, or an associative array 'name'=>'value'
     * @param mixed  $value the value (or null if $name is an array)
     */
    public function assign ($name, $value = null) {
        if (is_array($name)) {
            $this->_vars = array_merge($this->_vars, $name);
        } else {
            $this->_vars[$name] = $value;
        }
    }

    /**
     * assign a value by reference in a template variable
     * @param string $name the variable name
     * @param mixed  $value the value
     * @since jelix 1.1
     */
    public function assignByRef ($name, & $value) {
        $this->_vars[$name] = &$value;
    }

    /**
     * concat a value in with a value of an existing template variable
     * @param string|array $name the variable name, or an associative array 'name'=>'value'
     * @param mixed  $value the value (or null if $name is an array)
     */
    public function append ($name, $value = null) {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                if (isset($this->_vars[$key]))
                    $this->_vars[$key] .= $val;
                else
                    $this->_vars[$key] = $val;
            }
        } else {
            if (isset($this->_vars[$name]))
                $this->_vars[$name] .= $value;
            else
                $this->_vars[$name] = $value;
        }
    }

    /**
     * assign a value in a template variable, only if the template variable doesn't exist
     * @param string|array $name the variable name, or an associative array 'name'=>'value'
     * @param mixed  $value the value (or null if $name is an array)
     */
    public function assignIfNone ($name, $value = null) {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                if (!isset($this->_vars[$key]))
                    $this->_vars[$key] = $val;
            }
        } else {
            if (!isset($this->_vars[$name]))
                $this->_vars[$name] = $value;
        }
    }


    /**
     * says if a template variable exists
     * @param string $name the variable template name
     * @return boolean true if the variable exists
     */
    public function isAssigned ($name) {
        return isset($this->_vars[$name]);
    }

    /**
     * return the value of a template variable
     * @param string $name the variable template name
     * @return mixed the value (or null if it isn't exist)
     */
    public function get ($name) {
        if (isset ($this->_vars[$name])) {
            return $this->_vars[$name];
        } else {
            $return = null;
            return $return;
        }
    }

    /**
     * Return all template variables
     * @return array
     */
    public function getTemplateVars () {
        return $this->_vars;
    }

    /**
     * process all meta instruction of a template
     * @param string $tpl template selector
     * @param string $outputtype the type of output (html, text etc..)
     * @param boolean $trusted  says if the template file is trusted or not
     */
    public function meta ($tpl, $outputtype = '', $trusted = true) {
        $this->getTemplate($tpl,'template_meta_', $outputtype, $trusted);
        return $this->_meta;
    }

    /**
     * display the generated content from the given template
     * @param string $tpl template selector
     * @param string $outputtype the type of output (html, text etc..)
     * @param boolean $trusted  says if the template file is trusted or not
     */
    public function display ($tpl, $outputtype = '', $trusted = true) {
        $this->getTemplate ($tpl, 'template_', $outputtype, $trusted);
    }

    /**
     * contains the name of the template file
     * It have a public access only for plugins. So you musn't use directly this property
     * except from tpl plugins.
     * @var string
     * @since 1.1
     */
    public $_templateName;

    /**
     * include the compiled template file and call one of the generated function
     * @param string $tpl template selector
     * @param string $fctname the internal function name (meta or content)
     * @param string $outputtype the type of output (html, text etc..)
     * @param boolean $trusted  says if the template file is trusted or not
     */
    protected function getTemplate ($tpl, $fctname, $outputtype = '', $trusted = true) {
        $this->_templateName = $tpl;
        $tpl = jTplConfig::$templatePath . $tpl;
        if ($outputtype == '')
            $outputtype = 'html';

        $cachefile = dirname($this->_templateName).'/';
        if ($cachefile == './')
            $cachefile = '';

        if (jTplConfig::$cachePath == '/' || jTplConfig::$cachePath == '')
            throw new Exception('cache path is invalid ! its value is: "'.jTplConfig::$cachePath.'".');

        $cachefile = jTplConfig::$cachePath.$cachefile.$outputtype.($trusted?'_t':'').'_'.basename($tpl);

        $mustCompile = jTplConfig::$compilationForce || !file_exists($cachefile);
        if (!$mustCompile) {
            if (filemtime($tpl) > filemtime($cachefile)) {
                $mustCompile = true;
            }
        }

        if ($mustCompile) {
            include_once(JTPL_PATH . 'jTplCompiler.class.php');

            $compiler = new jTplCompiler();
            $compiler->compile($this->_templateName, $tpl, $outputtype, $trusted,
                               $this->userModifiers, $this->userFunctions);
        }
        require_once($cachefile);
        $fct = $fctname.md5($tpl.'_'.$outputtype.($trusted?'_t':''));
        $fct($this);
    }

    /**
     * return the generated content from the given template
     * @param string $tpl template selector
     * @param string $outputtype the type of output (html, text etc..)
     * @param boolean $trusted  says if the template file is trusted or not
     * @param boolean $callMeta false if meta should not be called
     * @return string the generated content
     */
    public function fetch ($tpl, $outputtype='', $trusted = true, $callMeta=true) {
        $content = '';
        ob_start ();
        try{
            $this->_templateName = $tpl;
            $tpl = jTplConfig::$templatePath . $tpl;

            $cachefile = dirname($this->_templateName).'/';
            if ($cachefile == './')
                $cachefile = '';

            if (jTplConfig::$cachePath == '/' || jTplConfig::$cachePath == '')
                throw new Exception('cache path is invalid ! its value is: "'.jTplConfig::$cachePath.'".');

            $cachefile = jTplConfig::$cachePath.$cachefile.$outputtype.($trusted?'_t':'').'_'.basename($tpl);

            $mustCompile = jTplConfig::$compilationForce || !file_exists($cachefile);
            if (!$mustCompile) {
                if (filemtime($tpl) > filemtime($cachefile)) {
                    $mustCompile = true;
                }
            }

            if ($mustCompile) {
                include_once(JTPL_PATH . 'jTplCompiler.class.php');
                $compiler = new jTplCompiler();
                $compiler->compile($this->_templateName, $tpl, $outputtype,
                                   $trusted, $this->userModifiers, $this->userFunctions);
            }
            require_once($cachefile);
            $md = md5($tpl.'_'.$outputtype.($trusted?'_t':''));
            if ($callMeta) {
                $fct = 'template_meta_'.$md;
                $fct($this);
            }
            $fct = 'template_'.$md;
            $fct($this);
            $content = ob_get_clean();

        } catch(Exception $e) {
            ob_end_clean();
            throw $e;
        }
        return $content;
    }

    protected $userModifiers = array();

    /**
     * register a user modifier. The function should accept at least a
     * string as first parameter, and should return this string
     * which can be modified.
     * @param string $name  the name of the modifier in a template
     * @param string $functionName the corresponding PHP function
     * @since jelix 1.1
     */
    public function registerModifier ($name, $functionName) {
        $this->userModifiers[$name] = $functionName;
    }

    protected $userFunctions = array();

    /**
     * register a user function. The function should accept a jTpl object
     * as first parameter.
     * @param string $name  the name of the modifier in a template
     * @param string $functionName the corresponding PHP function
     * @since jelix 1.1
     */
    public function registerFunction ($name, $functionName) {
        $this->userFunctions[$name] = $functionName;
    }

    /**
     * return the current encoding
     * @return string the charset string
     * @since 1.0b2
     */
    public static function getEncoding () {
        return jTplConfig::$charset;
    }

    public function getLocaleString($locale) {
        $getter = jTplConfig::$localesGetter;
        if ($getter)
            $res = call_user_func($getter, $locale);
        else
            $res = $locale;
        return $res;
    }
}
