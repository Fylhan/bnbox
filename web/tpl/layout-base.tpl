{# layout-base.tpl #}
<!DOCTYPE html>
<html lang="{{ Locale }}">
<head>
	<meta charset="{{ Encodage }}" />
	<base href="{{SitePath}}" />
	<title>{{ metaTitle }}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta name="author" content="{{ metaAuteur }}" />
	<meta name="description" content="{{ metaDesc }}" />
	<meta name="keywords" content="{{ metaKw }}" />
	{% block preJs -%}
	<!--[if lte IE 9]> 
		<script text="text/javascript" src="{{ThemePath}}js/html5.min.js"></script>
		<link rel="stylesheet" type="text/css" href="{{ThemePath}}css/ie.min.css" />
	<![endif]-->
	{% endblock %}
	{% if StyleEnabled %}
	{%- block css %}
	<link rel="stylesheet" type="text/css" title="{{SiteNom}}" href="{{ThemePath}}css/style.min.css" />
	{% endblock %}
	{% endif %}
	{%- block headProperties %}
	<link rel="start" href="./" title="Accueil" />
	<link rel="help" href="politique-accessibilite.html" title="Accessibilité" />
	<link rel="accesskeys" href="politique-accessibilite.html#accesskeys" title="Raccourcis et Accesskeys" />
	<link rel="shortcut icon" href="{{ ThemePath }}img/favicon.ico" type="image/ico" />
	{% endblock %}
	<link rel="alternate" type="application/rss+xml" href="{{FeedPath}}" title="Actualités (RSS)" />
	<link rel="alternate" type="application/atom+xml" href="{{FeedPath}}?feed=atom" title="Actualités (Atom)" />
</head>

<body>

<header id="header">
{% block header %}
	<h1><a href="{{SitePath}}">{{SiteNom}}</a></h1>
	<p>{{SiteDesc}}</p>
{% endblock header %}
</header>

<div id="content">
{% block nav %}
{% if 'Accueil' != action %}
	{% include 'bloc/nav.tpl' %}
{% endif %}
{% endblock %}
{% block content %}
{% endblock content %}
</div>

<footer>
{% block footer %}
	<nav role="navigation">
		<ul>
			<li><a href="{{ SitePath }}" accesskey="1">Accueil</a></li>
			<li><a href="contact.html" accesskey="7">Contact</a></li>
			<li class="last"><a href="sitemap.html" accesskey="3">Plan du site</a></li>
		</ul>
	</nav>
	<p>
		© 2014 {{SiteNom}}<br />
		Tous droits réservés
		<a href="administration.html" class="hide">&#160;&#160;&#160;</a>
		<a href="{{CurrentPath}}#mainContent" accesskey="s" class="hide"></a>
		<a href="sitemap.html"  accesskey="5" class="hide"></a>
		<a href="politique-accessibilite.html#accesskeys"  accesskey="0" class="hide"></a>
		<a href="politique-accessibilite.html"  accesskey="6" class="hide"></a>
		{% if Duration is defined %}{{Duration}} seconds{%endif%}
	</p>
{% endblock %}
</footer>

{% block js %}
<!-- <script type="text/javascript">var pkUrl="http://statistiques.la-bnbox.fr/";document.write(unescape("%3Cscript src='"+pkUrl+"piwik.js' type='text/javascript'%3E%3C/script%3E"));try{var piwikTracker=Piwik.getTracker(pkUrl+"piwik.php",3);piwikTracker.trackPageView();piwikTracker.enableLinkTracking();}catch(err){}</script> -->
{% endblock %}
</body>
</html>
