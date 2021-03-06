<?php

namespace App\database;

use App\utils\Logger;

/**
 * Classe gerenciadora de iterações com o sgbd
 * @author Jorge Lucas
 * @date 24/09/16
 */
final class Transaction
{
	private static $conn;
	private static $logger;
	
	/**
	 * Inpede a instanciação da classe
	 */
	private function __construct() {}
	
	/**
	 * Abre uma transação e uma conexão com o sgbd
	 * @param string $database = nome do bd
	 */
	public static function open($database) {
		
		//se não há conexão
		if(empty(self::$conn)) {
			self::$conn = Connection::open($database);
			self::$conn->beginTransaction();
			self::$logger = null;
		}
	}
	
	/**
	 * Retorna a conexão ativa do sbgd
	 */
	public static function get() {
		
		//retorna a conexão ativa;
		return self::$conn;
	}
	
	/**
	 * Desfaz as operações realizadas
	 */
	public static function rollback() {
		
		if(self::$conn) {
			self::$conn->rollBack();
			self::$conn = null;
		}
	}
	
	/**
	 * Aplica as alterações realizadas e fecha a conexão
	 */
	public static function close() {
		
		if(self::$conn) {
			self::$conn->commit();
			self::$conn = null;
		}
	}
}