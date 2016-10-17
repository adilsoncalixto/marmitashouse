<?php

namespace App\utils;

/**
 * Gerencia o registro da sessão
 * @author Jorge Lucas
 */
class Session
{
    /**
     * inicializa uma sessão
     */
    public function __construct() {
    	
        if (!session_id()) {
            session_start();
            session_regenerate_id(true);
            session_name(md5('sec'.$_SERVER['REMOTE_ADDR'].'sec'.$_SERVER['HTTP_USER_AGENT'].'sec'));
        }
        
        /** Verifica a propriedade já esta configurada, senão a cria **/
    	if(!isset($_SESSION['sessionTime'])) {
    		$_SESSION['sessionTime'] = time() + (60 * 10); //60s * Xmin = Tempo
    	}
        
        /** gera uma sequência de caracteres para verificação de formulário **/
        $_SESSION['_token'] = !isset($_SESSION['_token']) ? hash('sha256', random_int(0, 1000)) : $_SESSION['_token'];
    }
    
    /**
     * Verifica o tempo da sessão. Caso ainda esteja ativa, retorna true, senão,
     * destrói a sessão e retorna false
     * @return boolean
     */
    public function checkSessionTime() {
    	
    	if($_SESSION['sessionTime'] < time()) {
    		$_SESSION = array();
    		session_destroy();
    		return false;
    	}
    	return true;
    }
    
    /**
     * Aumenta o tempo da sessão em 6min
     * @return void
     */
    public function extendSessionTime() {
    	
    	$_SESSION['sessionTime'] = time() + (60 * 10);
    }
    
    /**
     * Gera novas strings hash
     * @return void
     */
    public function generateNewToken() {
    	
    	$_SESSION['_token'] = hash('sha256', random_int(0, 1000));
    }

    /**
     * Armazena uma variável na sessão
     * @param $var = Nome da variável
     * @param $value = Valor
     */
    public static function setValue($var, $value) {
    	
        $_SESSION[$var] = $value;
    }

    /**
     * Retorna uma variável da sessão
     * @param $var = Nome da variável
     * @return uma valor da propriedade de $_SESSION
     */
    public static function getValue($var) {
    	
        if (isset($_SESSION[$var])) {
            return $_SESSION[$var];
        }
    }

    /**
     * Destrói os dados de uma sessão
     */
    public static function clean() {
    	
        $_SESSION = array();
        session_destroy();
    }
}
