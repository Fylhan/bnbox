<?php
require_once(INCLUDE_PATH . '/action/Action.abstract.php');
include(INCLUDE_PATH . '/data/accessor/ActualiteDAOImpl.class.php');
include(INCLUDE_PATH . '/lib/SitemapGenerator/GroupEntry.class.php');

class SitemapAction extends Action {
	public function launch($params = array()) {
		// -- Sitemap type
		$params['type'] = $this->_controller->getRequest()->getParam('type');

		// -- List site pages
		$params['UrlEntries'][] = new HumanUrlEntry('Accueil - '.SiteNom, SITE_PATH, LastModificationActualites, ChangeFreqValues::WEEKLY, 0.8);
		$params['UrlEntries'][] = new HumanUrlEntry('Nous contacter', SITE_PATH . 'contact.html', LastModification, ChangeFreqValues::YEARLY, 0.6);
		$params['UrlEntries'][] = new HumanUrlEntry('Nous connaître', SITE_PATH . 'qui-sommes-nous.html', LastModification, ChangeFreqValues::YEARLY, 0.6);
		$params['UrlEntries'][] = new HumanUrlEntry('Nos activités', SITE_PATH . 'activites.html', LastModification, ChangeFreqValues::YEARLY, 0.6);
		$feedGroup = new GroupEntry('Rester informer');
		$feedGroup->addChild(new HumanUrlEntry('Flux RSS des dernières nouvelles', SITE_PATH . 'feed.xml', LastModificationActualites, ChangeFreqValues::WEEKLY));
		$feedGroup->addChild(new HumanUrlEntry('Flux RSS des dernières nouvelles (résumé seulement)', SITE_PATH . 'feed.xml?excerpt=1', LastModificationActualites, ChangeFreqValues::WEEKLY, 0.4));
		$feedGroup->addChild(new HumanUrlEntry('Flux ATOM des dernières nouvelles', SITE_PATH . 'feed.xml?feed=atom', LastModificationActualites, ChangeFreqValues::WEEKLY));
		$feedGroup->addChild(new HumanUrlEntry('Flux ATOM des dernières nouvelles (résumé seulement)', SITE_PATH . 'feed.xml?feed=atom&excerpt=1', LastModificationActualites, ChangeFreqValues::WEEKLY, 0.4));
		$params['UrlEntries'][] = $feedGroup;
		$params['UrlEntries'][] = new HumanUrlEntry('Politique d\'accessibilité', SITE_PATH . 'politique-accessibilite.html', LastModification, ChangeFreqValues::YEARLY, 0.3);
		$params['UrlEntries'][] = new HumanUrlEntry('Plan du site', SITE_PATH . 'sitemap.html', LastModificationActualites, ChangeFreqValues::WEEKLY, 0.3);
		// Actualites
		$actualiteModel = new ActualiteDAOImpl();
		$nbOfActualites = 50;
		$actualites = $actualiteModel->findAllActualites($nbOfActualites);
		$actualiteGroup = new GroupEntry('Dernières nouvelles', SITE_PATH . 'evenements.html');
		foreach ($actualites AS $actualite) {
			$entry = new HumanUrlEntry($actualite->getTitre(), SITE_PATH . 'evenement-' . $actualite->getId() . '.html', $actualite->getDateModif()->getTimestamp(),
					ChangeFreqValues::YEARLY);
			$entry->setDescription($actualite->getDateDebutString());
			$actualiteGroup->addChild($entry);
		}
		if ($nbOfActualites < $actualiteModel->calculNbActualites()) {
			$actualiteGroup->addChild(new HumanUrlEntry('Et d\'autres encore...', SITE_PATH . 'evenements.html'));
		}
		$params['UrlEntries'][] = $actualiteGroup;
		
		usort($params['UrlEntries'], function($a, $b) {
				return $a->getPriority() < $b->getPriority();
			});

		// -- Fill the View
		$this->render($params);
	}

	public function render($params = array()) {
		// -- Prepare parameters
		$tplParams = array('UrlEntries' => $params['UrlEntries']);

		// -- Fill the body and print the page
		if ('human' == $params['type']) {
			$tpl = 'sitemap-human.twig';
		}
		else {
			$this->_controller->getResponse()->addHeader("Content-Type", "application/xml");
			$tpl = 'sitemap.twig';
		}
		$this->_controller->render('api/' . $tpl, $tplParams);
		$this->printOut();
	}
}
?>
