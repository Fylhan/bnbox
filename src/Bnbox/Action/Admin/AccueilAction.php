<?php
include(INCLUDE_PATH . '/action/Action.abstract.php');
include(INCLUDE_PATH . '/data/accessor/ActualiteDAOImpl.class.php');
include(INCLUDE_PATH . '/data/Actualite.class.php');

class AccueilAction extends Action {
	public function launch($params = array()) {
		$request = $this->_controller->getRequest();
		$actualiteModel = new ActualiteDAOImpl();
		$params['layout'] = 'index';

		// --- Actions
		// -- News
		// - Si on a une demande de suppression
		if (NULL != ($id = $request->getParam('delete','int'))) {
			if ($actualiteModel->deleteActualite($id)) {
				// Cache obsolete
				$this->resetCache();
				// Send response
				$message = new Message('La news ' . $id . ' a été supprimée. Bien joué !', OK);
				$this->_controller->getResponse()->setFlash($message->toString());
				$this->_controller->redirect('administration.html');
			}
			else {
				$message = new Message('Arg, ça ne marche pas. La news ' . $id . ' n\'a pas été supprimée.', ERREUR);
				$this->_controller->getResponse()->addVar('Message', $message->toString());
			}
		}
		// - Si on update
		if ($request->isParam('sendNews')) {
			// Update
			$actualite = new Actualite();
			$actualite->fill($request->getParams());
			$actualite = $actualiteModel->updateActualite($actualite);
			$params['Actualite'] = $actualite;
			// Cache obsolete
			$this->resetCache();
			// Update parameter LastModificationActualites
			if (false != ($data = file_get_contents(ParameterFilePath))) {
				$toAdd = 'define(\'LastModificationActualites\', \'' . $actualite->getDateModif()->getTimestamp() . '\');';
				if (preg_match('!define\(\'LastModificationActualites\', \'[0-9]*\'\);!i', $data)) {
					$data = preg_replace('!define\(\'LastModificationActualites\', \'[0-9]*\'\);!i', $toAdd, $data);
				}
				else {
					$data .= $toAdd;
				}
				file_put_contents(ParameterFilePath, $data);
			}
			// Send response
			$message = new Message('Voilà une news ajoutée avec succès, bien joué !', OK);
			$this->_controller->getResponse()->setFlash($message->toString());
			$this->_controller->redirect('administration.html?update=' . $actualite->getId() . '#administrationPage');
		}
		// -- Parameters
		if ($request->isParam('sendData')) {
			// Generate content
			$data = '<?php' . "\n";
			if (null != $_POST['nbParPage'] && 0 != $_POST['nbParPage']) {
				$data .= 'define(\'NbParPage\', ' . parserI($_POST['nbParPage']) . ');' . "\n";
			}
			if (null != $_POST['nbParPageAdmin'] && 0 != $_POST['nbParPageAdmin']) {
				$data .= 'define(\'NbParPageAdmin\', ' . parserI($_POST['nbParPageAdmin']) . ');' . "\n";
			}
			$data .= 'define(\'DisplayHelp\', ' . parserI(@$_POST['displayHelp']) . ');' . "\n";
			if (null != $_POST['emailAdmin'] && NULL != $_POST['emailAdmin']) {
				$data .= 'define(\'EmailAdmin\', \'' . parserS($_POST['emailAdmin']) . '\');' . "\n";
			}
			$data .= 'define(\'LastModificationActualites\', \'' . LastModificationActualites . '\');' . "\n";
			$data .= "\n";

			// Open and update the data-user file
			if (false != ($fp = fopen(ParameterFilePath, 'w+')) && fwrite($fp, $data)) {
				fclose($fp);
				$message = new Message('Paramètres bien enregistrés, super !', OK);
				$this->_controller->getResponse()->setFlash($message->toString());
				$this->_controller->redirect('administration.html');
			}
			// Error
			else {
				$message = new Message(
						'Arg, impossible de mettre à jour les paramètres. Désolé, mais il va falloir en parler avec 
un administrateur.', ERREUR);
				$this->_controller->getResponse()->addVar('Message', $message->toString());
			}
		}
		// -- Purge Cache
		if ($request->isParam('cache', 'purge')) {
			$this->resetCache(true);
			$message = new Message('Cache supprimé ! On refait une partie de cache-cache ?', OK);
			$this->_controller->getResponse()->setFlash($message->toString());
		}
		
		// --- Display Actions
		// - Si on a une demande de modification
		if (NULL !== ($id = $request->getParam('update','int'))) {
			$params['layout'] = 'update';
			// Creation
			if (0 == $id) {
				$actualite = new Actualite();
			}
			else {
				$actualite = $actualiteModel->findActualiteById($id);
			}
			if (null != $actualite) {
				$params['Actualite'] = $actualite;
			}
			else {
				$message = new Message('La news ' . $id . ' n\'existe pas. C\'est un problème ?', NEUTRE);
				$this->_controller->getResponse()->setFlash($message->toString());
				$this->_controller->redirect('administration.html');
			}
		}
		// - Sinon liste des actualités
		else {
			// Récupération des éléments
			$page = calculPage();
			$this->_controller->getResponse()->addVar('page', $page);
			$nbElement = $actualiteModel->calculNbActualites(true);
			$nbPage = calculNbPage(NbParPageAdmin, $nbElement);
			$appellationElement = 'actualité';
			$actualites = $actualiteModel->findActualites($page, true, NbParPageAdmin);
			$params['page'] = $page;
			$params['nbElement'] = $nbElement;
			$params['nbMaxLienPagination'] = NbMaxLienPagination;
			$params['nbPage'] = $nbPage;
			$params['appellationElement'] = $appellationElement;
			$params['Actualites'] = $actualites;
		}
		$this->render($params);
	}

	public function render($params = array()) {
		// -- Prepare Meta Data
		$this->_controller->getResponse()->addVar('metaTitle', 'Administration');

		// -- Create params
		$tplPparams = array('UrlCourant' => 'administration.html', 'NbParPage' => NbParPage, 'NbParPageAdmin' => NbParPageAdmin,
				'DisplayHelp' => DisplayHelp, 'UrlEmailAdmin' => getUrlImgEmail(EmailAdmin), 'EmailAdmin' => EmailAdmin,);
		$tplPparams = array_merge($tplPparams, $params);

		// -- Fill the body and print the page
		$this->_controller->render('administration/layout-' . $params['layout'] . '.tpl', $tplPparams);
		$this->printOut();
	}
	
	private function resetCache($all=false) {
		CacheManager::purgeCache('api');
		CacheManager::purgeCache('accueil');
		if ($all) {
			CacheManager::purgeCache('contact');
			CacheManager::purgeCache('default');
			CacheManager::purgeCache('twig');
			CacheManager::purgeCache('email');
		}
	}
}
?>
