{{ Message|raw }}
{{ FlashMessage|raw }}
<form action="{{ urlCourant }}#email" method="post" id="formContact" class="form">
	<div>
		<label for="nom">Votre nom</label>
			<input type="text" name="nom" id="nom" value="{{ email.nom }}" placeholder="Votre nom" size="18" maxlength="100" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
	</div>
	<div>
		<label for="email" class="requis">Votre email</label>
			<input type="email" name="email" id="email" value="{{ email.email }}" size="18" maxlength="250" placeholder="Votre adresse email" required="required" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
	</div>
	<div>
		<label for="destinataire" class="requis">Qui voulez-vous contacter ?</label>
			<select name="destinataire" id="destinataire" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}">
				<option value="contact"{{EmailContact == email.destinataires[0] ? ' selected="selected"' : ''}}>Un responsable de l'église</option>
				<option value="flambeaux"{{EmailFlambeaux == email.destinataires[0] ? ' selected="selected"' : ''}}>Les Flambeaux</option>
				<option value="gdj"{{EmailGDJ == email.destinataires[0] ? ' selected="selected"' : ''}}>Le groupe de jeunes</option>
			</select>
	</div>
	<div>
		<label for="objet">Objet</label>
			<input type="text" name="objet" id="objet" value="{{ email.objet }}"  placeholder="Objet de votre email" size="25" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" />
	</div>
	<div>
		<label for="message" class="requis">Votre message</label>
			<textarea name="message" id="message" rows="10" placeholder="Si vous avez quelque chose à nous dire, c'est ici !" required="required" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}">{{ email.message }}</textarea>
	</div>
	<div>
		<label for="antibot" class="requis">Pour éviter le <a href="http://fr.wikipedia.org/wiki/Spam" title="Explication Wikipédia : Spam">spam</a></label>
			<div class="antibot">
				<label for="antibot" class="mini">10+2 =</label>
					<input type="number" name="antibot" id="antibot" value="{{email.antibot}}" size="3" required="required" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" class="mini" />
					<label for="antibot" class="mini">?</label>
			</div>
	</div>
	<div class="formEnd">
		<input type="submit" name="sendEmail" id="sendEmail" value="Envoyer" tabindex="{% set tabindex = tabindex+1 %}{{ tabindex }}" class="mini" />
	</div>
</form>
<p>Bien entendu, nous nous engageons à n'utiliser votre adresse email que pour vous répondre si besoin.</p>
