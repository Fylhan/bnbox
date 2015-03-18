<?php
require_once(INCLUDE_PATH.'/data/EmailData.class.php');
require_once('ContactService.class.php');

class ContactServiceImpl extends ContactService {

	public function _construct($params=array()) {
		parent::__construct($params);
	}
	
	/**
	* Envoi un e-mail à Olivier et Céline
	* @param EmailData $emailData Infos sur le message et son expéditeur
	* @param string $urlRetour Url de retour (défault : 'contact.html')
	* @return boolean True si l'envoi à réussi, false sinon
	*/
	public function envoyerEmailOlivierEtCeline(EmailData $emailData) {
		$emailData->addDestinataire(Email, 'Olivier & Céline');
		return $this->envoyerEmail($emailData);
	}
}