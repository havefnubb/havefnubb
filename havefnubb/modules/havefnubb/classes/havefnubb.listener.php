<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE
*
*/
/**
 * havefnubbListener that handle any events related to any havefnubb 'public' access
 */
class havefnubbListener extends jEventListener{
    /**
    * function to get the statistics content + footer content
    * @pararm event $event Object of a listener
    */
    function onHfnuBoxWidget ($event) {

        $box = new HfnuBoxWidget();
        $box->title = '';
        $box->content = jZone::get('havefnubb~footer_menu');
        $event->add($box);

        $box = new HfnuBoxWidget();
        $box->title = '';
        $box->content = jZone::get('havefnubb~lastposts');
        $event->add($box);

        $box = new HfnuBoxWidget();
        $box->title = '';
        $box->content = jZone::get('havefnubb~stats');
        $event->add($box);
    }
    /**
     * get the user connected today
     */
    function onfindLastVisitToday($event) {
        $m = array();
        $timeout = $event->getParam('today');
        $datas = jDao::get('jcommunity~user')->findLastVisit($timeout);
        foreach ($datas as $data)
            $event->Add($data);

    }
}
