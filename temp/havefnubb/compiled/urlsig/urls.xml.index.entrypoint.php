<?php 
$GLOBALS['SIGNIFICANT_PARSEURL']['index'] = array (
  0 => true,
  1 => 
  array (
    0 => 'jelix',
    1 => 'error:notfound',
    2 => '!^/404$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  2 => 
  array (
    0 => 'havefnubb',
    1 => 'posts:rss',
    2 => '!^/rss/(\\d+)-([^\\/]+)$!',
    3 => 
    array (
      0 => 'id_forum',
      1 => 'ftitle',
    ),
    4 => 
    array (
      0 => false,
      1 => true,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  3 => 
  array (
    0 => 'havefnubb',
    1 => 'default:index',
    2 => '!^/community$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  4 => 
  array (
    0 => 'havefnubb',
    1 => 'default:index',
    2 => '!^/$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  5 => 
  array (
    0 => 'havefnubb',
    1 => 'category:view',
    2 => '!^/cat/(\\d+)-([^\\/]+)$!',
    3 => 
    array (
      0 => 'id_cat',
      1 => 'ctitle',
    ),
    4 => 
    array (
      0 => false,
      1 => true,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  6 => 
  array (
    0 => 'havefnubb',
    1 => 'posts:lists',
    2 => '!^/forum/(\\d+)-([^\\/]+)$!',
    3 => 
    array (
      0 => 'id_forum',
      1 => 'ftitle',
    ),
    4 => 
    array (
      0 => false,
      1 => true,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  7 => 
  array (
    0 => 'havefnubb',
    1 => 'posts:view',
    2 => '!^/forum/(\\d+)-([^\\/]+)/posts/(\\d+)-(\\d+)-([^\\/]+)$!',
    3 => 
    array (
      0 => 'id_forum',
      1 => 'ftitle',
      2 => 'id_post',
      3 => 'parent_id',
      4 => 'ptitle',
    ),
    4 => 
    array (
      0 => false,
      1 => true,
      2 => false,
      4 => true,
      3 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  8 => 
  array (
    0 => 'havefnubb',
    1 => 'forum:read_rss',
    2 => '!^/readrss/(\\d+)-([^\\/]+)$!',
    3 => 
    array (
      0 => 'id_forum',
      1 => 'ftitle',
    ),
    4 => 
    array (
      0 => false,
      1 => true,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  9 => 
  array (
    0 => 'havefnubb',
    1 => 'posts:add',
    2 => '!^/post/create/(\\d+)$!',
    3 => 
    array (
      0 => 'id_forum',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  10 => 
  array (
    0 => 'havefnubb',
    1 => 'posts:reply',
    2 => '!^/post/reply/(\\d+)$!',
    3 => 
    array (
      0 => 'id_post',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  11 => 
  array (
    0 => 'havefnubb',
    1 => 'posts:notify',
    2 => '!^/post/notify/(\\d+)$!',
    3 => 
    array (
      0 => 'id_post',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  12 => 
  array (
    0 => 'havefnubb',
    1 => 'posts:edit',
    2 => '!^/post/edit/(\\d+)$!',
    3 => 
    array (
      0 => 'id_post',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  13 => 
  array (
    0 => 'havefnubb',
    1 => 'posts:quote',
    2 => '!^/post/quote/(\\d+)/(\\d+)$!',
    3 => 
    array (
      0 => 'id_post',
      1 => 'parent_id',
    ),
    4 => 
    array (
      0 => false,
      1 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  14 => 
  array (
    0 => 'havefnubb',
    1 => 'posts:delete',
    2 => '!^/post/delete/(\\d+)/(\\d+)$!',
    3 => 
    array (
      0 => 'id_post',
      1 => 'id_forum',
    ),
    4 => 
    array (
      0 => false,
      1 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  15 => 
  array (
    0 => 'havefnubb',
    1 => 'members:index',
    2 => '!^/members$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  16 => 
  array (
    0 => 'hfnusearch',
    1 => 'default:index',
    2 => '!^/search$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  17 => 
  array (
    0 => 'hfnusearch',
    1 => 'default:query',
    2 => '!^/search/results$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  18 => 
  array (
    0 => 'havefnubb',
    1 => 'default:cloud',
    2 => '!^/tag/([^\\/]+)$!',
    3 => 
    array (
      0 => 'tag',
    ),
    4 => 
    array (
      0 => true,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  19 => 
  array (
    0 => 'havefnubb',
    1 => 'members:changepwd',
    2 => '!^/profile/([^\\/]+)/changepwd$!',
    3 => 
    array (
      0 => 'user',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  20 => 
  array (
    0 => 'havefnubb',
    1 => 'members:mail',
    2 => '!^/profile/pm$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  21 => 
  array (
    0 => 'jmessenger',
    1 => 'jmessenger:index',
    2 => '!^/messages$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  22 => 
  array (
    0 => 'jmessenger',
    1 => 'jmessenger:inbox',
    2 => '!^/messages$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  23 => 
  array (
    0 => 'jmessenger',
    1 => 'jmessenger:outbox',
    2 => '!^/messages/outbox$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  24 => 
  array (
    0 => 'jmessenger',
    1 => 'jmessenger:create',
    2 => '!^/messages/new$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  25 => 
  array (
    0 => 'jmessenger',
    1 => 'jmessenger:precreate',
    2 => '!^/messages/new$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  26 => 
  array (
    0 => 'jmessenger',
    1 => 'jmessenger:preupdate',
    2 => '!^/messages/edit/([^\\/]+)$!',
    3 => 
    array (
      0 => 'id',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  27 => 
  array (
    0 => 'jmessenger',
    1 => 'jmessenger:view',
    2 => '!^/messages/read/([^\\/]+)$!',
    3 => 
    array (
      0 => 'id',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  28 => 
  array (
    0 => 'jmessenger',
    1 => 'jmessenger:answer',
    2 => '!^/messages/answer/([^\\/]+)$!',
    3 => 
    array (
      0 => 'id',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  29 => 
  array (
    0 => 'jmessenger',
    1 => 'jmessenger:delete',
    2 => '!^/messages/delete/([^\\/]+)$!',
    3 => 
    array (
      0 => 'id',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  30 => 
  array (
    0 => 'jauth',
    1 => 'login:out',
    2 => '!^/auth/dologout$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  31 => 
  array (
    0 => 'jcommunity',
    1 => 'login:index',
    2 => '!^/auth/login$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  32 => 
  array (
    0 => 'jcommunity',
    1 => 'login:in',
    2 => '!^/auth/dologin$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  33 => 
  array (
    0 => 'jcommunity',
    1 => 'login:out',
    2 => '!^/auth/dologout$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  34 => 
  array (
    0 => 'jcommunity',
    1 => 'default:index',
    2 => '!^/profile$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  35 => 
  array (
    0 => 'jcommunity',
    1 => 'account:prepareedit',
    2 => '!^/profile/([^\\/]+)/prepareedit$!',
    3 => 
    array (
      0 => 'user',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  36 => 
  array (
    0 => 'jcommunity',
    1 => 'account:show',
    2 => '!^/profile/([^\\/]+)$!',
    3 => 
    array (
      0 => 'user',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  37 => 
  array (
    0 => 'jcommunity',
    1 => 'account:edit',
    2 => '!^/profile/([^\\/]+)/edit$!',
    3 => 
    array (
      0 => 'user',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => 
    array (
      0 => 'account:prepareedit',
      1 => 'account:save',
    ),
  ),
  38 => 
  array (
    0 => 'jcommunity',
    1 => 'account:destroy',
    2 => '!^/profile/([^\\/]+)/destroy$!',
    3 => 
    array (
      0 => 'user',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => 
    array (
      0 => 'account:dodestroy',
    ),
  ),
  39 => 
  array (
    0 => 'jcommunity',
    1 => 'registration:index',
    2 => '!^/registration$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  40 => 
  array (
    0 => 'jcommunity',
    1 => 'registration:save',
    2 => '!^/registration/save$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  41 => 
  array (
    0 => 'jcommunity',
    1 => 'registration:confirm',
    2 => '!^/registration/confirm$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  42 => 
  array (
    0 => 'jcommunity',
    1 => 'registration:confirmform',
    2 => '!^/registration/confirmform$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  43 => 
  array (
    0 => 'jcommunity',
    1 => 'registration:confirmok',
    2 => '!^/registration/confirmok$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  44 => 
  array (
    0 => 'jcommunity',
    1 => 'password:index',
    2 => '!^/registration/password$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  45 => 
  array (
    0 => 'jcommunity',
    1 => 'password:send',
    2 => '!^/registration/password/send$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  46 => 
  array (
    0 => 'jcommunity',
    1 => 'password:confirm',
    2 => '!^/registration/password/confirm$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  47 => 
  array (
    0 => 'jcommunity',
    1 => 'password:confirmform',
    2 => '!^/registration/password/confirmform$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  48 => 
  array (
    0 => 'jcommunity',
    1 => 'password:confirmok',
    2 => '!^/registration/password/confirmok$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  49 => 
  array (
    0 => 'downloads',
    1 => 'default:index',
    2 => '!^/downloads/$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  50 => 
  array (
    0 => 'downloads',
    1 => 'default:index',
    2 => '!^/downloads/([^\\/]+)$!',
    3 => 
    array (
      0 => 'dir',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  51 => 
  array (
    0 => 'downloads',
    1 => 'default:dl',
    2 => '!^/downloads/dl/(\\d+)$!',
    3 => 
    array (
      0 => 'id',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  52 => 
  array (
    0 => 'downloads',
    1 => 'default:view',
    2 => '!^/downloads/([^\\/]+)/([^\\/]+)$!',
    3 => 
    array (
      0 => 'dir',
      1 => 'url',
    ),
    4 => 
    array (
      0 => false,
      1 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  53 => 
  array (
    0 => 'downloads',
    1 => 'feeds:lastest',
    2 => '!^/downloads/feeds/lastest/([^\\/]+)$!',
    3 => 
    array (
      0 => 'dir',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  54 => 
  array (
    0 => 'downloads',
    1 => 'feeds:mostpopular',
    2 => '!^/downloads/feeds/mostpopular/([^\\/]+)$!',
    3 => 
    array (
      0 => 'dir',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  55 => 
  array (
    0 => 'hfnucontact',
    1 => 'default:index',
    2 => '!^/contactme/([^\\/]+)$!',
    3 => 
    array (
      0 => 'to',
    ),
    4 => 
    array (
      0 => false,
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  56 => 
  array (
    0 => 'hfnucontact',
    1 => 'default:index',
    2 => '!^/contactme/$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
  57 => 
  array (
    0 => 'hfnucontact',
    1 => 'default:contacted',
    2 => '!^/contacted$!',
    3 => 
    array (
    ),
    4 => 
    array (
    ),
    5 => 
    array (
    ),
    6 => false,
  ),
);
?>