<h2>Création d'un compte</h2>

<p>Pour pouvoir profiter au mieux des services du site, inscrivez-vous
en remplissant le formulaire suivant.</p>

{form $form,'jcommunity~registration:save', array()}
<fieldset>
    <legend>Informations</legend>
    <p>{ctrl_label 'reg_login'} : {ctrl_control 'reg_login'}</p>
    <p>{ctrl_label 'reg_email'} : {ctrl_control 'reg_email'}</p>
</fieldset>
<p>Un e-mail vous sera envoyé pour que vous puissiez confirmer votre inscription
et ensuite pouvoir vous identifier sur le site.</p>
<p>{formsubmit}</p>
{/form}