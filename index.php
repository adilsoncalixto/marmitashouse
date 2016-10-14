<?php

//PSR-4 Autoload / Composer
require 'vendor/autoload.php';

use App\controller\Control;
use App\utils\Session;
use App\utils\Logger;
use App\widgets\base\Navbar;
use App\widgets\dialog\Message;

?>
<!DOCTYPE html>
<html lang='pt-br'>

<head>
	<meta charset='utf-8'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> Casa das Marmitas </title>
	
	<!-- bootstrap & jquery -->
	<script src='src/resources/bootstrap/js/jquery.min.js'></script>
	<link rel='stylesheet' href='src/resources/bootstrap/css/bootstrap.min.css'>
	<script src="src/resources/bootstrap/js/bootstrap.min.js"></script>
	
	<!-- project -->
	<link rel='stylesheet' href='src/resources/css/defaultStyles.css'>
</head>

<body>
<?php 
	
try 
{	
	/**
	 * Inicializa a sessão
	 * @var object $sessao
	 */
	$sessao = new Session();
	
	/**
	 * @var $class Armazena o nome da classe a ser invocada
	 * @var $method Armazena o nome do método a ser invicado
	 */
	$class;	$method;
	
	$class = isset($_GET['class']) ? $_GET['class'] : null;
	$method = isset($_GET['method']) ? $_GET['method'] : null;
	
	/**
	 * Se a sessão não está ativa, gera o menu de login, senão
	 * gera o menu pricipal e o do usuário
	 */
	if($sessao->getValue('nickname') == null) {
		
		$class = 'ControlLogin';
		$navbar = new Navbar();
		$navbar->setNavName("Casa das Marmitas v2.0");
		echo $navbar->show();
	
	} else {
		
		//Constrói a barra de manus
		$navbar = new Navbar();
		$navbar->setNavName("Casa das Marmitas");
		$itensMenu = array(
				"Cliente" => array(
						"Cadastrar" => "?class=ControlCliente&method=cadastrar",
						"Consultar" => "?class=ControlCliente&method=consultar"
				),
				"Produto" => array(
						"Cadastrar" => "?class=FormProduto&method=cadastrar",
						"Consultar" => "?class=FormProduto&method=consultar"
				),
				"Pedido" => array(
						"Cadastrar" => "?class=FormPedido&method=cadastrar",
						"Consultar" => "?class=FormPedido&method=consultar",
						"Relatório" => "?class=Formpedido&method=relatorio"
				),
				"Entregador" => array(
						"Cadastrar" => "?class=FormEntregador&method=cadastrar",
						"Consultar" => "?class=FormEntregador&method=consultar"
				),
				"Empresa Terceirizada" => array(
						"Cadastrar" => "?class=FormEmpresaTerceirizada&method=cadastrar",
						"Consultar" => "?class=FormEmpresaTerceirizada&method=consultar"
				),
				"Caixa" => array(
						"Novo" => "?class=ControlCaixa&method=novo",
						"Consultar" => "?class=ControlCaixa&method=consultar"
				),
				"Usuário" => array(
						"Cadastrar" => "?class=ControlUsuario&method=cadastrar",
						"Consultar" => "?class=ControlUsuario&method=consultar"
				)
		);
		$navbar->setMainManu($itensMenu);
		$navbar->userMenu($sessao->getValue('nickname'));
		echo $navbar->show();
	}
	
	/**
	 * Monitora as solicitações via GET e executa as classes,
	 * métodos e ações solicitadas
	 */
	if($_GET) {
		
		$page = Control::load($class);
		if($method && method_exists($page, $method)) {	
			call_user_func(array($page, $method));
		} else {
			$page = Control::load($class);
			$page->show();
		}
		
	} else {
		if($sessao->getValue('nickname') == null) {
			$page = Control::load('ControlLogin');
		} else {
			$page = Control::load('ControlHome');
		}	
		$page->show();
	}
}

catch (Exception $ex) 
{
	$log = new Logger();
	$log->open('erro');
	$log->write($ex->getMessage());
	
	$msg = new Message();
	$msg->setContent('Erro:', $ex->getMessage(), 'warning');
	echo $msg->show();
}

?>
</body>

</html>