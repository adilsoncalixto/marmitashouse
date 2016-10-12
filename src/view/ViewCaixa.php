<?php

namespace App\view;

use App\utils\Session;
use App\widgets\base\Element;
use App\widgets\container\Panel;
use App\widgets\form\Button;
use App\widgets\form\Input;
use Exception;

class ViewCaixa implements FormBasic
{
	private $title;
	private $form;
	
	public function show() {
		
		$config = isset($_GET['method']) ? $_GET['method'] : null;
		
		#if($config == 'deletar' || $config == 'editar') {
		#	$config = 'consultar';
		#}
		
		$sessao = new Session();
		$sessao->generateNewToken();
		
		$div = new Element("div");
		$div->setAttribute('class', 'form');
		echo $div->show();
		
		$content = '';
		$form = new Element("form");
		$input = new Input();
		$form->setAttribute("method", "post");
		
		switch($config)
		{
			
		}
	}
}