{meta_html csstheme 'css/iamhere.css'}
<h2>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > {@hfnucal~main.Calendar@}</h2>
<h3>{@iamhere~main.activities.summarize@}</h3>
<div class="box">
    <div class="box-content">
        <table>
            <tr>
                <th scope="col">{@iamhere~main.who@}</th>
                <th scope="col">{@iamhere~main.what@}</th>
                <th scope="col">{@iamhere~main.where@}</th>
            </tr>
            {foreach $recs as $rec}
                <tr>
                    <td>{$rec->name|eschtml}</td>
                    <td>{$rec->label}</td>
                    <td>{$rec->page_name}</td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>
