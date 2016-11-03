/**
* Armazena os dados relacionados ao pedido
* @author Jorge Lucas
*/

/* global produtos */
produtos = [];
loopLimit = 0;

/**
 * Recebe as mensagens vindas de pedido.js, analisa o comando
 * e executa a função correta
 */
onmessage = function(e) {
	
	switch(e.data.cmd) {
		case 'add':
			add(e.data.id, e.data.valor);
			break;
		case 'rmv':
			rmv(e.data.id);
			break;
		case 'lastId':
			setLimit(e.data.limit);
			break;
		case 'total':
			postMessage({
				cmd: 'attTotal',
				total: valorTotal()
			});
			break;
		case 'itensComprados':
			postMessage({
				cmd: 'cart',
				itens: prepareCart()
			});
			break;
	}
}

/**
 * Configura a extesão máxima do loop de coleta de valores
 * baseado no número do último pedido
 * @param int limit Número com o limite máximo
 * @returns void
 */
function setLimit(limit) {
	loopLimit = limit;
}

/**
 * Adiciona um produto na 'cesta' do pedido 
 * @param id Código do produto
 * @param valor Preço do produto
 * @returns void
 */
function add(id, valor) {
	var qtd = 0;
	if(produtos[id] == undefined) {
		produtos[id] = [valor, 1];
		qtd = 1;
	} else {
		qtd = produtos[id][1] + 1;
		produtos[id][1] = qtd;
	}
	postMessage({
		cmd: 'attQuantidade',
		id: 'qtd'+id,
		qtd: qtd
	});
}

/**
 * Remove um produto da 'cesta' do pedido
 * @param id Código do produto
 * @returns void
 */
function rmv(id) {
	var qtd = 0;
	if(produtos[id] == undefined) {
		return false;
	} else {
		qtd = produtos[id][1] - 1;
		if(qtd <= 0) {
			produtos[id] = null;
		} else {
			produtos[id][1] = qtd;
		}
	}
	postMessage({
		cmd: 'attQuantidade',
		id: 'qtd'+id,
		qtd: qtd
	});
}

/**
 * Retorna o valor total da compra
 * @returns float total | bool
 */
function valorTotal() {
	if(produtos == undefined) {
		return false;
	}	
	var total = 0;
	for(var i = 0; i <= loopLimit; i++) {
		if(produtos[i] == undefined) {
			continue;
		}
		total += (produtos[i][1] * produtos[i][0]);
	}
	/* Retorna o total e soma com a taxa de entrega */
	return (parseFloat(total) + parseFloat(4.50)).toFixed(2);
}

/**
 * Retorna uma lista com todos os produtos comprados
 * @returns string cart
 */
function prepareCart() {
	var cart = '';
	for(let i = 0; i <= loopLimit; i++) {
		if(produtos[i] == undefined) {
			continue;
		}
		cart += 'id='+i+':qtd='+produtos[i][1]+'_';
	}
	return cart;
}