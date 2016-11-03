/**
* Gerenciamento da inclusão de produtos no pedido,
* cálculos de quantias, valores e interação com o sgbd
* @author Jorge Lucas
*/

/**
 * Main
 */
window.onload = function() {
	wkrPedidos = new Worker('src/resources/js/workers/worker_pedido.js');
	wkrPedidos.onmessage = response;
	loadProductEvents();
}

/**
 * Retorna um elemento HTML com base no id da tag
 * passado por parâmetro
 * @param string id Identificador do elemento HTML
 * @returns object html
 */
function elem(id) {
	if(!document.getElementById(id)) {
		return false;
	}
	return document.getElementById(id);
}

/**
 * Configura eventos em elementos html
 * @param id Identificador do elemento
 * @param action Evento disparador
 * @param func Função a ser chamada
 * @returns void
 */
function setupEvent(id, action, func) {
	if(!elem(id)) {
		return false;
	}
	elem(id).addEventListener(action, func, false);
}

/**
 * Analisa as repostas do worker_pedido e realiza as
 * ações cabíveis
 * @returns void
 */
function response(res) {
	switch(res.data.cmd) {
		case 'attTotal':
			attTotal(res.data.total);
			break;
		case 'attQuantidade':
			attQuantidade(res.data.id, res.data.qtd);
			break;
		case 'cart':
			insertProds(res.data.itens);
			break;
	}
}

/**
 * Faz a leitura do campo input[id='lastID'], que contém o
 * valor do último id do produto exibido
 * @returns int
 */
function loadLastId() {
	var lastId = elem('lastId').value;
	wkrPedidos.postMessage({
		cmd: 'lastId',
		limit: lastId
	});
	return lastId;
}

/**
 * Carrega os eventos nos itens dos produtos
 * @returns void
 */
function loadProductEvents() {
	var limit = loadLastId();
	for(var i = 0; i <= limit; i++) {
		setupEvent('add'+i, 'click', addCart);
		setupEvent('rmv'+i, 'click', rmvCart);
	}
	setupEvent('valorPago', 'blur', calcValorPago);
	setupEvent('desconto','blur', calcValorDesconto);
}

/**
 * Adiciona itens no carrinho (array produtos)
 * @returns void
 */
function addCart(event) {
	let id = event.target.id.replace('add','');
	let price = parseFloat(elem(event.target.id).dataset.price);
	wkrPedidos.postMessage({
		cmd: 'add',
		id: id,
		valor: price
	});
	wkrPedidos.postMessage({
		cmd: 'total'
	});
	prepareProds();
}

/**
 * Remove itens no carrinho (array produtos)
 * @returns void
 */
function rmvCart(event) {
	var id = event.target.id.replace('rmv','');
	wkrPedidos.postMessage({
		cmd: 'rmv',
		id: id
	});
	wkrPedidos.postMessage({
		cmd: 'total'
	});
}

function attQuantidade(id, qtd) {
	var quantidade = elem(id);
	quantidade.innerHTML = qtd;
}

/**
 * Atualiza o valor total dos pedidos
 * @returns void
 */
function attTotal(total) {
	var fieldTotal = elem('valorTotal');
	fieldTotal.value = total;
}

function calcValorPago() {
	let vltot = elem('valorTotal');
	let vlpag = elem('valorPago');
	let vltro = elem('valorTroco');
	if(parseFloat(vlpag.value) < parseFloat(vltot.value)) {
		alert('Valor insuficiente');
		return false;
	} else {
		vltro.value = (parseFloat(vlpag.value) - parseFloat(vltot.value)).toFixed(2);
	}	
}

function calcValorDesconto(event) {
	let desc = elem('desconto');
	let vlto = elem('valorTotal');
	if((parseFloat(desc.value) > 0) || (parseFloat(desc.value) <= parseFloat(vlto.value))) {
		vlto.value = (parseFloat(vlto.value) - parseFloat(desc.value)).toFixed(2);
		desc.disabled = true;
	}
}

function prepareProds() {
	wkrPedidos.postMessage({
		cmd: 'itensComprados'
	});
}

function insertProds(prods) {
	var produtos = elem('itensComprados');
	produtos.value = prods;
}
