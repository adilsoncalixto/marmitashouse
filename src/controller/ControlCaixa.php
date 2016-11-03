<?php

namespace App\controller;

use App\model\Caixa;
use App\view\ViewCaixa;
use App\widgets\container\DataGrid;
use App\widgets\dialog\Message;
use DateTime;
use Exception;

/**
 * Gerencia as requisições dos formulários envolvendo
 * o Caixa
 * @author Jorge Lucas
 */
class ControlCaixa
{
	/**
	 * Chama a view para montar o formulário para cadastro de
	 * um novo caixa
	 * @throws Exception Caso ocorra um erro durante a transação
	 */
	public function novo() {
		
		$action = isset($_GET['action']) ? $_GET['action'] : null;
		
		/** variável ação (action) passada pela url **/
		if($action == 'submit') {
				
			/** verifica se o código hash do formulário para evitar envio de dados duplicados **/
			if($_POST['token'] !== $_SESSION['_token']) {
				throw new Exception('Token inválido!');
			}
			
			$model = new Caixa();
			$model->setData($_POST['data']);
			$model->setQuantia($_POST['quantia']);
			$model->setUsername();
			if($model->save()) {
				$msg = new Message();
				$msg->setContent('Concluído!', "Novo caixa cadastrado com sucesso!", 'success');
				echo $msg->show();
			} else {
				throw new Exception("Ocorreu um erro durante a operação! Contate o suporte!");
			}
		}
		
		$view = new ViewCaixa();
		$view->show();
	}
	
	/**
	 * Realiza a consulta com base nas datas recebidas do
	 * formulário
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
				
			$model = new Caixa();
			$dataInicio = new DateTime($_POST['dataInicio']);
			$dataFim = new DateTime($_POST['dataFim']);
			$result = $model->listCaixa(array(
					'data' => $dataInicio->format("Y-m-d"),
					'dataFim' => $dataFim->format("Y-m-d")
			));
				
			/** caso retorne dados, é montado uma tabela carregada com os mesmos, senão retorna
			 * uma mensagem informando que não há dados **/
			if($result) {
				$table = new DataGrid('Resultados da busca', 'ControlCaixa');
				$table->setColunHeaders(['#','Data', 'Quantia final', 'Responsável pela abertura', 'Ação']);
				$table->setRowItens($result);
				$tableResult = $table->mount(['deletar']);
			} else {
				$msg = new Message();
				$msg->setContent('Oops!', "Nenhum dado encontrado", 'info');
				echo $msg->show();
			}
		}
		
		/** chama a view cliente para montar o formulário de consulta **/
		$view = new ViewCaixa();
		$view->show();
		
		echo isset($tableResult) ? $tableResult : null;
	}
	
	/**
	 * Recebe a requisição para realizar a remoção do caixa com base
	 * na data repassada
	 * @throws Exception
	 */
	public function deletar() {
		
		if($_SESSION['permission'] !== 'all') {
			throw new Exception('Usuário sem permissão! Contate o adinistrador so sistema!');
		}
		
		/** variável código passada pela url **/
		$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
		
		$model = new Caixa();
		$result = $model->delCaixa($codigo);
		
		if($result) {
			$msg = new Message();
			$msg->setContent('Concluído!', "Caixa deletado com sucesso!", 'success');
			echo $msg->show();
		} else {
			throw new Exception('Não foi possível deletar o cliente!');
		}
		
		/** chama a view cliente para montar o formulário de consulta **/
		$view = new ViewCaixa();
		$view->show();
	}
}