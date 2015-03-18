<?php

include('UrlEntry.class.php');

class HumanUrlEntry extends UrlEntry {
	private $title;
	private $description;

	public function __construct($title, $loc, $lastmod='', $changefreq='', $priority=0.5) {
		parent::__construct($loc, $lastmod, $changefreq, $priority);
		$this->title = $title;
		$this->description = "";
	}
	
	
	public function getTitle() { return $this->title; }
	public function setTitle($title) { $this->title = $title; }
	
	public function getDescription() { return $this->description; }
	public function setDescription($description) { $this->description = $description; }
}