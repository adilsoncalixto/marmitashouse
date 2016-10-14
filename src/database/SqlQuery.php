<?php

namespace App\database;

use App\database\Transaction;
use App\utils\Logger;
use PDO;

/**
 * Gerenciador do CRUD no SGBD
 * @author Jorge Lucas
 */
trait SqlQuery
{
	/**
	 * REaliza o INSERT no bando de dados
	 * @param string $table Tabela alvo
	 * @param array $data Dados e seu valor
	 * @param string $target Campo da tabela alvo
	 * @param string $operator Operadores SQL (LIKE, +, > etc)
	 * @param string $bool Operadores lógicos (AND, OR, XOR)
	 * @return object $result Resultado da consulta
	 */
	public static function select(string $table, array $data, string $target, string $operator, string $bool,
								  string $typeLog) {
		Transaction::open('mysql');
		$conn = Transaction::get();
		$logger = new Logger();
		$logger->open($typeLog);
		$sql = "SELECT ";
		$i = 1;
		
		if(empty($target)) {
			foreach ($data as $item => $value) {
				if($i == count($data)) {
					$sql .= "{$item} FROM {$table} WHERE ";
					break;
				}
				$sql .= "{$item}, ";
				$i++;
			}
			$i = 1;
		} else if($target == '*'){
			$sql .= "{$target} FROM {$table}";
		}
		
		if(empty($data)) goto end;
		
		switch ($operator) {
			case 'LIKE':
				$sql .= " WHERE ";
				foreach ($data as $item => $valor) {
					if($i == count($data)) {
						$sql .= "{$item} LIKE '%{$valor}%'";
						break;
					}
					$sql .= "{$item} LIKE '%{$valor}%' {$bool} ";
					$i++;
				}
				break;
				
			case '=':
				$sql .= " WHERE ";
				foreach ($data as $item => $valor) {
					if($i == count($data)) {
						$sql .= "{$item} = '{$valor}'";
						break;
					}
					$sql .= "{$item} = '{$valor}' {$bool} ";
					$i++;
				}
				break;
		}
		
		end:
		$logger->write($sql);
		$query = $conn->prepare($sql);
		$query->execute();
		$data = $query->fetchAll(PDO::FETCH_OBJ);
		Transaction::close();
		return $data;
	}

	public static function insert(string $table, array $data, string $operator, string $bool, string $typeLog) {
		
		Transaction::open('mysql');
		$conn = Transaction::get();
		$logger = new Logger();
		$logger->open($typeLog);
		$sql = "INSERT INTO {$table} (";
		$i = 1;
		foreach ($data as $colun => $value) {
			if(count($data) == $i) {
				$sql .= "{$colun}) VALUES (";
				break;
			}
			$sql .= "{$colun}, ";
			$i++;
		}
		$i = 1;
		foreach ($data as $colun => $value) {
			if(count($data) == $i) {
				$sql .= "'{$value}')";
				break;
			}
			$sql .= "'{$value}', ";
			$i++;
		}
		$logger->write($sql);
		$query = $conn->prepare($sql);
		if($query->execute()) {
			Transaction::close();
			return true;
		}
	}
	
	public static function update(string $table, array $dados, string $typeLog) {
		
		Transaction::open('mysql');
		$conn = Transaction::get();
		$logger = new Logger();
		$logger->open($typeLog);
		$sql = "UPDATE {$table} SET ";
		$i = 1;
		$cod;
		foreach ($dados as $prop => $val) {
				if($prop == 'codigo') {
					$cod = $val;
				}
				if($i == count($dados)) {
					$sql .= "{$prop} = '{$val}' ";
					break;
				}
				$sql .= "{$prop} = '{$val}', ";
				$i++;
		}
		$sql .= "WHERE codigo = {$cod}";
		$logger->write($sql);
		$query = $conn->prepare($sql);
		if($query->execute()) {
			Transaction::close();
			return true;
		}
	}
	
	public static function drop(string $table, array $dados, string $typeLog) {
		
		Transaction::open('mysql');
		$conn = Transaction::get();
		$logger = new Logger();
		$logger->open($typeLog);
		if(is_int($dados[1])) {
			$sql = "DELETE FROM {$table} WHERE {$dados[0]} = {$dados[1]}";
		} else {
			$sql = "DELETE FROM {$table} WHERE {$dados[0]} = '{$dados[1]}'";
		}
		
		$logger->write($sql);
		$query = $conn->prepare($sql);
		if($query->execute()) {
			Transaction::close();
			return true;
		}
	}
}