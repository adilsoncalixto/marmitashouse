<?php

namespace App\controller;

use App\database\SqlQuery;
use App\model\Pedido;
use App\view\ViewPedido;
use App\widgets\dialog\Message;
use App\widgets\container\DataGrid;
use DateTime;
use Exception;
use App\widgets\container\Report;

/**
 * Gerencia as requisições dos formulários envolvendo
 * os pedidos
 * @author Jorge Lucas
 */
class ControlPedido
{
	use SqlQuery;
	
	/**
	* Responsável por realizar o recebimento dos dados do formuário
	* cadastrar pedido e enviá-los a classe model
	* @throws Exception Lança uma excessão caso o cadastro retorne um erro
	*/
	public function cadastrar() {
		
		$caixa = date('Y-m-d');
		$check = SqlQuery::select('caixa', ['data'=>$caixa], '*', '=', '', 'bus_caixa');
		if(!$check) {
			throw new Exception('Caixa ainda não aberto! Abra um novo caixa.');
		}
		
		$action = isset($_GET['action']) ? $_GET['action'] : null;
		
		/** variável ação (action) passada pela url **/
		if($action == 'submit') {
		
			/** verifica se o código hash do formulário para evitar envio de dados duplicados **/
			if($_POST['token'] !== $_SESSION['_token']) {
				throw new Exception('Token inválido!');
			}
		
			$model = new Pedido();
			$model->setData($_POST['data']);
			$model->setCliente($_POST['cliente']);
			$model->setProduto($_POST['itensComprados']);
			$model->setValorTotal($_POST['valorTotal']);
			$model->setTipoPagamento($_POST['tipoPagamento']);
			$model->setValorPago($_POST['valorPago']);
			$model->setValorTroco($_POST['valorTroco']);
			$model->setDesconto(isset($_POST['desconto']) ? $_POST['desconto'] : 0);
			$model->setEntregador($_POST['entregador']);
			$model->setStatus('Pendente');
			$model->setVendedor($_SESSION['nickname']);
			if($model->save()) {
				$msg = new Message();
				$msg->setContent('Concluído!', "Pedido cadastrado com sucesso!", 'success');
				echo $msg->show();
			} else {
				throw new Exception("Não foi possível cadastrar o pedido! Contate o suporte!");
			}
		}
		
		$view = new ViewPedido();
		
		/** busca todas es empresas no banco para formar uma lista **/
		$search = SqlQuery::select('cliente', [], '*', '', '', 'bus_cliente');
		$select = [];
		foreach ($search as $array) {
			foreach ($array as $key => $value) {
				if($key == 'nome') {
					$select[$array->codigo] = $value . " (código: {$array->codigo})";
				}
			}
		}
		
		/** busca todas es empresas no banco para formar uma lista **/
		$search = SqlQuery::select('entregador', [], '*', '', '', 'bus_entregador');
		$entregador = [];
		foreach ($search as $array) {
			foreach ($array as $key => $value) {
				if($key == 'nome') {
					$entregador[$array->codigo] = $value . " (código: {$array->codigo})";
				}
			}
		}
		
		/** busca todas es empresas no banco para formar uma lista **/
		$search = SqlQuery::select('produto', [], '*', '', '', 'bus_produto');
		$produtos = $search;
		
		$view->select = $select;
		$view->produtos = $produtos;
		$view->entregador = $entregador;
		$view->show();
	}
	
	/**
	 * Responsável por gerenciar as requisições de consulta do formulário consultar pedido
	 * @throws Exception
	 */
	public function consultar() {
		
		$action = isset($_GET['action']) ? $_GET['action'] : null;
		$tableResult = null;
		$msg = null;
		
		/** variável ação (action) passada pela url **/
		if($action == 'search') {
		
			/** verifica se o código hash do formulário para evitar envio de dados duplicados **/
			if($_POST['token'] !== $_SESSION['_token']) {
				throw new Exception('Token inválido!');
			}
		
			$model = new Pedido();
			$dataInicio = new DateTime($_POST['dataInicio']);
			$dataFim = new DateTime($_POST['dataFim']);
			$result = $model->listPedidos([
					'data' => $dataInicio->format("Y-m-d"),
					'dataFim' => $dataFim->format("Y-m-d")
			]);
		
			/** caso retorne dados, é montado uma tabela carregada com os mesmos, senão retorna
			 * uma mensagem informando que não há dados **/
			if($result) {
				$table = new DataGrid('Resultados da busca', 'ControlPedido');
				$table->setColunHeaders(['#','Data', 'Cliente', 'Compra (R$)', 'Usuário', 'Estado', 'Entregador', 'Ação']);
				$table->setRowItens($result);
				$tableResult = $table->mount(['editar','deletar']);
			} else {
				$msg = new Message();
				$msg->setContent('Oops!', "Nenhum dado encontrado", 'info');
				echo $msg->show();
			}
		}
		
		/** chama a view cliente para montar o formulário de consulta **/
		$view = new ViewPedido();
		$view->show();
		
		echo isset($tableResult) ? $tableResult : null;
	}
	
	/**
	 * Recebe a requisição para editar o status ou entregador pedido no sgbd
	 * @throws Exception
	 */
	public function editar() {
		
		/** variável ação (action) passada pela url **/
		$action = isset($_GET['action']) ? $_GET['action'] : null;
		
		/**
		 * Verifica se a ação é atualizar, caso não seja, significa que terá de montar primeiro
		 * o formulário para edição dos dados para enfim sua submissão
		 */
		if($action == 'atualizar') {
				
			/** verifica se o código hash do formulário para evitar envio de dados duplicados **/
			if($_POST['token'] !== $_SESSION['_token']) {
				throw new Exception('Token inválido!');
			}
				
			$model = new Pedido();
			$model->setCodigo($_POST['codigo']);
			$model->setStatus($_POST['status']);
			
			/** envia requisição para atualizar **/
			if($model->update()) {
				$msg = new Message();
				$msg->setContent('Concluído!', "Pedido atualizado com sucesso!", 'success');
				echo $msg->show();
			}
				
			/** chama a view pedido para montar o formulário de consulta **/
			$view = new ViewPedido();
			$view->show();
				
		} else {
				
			$model = new Pedido();
			$model->setCodigo($_GET['codigo']);
			$result = $model->search();
			
			/** lista os possíveis estados do pedidopar formar um lista **/
			$options = [];
			$options['Pendente'] = 'Pendente';
			$options['Em Trânsito'] = 'Em Trânsito';
			$options['Entregue'] = 'Entregue';			
			
			$view = new ViewPedido();
			$view->select = $options;
			$view->showEditForm($result);
		}
	}
	
	/**
	 * Recebe a requisição para deletar um pedido do sgbd
	 * @throws Exception
	 */
	public function deletar() {
		
		if($_SESSION['permission'] !== 'all') {
			throw new Exception('Usuário sem permissão! Contate o adinistrador so sistema!');
		}
		
		/** variável código passada pela url **/
		$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
		
		$model = new Pedido();
		$result = $model->delete(intval($codigo));
		
		if($result) {
			$msg = new Message();
			$msg->setContent('Concluído!', "Pedido deletado com sucesso!", 'success');
			echo $msg->show();
		} else {
			throw new Exception('Não foi possível deletar o pedido!');
		}
		
		/** chama a view cliente para montar o formulário de consulta **/
		$view = new ViewPedido();
		$view->show();
	}
	
	/**
	 * Recebe a requisição para gerar um relatório baseado
	 * em entregas realizadas
	 */
	public function relatorio() {
		
		if($_SESSION['permission'] !== 'all') {
			throw new Exception('Usuário sem permissão! Contate o adinistrador so sistema!');
		}
		
		$action = isset($_GET['action']) ? $_GET['action'] : null;
		
		if($action == 'generate') {
			$model = new Pedido();
			$dataInicio = new DateTime($_POST['dataInicio']);
			$dataFim = new DateTime($_POST['dataFim']);
			$result = $model->getDadosEntregas([
					'data' => $dataInicio->format("Y-m-d"),
					'dataFim' => $dataFim->format("Y-m-d")
			]);
			$dadosRelatorio = [];
			$totalEntregas = 0;
			if($result) {
				foreach ($result as $entrega => $dados) {
					if($dados->status == 'Entregue') {
						$totalEntregas++;
					}
				}
				foreach ($result as $entregas => $prop) {
					$search = SqlQuery::select('entregador', ['codigo'=>$prop->entregador], ['nome'], '=', '', 'bus_entregador');
					$codigo = $prop->entregador;
					$entregador = $search[0]->nome;
					if(!key_exists($entregador, $dadosRelatorio)) {
						$dadosRelatorio[$entregador] = 0;
					} else {
						continue;
					}
				}
				
				foreach ($result as $entregas => $prop) {
					$search = SqlQuery::select('entregador', ['codigo'=>$prop->entregador], ['nome'], '=', '', 'bus_entregador');
					$entregador = $search[0]->nome;
					if(key_exists($entregador, $dadosRelatorio)) {
						$dadosRelatorio[$entregador]++;
					}
				}
			}
		}
		
		$view = new ViewPedido();
		$view->show();
		
		if($action == 'generate') {
			$report = new Report($dadosRelatorio, $totalEntregas);
			echo "<div class='form'>" . $report->show() . "</div>";		
		}
	}
}