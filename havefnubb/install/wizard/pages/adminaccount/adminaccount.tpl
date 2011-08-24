<h2>{@title@}</h2>
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
            <th><label for="login">{@label.login@}</label><span class="required">*</span></th>
            <td><input id="login" name="login" size="40" value="{$login|eschtml}"/></td>
        </tr>
        <tr>
            <th><label for="password">{@label.password@}</label></th>
            <td><input id="password" type="password" name="password" value="{$password|eschtml}"/></td>
        </tr>
        <tr>
            <th><label for="password_confirm">{@label.password.confirm@}</label></th>
            <td><input id="password_confirm" type="password" name="password_confirm" value="{$password_confirm|eschtml}"/></td>
        </tr>
        <tr>
            <th><label for="email">{@label.email@}</label><span class="required">*</span></th>
            <td><input id="email" name="email" value="{$email|eschtml}"/></td>
        </tr>
    </table>
</fieldset>
</div>