<?php
require_once(INCLUDE_PATH . '/action/Action.abstract.php');
include(INCLUDE_PATH . '/data/accessor/ActualiteDAOImpl.class.php');
include(INCLUDE_PATH . '/service/FeedUpdater.class.php');

class FeedAction extends Action {
	public function launch($params = NULL) {
		// -- Retrieve feed parameters
		$params['feedType'] = RSS2;
		$feedTypes = array(RSS1 => 'rss1', RSS2 => 'rss2', ATOM => 'atom');
		if (isset($_GET['feed']) && NULL != $_GET['feed'] && "" != $_GET['feed'] && in_array($_GET['feed'], $feedTypes)) {
			$params['feedType'] = array_search($_GET['feed'], $feedTypes);
		}
		$excerpt = parserI(@$_GET['excerpt']);

		// -- Generate the Feed
		$actualiteModel = new ActualiteDAOImpl();
		$feedUpdater = new FeedUpdater($actualiteModel);
		$params['feedData'] = $feedUpdater->updateActualitesFeed($excerpt, $params['feedType']);

		// -- Fill the View
		$this->render($params);
	}

	public function render($params = NULL) {
		// Header
		$contentType = "application/rss+xml";
		if (RSS1 == $params['feedType']) {
			$contentType = "application/rdf+xml";
		}
		else if (ATOM == $params['feedType']) {
			$contentType = "application/atom+xml";
		}
		header("Content-Type: " . $contentType);
		// Feed
		echo $params['feedData'];
		$this->printOut();
	}
}
?>
