<?php
/**
* @package   havefnubb
* @subpackage hfnucal
* @author    FoxMaSk
* @copyright 2010 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Controller to manage the calendar
*/
class defaultCtrl extends jController {
    /**
     * @var plugins to manage the behavior of the controller
     */
    public $pluginParams = array(
        '*'	    => array('auth.required'=>false),
    );

    public function index() {
        $year   = (int) $this->param('year');
        $month  = (int) $this->param('month');
        $day    = (int) $this->param('day');

        if (! checkdate($month,date('d'),$year)) {
            $year   = date('Y');
            $month  = date('n');
            $day    = date('d');
        }


        if ($month == 1)
        {
            $monthBe    = 12;
            $yearBe     = $year-1;
        }
        else {
            $monthBe    = $month-1;
            $yearBe     = $year;
        }

        if ($month == 12)
        {
            $monthAf    = 1;
            $yearAf	= $year+1;
        }
        else {
            $monthAf    = $month+1;
            $yearAf     = $year;
        }

        $rep = $this->getResponse('html');
        $tpl = new jTpl();

        $tpl->assign('year', $year );
        $tpl->assign('month', $month );
        $tpl->assign('day', $day );

        $tpl->assign('yearBe', $yearBe );
        $tpl->assign('monthBe', $monthBe );

        $tpl->assign('yearAf', $yearAf );
        $tpl->assign('monthAf', $monthAf );

        $rep->body->assign('MAIN', $tpl->fetch('hfnucal~index'));
        return $rep;
    }

    public function view() {
        $year   = (int) $this->param('year');
        $month  = (int) $this->param('month');
        $day    = (int) $this->param('day');

        if (! checkdate($month,date('d'),$year)) {
            $year   = date('Y');
            $month  = date('n');
            $day    = date('d');
        }

        $dateB = mktime(0,0,0,$month,$day,$year);
        $dateE = mktime(23,59,59,$month,$day,$year);

        $day_events = jDao::get('hfnucal~cal')->findByDay($dateB,$dateE);

        $rep = $this->getResponse('html');
        $tpl = new jTpl();

        $tpl->assign('year', $year );
        $tpl->assign('month', $month );
        $tpl->assign('day', $day );
        $tpl->assign('datas', $day_events );
        $rep->body->assign('MAIN', $tpl->fetch('hfnucal~view'));
        return $rep;
    }
}
