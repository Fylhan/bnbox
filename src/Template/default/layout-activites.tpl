{# default/layout-activites.tpl #}

{% extends 'layout-base.tpl' %}

{% block content %}
<article id="mainContent" class="activitesPage page" role="main">
	<figure>
		<img src="{{ImgPath}}activites.png" alt="Nos activités" />
	</figure>
	
	<header>
		<h1>Nos activités</h1>
	</header>
	
	<h2 id="culte">Culte</h2>
	<p>
		Chaque dimanche à 10h.<br />
		Avec un enseignement biblique pour les enfants de 10h45 à 11h45.
	</p>
	
	<h2 id="gdm">Rencontres autour de la Bible</h2>
	<p>
		Chaque mercredi à 20h30 <span>(sauf parfois en période de vacances scolaires)</span>.<br />
		Etudes bibliques par thèmes, ateliers découverte ou groupes de prière.
	</p>
	
	<h2 id="gdj">Groupe de jeunes</h2>
	<p>
		Chaque samedi à 19h <span>(sauf parfois en période de vacances scolaires)</span>.<br />
		Pour les lycéens et plus si affinités : soirées à thèmes, enseignements, repas, sorties, discussions, débats, concerts...		
	</p>
	
	<h2 id="flambeaux">Flambeaux et Claires Flammes (Scoutisme)</h2>
	<img src="{{ImgPath}}logo-flambeaux.png" alt="Logo Flambeaux" class="logoFlambeaux" width="100" height="100" />
	<p>
		Un dimanche toutes les trois semaines.<br />
		De 7 à 16 ans.
	</p>
	<ul class="urlFlambeaux">
		<li><a href="http://flambeaux.org">Site officiel des Flambeaux</a></li>
		<li><a href="http://flambclair.flambeaux.org">Flamb'clair&#160;: le journal des Flambeaux</a></li>
	</ul>
	
	<div class="clear"></div>
	<p>Et s'il vous reste encore des questions&#160;: <a href="contact.html">n'hésitez pas à nous contacter</a>&#160;!</p>
</article>
{% endblock content %}
