{# erreur/404 #}

{% extends 'layout-base.tpl' %}

{% block content %}
<article id="page404" class="page">
	<header>
		<h1>Oups ! Page introuvable</h1>
	</header>
	<p>
		{% if '404' == action %}
			Désolé, cette page est introuvable !
		{% else %}
			Désolé, la page &quot;{% if null != page %}{{page}}{% else %}{{module}}/{{ action }}{% endif %}&quot; est introuvable !
		{% endif %}
		<br />
		Vous trouverez peut-être votre bonheur à l'aide du <a href="sitemap.html">plan du site</a>.
	</p> 
	{% if Debug and error is defined %}
		{% include 'error/error-debug.tpl' %}
	{% endif %}
</article>
{% endblock %}