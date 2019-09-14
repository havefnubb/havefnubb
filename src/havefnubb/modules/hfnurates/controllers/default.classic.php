<?php
/**
* @package   havefnubb
* @subpackage hfnurates
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk, 2019 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Controller to manage the rates
*/
class defaultCtrl extends jController {
    /**
     * @var plugins to manage the behavior of the controller
     */
    public $pluginParams = array(
        '*'     => array('auth.required'=>false),
    );
    /**
     * Put a rate
     */
    function rate_it() {
        //info about the "source" from where the datas come from
        $id_source = $this->intParam('id_source');
        $source= $this->param('source');
        // the star
        $rate = $this->floatParam('star1');
        $rates = jClasses::getService('hfnurates~rates');
        $result= $rates->saveRatesBySource($id_source,$source,$rate);
        $rep = $this->getResponse('redirect');
        $rep->action= $this->param('return_url');
        $rep->params= (array) $this->param('return_url_params');
        return $rep;
    }
    /**
     *Put a rate (in ajax)
     */
    function rate_ajax_it() {
        //info about the "source" from where the datas come from
        $id_source = $this->intParam('id_source');
        $source = $this->param('source');
        $rep = $this->getResponse('htmlfragment');

        //check if the cancel button was selected
        if ($id_source == 0 or $source == '')
            return $rep;

        $rate = $this->floatParam('star1');
        jClasses::getService('hfnurates~rates')->saveRatesBySource($id_source,$source,$rate);
        $result = jClasses::getService('hfnurates~rates')->getTotalRatesBySource($id_source,$source);

        if ($result) {
            $rep->addContent( jLocale::get('hfnurates~main.total.of.rates').':'.$result->total_rates . ' ' . jLocale::get('hfnurates~main.rate') .':'. $result->avg_level );
        }
        else {
            $rep->addContent( jLocale::get('hfnurates~main.total.of.rates').': 0 ' . jLocale::get('hfnurates~main.rate') .': 0');
        }
        return $rep;
    }
}
