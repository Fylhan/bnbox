<?php
namespace Bnoger\Action\Filter;

interface Filterable {
	public function preFilter();
	public function postFilter();
}
