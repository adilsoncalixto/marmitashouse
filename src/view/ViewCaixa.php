<?php

namespace App\view;

use App\utils\Session;
use App\widgets\base\Element;
use App\widgets\container\Panel;
use App\widgets\form\Button;
use App\widgets\form\Input;

/**
 * Trata da exibição dos formulários envolvendo
 * o Caixa
 * @author Jorge Lucas
 */
class ViewCaixa implements FormBasic
{
	/**
	 * @var string $title Título do formulário
	 * @var string $form Formulário
	 */
	private $title;
	private $form;
	
	/**
	 * Configura e exibe o formulário de acordo com o método que
	 * foi chamado
	 * {@inheritDoc}
	 * @see \App\view\FormBasic::show()
	 */
	public function show() {
		
		$config = isset($_GET['method']) ? $_GET['method'] : null;
		
		if($config == 'deletar' || $config == 'editar') {
			$config = 'consultar';
		}
		
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
			case 'novo':
				$form->setAttribute("action", "?class=ControlCaixa&method=novo&action=submit");
				$content .= $form->show();
				
				//input data do caixa
				$input->setType('date');
				$input->setAttributes('name', 'data');
				$input->setAttributes('id', 'data');
				$input->setAttributes('size', '30');
				$input->setAttributes('value', date("Y-m-d"));
				$input->setAttributes('readonly', 'true');
				$input->setLabel('data', 'Data de abertura:');
				$content .= $input->show();
				
				//input quantia para abertura
				$input->setType('text');
				$input->setAttributes('name', 'quantia');
				$input->setAttributes('id', 'quantia');
				$input->setAttributes('size', '300');
				$input->setAttributes('placeholder', 'Somente números. Ex: 120,50');
				$input->setAttributes('required', 'required');
				$input->setLabel('quantia', 'Quantia (R$):');
				$content .= $input->show();
				
				//input usuário
				$input->setType('text');
				$input->setAttributes('name', 'username');
				$input->setAttributes('id', 'username');
				$input->setAttributes('size', '300');
				$input->setAttributes('value', $sessao->getValue('nickname'));
				$input->setAttributes('readonly', 'true');
				$input->setLabel('username', 'Usuário:');
				$content .= $input->show();
				
				//hash de verificação
				$input->setType('hidden');
				$input->setAttributes('name', 'token');
				$input->setAttributes('value', $_SESSION['_token']);
				$input->setLabel('', '');
				$content .= $input->show();
				
				//botão enviar
				$bt = new Button();
				$bt->setContent("Cadastrar");
				$bt->setAttributes("type", "submit");
				$bt->setAttributes("value", "entrar");
				$content .= $bt->show();
				
				//botão limpar campos
				$bt->setContent('Limpar');
				$bt->setAttributes('type', 'reset');
				$bt->setAttributes('value', 'limpar');
				$content .= $bt->show();
				
				$content .= $form->closeTag();
				$this->title = 'Novo caixa';
				$this->form = $content;
				break;
				
			case 'consultar':
				$form->setAttribute("action", "?class=ControlCaixa&method=consultar&action=search");
				$content .= $form->show();
				
				//input data do caixa
				$input->setType('date');
				$input->setAttributes('name', 'dataInicio');
				$input->setAttributes('id', 'dataInicio');
				$input->setAttributes('size', '30');
				$input->setLabel('dataInicio', 'Entre a data:');
				$content .= $input->show();
				
				//input data do caixa
				$input->setType('date');
				$input->setAttributes('name', 'dataFim');
				$input->setAttributes('id', 'dataFim');
				$input->setAttributes('size', '30');
				$input->setLabel('dataFim', 'E:');
				$content .= $input->show();
				
				//hash de verificação
				$input->setType('hidden');
				$input->setAttributes('name', 'token');
				$input->setAttributes('value', $_SESSION['_token']);
				$input->setLabel('', '');
				$content .= $input->show();
				
				//input Submit
				$bt = new Button();
				$bt->setContent("Buscar");
				$bt->setAttributes("type", "submit");
				$bt->setAttributes("value", "buscar");
				$content .= $bt->show();
				
				$content .= $form->closeTag();
				$this->title = 'Buscar por caixa';
				$this->form = $content;
				break;
				
			default:
				echo 'Método não encontrado';
				break;
		}
		
		$panel = new Panel($this->title);
		$panel->setContent($this->form);
		$panel->show();
		
		echo $div->closeTag();
	}
}