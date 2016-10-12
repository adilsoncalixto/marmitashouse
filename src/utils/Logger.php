<?php

namespace App\utils;

/**
 * Classe geradora de logs de eventos
 * @author lucas
 * @date 24/09/2016
 */
class Logger
{
	/**
	 * @var $filename nome do arquivo do log
	 * @var $basedir diretório padrão do arquivo
	 */
	private $filename;
	private $basedir = "src/resources/logs/";
	
	/**
	 * Define o nome do arquivo com base no parâmetro e na data atual
	 * @param string $filename nome do arquivo do log
	 */
	public function open(string $filename) {
		
		date_default_timezone_set('America/Sao_Paulo');
		$time = date("Y-m-d");
		$this->filename = $filename . '_' . $time . '.txt';
	}
	
	/**
	 * Define o conteúdo da mensagem que será gravada no log
	 * @param string $message mensagem para ser gravado no log
	 */
	public function write(string $message) {
		
		date_default_timezone_set('America/Sao_Paulo');
		$time = date("Y-m-d H:i:s");
		$text = "$time :: $message\n";
		$handler = fopen($this->basedir . $this->filename, 'a+');
		fwrite($handler, $text);
		fclose($handler);	
	}
}