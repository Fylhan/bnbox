{% extends 'administration/layout-base.tpl' %}

{% block css %}
{{ parent() }}
<link rel="stylesheet" href="{{ThemePath}}/css/redactor.min.css" />
<link type="text/css" rel="stylesheet" href="{{ThemePath}}/css/ui-lightness/jquery-ui.min.css" />
{% endblock css %}

{% block SubTitle %}
 - {% if 0 == Actualite.id %}Ajouter{% else %}Modifier{% endif %} une news
{% endblock %}

{% block AdministrationBody %}
{{ Message|raw }}
{{ FlashMessage|raw }}
<p><a href="administration.html">Administration</a> > {% if 0 == Actualite.id %}Ajouter{% else %}Modifier{% endif %} une news</p>
<aside class="conseil">
<p class="conseilHandler">Quelques conseils...</p>
	<ul{% if 0 == DisplayHelp %} style="display: none"{% endif %}>
		<li>Plus une news est courte, mieux c'est ! Aux alentours de 140 caractères c'est pas mal.</li>
		<li>Il est possible de préciser une date de fin à une news, ce qui devrait permettre d'éviter que le site ne ressemble à un site fantôme en cas d'inactivitée prolongée.</li>
	</ul>
</aside>

<form action="{{ UrlCourant }}" method="post" id="formNews" class="form">
	<div>
		<label for="titre">Titre</label>
			<input type="text" name="titre" id="titre" value="{{Actualite.titre}}" placeholder="Titre" size="25" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
	</div>
	<div>
		<label for="contenu" class="requis">Contenu</label>
			<textarea name="contenu" id="contenu" rows="25" class="editor" placeholder="Si vous avez quelque chose à nous dire, c'est ici !" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}">{{Actualite.contenu}}</textarea>
	</div>
	<div>
		<label for="dateDebut">Date de début de l'affichage</label>
			<input type="date" name="dateDebut" id="dateDebut" value="{{Actualite.dateDebut|date('d/m/Y')}}" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
	
		<label for="dateFin">Date de fin de l'affichage</label>
			<input type="date" name="dateFin" id="dateFin" value="{{Actualite.getDateFin()|date('d/m/Y')}}" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
	</div>
	<div>
		<label for="etat">Etat</label>
			<select name="etat" id="etat" class="mini" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}">
				<option value="1"{{ 1 == Actualite.etat ? ' selected="selected"' : '' }}>Activé</option>
				<!--<option value="2"{{ 2 == Actualite.etat ? ' selected="selected"' : '' }}>Activé (invisible)</option>-->
				<option value="0"{{ 0 == Actualite.etat ? ' selected="selected"' : '' }}>Désactivé</option>
			</select>
	</div>
	<div class="formEnd">
		<input type="submit" name="sendNews" id="sendNews" value="{% if 0 == Actualite.id %}Ajouter{% else %}Modifier{% endif %}" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" class="mini" />
		<input type="hidden" name="id" id="id" value="{{Actualite.id}}" />
		<a href="administration.html">Annuler</a>
	</div>
</form>

{% endblock %}

{% block js %}
{{ parent() }}
<script type="text/javascript" src="{{ThemePath}}/js/jquery.min.js"></script>
<script type="text/javascript" src="{{ThemePath}}/js/jquery-ui.js"></script>
<script type="text/javascript" src="{{ThemePath}}/js/jquery.ui.datepicker-fr.js"></script>
<script type="text/javascript" src="{{ThemePath}}/js/redactor.js"></script>
<script type="text/javascript" src="{{ThemePath}}/js/redactor-fr.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	// -- Editor
	var buttons = ['html', '|', 'formatting', '|', 'bold', 'italic', 'deleted', '|',
	               		'fontcolor', 'backcolor', '|',
	                   'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
	                   'alignleft', 'aligncenter', 'alignright', 'justify', '|',
	                   'link', 'image', 'file', 'video',  '|',
	                   'more']
	$('.editor').redactor({
		lang: 'fr',
		imageUpload: 'upload-picture.html',
		fileUpload: 'upload-file.html',
		imageGetJson: 'galery.json',
		minHeight: '200',
		buttons: buttons,
		buttonsCustom: {
			more: {
				title: 'More',
				callback: buttonMore
			}
		}  
	});

	// -- Date picket
	$.datepicker.setDefaults( $.datepicker.regional['fr'] );
	$('#dateDebut').datepicker({'dateFormat': 'dd/mm/yy'});
	$('#dateFin').datepicker({'dateFormat': 'dd/mm/yy'});

	// -- Conseil
	$('.conseilHandler').click(function() {
		$(this).parent().find('ul').toggle('slow');
	});
});

function buttonMore(obj, event, key)
{
	obj.execCommand('insertHtml', '[Lire la suite]');
}
</script>
{% endblock js %}


