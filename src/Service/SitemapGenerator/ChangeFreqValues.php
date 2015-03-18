<?php

class ChangeFreqValues {
	const ALWAYS = 'always';
	const HOURLY = 'hourly';
	const DAILY = 'daily';
	const WEEKLY = 'weekly';
	const MONTHLY = 'monthly';
	const YEARLY = 'yearly';
	const NEVER = 'never';
	
	public static function isChangeFreqValue($val) {
		return (ChangeFreqValues::ALWAYS == $val
				|| ChangeFreqValues::HOURLY == $val
				|| ChangeFreqValues::DAILY == $val
				|| ChangeFreqValues::WEEKLY == $val
				|| ChangeFreqValues::MONTHLY == $val
				|| ChangeFreqValues::YEARLY == $val
				|| ChangeFreqValues::NEVER == $val
				);
	}
}