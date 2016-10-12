/**
* Script responsável por manipular os dados processados pela
* classe (model) Cliente e enviá-los a classe (view) FormCliente
* @author Jorge Lucas
*/

window.onload = function() {
	
	addEvent('keyup', 'nome', searchCliente);
	addEvent('keyup', 'telefone', searchCliente);
}

function addEvent(event, id, func) {
	
	if(!document.getElementById(id)) {
		return false;
	}
	
	document.getElementById(id).addEventListener(event, func, false);
}

function element(id) {
	return document.getElementById(id);
}

function searchCliente(event) {
	
	var search = element(event.target.id).value;
	var resultBusca = element('resultBuscaCliente');
	var ajax = new XMLHttpRequest();
    var pares = [];
    var dados = {
    		'field': event.target.id,
    		'term': search
    }
    
    for (var name in dados) {
        var value = dados[name].toString();
        name = encodeURIComponent(name).replace('%20', '+');
        value = encodeURIComponent(value).replace('%20', '+');
        pares.push(name + '=' + value);
    }
    
    var dados = pares.join('&');
    
    ajax.open('post', 'src/model/Cliente.php');
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=utf8');
    ajax.send(dados);
    ajax.onreadystatechange = function() {
        if(ajax.readyState == 4 && ajax.status == 200) {
            resultBusca.innerHTML = ajax.responseText;
        }
    }
}