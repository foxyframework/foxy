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
	  
});