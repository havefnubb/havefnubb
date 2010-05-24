<?php
/**
 * @package     havefnubb
 * @subpackage  jtpl_plugin
 * @author      Olivier Demah
 * @copyright  2010
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * hfnuhfnucal plugin :  display a hfnucal
 */
function jtpl_function_html_hfnucalendar($tpl, $params=array()) {

    echo hfnucal($params['year']   , $params['month']  ,$params['day'] , true);
    echo hfnucal($params['yearBe'] , $params['monthBe'], '1'           , false);
    echo hfnucal($params['yearAf'] , $params['monthAf'], '1'           , false);
}


function hfnucal ($year,$month,$day,$big) {
    $cat_text = array(
        'name'  =>  jLocale::get('hfnucal~main.Calendar'),
        'month' => array(
            jLocale::get('hfnucal~main.January'),
            jLocale::get('hfnucal~main.February'),
            jLocale::get('hfnucal~main.March'),
            jLocale::get('hfnucal~main.April'),
            jLocale::get('hfnucal~main.May'),
            jLocale::get('hfnucal~main.June'),
            jLocale::get('hfnucal~main.July'),
            jLocale::get('hfnucal~main.August'),
            jLocale::get('hfnucal~main.September'),
            jLocale::get('hfnucal~main.October'),
            jLocale::get('hfnucal~main.November'),
            jLocale::get('hfnucal~main.December'),
        ),

        'day' => array(
            jLocale::get('hfnucal~main.Monday'),
            jLocale::get('hfnucal~main.Tuesday'),
            jLocale::get('hfnucal~main.Wednesday'),
            jLocale::get('hfnucal~main.Thursday'),
            jLocale::get('hfnucal~main.Friday'),
            jLocale::get('hfnucal~main.Saturday'),
            jLocale::get('hfnucal~main.Sunday')
        )
    );

    if ($big) {
        // if month is january
        if ($month == 1) {
            $monthBe = 12;
            $yearBe = $year-1;
        }
        else {
            $monthBe = $month-1;
            $yearBe = $year;
        }

        // if month is december
        if ($month == 12) {
            $monthAf = 1;
            $yearAf = $year+1;
        }
        else {
            $monthAf = $month+1;
            $yearAf = $year;
        }

        // beginning of the output
        $output = '';

        $output .= '<table class="hfnucal" summary="'.$cat_text['name'].'">'."\n";

        // "navigation"
        $output .=  "\t".
                    '<caption>'.
                    "<a href=".jUrl::get('hfnucal~index',array('year'=>$yearBe,'month'=>$monthBe)).">" .'<<'."</a>".
                    ' ' . $cat_text['month'][$month-1].' '.$year.' '.
                    "<a href=".jUrl::get('hfnucal~index',array('year'=>$yearAf,'month'=>$monthAf)).">" .'>>' . "</a>".
                    '</caption>'."\n";
    }
    else {
        // beginning of the output
        $output = '';

        $output .= '<table class="minihfnucal" summary="'.$cat_text['name'].'">'."\n";

        // "navigation"
        $output .= "\t".'<caption>'."<a href="
                .jUrl::get('hfnucal~default:index',array('year'=>$year,'month'=>$month)).">".$cat_text['month'][$month-1].' '.$year."</a>"
                .'</caption>'."\n";
    }

    // list of events
    $monthEvents = array();

    $timeB = mktime(0,0,0,$month,1,$year);
    $timeE = mktime(0,0,0,$month+1,1,$year);

    $myEvents = jDao::get('hfnucal~cal')->findByMonth($timeB,$timeE);

    foreach ($myEvents as $myEvent) {
        $monthEvents[] = $myEvent->date_created;
    }

    // header
    $output .= "\t".'<thead>'."\n";
    $output .= "\t".'<tr>'."\n";

    foreach ($cat_text['day'] as $d)
        $output .= "\t\t".'<th scope="col"><abbr title="'.$d.'">'.substr($d, 0, 3).'</abbr></th>'."\n";

    $output .= "\t".'</tr>'."\n";
    $output .= "\t".'</thead>'."\n";
    $output .= "\t".'<tbody>'."\n";

    // body of the table
    $ts = strtotime($year.'-'.$month.'-01');

    $d = 1;
    $i = 0;
    $dstart = false;

    $first = date('w',$ts);
    $first = ($first == 0) ? 6 : ($first-1);

    $nbOfDays = date('t',$ts);

    while ($i<42) {

        if ($i%7 == 0) $output .= "\t".'<tr>'."\n";

        if ($i == $first) $dstart = true;

        if ($dstart && !checkdate($month,$d,$year)) $dstart = false;

        $ad_class = array();

        // past date  ?
        if ($dstart && (($year < date('Y')) || ($year == date('Y') && $month < date('n')) || ($year == date('Y') && $month == date('n') && $d < date('d'))))
            $ad_class[] = 'past';

        // date of the day ?
        if ( ($year == date('Y')) && ($month == date('n')) && ($d == date('d')) && $dstart )
            $ad_class[] = 'today';

        // active date  ? (e.g displayed date)
        if ($d == $day && $dstart) $ad_class[] = 'active';

        // box without dates ?
        if (!$dstart) $ad_class[] = 'inactive';

        $class = '';
        if (!empty($ad_class)) $class = ' class="'.implode(" ", $ad_class).'"';

        $output .= "\t\t".'<td'.$class.'>';

        $dateB = mktime(0,0,0,$month,$d,$year);
        $dateE = mktime(23,59,59,$month,$d,$year);

        $dateArray = array('begin'=>$dateB,'end'=>$dateE);

        if (!$dstart) $output .= '';

        elseif (isInTime($monthEvents,$dateB,$dateE)) {
            $output .= '<p class="daynumber">'.
                    "<a href=".jUrl::get('hfnucal~default:view',array('year'=>$year,'month'=>$month,'day'=>$d)).">" .$d . "</a>".
                    '</p>';
            if ($big) {
                $output .= '<ul class="eventlist">';

                $day_events = jDao::get('hfnucal~cal')->findByDay($dateB,$dateE);

                foreach($day_events as $day_event) {
                    $output .= '<li>'.
                    "<a href=".jUrl::get('havefnubb~posts:view',
                             array(
                                'id_forum'=>$day_event->id_forum,
                                'ftitle'=>$day_event->forum_name,
                                'ptitle'=>$day_event->subject,
                                'id_post'=>$day_event->id_post,
                                'parent_id'=>$day_event->parent_id
                                )
                            )."#p{$day_event->id_post}>".
                    stripslashes($day_event->subject)."</a>".
                    '</li>';
                }

                $output .= '</ul>';
            }

        }
        else
            $output .= ($dstart) ? '<p class="daynumber">'.$d.'</p>' : ' ';

        $output .= '</td>'."\n";

        if (($i+1)%7 == 0) {
            $output .= "\t".'</tr>'."\n";

            if ($d>=$nbOfDays) $i=42;
        }

        $i++;
        if ($dstart) $d++;
    }

    // end of table
    $output .= "\t".'</tbody>'."\n";
    $output .= '</table>'."\n";

    echo $output;
}

function isInTime($monthEvents,$dateB,$dateE) {
    $return = false;
    for ($i = 0 ; $i < count($monthEvents) ; $i++ )
        if ($monthEvents[$i] >= $dateB and
            $monthEvents[$i] <= $dateE)
    $return = true;
    return $return;
}
