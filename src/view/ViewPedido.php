<?php

namespace App\view;

use App\utils\Session;
use App\widgets\base\Element;
use App\widgets\container\Panel;
use App\widgets\container\ProdutosList;
use App\widgets\form\Button;
use App\widgets\form\Input;
use App\widgets\form\Select;
use Exception;

/**
 * Trata da exibição dos formulários envolvendo os pedidos
 * @author Jorge Lucas
 */
class ViewPedido implements FormBasic
{
	/**
	 * @var string $title Título do formulário
	 * @var string $form Formulário
	 * @var array $select Empresas Terceirizadas
	 */
	private $title;
	private $form;
	public $select;
	public $produtos;
	public $entregador;
	
	/**
	 * Configura e exibe o formulário de acordo com o método
	 * anteriormente chamado.
	 * {@inheritDoc}
	 * @see \App\view\FormBasic::show()
	 */
	public function show() {
		/* ajusta para exibir corretamente a data local */
		date_default_timezone_set("America/Fortaleza");
		
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
		
		switch ($config)
		{
			case 'cadastrar':
				$form->setAttribute("action", "?class=ControlPedido&method=cadastrar&action=submit");
				$form->setAttribute('id', 'formPedido');
				$content .= $form->show();
				
				//input Data
				$input->setType('date');
				$input->setAttributes('name', 'data');
				$input->setAttributes('id', 'data');
				$input->setAttributes('value', date('Y-m-d'));
				$input->setAttributes('size', '300');
				$input->setAttributes('readonly', 'true');
				$input->setLabel('data', 'Data do pedido:');
				$content .= $input->show();
				
				//input Cliente
				$select = new Select('cliente');
				$select->setLabel('cliente', 'Cliente:');
				$select->setOptions($this->select);
				$content .= $select->show();
				
				//select Produtos
				$prodList = new ProdutosList('Produtos', 'produtos');
				$prodList->setItens($this->produtos);
				$content .= $prodList->mount();
				
				//input Valor total
				$input->setType('text');
				$input->setAttributes('name', 'valorTotal');
				$input->setAttributes('id', 'valorTotal');
				$input->setAttributes('size', '300');
				$input->setAttributes('readonly', 'true');
				$input->setLabel('valorTotal', 'Valor total da compra:');
				$content .= $input->show();
				
				//input Tipo de pagamento
				$select = new Select('tipoPagamento');
				$select->setId('tipoPagamento');
				$select->setLabel('tipoPagamento', 'Tipo de pagamento:');
				$select->setOptions(['dinheiro'=>'Dinheiro', 'cartao_credito'=>'Cartão - Crédito (Máximo: 1x parcela)','cartao_debito'=>'Cartão - Débito']);
				$content .= $select->show();
				
				//input Valor pago
				$input->setType('text');
				$input->setAttributes('name', 'valorPago');
				$input->setAttributes('id', 'valorPago');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('valorPago', 'Valor pago:');
				$content .= $input->show();
				
				//input Valor troco
				$input->setType('text');
				$input->setAttributes('name', 'valorTroco');
				$input->setAttributes('id', 'valorTroco');
				$input->setAttributes('size', '300');
				$input->setAttributes('readonly', 'true');
				$input->setLabel('valorTroco', 'Valor do troco:');
				$content .= $input->show();
				
				//input Entregador
				$select = new Select('entregador');
				$select->setLabel('entregador', 'Entregador:');
				$select->setOptions($this->entregador);
				$content .= $select->show();
				
				//input Itens comprados
				$input->setType('text');
				$input->setAttributes('name', 'itensComprados');
				$input->setAttributes('id', 'itensComprados');
				$input->setAttributes('style', 'opacity: 0; z-index: -999; position: absolute;');
				$input->setLabel('', '');
				$content .= $input->show();
				
				//hash de verificação
				$input->setType('hidden');
				$input->setAttributes('name', 'token');
				$input->setAttributes('value', $_SESSION['_token']);
				$input->setLabel('', '');
				$content .= $input->show();
				
				$bt = new Button();
				$bt->setContent("Cadastrar");
				$bt->setAttributes("type", "submit");
				$bt->setAttributes('id', 'btCadPed');
				$bt->setAttributes("value", "cadastrar");
				$content .= $bt->show();
				
				$bt->setContent('Limpar');
				$bt->setAttributes('type', 'reset');
				$bt->setAttributes('value', 'limpar');
				$content .= $bt->show();
				
				$content .= $form->closeTag();
				$this->title = 'Novo pedido';
				$this->form = $content;
				break;
				
			case 'consultar':
				$form->setAttribute("action", "?class=ControlPedido&method=consultar&action=search");
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
				
				$bt = new Button();
				$bt->setContent("Consultar");
				$bt->setAttributes("type", "submit");
				$bt->setAttributes("value", "consultar");
				$content .= $bt->show();
				
				$bt->setContent('Limpar');
				$bt->setAttributes('type', 'reset');
				$bt->setAttributes('value', 'limpar');
				$content .= $bt->show();
				
				$content .= $form->closeTag();
				$this->title = 'Consultar pedidos';
				$this->form = $content;
				break;
			
			default:
				throw new Exception('Método não encontrado!');
				break;
		}
		
		$panel = new Panel($this->title);
		$panel->setContent($this->form);
		$panel->show();
		
		echo $div->closeTag();
	}
}