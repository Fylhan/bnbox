<?php
require_once(INCLUDE_PATH.'/action/Action.abstract.php');
require_once(INCLUDE_PATH.'/service/ContactService.class.php');
require_once(INCLUDE_PATH.'/data/EmailData.class.php');
require_once(INCLUDE_PATH.'/exception/Message.class.php');

class ContactAction extends Action {
	public function launch($params=array()) {
		$request = $this->_controller->getRequest();
		// Si on a une demande d'envoi d'email
		if ($request->isParam('sendEmail')) {
			$contactService = new ContactService();
			$emailData = new EmailData($request->getParams());
			try {
				// Email valide
				if ($emailData->isValid()) {
					// Email envoyé
					if ($contactService->envoyerEmail($emailData)) {
						$message = new Message('Votre message a été envoyé avec succès, merci !', OK);
						$this->_controller->getResponse()->setFlash($message->toString());
						$this->_controller->redirect('contact.html');
					}
				}
				$emailData->prepareToPrint();
			}
			// Problèmes
			catch(Exception $e) {
				$emailData->prepareToPrintForm();
				$message = new Message($e->getMessage(), ERREUR);
				logThatException($e);
				$this->_controller->getResponse()->addVar('Message', $message->toString());
			}
		}
		$this->render(array('emailData' => @$emailData));
	}
	
	public function render($params=array()) {
		// -- Prepare Meta Data
		$this->_controller->getResponse()->addVar('metaTitle', 'Nous contacter, nous trouver');
		$this->_controller->getResponse()->addVar('metaDesc', 'Si vous avez une question à propos du CEP Saint-Maur ou que vous désirez en savoir plus : n\'hésitez pas à nous rendre visite ou à nous contacter par email !');
		$this->_controller->getResponse()->addVar('metaKw', 'contact, plan, email');
		
		// -- Create params
		$tplPparams = array(
			'urlCourant' => getUrlCourant('contact.html'),
			'EmailContact' => EmailContact,
			'UrlImgEmailContact' => getUrlImgEmail(EmailContact),
			'EmailFlambeaux' => EmailFlambeaux,
			'UrlImgEmailFlambeaux' => getUrlImgEmail(EmailFlambeaux),
			'EmailGDJ' => EmailGDJ,
			'UrlImgEmailGDJ' => getUrlImgEmail(EmailGDJ),
			'email' => @$params['emailData']
		);
		
		// -- Fill the body and print the page
		$this->_controller->render('contact/layout-contact.tpl', $tplPparams);
		$this->printOut();
	}
}
?>
