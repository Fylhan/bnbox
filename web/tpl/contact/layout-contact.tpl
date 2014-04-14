{# contact/layout-contact.tpl #}

{% extends 'layout-base.tpl' %}

{% block content %}
<article id="mainContent" role="main" class="contactPage page" itemscope itemtype="http://schema.org/Organization">
	<figure>
		<img src="{{ImgPath}}accueil.png" alt="Nous contacter" />
	</figure>
	<header>
		<h1>Nous contacter</h1>
	</header>
	<p>
		Vous avez une question&#160;? Vous désirez en savoir plus&#160;? 
		N'hésitez pas à nous rendre visite ou à nous contacter en utilisant le formulaire ci-dessous,
		ou bien l'une des adresses emails suivantes si vous le préférez.
	</p>
	
	
	<h2>Nous trouver</h2>	
	<figure class="illustration map">
		<a href="http://www.openstreetmap.org/?lat=48.78998&lon=2.49355&zoom=17&layers=M" title="Plan OpenStreetMap : 137, rue Edgar Quinet à Saint-Maur"><img src="{{IllustrationPath}}plan-cep.png" alt="Plan OpenStreetMap du 137, rue Edgar Quinet à Saint-Maur" class="plan" /></a>
		<figcaption>
			<a href="http://www.openstreetmap.org/?lat=48.78998&lon=2.49355&zoom=17&layers=M">137, rue Edgar Quinet<br />à Saint-Maur</a>
		</figcaption>
	</figure>
	<p class="adresse-desc">Notre adresse est la suivante&#160;:</p>
	<p class="adresse">
		<meta itemprop="url" content="http://cepsaintmaur.fr" />
		<span itemprop="name">Le <abbr title="Communauté Evangélique Protestante">CEP</abbr> Saint-Maur</span><br />
		<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
			<span itemprop="streetAddress">137, rue Edgar Quinet</span><br />
			<span itemprop="postalCode">94100</span> <span itemprop="addressLocality">Saint-Maur-des-Fossés<br />France</span><br />
		</span>
	</p>
	<p>
		Pour visualiser l'emplacement de notre église sur une carte,
		vous pouvez consulter un plan détaillé interactif sur <a href="http://www.openstreetmap.org/?lat=48.78998&lon=2.49355&zoom=17&layers=M" title="OpenStreetMap : la carte coopérative libre">OpenStreetMap</a>
		(carte coopérative libre).
	</p>
	<a id="mapHandler" title="Charger le plan OpenStreetMap centré sur le CEP Saint-Maur">Afficher le plan interactif</a>
    <figure class="illustration" id="mapWrapper">
    	<div id="map"></div>
    </figure>  
	
	
	<h2 id="email">Nous contacter par email</h2>
	<p>N'hésitez pas à nous contacter à l'aide des adresses emails suivantes&#160;:</p>
	<ul>
		<li itemprop="contactPoints" itemscope itemtype="http://schema.org/ContactPoint"><span itemprop="contactType">Un responsable de l'église</span>&#160;: <img src="{{UrlImgEmailContact}}" alt="Email de l'église" class="email" itemprop="email" /></li>
		<li itemprop="contactPoints" itemscope itemtype="http://schema.org/ContactPoint"><span itemprop="contactType">Les Flambeaux</span>&#160;: <img src="{{UrlImgEmailFlambeaux}}" alt="Email des Flambeaux" class="email" itemprop="email" /></li>
		<li itemprop="contactPoints" itemscope itemtype="http://schema.org/ContactPoint"><span itemprop="contactType">Le groupe de jeunes</span>&#160;: <img src="{{UrlImgEmailGDJ}}" alt="Email du GDJ" class="email" itemprop="email" /></li>
	</ul>
		
	<p>Ou à l'aide du formulaire ci-dessous&#160;:</p>
	{% include 'contact/form/contact.tpl' %}
</article>
{% endblock content %}

{% block js %}
<script src="{{ThemePath}}/js/jquery.min.js"></script>
<script type="text/javascript">{% include 'contact.min.js' %}</script>
{{parent()}}
{% endblock js %}

