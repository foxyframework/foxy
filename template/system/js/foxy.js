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

function ajaxCall(url, callback) {
	var xhttp;
	xhttp=new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	  if (this.readyState == 4 && this.status == 200) {
		callback(this);
	  }
	};
	xhttp.open("GET", url, true);
	xhttp.send();
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

(function () {
	'use strict'
  
	// Fetch all the forms we want to apply custom Bootstrap validation styles to
	var forms = document.querySelectorAll('.needs-validation')
  
	// Loop over them and prevent submission
	Array.prototype.slice.call(forms)
	  .forEach(function (form) {
		form.addEventListener('submit', function (event) {
		  if (!form.checkValidity()) {
			event.preventDefault()
			event.stopPropagation()
		  }
  
		  form.classList.add('was-validated')
		}, false)
	  })
  })()

  if (document.getElementsByClassName('.dropzone').lenght) { Dropzone.autoDiscover = false; }

document.addEventListener("DOMContentLoaded", function() {

	var myTable = document.getElementById("datatable");
	if(typeof(myTable) != 'undefined' && myTable != null) {
		var table = new DataTable(myTable);
	}

	var editor = document.getElementById("editor");
	if(typeof(editor) != 'undefined' && editor != null) {
	    const editor = CKEDITOR.replace( 'editor' );
	}

	var media = document.getElementById("media");
	if(typeof(media) != 'undefined' && media != null) {
		var macy = Macy({
			container: '#media',
			trueOrder: false,
			waitForImages: false,
			margin: 24,
			columns: 6,
			breakAt: {
				1200: 5,
				940: 3,
				520: 2,
				400: 1
			}
		});
	}

	if (document.getElementsByClassName('.dropzone').lenght) {
		var myDropzone = new Dropzone(".dropzone", { url: domain+"?task=media.upload&mode=raw"});
		myDropzone.on("complete", function (file) {
			if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
				macy.recalculate();
			}
		});
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
					//Todo: display success message
					items.forEach(item => {
						document.querySelector(`[data-id="${item}"]`).remove();
					})
				} else {
					//display error message
				}
			}
		});
	}
});