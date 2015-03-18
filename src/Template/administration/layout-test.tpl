{% extends 'administration/layout-base.tpl' %}

{% block AdministrationBody %}
<h2>Test</h2>
{% if Debug %}
	{{ Message|raw }}
	{{ FlashMessage|raw }}
	{% if HashedWord %}<p>{{ HashedWord }}</p>{% endif %}
	<form action="upload-picture.html" enctype="multipart/form-data" method="POST">
		<div>
			<input type="file" name="file" id="file" />
		</div>
		<div class="formEnd">
			<input type="submit" name="sendFile" id="sendFile" value="Envoyer" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" class="mini" />
		</div>
	</form>
{% endif %}
{% endblock AdministrationBody %}