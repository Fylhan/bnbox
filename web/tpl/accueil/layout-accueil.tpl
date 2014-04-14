{# accueil/layout-accueil.tpl #}

{% extends 'layout-base.tpl' %}

{% block content %}
<div id="mainContent" role="main">
	{% include 'accueil/contact.tpl' %}
	{% include 'accueil/activites.tpl' %}
	{% include 'accueil/qui-sommes-nous.tpl' %}
</div>
{% include 'accueil/actualites.tpl' %}
{% endblock content %}
