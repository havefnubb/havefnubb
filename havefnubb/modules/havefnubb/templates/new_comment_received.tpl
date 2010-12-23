{@havefnubb~main.subscribe.hello@}

{@havefnubb~post.new.comment.received.on.the.post@}

http://{$server}{jurl 'havefnubb~posts:view',
array('id_post'=>$post->thread_id,
        'thread_id'=>$post->thread_id,
        'id_forum'=>$post->id_forum,
        'ftitle'=>$post->forum_name,
        'ptitle'=>$post->subject)}#p{$id_post}

{@havefnubb~member.your.subscriptions@} :
http://{$server}{jurl 'jcommunity~account:prepareedit', array('user'=>$login)}

{@havefnubb~post.new.comment.received.unsubscribe@} :
http://{$server}{jurl 'havefnubb~posts:unsubscribe',array('id_post'=>$post->thread_id)}
