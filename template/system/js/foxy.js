var domain   = location.origin+location.pathname;

function setCookie(c_name,value,exdays) {
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays===null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}

function getCookie(c_name) {
    var name = c_name + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name)===0) return c.substring(name.length,c.length);
    }
    return "";
}

function deleteAccount(username, domain) {
	if(document.getElementById('proceed').value.toLowerCase() == username) {
		document.location.href = domain+'?view=config&task=deleteAccount';
	} else {
		return false;
	}
}

function getParameterByName(name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	results = regex.exec(location.search);
	return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

var myTable = document.getElementById("datatable");
if(myTable != 'undefined' && myTable != 'null') {
	var table = new DataTable(myTable);
}

var editor = document.getElementById("editor");
if(editor != 'undefined' && editor != 'null') {
    SUNEDITOR.create('editor');
}

//delete
if(document.getElementsByClassName('.btn_delete').lenght) {
	document.querySelector('btn_delete').addEventListener("click",function(e) {

		e.preventDefault();
		var items = [];

		$(':checkbox').each(function(el) {
		if(this.checked) {
				var id    = el.target.getAttribute('data-id');
				items.push(id);
			}
		});

		var view = e.target.getAttribute('data-view');
		var pageURL = e.target.getAttribute("href");

		if(items == 0) { alert('Please check one item at least'); return false; } else { if(!confirm('Are you sure you want to delete this item?')) return false; }

		var list = JSON.stringify(items);

		var ajax = new XMLHttpRequest();

		ajax.open("POST", pageURL, true);
		ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajax.send("items="+list);

		// Cria um evento para receber o retorno.
		ajax.onreadystatechange = function() {
		
		// Caso o state seja 4 e o http.status for 200, é porque a requisiçõe deu certo.
			if (ajax.readyState == 4 && ajax.status == 200) {
			
				var data = ajax.responseText;
				Messenger().post({message: view+' success deleted', type: 'success', hideAfter: 10});
				items.forEach(item => {
					document.querySelector(`[data-id="${item}"]`).remove();
				})
			} else {
				Messenger().post({message: 'Sembla que tenim algun problema', type: 'error', hideAfter: 10});
			}
		}
	});
}