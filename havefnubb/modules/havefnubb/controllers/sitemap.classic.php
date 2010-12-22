<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2010 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Controller to manage the sitemap
*/
class sitemapCtrl extends jController {
    /**
    * Page info display to sitemap
    */
    function index() {
        $rep = $this->getResponse('sitemap');
        //index
        $rep->addUrl(jUrl::get('havefnubb~default:index'), null, 'daily', 1);
        //categories
        $cats = jDao::get('havefnubb~category')->findAll();
        foreach ($cats as $cat) {
            $rep->addUrl(
                jUrl::get('havefnubb~category:view',
                          array('id_cat'=>$cat->id_cat,
                                'ctitle'=>$cat->cat_name)
                          ),
                null,
                'daily',
                1);
        }
        // posts list :
        // 1=) get the list of forum
        $forums = jDao::get('havefnubb~forum')->findAll();
        foreach ($forums as $forum) {
            // 2=) for each forum, get the list of posts
            list($page,$posts) = jClasses::getService('havefnubb~hfnuposts')->findByIdForum($forum->id_forum,0,25);
            foreach ($posts as $post) {
                $rep->addUrl(
                        jUrl::get('havefnubb~posts:view',
                                    array(  'id_post'=>$post->id_post,
                                            'parent_id'=>$post->parent_id,
                                            'id_forum'=>$post->id_forum,
                                            'ftitle'=>$post->forum_name,
                                            'ptitle'=>$post->subject)
                                ),
                    null,
                    'hourly',
                    1);
            }
        }
        return $rep;
    }
}
