    <div id="user-subscription">
    <fieldset>
        <legend><span class="user-general user-image">{@havefnubb~member.your.subscriptions@}</span></legend>
        <div class="form_row">
        <table>
            <tr>
                <th>{@havefnubb~member.post.title@}</th>
                <th>{@havefnubb~member.forum.title@}</th>
                <th>{@havefnubb~forum.actions@}</th>
            </tr>
            {foreach $subs as $idx => $sub}
            <tr>
                <td><a href="{jurl 'havefnubb~posts:view', array('id_post'=>$sub['id_post'],'id_forum'=>$sub['id_forum'],'ftitle'=>$sub['ftitle'],'ptitle'=>$sub['ptitle'],'parent_id'=>$sub['parent_id']) }">{$sub['ptitle']|eschtml}</a></td>
                <td><a href="{jurl 'havefnubb~posts:lists', array('id_forum'=>$sub['id_forum'],'ftitle'=>$sub['ftitle'])}">{$sub['ftitle']|eschtml}</a></td>
                <td><a href="{jurl 'havefnubb~posts:unsubscribe', array('id_post'=>$sub['id_post'])}">{@havefnubb~post.unsubscribe@}</a></td></tr>
            {/foreach}
        </table>
        </div>
    </fieldset>
    </div>
