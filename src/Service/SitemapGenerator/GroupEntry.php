<?php

include('HumanUrlEntry.class.php');

class GroupEntry extends HumanUrlEntry {
	private $children;
	
	public function __construct($title, $loc='', $priority=0.5) {
		parent::__construct($title, $loc, '', '', $priority);
		$this->children = array();
	}
	
	
	public function getChildren() { return $this->children; }
	public function setChildren($children) { $this->children = $children; }
	public function addChild($child) { $this->children[] = $child; }
}