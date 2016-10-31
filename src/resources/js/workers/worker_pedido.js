/**
* Armazena os dados relacionados ao pedido
* @author Jorge Lucas
*/

/* global produtos */
produtos = [];
loopLimit = 0;

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

function setLimit(limit) {
	loopLimit = limit;
}

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

function rmv(id) {
	var qtd = 0;
	if(produtos[id] == undefined) {
		return false;
	} else {
		qtd = produtos[id][1] - 1;
		produtos[id][1] = qtd;
		if(qtd <= 0) {
			produtos[id][1] = null;
		}
	}
	postMessage({
		cmd: 'attQuantidade',
		id: 'qtd'+id,
		qtd: qtd
	});
}
	
function quantidade(id) {
	if(produtos[id] == undefined) {
		return false;
	}
}

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
	return parseFloat(total).toFixed(2);
}

function prepareCart() {
	var cart = '';
	for(let i = 0; i <= loopLimit; i++) {
		if(produtos[i] == undefined) {
			continue;
		}
		cart += 'id:'+i+'qtd:'+produtos[i][1]+'_';
	}
	return cart;
}