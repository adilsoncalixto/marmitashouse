<?php

namespace App\view;

use App\database\SqlQuery;
use App\utils\Session;
use App\widgets\base\Element;
use App\widgets\container\Panel;
use App\widgets\container\ProdutosList;
use App\widgets\container\ProdutosList_Simple;
use App\widgets\form\Button;
use App\widgets\form\Input;
use App\widgets\form\Select;
use DateTime;
use Exception;

/**
 * Trata da exibição dos formulários envolvendo os pedidos
 * @author Jorge Lucas
 */
class ViewPedido implements FormBasic
{
	/**
	 * @var string title Título do formulário
	 * @var string form Formulário
	 * @var array select Empresas Terceirizadas
	 * @var array produtos Produtos cadastrados
	 * @var array entregador Entergadores cadastrados
	 * @trait SqlQuery Monta a instrução SQL e interage com o sgbd
	 */
	private $title;
	private $form;
	public $select;
	public $produtos;
	public $entregador;
	use SqlQuery;
	
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
				$input->setLabel('valorTotal', 'Valor total da compra (+ R$ 4,50 :: Taxa de entrega):');
				$content .= $input->show();
				
				//input Tipo de pagamento
				$select = new Select('tipoPagamento');
				$select->setId('tipoPagamento');
				$select->setLabel('tipoPagamento', 'Tipo de pagamento:');
				$select->setOptions(['Dinheiro'=>'Dinheiro', 'Cartão - Crédito'=>'Cartão - Crédito (Máximo: 1x parcela)','Cartão - Débito'=>'Cartão - Débito']);
				$content .= $select->show();
				
				//input Valor desconto
				$input->setType('text');
				$input->setAttributes('name', 'desconto');
				$input->setAttributes('id', 'desconto');
				$input->setAttributes('size', '300');
				$input->setAttributes('required', 'required');
				$input->setLabel('desconto', 'Desconto:');
				$content .= $input->show();
				
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

	/**
	 * Configura e exibe o formulário de adição de dados com os mesmo
	 * carregados
	 * @param array $dados Dados invocados para realizar a edição
	 */
	public function showEditForm(array $dados) {
		

		$sessao = new Session();
		$sessao->generateNewToken();
		
		$div = new Element("div");
		$div->setAttribute('class', 'form');
		echo $div->show();
		
		$content = '';
		$form = new Element("form");
		$input = new Input();
		$form->setAttribute("method", "post");
		
		$form->setAttribute("action", "?class=ControlPedido&method=editar&action=atualizar");
		$content .= $form->show();
		
		foreach ($dados as $obj) {
			foreach ($obj as $prop => $val) {
				if($prop == 'codigo') {
					$input->setType('text');
					$input->setAttributes('name', $prop);
					$input->setAttributes('id', $prop);
					$input->setAttributes('size', '300');
					$input->setAttributes('required', 'required');
					$input->setAttributes('value', $val);
					$input->setAttributes('readonly', 'true');
					$input->setLabel($prop, 'Código do pedido:');
					$content .= $input->show();
					continue;
				}
				if($prop == 'data') {
					$input->setType('date');
					$input->setAttributes('name', $prop);
					$input->setAttributes('id', $prop);
					$input->setAttributes('size', '300');
					$input->setAttributes('required', 'required');
					$val = new DateTime($val);
					$input->setAttributes('value', $val->format('Y-m-d'));
					$input->setAttributes('readonly', 'true');
					$input->setLabel($prop, 'Data da realização do pedido:');
					$content .= $input->show();
					continue;
				}
				if($prop == 'cliente') {
					$input->setType('text');
					$input->setAttributes('name', $prop);
					$input->setAttributes('id', $prop);
					$input->setAttributes('size', '300');
					$val = SqlQuery::select('cliente', ['codigo'=>intval($val)], ['nome'], '=', '', 'bus_cliente');
					$input->setAttributes('value', $val[0]->nome);
					$input->setAttributes('readonly', 'true');
					$input->setLabel($prop, 'Cliente:');
					$content .= $input->show();
					continue;
				}
				if($prop == 'tipoPagamento') {
					$input->setType('text');
					$input->setAttributes('name', $prop);
					$input->setAttributes('id', $prop);
					$input->setAttributes('size', '300');
					$input->setAttributes('value', $val);
					$input->setAttributes('readonly', 'true');
					$input->setLabel($prop, 'Forma de pagamento:');
					$content .= $input->show();
					continue;
				}
				if($prop == 'valorTotal') {
					$input->setType('text');
					$input->setAttributes('name', $prop);
					$input->setAttributes('id', $prop);
					$input->setAttributes('size', '300');
					$input->setAttributes('value', $val);
					$input->setAttributes('readonly', 'true');
					$input->setLabel($prop, 'Valor total do pedido:');
					$content .= $input->show();
					continue;
				}
				if($prop == 'produtos') {
					$prod = explode('_', $val);
					array_pop($prod);
					foreach ($prod as $key => $value) {
						$prod[$key] = explode(':', preg_replace(["/id=/", "/qtd=/"], "", $value));
						$dataProd = SqlQuery::select('produto',['codigo'=>$prod[$key][0]],['nome','tamanho', 'valor'],'=','','bus_produto');
						$prod[$key][0] = 'Nome: ' . $dataProd[0]->nome . ' - Tamanho: ' . $dataProd[0]->tamanho . ' - Preço: R$ ' . $dataProd[0]->valor;
					}
					$prodList_Simple = new ProdutosList_Simple('Produtos', 'produtos');
					$prodList_Simple->setItens($prod);
					$content .= $prodList_Simple->mount();
					continue;
				}
				if($prop == 'entregador') {
					$input->setType('text');
					$input->setAttributes('name', $prop);
					$input->setAttributes('id', $prop);
					$input->setAttributes('size', '300');
					$val = SqlQuery::select('entregador', ['codigo'=>$val],['nome'],'=','','bus_entregador');
					$input->setAttributes('value', $val[0]->nome);
					$input->setAttributes('readonly', 'true');
					$input->setLabel($prop, 'Entregador:');
					$content .= $input->show();
					continue;
				}
				if($prop == 'status') {
					$select = new Select('status');
					$select->setLabel('status', 'Estado do pedido:');
					$select->setOptions($this->select);
					$content .= $select->show();
					continue;
				}
			}
		}
		
		//hash de verificação
		$input->setType('hidden');
		$input->setAttributes('name', 'token');
		$input->setAttributes('value', $_SESSION['_token']);
		$input->setLabel('', '');
		$content .= $input->show();
		
		$bt = new Button();
		$bt->setContent("Atualizar");
		$bt->setAttributes("type", "submit");
		$bt->setAttributes("value", "enviar");
		$content .= $bt->show();
		
		$content .= $form->closeTag();
		$this->title = 'Atualizar dados do pedido';
		$this->form = $content;
		
		$panel = new Panel($this->title);
		$panel->setContent($this->form);
		$panel->show();
		
		echo $div->closeTag();
	}
}