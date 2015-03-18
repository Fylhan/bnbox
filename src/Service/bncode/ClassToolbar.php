<?php
/* Info : To add spacer between butons : $output .= "\t".'tb.addSpace(10);'."\n"; with 10 the size of the space */

class Toolbar
{
	// Attributs modifiable ici en dur ou à l'aide des fonctions set_attribut()
	var $prefixe = 'tlbr_'; // Préfixe des tables et des fichiers du module
	var $fichierConfig = 'config'; // Fichier de configuration (dans le dossier cache /). Ex. : config donnera prefixeconfig.php
	var $tableBouton = 'bouton'; // Table pour les boutons. Ex. : bouton donnera prefixebouton
	var $tableSmilie = 'smilie'; // Table pour les smilies. Ex. : smilie donnera prefixesmilie
	var $cheminCache = 'data/cache/'; // Chemin vers le dossier de cache
	var $smilieWidth = 240; // Taille du pop up smilie
	var $smilieHeight = 140;
	var $colorWidth = 400; // Taille du pop up couleur
	var $colorHeight = 230;
	var $uploadWidth = 400; // Taille du pop up upload
	var $uploadHeight = 230;
	
	// Attributs modiafiable dans la partie administration ou à l'aide des fonctions set_attribut()
	var $etat; // Etat du module : 1 activé, 0 désactivé (défaut)
	var $idTextarea; // Id par défaut du textarea
	var $cheminImgBouton; // Chemin vers le dossier contenant les images des boutons
	var $cheminImgSmilie; // Chemin vers le dossier contenant les images des smilies
	var $nbSmilie; // Nombre de smilies à afficher
	var $fichierJS; // Fichier JS d'une toolbar
	
	function Test() {
		$this->_construct();
	}
	
	function __construct()
	{
		// MAJ du nom des tables et du fichier de config pour prendre en compte le préfixe
		$this->fichierConfig = $this->prefixe.$this->fichierConfig.'.php';
		$this->tableBouton = $this->prefixe.$this->tableBouton;
		$this->tableSmilie = $this->prefixe.$this->tableSmilie;
		
		// Récupération des valeurs des attributs dans le fichier de config
		if (is_file($this->cheminCache.$this->fichierConfig))
			require_once($this->cheminCache.$this->fichierConfig);
		$this->etat = @$param_toolbar['etat']; // 1 activée, 0 sinon
		$this->idTextarea = @$param_toolbar['id_textarea'];
		$this->cheminImgBouton = @$param_toolbar['dossier_bouton']; //'style/global/toolbar/';
		$this->cheminImgSmilie = @$param_toolbar['dossier_smilie']; //'style/global/smilies/';
		$this->nbSmilie = @$param_toolbar['nb_smilie']; // 20;
		$this->fichierJS = @$param_toolbar['fichier_js']; // 'js/toolbar.js';
	}
	
	function set_prefixe($var) { $this->prefixe = $var; }
	function get_prefixe() { return $this->prefixe; }
	
	function set_fichier_config($var)
	{
		if (!preg_match('!^'.$this->prefixe.'!', $var))
			$var = $this->prefixe.$var;
		$this->fichierConfig = $var;
	}
	function get_fichier_config() { return $this->fichierConfig; }
	
	function set_table_bouton($var)
	{
		if (!preg_match('!^'.$this->prefixe.'!', $var))
			$var = $this->prefixe.$var;
		$this->tableBouton = $var;
	}
	function get_table_bouton() { return $this->tableBouton; }
	
	function set_table_smilie($var)
	{
		if (!preg_match('!^'.$this->prefixe.'!', $var))
			$var = $this->prefixe.$var;
		$this->tableSmilie = $var;
	}
	function get_table_smilie() { return $this->tableSmilie; }
	
	function set_chemin_cache($var)
	{
		if (!preg_match('!/$!', $var))
			$var .= '/';
		$this->cheminCache = $var;
	}
	function get_chemin_cache() { return $this->cheminCache; }
	
	function get_smilie_size() { return array($this->smilieWidth, $this->smilieHeight); }
	function set_smilie_size($array) { $this->smilieWidth = $array[0]; $this->smilieHeight = $array[1]; }
	
	function get_color_size() { return array($this->colorWidth, $this->colorHeight); }
	function set_color_size($array) { $this->colorWidth = $array[0]; $this->colorHeight = $array[1]; }
	
	function get_upload_size() { return array($this->uploadWidth, $this->uploadHeight); }
	function set_upload_size($array) { $this->uploadWidth = $array[0]; $this->uploadHeight = $array[1]; }
	
	function get_etat() { return $this->etat; }
	function set_etat($var) { $this->etat = $var; }
	
	function get_id_textarea() { return $this->idTextarea; }
	function set_id_textarea($var) { $this->idTextarea = $var; }
	
	function get_chemin_img_bouton() { return $this->cheminImgBouton; }
	function set_chemin_img_bouton($var) { $this->cheminImgBouton = $var; }
	
	function get_chemin_img_smilie() { return $this->cheminImgSmilie; }
	function set_chemin_img_smilie($var) { $this->cheminImgSmilie = $var; }
	
	function get_nb_smilie() { return $this->nbSmilie; }
	function set_nb_smilie($var) { $this->nbSmilie = $var; }
	
	function get_fichier_js() { return $this->fichierJS; }
	function set_fichier_js($var) { $this->fichierJS = $var; }
	
	/**
	 * Affiche une toolbar
	 *
	 * @param string $name Nom de la toolbar (complété automatiquement si besoin)
	 * @post Affiche la toolbar $name
	*/
	function afficher_toolbar($name)
	{
		if (preg_match('!^'.$this->prefixe.'!', $name) || preg_match('!\.php$!', $name))
		{
			if (preg_match('!^'.$this->prefixe.'!', $name) && !preg_match('!\.php$!', $name))
				$name = $name.'.php';
			elseif (!preg_match('!^'.$this->prefixe.'!', $name) && preg_match('!\.php$!', $name))
				$name = $this->prefixe.$name;
		}
		else
			$name = $this->prefixe.$name.'.php';
		include($this->cheminCache.$name);
	}
	
	/**
	 * Affiche l'interface d'administration du module toolbar
	 * 
	 * Par défaut, toute l'interface s'affiche
	 * A afficher dans le corps du backoffice.
	 * @param array $param 1 pour afficher la gestion des paramètres, 0 sinon 
	 * @param array $toolbar 1 pour afficher la gestion des toolbars, 0 sinon 
	 * @param array $bouton 1 pour afficher la gestion des boutons, 0 sinon 
	 * @param array $smilie 1 pour afficher la gestion des smilies, 0 sinon 
	 * @post Affiche l'interface d'administration demandée
	*/
	function afficher_administration($param=1, $toolbar=1, $bouton=1, $smilie=1)
	{
		$action = $_SERVER['PHP_SELF'];
		echo '<ul class="tlb_onglet_hand">
			'.($param ? '<li id="form_config_hand" class="tlb_hand"><a href="'.$action.'#form_config">Modifier les paramètres</a></li>' : '').'
			'.($toolbar ? '<li id="form_generer_hand" class="tlb_hand"><a href="'.$action.'#form_generer">Générer des toolbars</a></li>' : '').'
			'.($bouton ? '<li id="form_bouton_hand" class="tlb_hand"><a href="'.$action.'#form_bouton">Gérer les boutons</a></li>' : '').'
			'.($smilie ? '<li id="form_smilie_hand" class="tlb_hand"><a href="'.$action.'#form_smilie">Gérer les smilies</a></li>' : '').'
		</ul>';
		echo $this->afficher_res();
		echo ($param ? '<div id="form_config" class="tlb_onglet">'.$this->form_install().$this->form_config().'</div>' : '');
		echo ($toolbar ? '<div id="form_generer" class="tlb_onglet">'.$this->form_generate().'</div>' : '');
		echo ($bouton ? '<div id="form_bouton" class="tlb_onglet">'.$this->form_bouton().'</div>' : '');
		echo ($smilie ? '<div id="form_smilie" class="tlb_onglet">'.$this->form_smilie().'</div>' : '');
		echo '<script type="text/javascript" src="js/'.$this->prefixe.'js_administration.js"></script>';
	}
	
	/**
	 * Effectue les différents traitements de la partie administration du module toolbar
	 *
	 * Annulation, confirmation de suppression, suppression, ajout, modification
	 * A afficher dans le header du backoffice, après la connexion à la BDD et avant tout affichage à l'écran.
	*/
	function traitement_administration()
	{
		$action = $_SERVER['PHP_SELF'];
		// Annulation
		if (@$_POST['non'] || (@$_POST['oui'] && @$_POST['a_supprimer'] == NULL)) {
			header('Location: '.$action.'?msg=annulation');
			exit;
		}
		// Maj bouton
		elseif (@$_POST['maj_bouton'] && ($_POST['a_name'] != NULL)) {
			$b_result = $this->maj_boutons($_POST['a_name'], $_POST['a_etat'], $_POST['a_aide'], $_POST['a_type'], $_POST['a_ordre'], $_FILES['a_bouton']);
			if ($b_result)
				header('Location: '.$action.'?msg=maj_bouton');
			else
				header('Location: '.$action.'?msg=e_maj_bouton');
			exit;
		}
		// Maj smilie
		elseif (@$_POST['maj_smilie'] && @$_POST['a_name'] != NULL) {
			$b_result = $this->maj_smilies($_POST['a_name'], $_POST['a_symbole'], $_POST['a_etat'], $_POST['a_ordre'], $_FILES['a_smilie']);
			if ($b_result)
				header('Location: '.$action.'?msg=maj_smilie');
			else
				header('Location: '.$action.'?msg=e_maj_smilie');
			exit;
		}
		// Maj param
		elseif (@$_POST['maj_config']) {
			$b_result = $this->maj_config($_POST['etat'], $_POST['id_textarea'], $_POST['dossier_bouton'], $_POST['dossier_smilie'], $_POST['nb_smilie'], $_POST['fichier_js']);
			if ($b_result)
				header('Location: '.$action.'?msg=maj_config');
			else
				header('Location: '.$action.'?msg=e_maj_config');
			exit;
		}
		// Générer une toolbar
		elseif (@$_POST['generer'] && @$_POST['name'] != NULL) {
			$b_result = $this->generer_toolbar($_POST['name'], $_POST['id_textarea'], $_POST['dossier_bouton'], $_POST['dossier_smilie'], $_POST['nb_smilie'], $_POST['fichier_js']);
			if ($b_result)
				header('Location: '.$action.'?msg=generer_toolbar');
			else
				header('Location: '.$action.'?msg=e_generer_toolbar');
			exit;
		}
		// Installer le module toolbar
		elseif (@$_POST['install']) {
			$b_result = $this->install();
			if ($b_result)
				header('Location: '.$action.'?msg=install');
			else
				header('Location: '.$action.'?msg=e_install');
			exit;
		}
		// Confirmation de désinstallation du module toolbar
		elseif (@$_POST['uninstall']) {
			echo $this->confirmer_del(array('0'), 'uninstall');
			exit;
		}
		// Suppression d'un bouton, d'un smilie ou d'une toolbar, désinstallation du module toolbar
		elseif (@$_POST['oui'] && @$_POST['a_supprimer'] != NULL && @$_POST['type'] != NULL)
		{
			$b_result = $this->del($_POST['a_supprimer'], $_POST['type']);
			if ($b_result)
				header('Location: '.$action.'?msg=suppression');
			else
				header('Location: '.$action.'?msg=e_suppression');
			exit;
		}
		// Confirmation suppression d'un bouton
		elseif (@$_POST['del_bouton'] && @$_POST['a_del_bouton'] != NULL)
		{
			echo $this->confirmer_del($_POST['a_del_bouton'], $this->tableBouton);
			exit;
		}
		// Confirmation suppression d'un smilie
		elseif (@$_POST['del_smilie'] && @$_POST['a_del_smilie'] != NULL)
		{
			echo $this->confirmer_del($_POST['a_del_smilie'], $this->tableSmilie);
			exit;
		}		
		// Confirmation suppression d'une toolbar
		elseif (@$_POST['del_toolbar'] && @$_POST['a_del_toolbar'] != NULL)
		{
			echo $this->confirmer_del($_POST['a_del_toolbar'], 'toolbar');
			exit;
		}
	}
	
	/**
	 * Affiche les résultats dans traitement dans la partie administration
	*/
	function afficher_res()
	{
		$msg = '';
		$debErreur = '<div class="tlb_erreur">'; $debOk = '<div class="tlb_ok">'; $fin = '</div>';
		switch(@$_GET['msg'])
		{
			case 'annulation': $msg = $debOk.'Action annulée.'.$fin; break;
			case 'maj_bouton' : $msg = $debOk.'Mise à jour des boutons réalisée avec succés.'.$fin; break;
			case 'e_maj_bouton' : $msg = $debErreur.'Erreur lors de la mise à jour des boutons.'.$fin; break;
			case 'maj_smilie' : $msg = $debOk.'Mise à jour des smilies réalisée avec succés.'.$fin; break;
			case 'e_maj_smilie' : $msg = $debErreur.'Erreur lors de la mise à jour des smilies.'.$fin; break;
			case 'maj_config' : $msg = $debOk.'Mise à jour des paramètres réalisée avec succés.'.$fin; break;
			case 'e_maj_config' : $msg = $debErreur.'Erreur lors de la mise à jour des paramètres.'.$fin; break;
			case 'generer_toolbar' : $msg = $debOk.'Toolbar générée avec succés.'.$fin; break;
			case 'e_generer_toolbar' : $msg = $debErreur.'Erreur lors de la génération de la toolbar.'.$fin; break;
			case 'install' : $msg = $debOk.'BDD et fichier de configuration du module toolbar installés avec succés.'.$fin; break;
			case 'e_install' : $msg = $debErreur.'Erreur lors de l\'installation de la BDD et fichier de configuration.'.$fin; break;
			case 'uninstall' : $msg = $debOk.'BDD et fichier de configuration du module toolbar désinstallés avec succés.'.$fin; break;
			case 'e_uninstall' : $msg = $debErreur.'Erreur lors de la désinstallation de la BDD et fichier de configuration.'.$fin; break;
			case 'suppression' : $msg = $debOk.'Suppression réalisée avec succés.'.$fin; break;
			case 'e_suppression' : $msg = $debErreur.'Erreur lors de la suppression.'.$fin;
			case 'e_suppression-table' : $msg = $debErreur.'Erreur lors de la suppression : table SQL non précisée.'.$fin;
		}
		return $msg;
	}
	
	function form_install($t=1)
	{
		$action = $_SERVER['PHP_SELF'].'#form_config';
		$formulaire = '<form action="'.$action.'" method="post" id="form_install">'."\n"
		.'<h3>Plugin d\'administration des toolbars</h3>'."\n";
		$qry = 'SELECT id FROM '.$this->tableBouton.' LIMIT 0,1';
		$select = @mysql_query($qry);
		if (!$select) {
			$formulaire .= '<p>Avant de faire quoi que ce soit, installez donc la base de données et le fichier de configuration.<br /><span>Modifiez au préalable le nom des tables et du fichier de configuration dans tlb_toolbar.php si vous le souhaitez.</span></p>'."\n"
			.'<p><input type="submit" name="install" value="Installer" tabindex="'.$t++.'" /></p>'."\n";
		}
		else {
			$formulaire .= '<p>La base de données et le fichier de configuration des toolbars sont installés.<br />Vous pouvez utiliser tranquilement le module toolbar.</p>'."\n"
			.'<p><input type="submit" name="uninstall" value="Désinstaller" tabindex="'.$t++.'" /></p>'."\n";
		}
		$formulaire .= '</form>';
		return $formulaire;
	}
	
	function form_config($t = 1)
	{
		$action = $_SERVER['PHP_SELF'].'#form_config';
		$formulaire = '<h3>Réglage des paramètres par défaut</h3>'."\n"
		.'<form action="'.$action.'" method="post" id="form_config">'."\n"
		.'<table class="tlbr_table">'."\n"
		."\t".'<tbody>'."\n"
		."\t\t".'</tr>'."\n"
		."\t\t".'<tr class="impair left">'."\n"
			."\t\t\t".'<th><label for="etat">Etat de la toolbar</label></th>'."\n"
			."\t\t\t".'<td>'."\n"
			."\t\t\t".'<select name="etat" id="etat" tabindex="'.$t++.'">'."\n"
			."\t\t\t\t".'<option value="1"'.($this->etat == 1 ? 'selected="selected"' : '').'>Activé</option>'."\n"
			."\t\t\t\t".'<option value="0"'.($this->etat == 0 ? 'selected="selected"' : '').'>Désactivé</option>'."\n"
			."\t\t\t".'</select>'."\n"
			."\t\t\t".'</td>'."\n"
		."\t\t".'</tr>'."\n"
		."\t\t".'<tr class="pair left">'."\n"
			."\t\t\t".'<th><label for="id_textarea">ID du textarea</label></th>'."\n"
			."\t\t\t".'<td><input type="text" name="id_textarea" id="id_textarea" value="'.$this->idTextarea.'" size="20" tabindex="'.$t++.'" /></td>'."\n"
		."\t\t".'</tr>'."\n"
		."\t\t".'<tr class="impair left">'."\n"
			."\t\t\t".'<th><label for="dossier_bouton">Dossier des boutons</label></th>'."\n"
			."\t\t\t".'<td><input type="text" name="dossier_bouton" id="dossier_bouton" value="'.$this->cheminImgBouton.'" size="20" tabindex="'.$t++.'" /></td>'."\n"
		."\t\t".'</tr>'."\n"
		."\t\t".'<tr class="pair left">'."\n"
			."\t\t\t".'<th><label for="dossier_smilie">Dossier des smilies</label></th>'."\n"
			."\t\t\t".'<td><input type="text" name="dossier_smilie" id="dossier_smilie" value="'.$this->cheminImgSmilie.'" size="20" tabindex="'.$t++.'" /></td>'."\n"
		."\t\t".'</tr>'."\n"
		."\t\t".'<tr class="impair left">'."\n"
			."\t\t\t".'<th><label for="nb_smilie">Nombre de smilies</label></th>'."\n"
			."\t\t\t".'<td><input type="text" name="nb_smilie" id="nb_smilie" value="'.$this->nbSmilie.'" size="5" tabindex="'.$t++.'" /></td>'."\n"
		."\t\t".'</tr>'."\n"
		."\t\t".'<tr class="pair left">'."\n"
			."\t\t\t".'<th><label for="dossier_js">Fichier Javascript</label></th>'."\n"
			."\t\t\t".'<td><input type="text" name="fichier_js" id="fichier_js" value="'.$this->fichierJS.'" size="20" tabindex="'.$t++.'" /></td>'."\n"
		."\t\t".'</tr>'."\n"
		."\t\t".'<tr class="impair left">'."\n"
			."\t\t\t".'<th></th>'."\n"
			."\t\t\t".'<td><input type="submit" name="maj_config" value="Mettre à jour" tabindex="'.$t++.'" /></td>'."\n"
		."\t\t".'</tr>'."\n"
		."\t".'</tbody>'."\n"
		.'</table>'."\n"
		.'</form>';
		return $formulaire;
	}
	
	function form_generate($t = 1)
	{
		$action = $_SERVER['PHP_SELF'].'#form_generer';
		$formulaire = '<h3>Générer une toolbar</h3>'."\n"
		.'<p>La toolbar contiendra les boutons et les smilies activés en ce moment.</p>'."\n"
		.'<form action="'.$action.'" method="post" id="form_generate">'."\n"
		.'<table class="tlbr_table">'."\n"
		."\t".'<tbody>'."\n"
		."\t\t".'</tr>'."\n"
		."\t\t".'<tr class="impair left">'."\n"
			."\t\t\t".'<th><label for="name">Nom de la toolbar</label></th>'."\n"
			."\t\t\t".'<td><input type="text" name="name" id="name" size="20" tabindex="'.$t++.'" /><br /> <span>Ex. : test =&raquo; '.$this->prefixe.'test.php</span></td>'."\n"
		."\t\t".'</tr>'."\n"
		."\t\t".'<tr class="pair left">'."\n"
			."\t\t\t".'<th><label for="id_textarea">ID du textarea</label></th>'."\n"
			."\t\t\t".'<td><input type="text" name="id_textarea" id="id_textarea" value="'.$this->idTextarea.'" size="20" tabindex="'.$t++.'" /></td>'."\n"
		."\t\t".'</tr>'."\n"
		."\t\t".'<tr class="impair left">'."\n"
			."\t\t\t".'<th><label for="dossier_bouton">Dossier des boutons</label></th>'."\n"
			."\t\t\t".'<td><input type="text" name="dossier_bouton" id="dossier_bouton" value="'.$this->cheminImgBouton.'" size="20" tabindex="'.$t++.'" /></td>'."\n"
		."\t\t".'</tr>'."\n"
		."\t\t".'<tr class="pair left">'."\n"
			."\t\t\t".'<th><label for="dossier_smilie">Dossier des smilies</label></th>'."\n"
			."\t\t\t".'<td><input type="text" name="dossier_smilie" id="dossier_smilie" value="'.$this->cheminImgSmilie.'" size="20" tabindex="'.$t++.'" /></td>'."\n"
		."\t\t".'</tr>'."\n"
		."\t\t".'<tr class="impair left">'."\n"
			."\t\t\t".'<th><label for="nb_smilie">Nombre de smilies</label></th>'."\n"
			."\t\t\t".'<td><input type="text" name="nb_smilie" id="nb_smilie" value="'.$this->nbSmilie.'" size="5" tabindex="'.$t++.'" /></td>'."\n"
		."\t\t".'</tr>'."\n"
		."\t\t".'<tr class="pair left">'."\n"
			."\t\t\t".'<th><label for="dossier_js">Fichier Javascript</label></th>'."\n"
			."\t\t\t".'<td><input type="text" name="fichier_js" id="fichier_js" value="'.$this->fichierJS.'" size="20" tabindex="'.$t++.'" /></td>'."\n"
		."\t\t".'</tr>'."\n"
		."\t\t".'<tr class="impair left">'."\n"
			."\t\t\t".'<th></th>'."\n"
			."\t\t\t".'<td><input type="submit" name="generer" value="Générer" tabindex="'.$t++.'" /></td>'."\n"
		."\t\t".'</tr>'."\n"
		."\t".'</tbody>'."\n"
		.'</table>'."\n"
		
		.'<h3>Gérer les toolbars générées</h3>'."\n"
		.'<p>Servez-vous de <code style="font-family:\'courier new\';color=gray;">afficher_toolbar(\'nom de la toolbar\')</code> pour utiliser une toolbar générée.</p>'."\n"
		.'<table class="tlbr_table">'."\n"
		."\t".'<thead>'."\n"
		."\t\t".'<tr>'."\n"
		."\t\t".'<th>Nom de la toolbar</th>'."\n"
		."\t\t".'<th>Fichier de la toolbar</th>'."\n"
		."\t\t".'<th>'."\n"
			."\t\t\t".'<input type="submit" name="del_toolbar" value="X" tabindex="'.$t++.'" /><br />'."\n"
			."\t\t\t".'<input type="checkbox" name="checkAll1" id="checkAll1" tabindex="'.$t++.'" />'."\n"
		."\t\t".'</th>'."\n"
		."\t\t".'</tr>'."\n"
		."\t".'</thead>'."\n"
		
		."\t".'<tbody>'."\n";
			
		$dir = 'cache/';
		if (is_dir($dir))
		{
			$i = 0;
			$fd = opendir($dir);
			while(($file = readdir($fd)) !== false) {
				if (is_file($dir.$file) && preg_match('!^'.$this->prefixe.'.*$!', $file) && $file != $this->fichierConfig)
				{
					$name = preg_replace('!^'.$this->prefixe.'(.*)\.php$!', '$1', $file);
					$formulaire .= "\t\t".'<tr class="'.($i++ % 2 == 0 ? 'pair' : 'impair').'">'."\n"
									."\t\t".'<td>'.$name.'</td>'."\n"
									."\t\t".'<td>'.$dir.$file.'</td>'."\n"
									."\t\t".'<td class="del"><input type="checkbox" name="a_del_toolbar['.$dir.$file.']" id="supprimer['.$dir.$file.']" tabindex="'.$t++.'" /></td>'."\n"
								."\t\t".'</tr>'."\n";
				}
			}
			closedir($fd);
		}
		
		$formulaire .= "\t".'</tbody>'."\n"
		
		.'</table>'."\n"
		.'</form>';
		return $formulaire;
	}
	
	function form_bouton($t = 1)
	{
		$action = $_SERVER['PHP_SELF'].'#form_bouton';
		$formulaire = '<form action="'.$action.'" method="post" id="form_bouton" enctype="multipart/form-data">'."\n"
		.'<p>'."\n"
		."\t".'<input type="submit" name="maj_bouton" value="Mettre à jour" tabindex="'.$t++.'" />'."\n"
		.'</p>'."\n"
		.'<table class="tlbr_table">'."\n"
		.'<caption>Paramètrer les boutons</caption>'."\n"
		."\t".'<thead>'."\n"
		."\t\t".'<tr>'."\n"
		."\t\t".'<th colspan="2">Image</th>'."\n"
		."\t\t".'<th>Nom</th>'."\n"
		."\t\t".'<th>Type de bouton</th>'."\n"
		."\t\t".'<th>Aide<br /><span>séparez les phrases par des pipes (Alt Gr + 6 -> |)</span></th>'."\n"
		."\t\t".'<th>Activé</th>'."\n"
		."\t\t".'<th>Ordre</th>'."\n"
		."\t\t".'<th>'."\n"
			."\t\t\t".'<input type="submit" name="del_bouton" value="X" tabindex="'.$t++.'" /><br />'."\n"
			."\t\t\t".'<input type="checkbox" name="checkAll3" id="checkAll3" tabindex="'.$t++.'" />'."\n"
		."\t\t".'</th>'."\n"
		."\t\t".'</tr>'."\n"
		."\t".'</thead>'."\n"
		
		."\t".'<tfoot>'."\n"
		."\t\t".'<tr>'."\n"
		."\t\t".'<th colspan="2">Image</th>'."\n"
		."\t\t".'<th>Nom</th>'."\n"
		."\t\t".'<th>Type de bouton</th>'."\n"
		."\t\t".'<th><span>séparez les phrases par des pipes (Alt Gr + 6 -> |)</span><br />Aide</th>'."\n"
		."\t\t".'<th>Activé</th>'."\n"
		."\t\t".'<th>Ordre</th>'."\n"
		."\t\t".'<th>'."\n"
			."\t\t\t".'<input type="checkbox" name="checkAll4" id="checkAll4" tabindex="'.$t++.'" /><br />'."\n"
			."\t\t\t".'<input type="submit" name="del_bouton" value="X" tabindex="'.$t++.'" />'."\n"
		."\t\t".'</th>'."\n"
		."\t\t".'</tr>'."\n"
		
		."\t".'</tfoot>'."\n"
		
		."\t".'<tbody>'."\n"
		."\t\t".'<tr class="impair">'."\n"
			."\t\t".'<td><input type="file" name="a_bouton[0]" id="a_bouton[0]" size="13" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td> ou </td>'."\n"
			."\t\t".'<td><input type="text" name="a_name[0]" id="name[0]" value="" size="8" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td>'."\n"
				."\t\t\t".'<select name="a_type[0]" id="type[0]" tabindex="'.$t++.'">'."\n"
					."\t\t\t\t".'<option value="1">Standard</option>'."\n"
					."\t\t\t\t".'<option value="2">JS : 1 fenêtre</option>'."\n"
					."\t\t\t\t".'<option value="3">JS : 2 fenêtres</option>'."\n"
					."\t\t\t\t".'<option value="4">PopUp</option>'."\n"
					."\t\t\t\t".'<option value="5">Barre</option>'."\n"
					."\t\t\t\t".'<option value="6">Espace</option>'."\n"
				."\t\t\t".'</select>'."\n"
			."\t\t".'</td>'."\n"
			."\t\t".'<td><input type="text" name="a_aide[0]" id="aide[0]" value="" size="40" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td>'."\n"
				."\t\t\t".'<input type="radio" name="a_etat[0]" id="etat_oui[0]" value="1" checked="checked" tabindex="'.$t++.'" /> <label for="etat_oui[0]">Oui</label>'."\n"
				."\t\t\t".'<input type="radio" name="a_etat[0]" id="etat_non[0]" value="0" tabindex="'.$t++.'" /> <label for="etat_non[0]">Non</label>'."\n"
			."\t\t".'</td>'."\n"
			."\t\t".'<td><input type="text" name="a_ordre[0]" id="ordre[0]" value="" size="4" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td class="del"></td>'."\n"
			."\t\t".'</tr>'."\n";
		
		$i = 0;
		$result = @mysql_query('SELECT id AS id, name AS name, etat AS etat, aide as aide, type AS type, ordre AS ordre FROM '.$this->tableBouton.' ORDER BY ordre');
		while ($bouton = @mysql_fetch_array($result)) {
			$bouton = array_map('stripslashes', $bouton);
			$id = $bouton['id'];
			$formulaire .= "\t\t".'<tr class="'.($i++ % 2 == 0 ? 'pair' : 'impair').'">'."\n"
			."\t\t".'<td><input type="file" name="a_bouton['.$id.']"  id="bouton['.$id.']" size="13" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td>'.($bouton['type'] != 6 ? '<img src="'.$this->cheminImgBouton.(is_file($this->cheminImgBouton.$bouton['name'].'.png') ? $bouton['name'] : 'default').'.png" alt="'.$bouton['name'].'" />' : '').'</td>'."\n"
			."\t\t".'<td><input type="text" name="a_name['.$id.']" id="name['.$id.']" value="'.$bouton['name'].'" size="8" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td>'."\n"
				."\t\t\t".'<select name="a_type['.$id.']" id="type['.$id.']" tabindex="'.$t++.'">'."\n"
					."\t\t\t\t".'<option value="1"'.($bouton['type'] == 1 ? ' selected="selected"' : '').'>Standard</option>'."\n"
					."\t\t\t\t".'<option value="2"'.($bouton['type'] == 2 ? ' selected="selected"' : '').'>JS : 1 fenêtre</option>'."\n"
					."\t\t\t\t".'<option value="3"'.($bouton['type'] == 3 ? ' selected="selected"' : '').'>JS : 2 fenêtres</option>'."\n"
					."\t\t\t\t".'<option value="4"'.($bouton['type'] == 4 ? ' selected="selected"' : '').'>PopUp</option>'."\n"
					."\t\t\t\t".'<option value="5"'.($bouton['type'] == 5 ? ' selected="selected"' : '').'>Barre</option>'."\n"
					."\t\t\t\t".'<option value="6"'.($bouton['type'] == 6 ? ' selected="selected"' : '').'>Espace</option>'."\n"
				."\t\t\t".'</select>'."\n"
			."\t\t".'</td>'."\n"
			."\t\t".'<td><input type="text" name="a_aide['.$id.']" id="aide['.$id.']" value="'.$bouton['aide'].'" size="40" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td>'."\n"
				."\t\t\t".'<input type="radio" name="a_etat['.$id.']" id="etat_oui['.$id.']" value="1"'.($bouton['etat'] == 1 ? ' checked="checked"' : '').' tabindex="'.$t++.'" /> <label for="etat_oui['.$id.']">Oui</label>'."\n"
				."\t\t\t".'<input type="radio" name="a_etat['.$id.']" id="etat_non['.$id.']" value="0"'.($bouton['etat'] == 0 ? ' checked="checked"' : '').' tabindex="'.$t++.'" /> <label for="etat_non['.$id.']">Non</label>'."\n"
			."\t\t".'</td>'."\n"
			."\t\t".'<td><input type="text" name="a_ordre['.$id.']" id="ordre['.$id.']" value="'.$bouton['ordre'].'" size="4" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td class="del"><input type="checkbox" name="a_del_bouton['.$id.']" id="supprimer['.$id.']" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'</tr>'."\n";
		}
		
		$formulaire .= "\t".'</tbody>'."\n"
		
		.'</table>'."\n"
		.'<p>'."\n"
		."\t".'<input type="submit" name="maj_bouton" value="Mettre à jour" tabindex="'.$t++.'" />'."\n"
		.'</p>'."\n"
		.'</form>';
		return $formulaire;
	}
	
	function form_smilie($t = 1)
	{
		$action = $_SERVER['PHP_SELF'].'#form_smilie';
		$formulaire = '<form action="'.$action.'" method="post" id="form_smilie" enctype="multipart/form-data">'."\n"
		.'<p>'."\n"
		."\t".'<input type="submit" name="maj_smilie" value="Mettre à jour" tabindex="'.$t++.'" />'."\n"
		.'</p>'."\n"
		.'<table class="tlbr_table">'."\n"
		.'<caption>Paramètrer les smilies</caption>'."\n"
		."\t".'<thead>'."\n"
		."\t\t".'<tr>'."\n"
		."\t\t".'<th colspan="2">Image</th>'."\n"
		."\t\t".'<th>Nom</th>'."\n"
		."\t\t".'<th>Symbôle<br /><span>séparez les symbôles multiples par des pipes (Alt Gr + 6 -> |)</span></th>'."\n"
		."\t\t".'<th>Activé</th>'."\n"
		."\t\t".'<th>Ordre</th>'."\n"
		."\t\t".'<th>'."\n"
			."\t\t\t".'<input type="submit" name="del_smilie" value="X" tabindex="'.$t++.'" /><br />'."\n"
			."\t\t\t".'<input type="checkbox" name="checkAll1" id="checkAll1" tabindex="'.$t++.'" />'."\n"
		."\t\t".'</th>'."\n"
		."\t\t".'</tr>'."\n"
		."\t".'</thead>'."\n"
		
		."\t".'<tfoot>'."\n"
		."\t\t".'<tr>'."\n"
		."\t\t".'<th colspan="2">Image</th>'."\n"
		."\t\t".'<th>Nom</th>'."\n"
		."\t\t".'<th><span>séparez les symbôles multiples par des pipes (Alt Gr + 6 -> |)</span><br />Symbôle</th>'."\n"
		."\t\t".'<th>Activé</th>'."\n"
		."\t\t".'<th>Ordre</th>'."\n"
		."\t\t".'<th>'."\n"
			."\t\t\t".'<input type="checkbox" name="checkAll2" id="checkAll2" tabindex="'.$t++.'" /><br />'."\n"
			."\t\t\t".'<input type="submit" name="del_smilie" value="X" tabindex="'.$t++.'" />'."\n"			
		."\t\t".'</th>'."\n"
		."\t\t".'</tr>'."\n"
		."\t\t".'</tr>'."\n"
		
		."\t".'</tfoot>'."\n"
		
		."\t".'<tbody>'."\n"
		."\t\t".'<tr class="impair">'."\n"
			."\t\t".'<td><input type="file" name="a_smilie[0]" id="smilie[0]"  size="13" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td> ou </td>'."\n"
			."\t\t".'<td><input type="text" name="a_name[0]" id="name[0]" value="" size="20" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td><input type="text" name="a_symbole[0]" id="symbole[0]" value="" size="20" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td>'."\n"
				."\t\t\t".'<input type="radio" name="a_etat[0]" id="etat_oui[0]" value="1" checked="checked" tabindex="'.$t++.'" /> <label for="etat_oui[0]">Oui</label>'."\n"
				."\t\t\t".'<input type="radio" name="a_etat[0]" id="etat_non[0]" value="0" tabindex="'.$t++.'" /> <label for="etat_non[0]">Non</label>'."\n"
			."\t\t".'</td>'."\n"
			."\t\t".'<td><input type="text" name="a_ordre[0]" id="ordre[0]" value="" size="4" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td class="del"></td>'."\n"
			."\t\t".'</tr>'."\n";
		
		$i = 0;
		$result = @mysql_query('SELECT id AS id, name AS image, symbole AS symbole, etat AS etat, ordre AS ordre FROM '.$this->tableSmilie.' ORDER BY ordre');
		while ($smilie = @mysql_fetch_array($result)) {
			$smilie = array_map('stripslashes', $smilie);
			$id = $smilie['id'];
			$formulaire .= "\t\t".'<tr class="'.($i++ % 2 == 0 ? 'pair' : 'impair').'">'."\n"
			."\t\t".'<td><input type="file" name="a_smilie['.$id.']" id="smilie['.$id.']"  size="13" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td><img src="'.$this->cheminImgSmilie.(is_file($this->cheminImgSmilie.$smilie['image']) ? $smilie['image'] : 'default').'" alt="'.$smilie['image'].'" /></td>'."\n"
			."\t\t".'<td><input type="text" name="a_name['.$id.']" id="name['.$id.']" value="'.$smilie['image'].'" size="20" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td><input type="text" name="a_symbole['.$id.']" id="symbole['.$id.']" value="'.$smilie['symbole'].'" size="20" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td>'."\n"
				."\t\t\t".'<input type="radio" name="a_etat['.$id.']" id="etat_oui['.$id.']" value="1" checked="checked" tabindex="'.$t++.'" /> <label for="etat_oui[0]">Oui</label>'."\n"
				."\t\t\t".'<input type="radio" name="a_etat['.$id.']" id="etat_non['.$id.']" value="0" tabindex="'.$t++.'" /> <label for="etat_non[0]">Non</label>'."\n"
			."\t\t".'</td>'."\n"
			."\t\t".'<td><input type="text" name="a_ordre['.$id.']" id="ordre['.$id.']" value="'.$smilie['ordre'].'" size="4" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'<td class="del"><input type="checkbox" name="a_del_smilie['.$id.']" id="supprimer['.$id.']" tabindex="'.$t++.'" /></td>'."\n"
			."\t\t".'</tr>'."\n";
		}
		
		$formulaire .= "\t".'</tbody>'."\n"
		
		.'</table>'."\n"
		.'<p>'."\n"
		."\t".'<input type="submit" name="maj_smilie" value="Mettre à jour" tabindex="'.$t++.'" />'."\n"
		.'</p>'."\n"
		.'</form>';
		return $formulaire;
	}
	
	function maj_boutons($a_name, $a_etat, $a_aide, $a_type, $a_ordre, $file_bouton, $msg='')
	{
		$b_result = 1;
		foreach($a_type AS $k => $val) {
			$k = $this->protectI($k);
			$a_name[$k] = $this->protectS($a_name[$k]);
			$a_etat[$k] = $this->protectI($a_etat[$k]);
			$a_type[$k] = $this->protectS($a_type[$k]);
			$a_aide[$k] = $this->protectS($a_aide[$k]);
			$a_ordre[$k] = $this->protectI($a_ordre[$k]);
			// Calcule de l'ordre s'il n'est pas fourni
			if ($a_ordre[$k] == 0) {
				$qry = 'SELECT ordre FROM '.$this->tableBouton.' ORDER BY ordre DESC LIMIT 0,1';
				$result = mysql_query($qry);
				$a_ordre[$k] = mysql_result($result, 0)+10;
			}
			// $k == 0, c'est une insertion
			if ($k == 0 && ($a_name[0] != NULL || ($file_bouton['name'][0] != NULL && !$file_bouton['error'][0]) || $a_type[0] == 6))
			{
				if ($a_name[$k] == NULL && $file_bouton['name'][$k] != NULL)
					$a_name[$k] = preg_replace('!^(.+)\.(png|bmp|jpg|jpeg|gif)!i', '$1', $file_bouton['name'][$k]);
				$qry = "INSERT INTO ".$this->tableBouton."
							(name, aide, etat, type, ordre) 
							VALUES('".$a_name[$k]."', '".$a_aide[$k]."', '".$a_etat[$k]."', '".$a_type[$k]."', '".$a_ordre[$k]."')";
				$b_insert = mysql_query($qry);
				if (!$b_insert)
				{
					$msg .= 'Erreur lors de l\'insertion dans la BDD.';
					$b_result *= 0;
				}
				
			}
			// C'est une update
			elseif ($k != 0 && ($a_name[$k] != NULL || ($file_bouton['name'][$k] != NULL && !$file_bouton['error'][$k]) || $a_type[$k] == 6))
			{
				if ($a_name[$k] == NULL && $file_bouton['name'][$k] != NULL)
					$a_name[$k] = preg_replace('!^(.+)\.(png|bmp|jpg|jpeg|gif)!i', '$1', $file_bouton['name'][$k]);
				$qry = "UPDATE ".$this->tableBouton."
					SET name='".$a_name[$k]."', etat='".$a_etat[$k]."', aide='".$a_aide[$k]."', type='".$a_type[$k]."', ordre='".$a_ordre[$k]."'
					WHERE id='".$k."'";
				$b_update = mysql_query($qry);
				if (!$b_update)
				{
					$msg .= 'Erreur lors de l\'update de la BDD.';
					$b_result *= 0;
				}
			}
			// Upload du fichier s'il y en a un
			if (isset($file_bouton['name'][$k]) && $file_bouton['name'][$k] != NULL && $file_bouton['error'][$k] == 0) {
				$tmp_name = $file_bouton['tmp_name'][$k];
				$new_name = $this->cheminImgBouton.$a_name[$k].'.png';
				$b_move = move_uploaded_file($tmp_name, $new_name);
				if (!$b_move) {
					$msg .= 'Erreur lors de l\'upload du fichier.';
					$b_result *= 0;
				}
			}
		}
		return $b_result;
	}
	
	function maj_smilies($a_name, $a_symbole, $a_etat, $a_ordre, $file_smilie, $msg='')
	{
		$b_result = 1;
		foreach($a_name AS $k => $val) {
			$k = $this->protectI($k);
			$val = $this->protectS($val);
			$a_symbole[$k] = $this->protectS($a_symbole[$k]);
			$a_etat[$k] = $this->protectI($a_etat[$k]);
			$a_ordre[$k] = $this->protectI($a_ordre[$k]);
			if ($a_ordre[$k] == 0) {
				$qry = 'SELECT ordre FROM '.$this->tableSmilie.' ORDER BY ordre DESC LIMIT 0,1';
				$result = mysql_query($qry);
				$a_ordre[$k] = mysql_result($result, 0)+10;
			}
			if ($k == 0 && ($val != NULL || !$file_smilie['error'][$k]))
			{
				if ($val != NULL && !preg_match('!\.(png|bmp|jpg|jpeg|gif)!i', $val))
					$val .= '.png';
				if ($val == NULL)
					$val = $file_smilie['name'][$k];
				$qry = "INSERT INTO ".$this->tableSmilie."
							(name, symbole, etat, ordre) 
							VALUES('".$val."', '".$a_symbole[$k]."', '".$a_etat[$k]."', '".$a_ordre[$k]."')";
				$b_insert = mysql_query($qry);
				if (!$b_insert)
				{
					$msg = 'Erreur lors de l\'insertion dans la BDD.';
					$b_result *= 0;
				}
			}
			else
			{
				if ($val != NULL && !preg_match('!\.(png|bmp|jpg|jpeg|gif)!i', $val))
					$val .= '.png';
				if ($val == NULL)
					$val = $file_smilie['name'][$k];
				$qry = "UPDATE ".$this->tableSmilie."
					SET name='".$val."', symbole='".$a_symbole[$k]."', etat='".$a_etat[$k]."', ordre='".$a_ordre[$k]."'
					WHERE id='".$k."'";
				$b_update = mysql_query($qry);
				if (!$b_update)
				{
					$msg = 'Erreur lors de l\'update de la BDD.';
					$b_result *= 0;
				}
			}
			// Upload du fichier s'il y en a un
			if (isset($file_smilie['name'][$k]) && $file_smilie['name'][$k] != NULL && $file_smilie['error'][$k] == 0)
			{
				$tmp_name = $file_smilie['tmp_name'][$k];
				if ($val != NULL)
					$new_name = $this->cheminImgSmilie.$val;
				else
					$new_name = $file_smilie['name'][$k];
				$b_move = move_uploaded_file($tmp_name, $new_name);
				if (!$b_move)
				{
					$msg = 'Erreur lors de l\'upload du fichier.';
					$b_result *= 0;
				}
			}
		}
		return $b_result;
	}
	
	function maj_config($etat, $id_textarea, $dossier_bouton, $dossier_smilie, $nb_smilie, $fichier_js)
	{
		$etat = $this->protectI($etat);
		$id_textarea = $this->protectS($id_textarea);
		$dossier_bouton = $this->protectS($dossier_bouton);
		$dossier_smilie = $this->protectS($dossier_smilie);
		$nb_smilie = $this->protectI($nb_smilie);
		$fichier_js = $this->protectS($fichier_js);
		$fichier = $this->fichierConfig;
		$contenu = '<?php'."\n".'$param_toolbar = array('."\n"
		.'"etat" => "'.$etat.'",'."\n"
		.'"id_textarea" => "'.$id_textarea.'",'."\n"
		.'"dossier_bouton" => "'.$dossier_bouton.'",'."\n"
		.'"dossier_smilie" => "'.$dossier_smilie.'",'."\n"
		.'"nb_smilie" => "'.$nb_smilie.'",'."\n"
		.'"fichier_js" => "'.$fichier_js.'",'."\n"
		.');'."\n".'?>';
		$b_result = $this->generer_cache($fichier, $contenu);
		return $b_result;
	}
	
	function install()
	{
		$qry = "CREATE TABLE IF NOT EXISTS `$this->tableBouton` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '',
		  `aide` varchar(400) CHARACTER SET utf8 NOT NULL DEFAULT '',
		  `etat` int(1) NOT NULL DEFAULT '0',
		  `type` int(1) NOT NULL DEFAULT '1',
		  `ordre` int(5) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`),
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;";
		$create = mysql_query($qry);
		if (!$create)
			return false;
		$qry = "CREATE TABLE IF NOT EXISTS `$this->tableSmilie` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
		  `symbole` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
		  `etat` int(1) NOT NULL DEFAULT '0',
		  `ordre` int(3) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;";
		$create = mysql_query($qry);
		if (!$create)
			return false;
		$b_result = $this->maj_config(0, 'message', 'style/global/bouton/', 'style/global/smilie/', '20', 'js/toolbar.js', 'cache/');
		return $b_result;
	}
	
	function uninstall()
	{
		$qry = "DROP TABLE `$this->tableBouton`";
		$drop = mysql_query($qry);
		if (!$drop)
			return false;
		$qry = "DROP TABLE`$this->tableSmilie`";
		$drop = mysql_query($qry);
		if (!$drop)
			return false;
		$b_result = unlink($this->cheminCache.$this->fichierConfig);
		return $b_result;
	}
	
	/**
	 * Affiche un formulaire de confirmation de suppression
	 *
	 * Permet de visualiser les éléments à supprimer et de les déselectioner
	 * @param array $a_supprimer ID des éléments à supprimer
	 * @param string $table Table SQL ou type de la suppression
	 * @param int $t=1 Tabindex
	 * @return string Chaine de caractères contenant le formulaire de suppression
	*/
	function confirmer_del($a_supprimer, $table,$t = 1)
	{
		$action = $_SERVER['PHP_SELF'];
		$formulaire = '<form action="'.$action.'" method="post" style="width:300px;margin:20px auto;text-align:center;">'."\n"
		.'<p>Confirmez-vous la suppression de '.(count($a_supprimer) < 2 ? 'cet élément' : 'ces éléments').' ?</p>'."\n"
		.'<div>'."\n"
			."\t".'<ul style="margin:10px 0 20px 0;padding:0;list-style-type:none;">'."\n";
			foreach($a_supprimer AS $k => $val) {
				$var = ' <label for="a_supprimer['.$k.']">'.$k.'</label>';
				$qry = 'SELECT name FROM '.$table.' WHERE id="'.$k.'"';
				$res = @mysql_query($qry);
				if ($res) {
					$var = mysql_fetch_array($res);
					if ($var['name'] == '')
						$var['name'] = 'espace';
					$var = ' <label for="a_supprimer['.$k.']">'.$var['name'].'</label><br />';
				}
				$formulaire .= "\t".'<li><input type="checkbox" name="a_supprimer['.$k.']" checked="checked" tabindex="'.$t++.'" />'.@$var.'</li>'."\n";
			}
			$formulaire .= "\t".'</ul>'."\n"
			."\t".'<input type="hidden" name="type" value="'.$table.'" />'."\n"
			."\t".'<input type="submit" name="oui" id="oui" value="Oui" tabindex="'.$t++.'" />'."\n"
			."\t".'<input type="submit" name="non" id="non" value="Non" tabindex="'.$t++.'" />'."\n"
		.'</div>'."\n"
		.'</form>';
		return $formulaire;
	}

	/**
	 * Supprime des éléments
	 *
	 * En fonction de la valeur de $table, cette fonction désinstalle la toolbar, ou supprime
	 * un fichier toolbar, ou supprime des boutons ou des smilies d'une table SQL
	 * @param array $a_supprimer ID des éléments à supprimer
	 * @param string $table Table SQL ou type des éléments à supprimer
	 * @return true si suppression réussie, false sinon
	*/
	function del($a_supprimer, $table)
	{
		$b_result = 1;
		// Désinstallation du module toolbar
		if ($table == 'uninstall') {
			return $this->uninstall();
		}
		// Suppression de fichiers (ex : toolbar)
		elseif ($table == 'toolbar') {
			foreach($a_supprimer AS $k => $val) {
				if ($val && is_file($k)) {
					$b_suppression = unlink($k);
					if (!$b_suppression)
						$b_result *= 0;
				}
			}
		}
		// Suppression de tuples (ex : boutons, smilies)
		else {
			foreach($a_supprimer AS $k => $val) {
				if ($val) {
					$qry = 'DELETE FROM '.$table.' WHERE id="'.$this->protectI($k).'"';
					$b_suppression = mysql_query($qry);
					if (!$b_suppression)
						$b_result *= 0;
				}
			}
		}
		return $b_result;
	}
	
	/**
	 * Retourne l'aide demandée
	 *
	 * Les aides sont séparées par des pipes (|), cette fonction ne fait que les séparer
	 * @param string $aide Chaine de caractères d'aide à étudier
	 * @param int $i=1 Numéro de l'aide à retourner
	 * @param string L'aide n°$i
	*/
	function get_aide($aide, $i=1)
	{
		$aide = stripslashes($aide);
		$aide = str_replace('\\\\', ' backslash ', $aide);
		$aide = str_replace('\|', ' pipe ', $aide);
		$a_aide = explode('|', $aide);
		$aide = str_replace(' backslash ', '\\', @$a_aide[($i-1)]);
		$aide = str_replace(' pipe ', '|', $aide);
		return $aide;
	}
	
	/**
	 * Génére la toolbar contenant les boutons et les smilies activés au moment de sa génération
	 *
	 * @param string $name Nom du fichier toolbar (complété automatiquement si besoin)
	 * @param string $idTextarea ID du textarea sur lequel agit la toolbar (rempli avec la valeur par défaut si vide)
	 * @param string $cheminImgBouton Chemin vers le dossier contenant les images des boutons (rempli avec la valeur par défaut si vide)
	 * @param string $cheminImgSmilie Chemin vers le dossier contenant les images des smilies (rempli avec la valeur par défaut si vide)
	 * @param int $nbSmilie Nombre de smilies à afficher avant d'afficher un lien "Plus de smilies" (rempli avec la valeur par défaut si vide)
	 * @param string $fichieJs Fichier JS à utiliser dans le dossier JS (rempli avec la valeur par défaut si vide)
	 * @param string $msg Message à renvoyer suite à la génération
	 * @return true si le fichier a bien été généré, false sinon
	*/
	function generer_toolbar($name, $idTextarea='', $cheminImgBouton='', $cheminImgSmilie='', $nbSmilie='', $fichierJS, $msg='')
	{
		// MAJ de la configuration si elle n'est pas donnée
		if (preg_match('!^'.$this->prefixe.'!', $name) || preg_match('!\.php$!', $name))
		{
			if (preg_match('!^'.$this->prefixe.'!', $name) && !preg_match('!\.php$!', $name))
				$name = $name.'.php';
			elseif (!preg_match('!^'.$this->prefixe.'!', $name) && preg_match('!\.php$!', $name))
				$name = $this->prefixe.$name;
		}
		else
			$name = $this->prefixe.$name.'.php';
		if ($name == $this->fichierConfig) // Une toolbar ne peut s'appeler config
			return false;
		$idTextarea = $idTextarea == '' ? $this->idTextarea : $idTextarea;
		$cheminImgBouton = $cheminImgBouton == '' ? $this->cheminImgBouton : $cheminImgBouton;
		$cheminImgSmilie = $cheminImgSmilie == '' ? $this->cheminImgSmilie : $cheminImgSmilie;
		$nbSmilie = $nbSmilie == '' ? $this->nbSmilie : $nbSmilie;
		$fichierJS = $fichierJS == '' ? $this->fichierJS : $fichierJS;
		
		// Création du texte du fichier de cache
		$output = '<?php if (Toolbar::get_etat()) { ?>'."\n".
		'<script type="text/javascript" src="'.$fichierJS.'"></script>'."\n".
		'<noscript><p><strong>Vous pouvez activer le Javascript pour faciliter l\'écriture de vos messages.</strong></p></noscript>'."\n".
		'<script type="text/javascript">'."\n".
		'if (document.getElementById) {'."\n".
			"\t".'var tb = new toolbar(document.getElementById(\''.$idTextarea.'\'), \''.$idTextarea.'\', \''.$cheminImgBouton.'\', \''.$cheminImgSmilie.'\', \''.$this->cheminCache.'\', \''.$name.'\');'."\n";

		$result = mysql_query('SELECT * FROM '.$this->tableBouton.' ORDER BY ordre');
		if (mysql_num_rows($result) == 0)
		{
			$msg = 'La base de données de la toolbar n\'est pas installée.';
			return false;
		}
		while ($bouton = mysql_fetch_array($result)) {
			/*
			* Bon à savoir
			* $bouton['type'] == 1 ->Standard
			* $bouton['type'] == 2 ->JS : 1 fenêtre
			* $bouton['type'] == 3 ->JS : 2 fenêtres
			* $bouton['type'] == 4 ->PopUp
			* $bouton['type'] == 5 ->Barre
			* $bouton['type'] == 6 ->Espace
			*/
				
			// Construction des barres
			// Bouton standart (ex : b, i)
			if($bouton['etat'] AND $bouton['type'] == 1) {
				$aide = $this->protectS($this->get_aide($bouton['aide']));
				$output .= "\t".'tb.bt("'.$bouton['name'].'", "'.$aide.'");'."\n";
			}
			// Bouton JS 1 ou 2 fenêtre (ex : img, lien)
			if($bouton['etat'] AND ($bouton['type'] == 3 OR $bouton['type'] == 2)) {
				$aide = $this->protectS($this->get_aide($bouton['aide']));
				$js1 = $this->protectS($this->get_aide($bouton['aide'], 2));
				$js2 = $this->protectS($this->get_aide($bouton['aide'], 3));
				$output .= "\t".'tb.btAide("'.$bouton['name'].'", "'.@$aide.'", "'.@$js1.'", "'.@$js2.'");'."\n";
			}
			// Bouton PopUp (ex : color, upload)
			if($bouton['etat'] AND $bouton['type'] == 4) {
				$aide = $this->protectS($this->get_aide($bouton['aide']));
				$js1 = $this->protectS($this->get_aide($bouton['aide'], 2));
				$js2 = $this->protectS($this->get_aide($bouton['aide'], 3));
				if($bouton['name'] == 'upload') {
					$i_width = $this->uploadWidth;
					$i_height = $this->uploadHeight;
				}
				else {
					$i_width = $this->colorWidth;
					$i_height = $this->colorHeight;
				}
				$output .= "\t".'tb.btPopUp("'.$bouton['name'].'", "'.$aide.'", "'.$i_width.'", "'.$i_height.'");'."\n";
			}
			// Bouton Barre (ex : smilie, math)
			if($bouton['etat'] AND $bouton['type'] == 5) {
				$aide = $this->protectS($this->get_aide($bouton['aide']));
				$js1 = $this->protectS($this->get_aide($bouton['aide'], 2));
				$js2 = $this->protectS($this->get_aide($bouton['aide'], 3));
				// Barre Smilies
				if($bouton['name'] == 'smilies') {
					$a_smilie = $this->generer_smilie($name, $msg);
					if (!$a_smilie) {
						return false;
					}
					$a_smilieSymbole = $a_smilie[0];
					$a_smilieImg = $a_smilie[1];
					$n = count($a_smilieSymbole);
					$smiley_dups = array();
					$s_smiliesTxt = $s_smiliesImg = '';
					for ($i=0; $i<$n; $i++) {
						$a_smilieSymbole[$i] = stripslashes(stripslashes($a_smilieSymbole[$i]));
						$a_smilieSymbole[$i] = str_replace('\\\\', ' backslash ', $a_smilieSymbole[$i]);
						$a_smilieSymbole[$i] = str_replace('\|', ' pipe ', $a_smilieSymbole[$i]);
						if (preg_match('!|!', $a_smilieSymbole[$i])) { 
							$a_smilieSymbole[$i] = preg_replace('!^([^|]+)|.*$!', '$1', $a_smilieSymbole[$i]);
						}
						$a_smilieSymbole[$i] = str_replace(' backslash ', '\\\\\\\\' , $a_smilieSymbole[$i]);
						$a_smilieSymbole[$i] = str_replace(' pipe ', '|', $a_smilieSymbole[$i]);
						$more_smilies = false;
						if($i < $nbSmilie) {
							$s_smiliesTxt .= '\''.stripslashes($a_smilieSymbole[$i]).'\', ';
							$s_smiliesImg .= '\''.$a_smilieImg[$i].'\', ';
						} 
						else {
							$i_width = $this->smilieWidth;
							$i_height = $this->smilieHeight;
							$s_txt = 'Plus de smilies';
							break;
						}
					}
					$output .= "\t".'var smiliesTxt = new Array('.substr($s_smiliesTxt, 0, -2).');'."\n".
							"\t".'var smiliesImg = new Array('.substr($s_smiliesImg, 0, -2).');'."\n";
				}
				// Barre Math
				elseif($bouton['name'] == 'maths') {
					$a_mathsTxt = array('\frac{a}{b}', '\times', '\forall', '\exists', '\in', '\subset', '\geq', '\leq', '\neq', '\mathbb{R}', '\sqrt{x}', '\alpha', '\beta', '\gamma', '\delta', '\epsilon', '\eta', '\theta', '\lambda', '\mu', '\xi', '\pi', '\rho', '\sigma', '\tau', '\phi', '\varphi', '\chi', '\omega', ' \,\, \Longleftrightarrow \,\, ', ' \,\, \Longrightarrow \,\, ', ' \,\, \Longleftarrow \,\, ', '\int_{}^{} \mathrm{d} ', '\sum_{}^{}', '\prod_{}^{}', '\lim_{a \rightarrow b}');
					$s_mathsTxt = $s_mathsImg = '';
					for ($i=0; $i<count($a_mathsTxt); ++$i) {
						$s_mathsTxt .= '\''.addslashes($a_mathsTxt[$i]).'\', ';
						$s_mathsImg .= '\'cgi-bin/latex.cgi?'.addslashes(str_replace(' \,\, ', '', str_replace(' \mathrm{d} ', '', $a_mathsTxt[$i]))).'\', ';
					}
					$output .= "\t".'var mathsTxt = new Array('.substr($s_mathsTxt, 0, -2).');'."\n".
							"\t".'var mathsImg = new Array('.substr($s_mathsImg, 0, -2).');'."\n";
					$i_width = '800';
					$i_height = '600';
					$s_txt = 'Tutoriel Latex';
				}
				$output .= "\t".'tb.btBarre("'.$bouton['name'].'", "'.$aide.'");'."\n"."\t".'tb.creerBarre("'.$bouton['name'].'", '.$bouton['name'].'Txt, '.$bouton['name'].'Img);'."\n";
				if(isset($s_txt))
					$output .= "\t".'tb.moreInfo("'.$bouton['name'].'", "'.$i_width.'", "'.$i_height.'", "'.$s_txt.'");'."\n";
			}
			// Bouton espace
			if($bouton['etat'] AND $bouton['type'] == 6) {
				$output .= "\t".'tb.addSpace(10);'."\n";
			}
		}
		$output .= "\t".'tb.draw();'."\n".
		'}'."\n".'</script>'."\n".
		'<?php } ?>'."\n";
		// Création du fichier de cache
		return $this->generer_cache($name, $output, $msg);
	}
	
	/**
	 * Génére le cache des smilies
	 *
	 * Ce cache est utile lors de l'affichage du pop up des smilies
	 * @param string $name Nom du fichier à générer (Ex. : tlbr_exemple.php -> smilie_tlbr_exemple.php)
	 * @param string $msg Message d'erreur éventuel
	 * @post Génére un fichier dans le dossier cache contenant l'array des noms des smilies et l'array des symboles associés
	 * @return array Un array contenant l'array des noms des smilies et l'array des symbôles associés
	*/
	function generer_smilie($name, $msg='')
	{
		$a_smilieSymbole = array();
		$a_smilieImg = array();
		
		$result = mysql_query('SELECT name AS image, symbole AS symbole FROM '.$this->tableSmilie.' ORDER BY ordre');
		if (mysql_num_rows($result) == 0)
		{
			$msg = 'La base de données des smilies n\'est pas installée.';
			return false;
		}
		// Création des array
		while ($db_smilies = mysql_fetch_assoc($result)) {
			$a_smilieSymbole[] = $this->protectS($db_smilies['symbole']);
			$a_smilieImg[] = $this->protectS($db_smilies['image']);
		}
		
		// Création du contenu du fichier de cache
		$str = '<?php'."\n".'$a_smilieSymbole = array('."\n";
		foreach ($a_smilieSymbole AS $val) {
			$str .= "'".$val."',"."\n";
		}
		$str .= ');'."\n\n".'$a_smilieImg = array('."\n";
		foreach ($a_smilieImg AS $val)
			$str .= "'".$val."',"."\n";
		$str .= ');'."\n".'?>';
		
		// Génération du cache
		$res = $this->generer_cache('smilies_'.$name, $str, $msg);
		if (!$res)
		{
			return false;
		}
		return array($a_smilieSymbole, $a_smilieImg);
	}
	
	/**
	 * Génére un fichier de cache à partir d'un contenu
	 *
	 * Le fichier est généré dans le dossier de cache
	 * @param string $fichier Nom du fichier à générer
	 * @param string $contenu Contenu du fichier à générer
	 * @param string $msg Message d'erreur à afficher éventuellement
	 * @return boolean True si la génération s'est bien déroulée, false sinon
	*/
	function generer_cache($fichier, $contenu, $msg='')
	{
		$fd = fopen('cache/'.$fichier, 'wb');
		if (!$fd)
		{
			$msg = "Impossible de créer le fichier $fichier dans le répertoire 'cache/'. Veuillez vous assurer que PHP a accès en écriture au répertoire 'cache/'";
			return false;
		}
		$res = fwrite($fd, $contenu);
		if (!$res)
		{
			$msg = "Impossible d'écrire le contenu du fichier $fichier dans le répertoire 'cache/'. Veuillez vous assurer que PHP a accès en écriture au répertoire 'cache/'";
			return false;
		}
		fclose($fd);
		$msg = "Le fichier $fichier a été créé avec succès dans le répertoire 'cache/'.";
		return true;
	}
	
	function protectS($string)
	{
		$string = mysql_real_escape_string(trim($string));
		return $string;
	}

	function protectI($int)
	{
		$int = intval(mysql_real_escape_string($int));
		return $int;
	}
}

?>