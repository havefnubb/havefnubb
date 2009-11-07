<?php
/**
* @package   havefnubb
* @subpackage hook
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hookListener extends jEventListener{
   /* Dummy listener to show what are the events that can be managed */
   function onSampleBannerAnnouncement ($event) {
        $event->add( jZone::get('hook~samplebanner_accouncement') );
   }
   
   function onBeforeCategoryList ($event) {
      
   }
   function onCategoryList ($event) {
      
   }
   function onAfterCategoryList ($event) {
      
   }
   function onBeforeForumIndex ($event) {
      
   }
   function onForumIndex ($event) {
      
   }
   function onAfterForumIndex ($event) {
      
   }
   function onBeforeStats ($event) {
      
   }
   function onStats ($event) {
      
   }
   function onAfterStats ($event) {
      
   }
   function onBeforeOnline ($event) {
      
   }
   function onOnline ($event) {
      
   }
   function onAfterOnline ($event) {
      
   }
   function onBeforeOnlineToday ($event) {
      
   }
   function onOnlineToday ($event) {
      
   }
   function onAfterOnlineToday ($event) {
      
   }
   function onBeforePostsList($event) {
      
   }
   function onPostsList($event) {
      
   }
   function onAfterPostsList ($event) {
      
   }
   function onBeforePostsReplies($event) {
      
   }
   function onPostsReplies($event) {
      
   }
   function onAfterPostsReplies ($event) {
      
   }   
   function onBeforePostsEdit($event) {
      
   }
   function onPostsEdit($event) {
      
   }
   function onAfterPostsEdit ($event) {
      
   }
   function onBeforeFlood($event) {
      
   }
   function onFlood($event) {
      
   }
   function onAfterFlood($event) {
      
   }
   function onBeforeMembersList($event) {
      
   }
   function onMembersList($event) {
      
   }
   function onAfterMembersList($event) {
      
   }
   function onBeforeMemberProfile($event) {
      
   }
   function onMemberProfile($event) {
      
   }
   function onAfterMemberProfile($event) {
      
   }
   function onBeforeSearch($event) {
      
   }
   function onSearch($event) {
      
   }
   function onAfterSearch($event) {
      
   }      
   function onBeforeBan($event) {
      
   }
   function onBan($event) {
      
   }
   function onAfterBan($event) {
      
   }
   function onJcommunityStatusConnected($event) {
      
   }
   function onMainInHeader($event) {
      
   }
   function onMainInFooter($event) {
      
   }         
}