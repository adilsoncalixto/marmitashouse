<?php

namespace App\utils;

/**
 * Gerencia o registro da seção
 * @author Jorge Lucas
 */
class Session
{
    /**
     * inicializa uma seção
     */
    public function __construct() {
    	
        if (!session_id()) {
            session_start();
            session_regenerate_id(true);
            session_name(md5('sec'.$_SERVER['REMOTE_ADDR'].'sec'.$_SERVER['HTTP_USER_AGENT'].'sec'));
            $_SESSION['_token'] = !isset($_SESSION['_token']) ? hash('sha256', random_int(0, 1000)) : $_SESSION['_token'];
        }
    }
    
    /**
     * Gera novas strings hash
     * @return void
     */
    public function generateNewToken() {
    	$_SESSION['_token'] = hash('sha256', random_int(0, 1000));
    }

    /**
     * Armazena uma variável na seção
     * @param $var = Nome da variável
     * @param $value = Valor
     */
    public static function setValue($var, $value) {
    	
        $_SESSION[$var] = $value;
    }

    /**
     * Retorna uma variável da seção
     * @param $var = Nome da variável
     * @return sessão logada
     */
    public static function getValue($var) {
    	
        if (isset($_SESSION[$var])) {
            return $_SESSION[$var];
        }
    }

    /**
     * Destrói os dados de uma seção
     */
    public static function clean() {
    	
        $_SESSION = array();
        session_destroy();
    }
}
