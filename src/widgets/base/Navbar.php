<?php

namespace App\widgets\base;

/**
 * Classe geradora de Barra de Menu
 * @author Jorge Lucas
 */
class Navbar
{
	private $navName; //Armazena o título da barra de navegação
	private $mainMenu; //Armazena o menu principal
	private $userMenu; //Armazena o menu do usuário
	private $btMobile; //Botão para acesso mobile

	/**
	 * Configura o nome que será exibido na barra de navegação
	 * @param string $name
	 */
	public function setNavName(string $name) {
		$this->navName = $name;
	}
	
	/**
	 * Contrutora do menu principal
	 * Recebe um array de dados contendo título do menu e seus respectivos submenus.
	 * @param array $itens Conjunto de dados com as informações do menu
	 */
	public function setMainManu(array $itens) {
		$this->mainMenu = "<ul class=\"nav navbar-nav\">";
		foreach ($itens as $menu => $item) {
			$this->mainMenu .= <<<HTML
			<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> {$menu} <span class="caret"></span></a>
				<ul class="dropdown-menu">
HTML;
			foreach ($item as $subMenu => $link) {
				$this->mainMenu .= "<li><a href=\"{$link}\"> {$subMenu} </a></li>";
			}
			$this->mainMenu .= "</ul>";
		}
		$this->mainMenu .= "</li> </ul>";
	}

	/**
	 * Geradora do menu do usuário
	 * @param string $username Nome do usuário
	 */
	public function userMenu(string $username) {
		$this->userMenu = <<<HTML
		<!-- user menu -->
		<ul class="nav navbar-nav navbar-right">
		    <li class="dropdown">
		    	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> {$username} <span class="caret"></span></a>
		        <ul class="dropdown-menu">
		            <li><a href="?class=ControlLogin&method=logout"><span class="glyphicon glyphicon-off" aria-hidden="true"></span>	Sair</a></li>
		       	</ul>
			</li>
	    </ul> <!-- /user menu -->		
HTML;
		$this->btMobile = <<<BT
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
BT;
	}
	
	/**
	 * Exibe o menu construído
	 * @return void
	 */
	public function show() {
		$content = <<<HTML
		<nav class="navbar navbar-default">
			<div class="container-fluid">
		    
			    <!-- Brand and toggle get grouped for better mobile display -->
			    <div class="navbar-header">
			    	{$this->btMobile}
			    <a class="navbar-brand" href="?class=ControlHome"> {$this->navName} </a>
			    </div>
			
			    <!-- Collect the nav links, forms, and other content for toggling -->
			    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			    	{$this->mainMenu}
			      	{$this->userMenu}
			   	</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
HTML;
		
		return $content;
	}
}