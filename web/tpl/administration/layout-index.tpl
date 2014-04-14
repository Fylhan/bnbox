{% extends 'administration/layout-base.tpl' %}

{% block SubTitle %}
{% endblock %}

{% block Question %}
<p>
	Pour l'instant, personne à Saint-Maur n'est responsable du site, alors en attendant que cela se mette en place :
	pour toutes questions, rapport de bugs, demandes de modification d'un texte, ou ajout d'une fonctionnalité,
	n'hésitez pas à contacter Olivier Maridat (<img src="{{UrlEmailAdmin}}" alt="email" class="email" />).
</p>
{% endblock %}

{% block AdministrationBody %}
{{ Message|raw }}
{{ FlashMessage|raw }}

<h2>Gérer les news</h2>
<p>A partir de cette interface, vous pouvez ajouter / modifier / supprimer les news s'affichant sur la <a href="{{ SitePath }}">page d'accueil</a>.</p>
<p><a href="{{ UrlCourant }}?update=0">Ajouter une news</a></p>

{% include 'bloc/paginationHaut.tpl' %}

{% if Actualites|length > 0 %}
	<table class="liste">
	<thead>
		<tr>
			<th>Titre</th>
			<th>Date</th>
			<th>Etat</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
	{% for actualite in Actualites %}
		<tr>
			<td>
				{% if actualite.titre != '' %}{{actualite.titre|raw}}
				{% else %}{{actualite.contenu|raw}}
				{% endif %}
			</td>
			<td>Du {{actualite.dateDebut|date('d/m/Y')}}{% if null != actualite.dateFin and 0 != actualite.dateFin %} au {{actualite.dateFin|date('d/m/Y')}}{% endif %}</td>
			<td>
				{% if actualite.etat and "now"|date('Y-m-d') > actualite.dateFin|date('Y-m-d') %}terminé
				{% elseif actualite.etat and "now"|date('Y-m-d') < actualite.dateDebut|date('Y-m-d') %}planifié
				{% elseif 2 == actualite.etat %}actif (invisible)
				{% elseif actualite.etat %}actif
				{% else %}désactivé
				{% endif %}
			</td>
			<td>
				<a href="{{ UrlCourant }}?update={{actualite.id}}" class="update" title="Modifier">Modifier</a>
				| <a href="{{ UrlCourant }}?delete={{actualite.id}}" class="delete" title="Supprimer">Supprimer</a>
			</td>
		</tr>
	{% endfor %}
	</tbody>
	</table>
{% else %}
	<p>Aucune "news" pour le moment.</p>
{% endif %}

{% include 'bloc/paginationBas.tpl' %}

<h2>Paramètres</h2>
<p>Quelques paramêtre du sites sont configurables ci-dessous. Faites bien attention ;-)</p>
<form action="{{ UrlCourant }}" method="post" id="formData" class="form">
	<fieldset>
		<legend>Interface utilisateur</legend>
		<div>
			<label for="nbParPage">Nombre de news par page</label>
				<input type="number" min="1" name="nbParPage" id="nbParPage" value="{{NbParPage}}" placeholder="2" size="3" class="mini" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
			<div class="clear"></div>
			<p><a href="{{ UrlCourant }}?cache=purge">Effacer les fichiers de cache</a> <small>(à faire suite à l'installation d'une nouvelle version du site Web)</small></p>
		</div>
	</fieldset>
	<fieldset>
		<legend>Interface administrateur</legend>
		<div>
			<label for="nbParPageAdmin">Nombre de news par page</label>
				<input type="number" min="1" name="nbParPageAdmin" id="nbParPageAdmin" value="{{NbParPageAdmin}}" placeholder="10" size="3" class="mini" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
		</div>
		<div>
			<label for="emailAdmin">Email de l'administrateur</label>
				<input type="email" name="emailAdmin" id="emailAdmin" value="{{EmailAdmin}}" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
		</div>
		<div>
			<label for="displayHelp">Afficher les conseils</label>
				<input type="checkbox" name="displayHelp" id="displayHelp" value="1"{% if DisplayHelp %} checked="checked"{% endif%} class="mini" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
		</div>
	</fieldset>
	<div class="formEnd">
		<input type="submit" name="sendData" id="sendData" value="Modifier" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" class="mini" />
	</div>
</form>

<!-- <h2><a href="administration-listes-diffusion.html">Gérer les listes de discussion</a></h2>  -->


{% endblock AdministrationBody %}


{% block js %}
<script type="text/javascript" src="{{ThemePath}}/js/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
	$('.delete').click(function(){
		return confirm("Souhaitez-vous vraiment supprimer cette news ?");
	});
});
</script>
{{ parent() }}
{% endblock js %}
