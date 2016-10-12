<?php

namespace App\view;

use App\utils\Session;
use App\view\FormBasic;
use App\widgets\base\Element;
use App\widgets\container\Panel;
use App\widgets\form\Input;
use App\widgets\form\Button;

/**
 * Gerencia 
 * @author lucas
 *
 */
class ViewLogin implements FormBasic
{
	public function show() {
		
		$sessao = new Session();
		$sessao->generateNewToken();
		
		$div = new Element("div");
		$div->setAttribute("style", "width: 350px; margin: auto;");
		echo $div->show();
		
		$content = "";
		
		$el = new Element("form");
		$el->setAttribute("method", "post");
		$el->setAttribute("action", "?class=ControlLogin&method=auth");
		$content .= $el->show();
		
		$input = new Input();
		$input->setType("text");
		$input->setLabel("username", "Usuário");
		$input->setAttributes("size", "100");
		$input->setAttributes("name", "username");
		$input->setAttributes("id", "username");
		$input->setAttributes("required", "required");
		$content .= $input->show();
		
		$input->setType("password");
		$input->setLabel("password", "Senha");
		$input->setAttributes("size", "100");
		$input->setAttributes("name", "password");
		$input->setAttributes("id", "password");
		$input->setAttributes("required", "required");
		$content .= $input->show();
		
		//hash de verificação
		$input->setType('hidden');
		$input->setAttributes('name', 'token');
		$input->setAttributes('value', $_SESSION['_token']);
		$input->setLabel('', '');
		$content .= $input->show();
		
		$bt = new Button();
		$bt->setContent("Entrar");
		$bt->setAttributes("type", "submit");
		$bt->setAttributes("value", "entrar");
		$content .= '<br>' . $bt->show();
		
		$content .= $el->closeTag();
		
		$panel = new Panel("Autenticação");
		$panel->setContent($content);
		$panel->show();
		
		echo $div->show();
	}
}