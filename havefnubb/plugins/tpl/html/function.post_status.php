<?php
/**
* @package     havefnubb
* @subpackage  jtpl_plugin
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * function that display the status of one post or post in a given forum
 */
function jtpl_function_html_post_status($tpl, $source, $data,$lastMarkThreadAsRead=0, $forum = null) {
    $statusAvailable = array('pined',
                            'pinedclosed',
                            'opened',
                            'closed',
                            'censored',
                            'uncensored',
                            'hidden');
    if ($source == 'forum') {
        $id_forum = $data;

        // does the user still read everything in the forum ?
        if ( !jClasses::getService('havefnubb~hfnuposts')->getCountUnreadThreadbyForumId($id_forum) )
        //yes
            $status = 'forumicone';
        //no
        else
            $status = 'forumiconenew';
    }
    elseif ($source == 'post') {
        $post = $data;

        $status = $statusAvailable[ $post->status_thread - 1];
        if (jAuth::isConnected()) {
            //opened thread ?
            if ($post->status_thread == 3) {
                //do the member already read that post ?
                // yes so status is opened
                if ($post->date_last_post < $lastMarkThreadAsRead ||
                        $post->date_read_post >= $post->date_last_post)
                    $status = 'opened';
                else
                    // no so post is new
                    $status = 'post-new';
            }
        }
        // does this forum manage auto-expiration ?
        $dayInSecondes = 24 * 60 * 60;
        $dateDiff =  ($post->date_modified == 0) ? floor( (time() - $post->date_created ) / $dayInSecondes) : floor( (time() - $post->date_modified ) / $dayInSecondes) ;

        //if forum has expired ...
        if ( $forum->post_expire > 0 and $dateDiff >= $forum->post_expire )
            //close the thread
            $status = 'closed';

        $important = false;
        if ($post->status_thread <> 5 and $post->status_thread <> 7) {
            if ($post->nb_replies >= jApp::config()->havefnubb['important_nb_replies'])
                $important = true;
            if ($post->nb_viewed >= jApp::config()->havefnubb['important_nb_views'])
                $important = true;
        }
        $status = ($important === true) ? $status . '_important' : $status;

    }
    echo $status;
}
