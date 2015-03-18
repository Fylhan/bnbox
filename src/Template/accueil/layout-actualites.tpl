{# accueil/layout-accueil.tpl #}

{% extends 'layout-base.tpl' %}

{% block content %}
<div id="mainContent" role="main">
<article id="actualites" class="actualitesPage page">
	<figure>
		<img src="{{ImgPath}}contact.png" alt="Actualités" />
	</figure>
	
	<header>
		<h1>Dernières Nouvelles</h1>
	</header>
	{% include 'bloc/paginationHaut.tpl' %}
	{% for actualite in actualites %}
		<article class="actualite">
			<header>
				<h2><a href="evenement-{{actualite.id}}.html">{{actualite.titre}}</a></h2>
				<p>{{actualite.dateDebutString|capitalize}}</p>
			</header>
			<div class="texte">{{actualite.extrait|raw}}</div>
		</article>
	{% else %}
	<p>Aucun événement en ce moment.</p>
	{% endfor %}
	{% include 'bloc/paginationBas.tpl' %}
</article>
</div>
{% endblock content %}
