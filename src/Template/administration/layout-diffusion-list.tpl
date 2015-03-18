{% extends 'administration/layout-base.tpl' %}

{% block AdministrationBody %}
<aside class="conseil">
<h2 class="conseilHandler">Gérer les listes de diffusion...</h2>
<div{% if 0 == DisplayHelp %} style="display: none"{% endif %}><p>
Cette interface permet de gérer les listes de diffusion. Une liste de diffusion correspond en fait à une adresse email qui renvoie vers de multiples adresses emails.
Ainsi, en envoyant un seul email, on peut envoyer un message à toutes les personnes inscrites à cet email, i.e. à cette liste de diffusion.<br />
Via cette interface, on peut donc inscrire ou désinscrire une adresse email d'une liste de diffusion.
Bien entendu, pour une inscription, les utilisateurs recevront d'abord un email leur permettant d'accepter ou de refuser de suivre cette liste de diffusion.
<br />
Quelques limitations :</p>
<ul>
	<li>L'hébergement actuel ne permet que de créer 5 listes de diffusion, sans compter que d'autres sites utilisent cette hébergement, et pourrait nécessiter l'utilisation de listes de diffusion.</li>
	<li>L'envoi d'une pièce-jointe via une liste de diffusion prendra plus de temps que pour une adresse email classique.</li>
	<li>On ne peut pas savoir facilement qui est inscrit à une liste de diffusion. Si besoin, demander à Olivier.</li>
</ul></div>
</aside>

{{ Message|raw }}
{{ FlashMessage|raw }}

<form action="http://kundenserver.de/cgi-bin/mailinglist.cgi" method="POST">
	<div>
		<label for="listSelection">Liste de diffusion</label>
			<select id="listSelection"tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}">
			{% for liste in ListesDiffusion %}
				<option value="{{liste}}">{{liste}}</option>
			{% endfor %}
			</select>
			
	</div>
	<div>
		<label for="email">Email de l'utilisateur</label>
			<input type="email" name="mailaccount_r" id="listEmail" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
		<label for="email2">Confirmation de l'email</label>
			<input type="email" name="mailaccount2_r" id="email2" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
	</div>
	<div>
		<label for="listSubscribe">Action</label>
			<input type="radio" name="subscribe_r" id="listSubscribe" value="subscribe" checked="checked" class="mini" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
			<label for="listSubscribe" class="mini">inscrire</label>
			<input type="radio" name="subscribe_r" id="listUnsubscribe" value="unsubscribe" class="mini" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
			<label for="listUnsubscribe" class="mini">désinscrire</label>
	</div>
	<div class="formEnd">
		<input type="submit" name="sendDiffusionList" id="sendDiffusionList" value="Envoyer" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" class="mini" />
		<input type="hidden" name="FBMLNAME" id="listSelected" value="{{ListesDiffusion[0]}}">
		<input type="hidden" name="FBLANG" id="listLang" value="fr">
		<input type="hidden" name="FBURLERROR_L" id="listError" value="http://kundenserver.de/mailinglist/error.fr.html">
		<input type="hidden" name="FBURLSUBSCRIBE_L" id="listSubscribeDesc" value="http://kundenserver.de/mailinglist/subscribe.fr.html">
		<input type="hidden" name="FBURLUNSUBSCRIBE_L" id="listUnsubscribeDesc" value="http://kundenserver.de/mailinglist/unsubscribe.fr.html">
		<input type="hidden" name="FBURLINVALID_L" id="listInvalid" value="http://kundenserver.de/mailinglist/invalid.fr.html">
	</div>
</form>


{% endblock AdministrationBody %}

{% block js %}
<script type="text/javascript" src="{{ThemePath}}/js/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
	// -- Conseil
	$('.conseilHandler').click(function() {
		$(this).parent().find('div').toggle('slow');
	});

	// -- Diffusion list selected
	$('#listSelection').change(function() {
		$('#listSelected').val($('#listSelection>option:selected').text());
	});
});
</script>
{{ parent() }}
{% endblock js %}