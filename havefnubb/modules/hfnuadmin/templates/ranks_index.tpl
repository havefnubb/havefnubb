<h1>{@havefnubb~rank.the.ranks@}</h1>
<ul>
<li><a href="{jurl 'hfnuadmin~ranks:create'}" title="{@hfnuadmin~rank.create.a.rank@}">{@hfnuadmin~rank.create.a.rank@}</a></li>
</ul>
<table width="100%">
    <tr>
        <th class="forumlistcol">{@havefnubb~rank.rank_name@}</th>
        <th class="forumlistcol">{@havefnubb~rank.rank_limit@}</th>
        <th class="forumlistcol">{@hfnuadmin~rank.actions@}</th>
    </tr>
    {foreach $ranks as $rank}
    <tr>
        <td class="line coltitle linkincell"><a href="{jurl 'hfnuadmin~ranks:edit',array('id_rank'=>$rank->id_rank)}" title="{@hfnuadmin~rank.edit.this.rank@}">{$rank->rank_name|eschtml}</a></td>
        <td class="line coldate linkincell">{$rank->rank_limit}</td>
        <td class="line coldate linkincell"><a href="{jurl 'hfnuadmin~ranks:delete',array('id_rank'=>$rank->id_rank)}" title="{@hfnuadmin~rank.delete.this.rank@}" onclick="return confirm('{jLocale 'hfnuadmin~rank.confirm.deletion',array($rank->rank_name)}')">{@hfnuadmin~rank.delete.this.rank@}</a> - 
        <a href="{jurl 'hfnuadmin~ranks:edit',array('id_rank'=>$rank->id_rank)}" title="{@hfnuadmin~rank.edit.this.rank@}">{@hfnuadmin~rank.edit.this.rank@}</a></td>
    </tr>
    {/foreach}
</table>