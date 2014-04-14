<nav id="menu" role="navigation">
	<ul>
		<li><a href="{{ SitePath }}"{{ action == 'Accueil' ? ' class="select"' : '' }} title="Accueil et événements">Accueil</a></li>
		<li><a href="contact.html" {{ action == 'Contact' ? ' class="select"' : '' }}>Nous contacter</a></li>
		<li><a href="qui-sommes-nous.html"{{ action == 'qui-sommes-nous' ? ' class="select"' : '' }}>Nous connaître</a></li>
		<li class="last"><a href="activites.html"{{ action == 'activites' ? ' class="select"' : '' }}>Nos activités</a></li>
	</ul>
</nav>