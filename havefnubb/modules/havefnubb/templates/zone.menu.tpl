{foreach $menuitems as $bloc}
    {if count($bloc->childItems)}
    <ul class="nav main">
{foreach $bloc->childItems as $item}
        <li{if $item->id == $selectedMenuItem} class="selected"{/if}{if $item->icon} style="background-image:url({$item->icon});"{/if}>
{if $item->type == 'url'}<a href="{$item->content|eschtml}">{$item->label|eschtml}</a>
{else}{$item->content}{/if}
        </li>
{/foreach}
    {hook 'hfbMenuBar'}
    </ul>
    {/if}
{/foreach}
