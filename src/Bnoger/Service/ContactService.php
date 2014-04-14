<?php

class ContactService {
	/**
	 * Envoi un e-mail
	 * @param EmailData $emailData Données de l'email
	 * @return boolean True si l'envoi à réussi, false sinon
	 */
	public function envoyerEmail(EmailData $emailData) {
		// Erreur : pas de destinataires
		if (NULL == $emailData->getDestinataires() || count($emailData->getDestinataires()) <= 0) {
			throw new Exception('Pas de destinataires pour cet email. Réessayez.', ERREUR_BLOQUANT);
		}
		
		$emailData->prepareToPrint();
		
		$EOF = "\r\n";
		$CHARSET = Encodage;
		
		// -- Initialisation des variables
		$destinataires = $this->getListeEmail($emailData->getDestinataires(), ',');
		$boundary = "-----=".md5(rand());
		$objet = $emailData->getObjet();
		$message_brut = strip_tags(br2nl($emailData->getMessage()));
		$message_html = $emailData->getMessage();
		
		// -- Initialisation du header
		$header = 'From: '.$this->getListeEmail($emailData->getExpediteur()).$EOF;
		$header.= 'Reply-to: '.$this->getListeEmail($emailData->getExpediteur()).$EOF;
		$header.= 'MIME-Version: 1.0'.$EOF;
		$header.= 'Content-Type: multipart/alternative;'.$EOF.' boundary="'.$boundary.'"'.$EOF;
		$header.= 'X-Priority: 1';
		$header.= 'X-Mailer: PHP/'.phpversion();
		if (NULL != $emailData->getDestinatairesCc() && count($emailData->getDestinatairesCc()) > 0) {
			$header.= 'Bcc: '.$this->getListeEmail($emailData->getDestinatairesCc()).$EOF;
		}
		if (NULL != $emailData->getDestinatairesCci() && count($emailData->getDestinatairesCci()) > 0) {
			$header.= 'Bcci: '.$this->getListeEmail($emailData->getDestinatairesCci()).$EOF;
		}
		
		// -- Finalisation du message
		// Fin entete
		$message = $EOF.$boundary.$EOF;
		$message.= 'Content-Type: text/plain; charset="'.$CHARSET.'"'.$EOF;
		$message.= 'Content-Transfer-Encoding: 8bit'.$EOF;
		// Message brut
		$message.= $EOF.$message_brut.$EOF;
		$message.= $EOF.'--'.$boundary.$EOF;
		// Message HTML
		$message.= 'Content-Type: text/html; charset="'.$CHARSET.'"'.$EOF;
		$message.= 'Content-Transfer-Encoding: 8bit'.$EOF;
		$message.= $EOF.$message_html.$EOF;
		$message.= $EOF.'--'.$boundary.'--'.$EOF;
		$message.= $EOF.'--'.$boundary.'--'.$EOF;
	 	
		// -- Envoi de l'e-mail
		try {
			$bEnvoiEmail = @mail($destinataires, $objet, $message, $header);
			// Erreur : envoi e-mail
			if (!$bEnvoiEmail) {
				throw new Exception('Erreur lors de l\'envoi de l\'email. Veuillez nous contacter directement en attendant la correction du problème : <img src="'.getUrlImgEmail(EmailContact).'" alt="email" class="email" />.<br />Merci de votre compréhension !', ERREUR_BLOQUANT);
			}
		}
		catch(Exception $e) {
			throw new Exception('Erreur lors de l\'envoi de l\'email. Veuillez nous contacter directement en attendant la correction du problème : <img src="'.getUrlImgEmail(EmailContact).'" alt="email" class="email" />.<br />Merci de votre compréhension !', ERREUR_BLOQUANT, $e);
		}
		// Succès : envoi e-mail
		return true;
	}
	
	/**
	 * Renvoie une string de la liste des emails
	 * @param string|array $emails  email ou <email> ou nom <email>, ou array(email) ou array("nom" => email)
	 * @param string $separateur Séparateur. ';' par défaut
	 * return string Une chaine de caractère mettant les emails les uns à la suite des autres séparés par le séparateur
	 */
	private function getListeEmail($emails, $separateur=';')
	{
		// Chaine de caractère
		if (!is_array($emails)) {
			return $emails;
		}
		// Array de chaine de caractère
		if (array_key_exists(0, $emails)) {
			return implode($separateur, $emails);
		}
		// Array de couple nom => email
		$ret = '';
		foreach($emails AS $nom => $email) {
			if ('' == $ret) {
				$ret .= '"'.addslashes($nom).'" <'.$email.'>';
			} else {
				$ret .= ' '.$separateur.' "'.addslashes($nom).'" <'.$email.'>';
			}
		}
		return $ret;
	}

	/**
	 * Prépare des données à être affichées à l'écran
	 * @param array $data Données à modifier ($data[int]['cle'])
	 * @return array Les données modifiés
	*/
	public function emailToPrint($data, $decode=1)
	{
		if ($decode) {
			include_once(BNCODE_PATH.'/bncode.php');
		}
		if (isset($data) && count($data) != 0) {
			$data['nom'] = stripslashes(@$data['nom']);
			$data['email'] = stripslashes(@$data['email']);
			$data['objet'] = stripslashes(@$data['objet']);
			$data['message'] = stripslashes(@$data['message']);
			$data['antibot'] = stripslashes(@$data['antibot']);
			
			if ($decode) {
				$data['message'] = decoder($data['message']);
			}
		}
		return $data;
	}

	/**
	 * Prépare des données à être enregistrer dans une base de données
	 * @param array $data Données à modifier
	 * @return array Les données modifiés
	*/
	public function emailToSend($data, $decode=1)
	{
		if ($decode) {
			include_once(BNCODE_PATH.'/bncode.php');
		}
		if (isset($data) && count($data) != 0) {
			$data['nom'] = stripslashes(trim(@$data['nom']));
			$data['email'] = stripslashes(trim(@$data['email']));
			$data['objet'] = stripslashes(trim(@$data['objet']));
			$data['message'] = stripslashes(trim(@$data['message']));
			$data['antibot'] = parserI(@$data['antibot']);
					
			if ($decode) {
				$data['message'] = decoder($data['message']);
			}
		}
		return $data;
	}
}

?>