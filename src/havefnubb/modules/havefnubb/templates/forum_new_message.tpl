{@havefnubb~main.subscribe.hello@}

{@havefnubb~forum.new.post.in.your.subcribed.forum@} : {$post->forum_name} > {$post->subject}

{jfullurl 'havefnubb~posts:view',
array('id_post'=>$post->id_post,
        'thread_id'=>$post->thread_id,
        'id_forum'=>$post->id_forum,
        'ftitle'=>$post->forum_name,
        'ptitle'=>$post->subject)}#p{$post->id_post}

{@havefnubb~forum.unsubscribe.to.this.forum@} :
{jfullurl 'havefnubb~forum:unsubscribe',
                    array(  'id_forum'=>$post->id_forum,
                            'ftitle'=>$post->forum_name)}
