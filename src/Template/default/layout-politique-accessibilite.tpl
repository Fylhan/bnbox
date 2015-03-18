{# default/layout-politique-accessibilite.tpl #}

{% extends 'layout-base.tpl' %}

{% block content %}
<article id="mainContent" class="politiqueAccessibilitePage page actualitesPage" role="main">
	<header>
		<h1>Politique d'accessibilité</h1>
	</header>
	
<div class="texte">
<h3>Taille d'affichage</h3>
<p>Les textes de contenu ont une taille de police relative, c'est à dire agrandissable selon les besoins.</p>

<p>Pour modifier la taille d'affichage du texte :</p>

<ul>
	<li>Avec divers navigateurs : <kbd>Ctrl</kbd> + <kbd>molette de la souris</kbd></li>
	<li>Internet Explorer : allez dans <em>Affichage</em> &gt;&gt; <em>Taille du texte</em> et choisissez.</li>
	<li>Mozilla Firefox, Safari, Chrome : faites <kbd>Ctrl</kbd> + <kbd>+</kbd> pour agrandir et <kbd>Ctrl</kbd> + <kbd>-</kbd> pour diminuer.</li>
	<li>Opera : appuyez sur les touches <kbd>+</kbd> ou <kbd>-</kbd> du pavé numérique. Ou bien allez dans <em>Affichage</em> &gt;&gt; <em>Zoom</em> et choisir.</li>
</ul>

<h3>Aides à la navigation</h3>
<h4>Liens d'évitement</h4>

<p>Menu placé dès le début du document, ces liens permettent dès le chargement de la page d'accéder directement à la partie recherchée : au contenu, au menu général, au moteur de recherche, etc. sans avoir à parcourir des informations non souhaitées.</p>

<p>Ces liens facilitent l'accès au site pour les handicapés et notamment les non voyants&nbsp;: ils leurs permettent de se placer directement à l'endroit souhaité.</p>
<h4>Navigation par tabulation</h4>

<p>Appuyez sur Tab et répétez jusqu'à sélectionner le lien désiré, validez par <kbd>Entrée</kbd></p>

<h4 id="accesskeys">Raccourcis clavier (accesskeys)</h4>

<ul>
	<li><kbd>s</kbd> = Aller directement au contenu</li>
	<li><kbd>0</kbd> ou <kbd>6</kbd> = Politique d'accessibilité</li>
	<li><kbd>1</kbd> = Aller à l'Accueil du site</li>
	<li><kbd>2</kbd> = Page d'actualités<br>
</li>
	<li><kbd>3</kbd> ou <kbd>5</kbd> = Aller au Plan du site</li>
	<li><kbd>7</kbd> = Page de contact<br>
</li>
</ul>
<br />

<p>Mode d'emploi :</p>
<ul>
	<li><b>Mozilla Firefox, Safari, Netscape</b> (Windows) : Appuyez simultanément sur la touche <kbd>Shift</kbd> et <kbd>Alt</kbd>, ainsi que sur une des touches <kbd>accesskey</kbd> du clavier (pas le pavé numérique). Vous vous rendrez directement à l'endroit voulu.</li>
	<li><b>Internet Explorer</b> (Windows) : Appuyez simultanément sur la touche <kbd>Alt</kbd> et sur une des touches <kbd>accesskey</kbd> du clavier (pas le pavé numérique) et ensuite appuyez sur la touche <kbd>Entrée</kbd> pour vous rendre à l'endroit voulu.</li>
	<li>Opera (Windows, Mac et Linux) : <kbd>Esc</kbd> + <kbd>Shift</kbd> et <kbd>accesskey</kbd></li>
	<li>Safari, Internet Explorer (Mac OS X) : <kbd>Ctrl</kbd> et <kbd>accesskey</kbd></li>
	<li>Mozilla, Netscape (Mac OS X) : <kbd>Ctrl</kbd> et <kbd>accesskey</kbd></li>
	<li>Mozilla Firefox, Galeon (Linux) : <kbd>Alt</kbd> et <kbd>accesskey</kbd></li>
	<li>Konqueror (Linux) : <kbd>Ctrl</kbd>, puis <kbd>accesskey</kbd> (successivement)</li>
	<li>Les navigateurs plus anciens (Netscape 4, Camino, Galeon, Konqueror avant la version 3.3.0, Omniweb, Safari avant la version 1.2, Opera Windows/Linux avant la version 7) ne supportent pas les accesskeys.</li>
</ul>
</div>
</article>
{% endblock content %}
