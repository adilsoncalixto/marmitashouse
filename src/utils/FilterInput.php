<?php

namespace App\utils;

trait FilterInput
{
	static function cleanInput($text) {
		$text = str_replace(';', '', $text);
		$text = str_replace('*', '', $text);
		$text = str_replace('-', '', $text);
		$text = str_replace('"', '', $text);
		$text = str_replace('>', '', $text);
		$text = str_replace('<', '', $text);
		$text = str_replace('%', '', $text);
		$text = str_replace('#', '', $text);
		return $text;
	}
}