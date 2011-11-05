    <div id="user-profile-subscription">
    <fieldset>
        <legend><span class="user-subscription user-image">{@havefnubb~member.your.subscriptions@}</span></legend>
        <table>
            <thead>
            <tr>
                <th>{@havefnubb~member.post.title@}</th>
                <th>{@havefnubb~member.forum.title@}</th>
                <th>{@havefnubb~forum.actions@}</th>
            </tr>
            </thead>
            <tbody>
            {foreach $subs as $idx => $sub}
            <tr>
                <td><a href="{jurl 'havefnubb~posts:view', array('id_forum'=>$sub['id_forum'],'ftitle'=>$sub['ftitle'],'id_post'=>$sub['id_post'],'ptitle'=>$sub['ptitle'],'thread_id'=>$sub['thread_id']) }">{$sub['ptitle']|eschtml}</a></td>
                <td><a href="{jurl 'havefnubb~posts:lists', array('id_forum'=>$sub['id_forum'],'ftitle'=>$sub['ftitle'])}">{$sub['ftitle']|eschtml}</a></td>
                <td><a href="{jurl 'havefnubb~posts:unsub', array('thread_id'=>$sub['thread_id'])}">{@havefnubb~post.unsubscribe@}</a></td></tr>
            {/foreach}
            </tbody>
        </table>
    </fieldset>
    </div>
