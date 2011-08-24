<h2>{@configuration@}</h2>
<div class="box-content">
{if count($errors)}
<ul class="jelix-msg">
  {foreach $errors as $err}<li class="jelix-msg-item-error">{$err|eschtml}</li>{/foreach}
</ul>
{/if}

<fieldset>
    <legend>{@title@}</legend>
    <table>
        <tr>
            <th><label for="title">{@label.title@}</label><span class="required">*</span></th>
            <td><input id="title" name="title" size="40" value="{$title|eschtml}"/></td>
        </tr>
        <tr>
            <th><label for="description">{@label.description@}</label></th>
            <td><input id="description" name="description" size="40" value="{$description|eschtml}"/></td>
        </tr>
        <tr>
            <th><label for="theme">{@label.theme@}</label><span class="required">*</span></th>
            <td><select id="theme" name="theme">
            {foreach $themes as $t}
            <option value="{$t|eschtml}" {if $theme == $t}selected="selected"{/if}>{$t|eschtml}</option>
            {/foreach}
          </select></td>
        </tr>
    </table>
</fieldset>
</div>