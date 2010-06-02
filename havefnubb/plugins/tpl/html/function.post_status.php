<?php
/**
* @package     havefnubb
* @subpackage  jtpl_plugin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * function that display the age of the member
 */
function jtpl_function_html_post_status($tpl, $source, $data) {
    global $gJConfig;
    if ($source == 'forum') {
        $id_forum = $data;
        $rec = jClasses::getService('havefnubb~hfnuread')->getReadForum($id_forum);

        if ( $rec === true)
            $status = 'forumicone';
        else
            $status = 'forumiconenew';
    }
    elseif ($source == 'post') {
        $post = $data;
        $status = $post->status_thread;

        //opened thread ?
        if ($post->status_thread == 3) {
            //do the member already read that post ?
            if ( jClasses::getService('havefnubb~hfnuread')->getReadPost($post->id_post,$post->id_forum) === false )
                // no so post is new
                $status = 'post-new';
            else
                // yes so status is opened
                $status = 'opened';
        }

        // does this forum manage auto-expiration ?
        $dayInSecondes = 24 * 60 * 60;
        $dateDiff =  ($post->date_modified == 0) ? floor( (time() - $post->date_created ) / $dayInSecondes) : floor( (time() - $rec->date_modified ) / $dayInSecondes) ;

        $recForum   = jClasses::getService('havefnubb~hfnuforum')->getForum($post->id_forum);
        //if forum has expired ...
        if ( $recForum->post_expire > 0 and $dateDiff >= $recForum->post_expire )
            //close the thread
            $status = 'closed';

        $important = false;
        if ($post->status_thread <> 5 and $post->status_thread <> 7) {
            if ($post->nb_replies >= $gJConfig->havefnubb['important_nb_replies'])
                $important = true;
            if ($post->nb_viewed >= $gJConfig->havefnubb['important_nb_views'])
                $important = true;
        }
        $status = ($important === true) ? $status . '_important' : $status;
    }
    echo $status;
}
