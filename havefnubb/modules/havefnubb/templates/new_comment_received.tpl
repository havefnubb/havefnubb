{@havefnubb~main.subscribe.hello@}

{@havefnubb~post.new.comment.received.on.the.post@}

http://{$server}{jurl 'havefnubb~posts:view',
array('id_post'=>$post->parent_id,
        'parent_id'=>$post->parent_id,
        'id_forum'=>$post->id_forum,
        'ftitle'=>$post->forum_name,
        'ptitle'=>$post->subject)}#p{$post->id_post}


{@havefnubb~post.new.comment.received.unsubscribe@} :
http://{$server}{jurl 'havefnubb~posts:unsubscribe',array('id_post'=>$post->id_post)}
