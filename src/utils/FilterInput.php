<?php

namespace App\utils;

/**
 * Reposnsável por remover caracteres nocivos em strings
 * @author Jorge Lucas
 */
trait FilterInput
{
	/**
	 * Realiza a remoção e retorna o texto mais seguro
	 * @param string $text
	 * @return string
	 */
	public static function cleanInput($text) {
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