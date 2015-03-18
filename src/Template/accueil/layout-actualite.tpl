{# accueil/layout-accueil.tpl #}

{% extends 'layout-base.tpl' %}

{% block content %}
<article id="actualites" class="actualitesPage page">
	<header>
		<h1><a href="evenements.html">Dernières Nouvelles</a></h1>
	</header>
	
	<article class="actualite">
	<header>
		<h2><a href="evenement-{{actualite.id}}.html">{{actualite.titre}}</a></h2>
		<p>{{actualite.dateDebutString|capitalize}}</p>
	</header>
	<div class="texte">{{actualite.contenu|raw}}</div>
</article>
</article>
{% endblock content %}
