{% if actualites %}
<aside>
<article id="actualites" class="actualitesPage page">
	<figure>
		<img src="{{ImgPath}}contact.png" alt="Actualités" />
	</figure>
	
	<header>
		<h1><a href="{% if nbPage > 1 %}2/{% endif %}evenements.html">Dernières <strong>Nouvelles</strong></a></h1>
		{% if nbPage > 1 %}<p>&raquo; <a href="2/evenements.html">Voir les événements plus anciens</a></p>{% endif %}
	</header>
	{% for actualite in actualites %}
		<article class="actualite">
			<header>
				<h2><a href="evenement-{{actualite.id}}.html">{{actualite.titre}}</a></h2>
				<p>{{actualite.dateDebutString|capitalize}}</p>
			</header>
			<div class="texte">{{actualite.extrait|raw}}</div>
		</article>
	{% endfor %}
</article>
</aside>
{% endif %}