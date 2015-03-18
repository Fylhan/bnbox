{# erreur/500 #}

{% extends 'layout-base.tpl' %}

{% block content %}
<article id="page500" class="page">
	<header>
		<h1>Erreur critique !</h1>
	</header>
	<p>
		Houlà, petit souci avec la page &quot;{{module}}/{{ action }}&quot; ! Nous nous excusons de ce contre-temps.
		<br />
		Vous trouverez peut-être votre bonheur à l'aide du <a href="sitemap.html">plan du site</a>.
	</p> 
	{% if Debug and error is defined %}
		{% include 'error/error-debug.tpl' %}
	{% endif %}
</article>
{%endblock %}