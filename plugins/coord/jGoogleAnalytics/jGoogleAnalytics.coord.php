<?php
/**
* @package      plugins
* @subpackage   jGoogleAnalytics
* @author       Loic Mathaud <loic@mathaud.net>
* @copyright    Loic Mathaud 2007
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
* Plugin to add tracking code for Google Analytics into your site
* @package      plugins
* @subpackage   jGoogleAnalytics
*/
class jGoogleAnalyticsCoordPlugin implements jICoordPlugin {

    /**
    * Url to the urchin.js file on google server
    * @var string
    */
    protected $urchinJs;
    
    /**
    * Your uacct number to use Google Analytics
    * @var string
    */
    protected $uacct;
    
    /**
    * Tells if we have to display the marker or not
    * @var boolean
    */
    protected $displayMarker;
    

    public function __construct($config) {
        $this->urchinJs = $config['urchin_js'];
        $this->uacct = $config['uacct'];
        $this->displayMarker = ($config['display_marker'] ? true : false);
    }
    
    public function beforeAction($params) {
        if (isset($params['googleanalytics.display_marker'])) {
            $this->displayMarker = $params['googleanalytics.display_marker'];
        }
    }
    
    public function beforeOutput() {
        global $gJCoord;
        
        if ($gJCoord->response instanceof jResponseHtml && $this->displayMarker) {
			$str_uacct = '
			<script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
			</script>
			<script type="text/javascript">
			try {
			var pageTracker = _gat._getTracker("'.$this->uacct.'");
			pageTracker._trackPageview();
			} catch(err) {}</script>';
			$gJCoord->response->addContent($str_uacct);
			
        }
    }
    
    public function afterProcess () {}
    
}

?>
