var domain   = location.origin+location.pathname;

function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires="+ d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
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

function load(url, method, element) {
    req = new XMLHttpRequest();
    req.open(method, url, false);
    req.send(null);
    
    document.getElementById(element).innerHTML = req.responseText; 
}

function ajaxCall(url, callback, method='GET') {
	var xhttp;
	xhttp= new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	  if (this.readyState == 4 && this.status == 200) {
		callback(this);
	  }
	};
	xhttp.open(method, url, true);
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

// get first selected checkbox
function getFirstSelectedCheckbox() {

	return document.querySelector('.tableCheck:checked').getAttribute('data-id');
}

// get all selecteded checkboxes
function getAllSelectedCheckboxes() {

	const checkbox = document.querySelectorAll('.tableCheck:checked');
	const items = [];
	checkbox.forEach(item => {
		items.push(item.dataset.id)
	});

	return items;
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

document.addEventListener("DOMContentLoaded", function() {

	if(document.getElementById('editor')) { const editor = CKEDITOR.replaceAll( 'editor' ); }
	if(document.getElementById('upload')) { Dropzone.autoDiscover = false; }

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

	//select all checkbox
	if(document.getElementById('selectAll')) {
		document.getElementById('selectAll').addEventListener("click",function(e) {
			var checkboxes = document.getElementsByClassName('tableCheck');
			
			for (var i=0; i<checkboxes.length; i++)  {
				if (checkboxes[i].checked == false)   {
					checkboxes[i].checked = true;
				} else {
					checkboxes[i].checked = false;
				}
			}
		});
	}

	//init dropzone to upload files
	if(document.getElementById('upload')) {
		let myDropzone = new Dropzone(".dropzone", { url: domain+"?task=media.upload&mode=raw"});
		myDropzone.on("complete", function (file) {
			if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
				macy.recalculate();
			}
		});
	}

	//click image in media field
	document.querySelectorAll('.img-selector').forEach(item => {
		item.addEventListener('click', evt => {
			let src = evt.currentTarget.src;
			let id = evt.currentTarget.getAttribute('data-id');
			let uniqid = evt.currentTarget.getAttribute('data-uniqid');
			document.getElementById(id).value = src;
			var myModal = new bootstrap.Modal(document.getElementById(uniqid+'Modal'));
			myModal.hide();
		})
	});

	//click edit buttons
	document.querySelectorAll('.editable').forEach(item => {
		item.addEventListener('click', evt => {
			evt.preventDefault();
			let id   = evt.currentTarget.dataset.id;
			let view = evt.currentTarget.dataset.view;
			load(domain+'?view='+view+'&layout=edit&id='+id+'&mode=raw', 'POST', 'mbody');
			const editor = CKEDITOR.replaceAll( 'editor' );
			var myModal = new bootstrap.Modal(document.getElementById('editable'));
			myModal.show();
		})
	});

	//click lang options create cookie
	document.querySelectorAll('.lang').forEach(item => {
		item.addEventListener('click', evt => {
			evt.preventDefault();
			let lang = evt.target.dataset.lang;
			var xhttp;
			xhttp= new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					window.location.reload();
				}
			};
			xhttp.open('GET', domain+'?task=register.setCookie&lang='+lang+'&mode=raw', true);
			xhttp.send();
		})
	});

	document.querySelectorAll('.closeModal').forEach(item => {
		item.addEventListener('click', evt => {
			let id = evt.currentTarget.getAttribute('data-id');
			var myModal = new bootstrap.Modal(document.getElementById(id));
			myModal.hide();
		})
	});	
	
	//reorder tables system
	var el = document.getElementById('datatable');
	var dragger = tableDragger(el, {
		mode: 'row',
		dragHandler: '.handle',
		onlyBody: true
	});
	dragger.on('drop',function(from, to) {

		const id         = [];
		const order      = [];

		let view = el.rows[from].querySelector('.handle').dataset.view;

		id.push(el.rows[from].querySelector('.handle').dataset.id);
		id.push(el.rows[to].querySelector('.handle').dataset.id);
		order.push(el.rows[from].querySelector('.handle').dataset.ordering);
		order.push(el.rows[to].querySelector('.handle').dataset.ordering);

		let ids = JSON.stringify(id);
		let orders = JSON.stringify(order);

		//call ajax
		var xhttp;
		xhttp= new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				//do nothing
			}
		};
		xhttp.open('GET', domain+'?task='+view+'.reorder&id='+ids+'&order='+orders+'&mode=raw', true);
		xhttp.send();
	});	  
	  
});