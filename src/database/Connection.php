<?php

namespace App\database;

use Exception;
use PDO;

/**
 * Classe para definição de parâmetros de conexão com o sgbd
 * @author Jorge Lucas
 * @date 24/09/16
 */
class Connection
{	
	/**
	 * Impossibilita a instância da classe
	 */
	private function __construct() {}
	
	/**
	 * Recebe o nome do conector (SGBD) e instancia o objeto PDO
	 * @param string $name nome do SGBD
	 */
	public static function open(string $name) {
		
		if(file_exists("src/config/{$name}.ini")) {
			
			//converte o arquivo *.ini em uma array
			$sgbd = parse_ini_file("src/config/{$name}.ini");
		} else {
			//se não existir, lança um erro
			throw new Exception("Arquivo '{$name}' não encontrado.");
		}
		
		//lê as informações e as armazena
		$type = isset($sgbd['type']) ? $sgbd['type'] : null;
		$host = isset($sgbd['host']) ? $sgbd['host'] : null;
		$port = isset($sgbd['port']) ? $sgbd['port'] : null;
		$username = isset($sgbd['username']) ? $sgbd['username'] : null;
		$password = isset($sgbd['password']) ? $sgbd['password'] : null;
		$dbname = isset($sgbd['dbname']) ? $sgbd['dbname'] : null;
		
		//determina o tipo (driver) do sgbd a ser usado
		switch($type) {
			case 'mysql':
				$port = $port ? $port : '3306';
				$conn = new PDO("mysql:host={$host};port={$port};dbname={$dbname}", $username, $password);
				break;
		}
		
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;
	}
}
