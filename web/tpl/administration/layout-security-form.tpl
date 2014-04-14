{% extends 'administration/layout-base.tpl' %}

{% block SubTitle %}
{% endblock %}

{% block AdministrationBody %}
<p>Accès réservé.</p>
{{ Message|raw }}
{{ FlashMessage|raw }}
<form action="{{ UrlCourant }}" method="post" id="formSecurity" class="form">
	<div>
		<label for="securityKey">Mot de passe</label>
			<input type="password" name="securityKey" id="securityKey" size="25" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
	</div>
	<div>
		<label for="keepConnected">Rester connecté</label>
		<input type="checkbox" name="keepConnected" id="keepConnected" value="1" class="mini" checked="checked" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
	</div>
	<div class="formEnd">
		<input type="submit" name="sendSecurityKey" id="sendSecurityKey" value="Ok" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" class="mini" />
	</div>
</form>
{% endblock %}
