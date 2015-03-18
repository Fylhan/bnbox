{# administration/layout-base.tpl #}

{% extends 'layout-base.tpl' %}

{% block content %}
<article id="administrationPage" class="page">
	<header>
		<h1><a href="administration.html">Administration</a>{% block SubTitle %}{% endblock %}</h1>
	</header>
	{% block Question %}
	{% endblock %}
	
	{% block AdministrationBody %}
	{% endblock %}
</article>
{% endblock content %}
