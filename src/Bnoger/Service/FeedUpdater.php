<?php

include(INCLUDE_PATH . '/lib/FeedWriter/FeedTypes.php');
include_once(INCLUDE_PATH . '/service/CacheManager.class.php');

class FeedUpdater {
	private $actualiteModel;
	
	public function __construct(ActualiteDAO $actualiteModel) {
		$this->actualiteModel = $actualiteModel;
	}
	
	public function updateFeed($items, $excerpt, $feedType=RSS2) {
		$feedTypes = array(RSS1 => 'rss1', RSS2 => 'rss2', ATOM => 'atom');

		// - Configure Feed
		$feed = FeedWriterFactory::create($feedType);
		$feed->setTitle(SiteNom);
		$feed->setLink(SITE_PATH);
		$feed->setDescription(SiteDesc);
		//Image title and link must match with the 'title' and 'link' channel elements for RSS 2.0
		$feedImgUrl = ILLUSTRATION_PATH . '/cepsaintmaur.png';
		$feed->setImage(SiteNom, SITE_PATH, $feedImgUrl);
		$feed->setChannelElement('language', DefaultLocale);
		$feed->setChannelElement('copyright', SITE_PATH);
		$feed->setChannelElement('pubDate', date(DATE_RSS, time()));
		
		// - Add items
		foreach ($items AS $item) {
			$feed->addItem($item);
		}
		
		// - Generate feed
		$feedData = $feed->generateFeedAndRetrieve();
		return $feedData;
	}
	
	/**
	 * Update Actualite feed (for one type of feed)
	 * @param $complete Complete content or not (complete by default)
	 * @param string $feedType
	 */
	public function updateActualitesFeed($excerpt=false, $feedType=RSS2) {
		// Retrieve news
		$actualites = $this->actualiteModel->findAllActualites(NbItemPerFeed);
		$items = array();
		foreach ($actualites AS $actualite) {
			// Fill the item
			$items[] = $actualite->toFeedItem($excerpt, $feedType);
		}
		// Generate feed
		return $this->updateFeed($items, $excerpt, $feedType);
	}
	
	/**
	 * Update RSS1, 2 and ATOM Feeds for actualites
	 */
	public function updateAllActualitesFeed() {
		CacheManager::purgeCache('api');
		$this->updateActualitesFeed(false, RSS1);
		$this->updateActualitesFeed(false, RSS2);
		$this->updateActualitesFeed(false, ATOM);
	}
}
