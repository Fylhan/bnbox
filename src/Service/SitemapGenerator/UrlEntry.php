<?php

include('ChangeFreqValues.enum.php');

class UrlEntry {
	private $loc;
	private $lastmod;
	private $changefreq;
	private $priority;
	
	public function __construct($loc, $lastmod='', $changefreq='', $priority=0.5) {
		$this->loc = $loc;
		$this->lastmod = $lastmod;
		$this->changefreq = (ChangeFreqValues::isChangeFreqValue($changefreq) ? $changefreq : '');
		$priority = parserF($priority);
		$this->priority = ($priority >= 0 && $priority <= 1 ? $priority : 0.5);
	}
	
	public function getLoc() { return $this->loc; }
	public function setLoc($loc) { $this->loc = $loc; }
	
	public function getLastmod() { return $this->lastmod; }
	public function setLastmod($lastmod) { $this->lastmod = $lastmod; }
	
	public function getChangefreq() { return $this->changefreq; }
	public function setChangefreq($changefreq) { $this->changefreq = $changefreq; }
	
	public function getPriority() { return $this->priority; }
	public function setPriority($priority) { $this->priority = $priority; }
}