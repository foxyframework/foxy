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

function closeAllModals() {

    // get modals
    const modals = document.getElementsByClassName('modal');

    // on every modal change state like in hidden modal
    for(let i=0; i<modals.length; i++) {
      modals[i].classList.remove('show');
      modals[i].setAttribute('aria-hidden', 'true');
      modals[i].setAttribute('style', 'display: none');
    }

     // get modal backdrops
     const modalsBackdrops = document.getElementsByClassName('modal-backdrop');

     // remove every modal backdrop
     for(let i=0; i<modalsBackdrops.length; i++) {
       document.body.removeChild(modalsBackdrops[i]);
     }
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

	if (document.getElementsByClassName('.dropzone').lenght) {
		let myDropzone = new Dropzone(".dropzone", { url: domain+"?task=media.upload&mode=raw"});
		myDropzone.on("complete", function (file) {
			if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
				macy.recalculate();
			}
		});
	}

	if(document.getElementsByClassName('.img-selector').lenght) {
		document.querySelector('.img-selector').addEventListener("click",function(e) {
			let src = e.target.src;
			let id = e.target.getAttribute('data-id');
			document.getElementById(id).value = src;
			closeAllModals();
		});
	}

	//edit
	if(document.getElementById('btn_edit')) {
		document.getElementById('btn_edit').addEventListener("click",function(e) {
			e.preventDefault();
			let href = e.target.getAttribute('href');
			let item = getFirstSelectedCheckbox();
			document.location.href = href+'&id='+item;
		});
	}
});

//reorder table 
	const dragTable = {from: '', to: ''};

    // Note: Cloned copy of element1 will be returned to get a new reference back
    function exchangeElements(element1, element2) {
        var clonedElement1 = element1.cloneNode(true);
        var clonedElement2 = element2.cloneNode(true);

        if (element2.parentNode !== null) { // prevent warning when dragging onto self
          element2.parentNode.replaceChild(clonedElement1, element2);
        }

        if (element1.parentNode !== null) { // prevent warning when dragging onto self
          element1.parentNode.replaceChild(clonedElement2, element1);
        }

        return clonedElement1;
    }

    function dragStart(ev) { // occurs when the user starts to drag an element
      dragTable.from = ev.target.getAttribute('data-ordering');

      // this.style.opacity = '0.4';  // this / ev.target is the source node.
      // this.style.opacity = '0.4';
      // dragSrcEl = this
      ev.target.style.color = 'blue';

      // Allow the drag effect
      ev.dataTransfer.effectAllowed='move';

      // Save the dragged element ID as the dataTransfer attribute
      ev.dataTransfer.setData("Text", ev.target.getAttribute('id'));

      // Set the image of the element as it is being dragged
      ev.dataTransfer.setDragImage(ev.target,0,0);

      return true;
	}
	
    // function dragEnd(ev) { // occurs when the user has finished dragging the element
    //   ev.target.style.color = 'black';
    //   return false;
    // }

    function dragEnter(ev) {
        // alert("ENTER") // works, but does not allow the drop
        ev.target.parentNode.style.opacity = '0.2';
        event.preventDefault();
        return true;
	}
	
    function dragOver(ev) {
        // alert("OVER")  // infinite alert loop
        return false;
	}
	
    function dragLeave(ev) {
        // alert("OVER")  // infinite alert loop
        ev.target.parentNode.style.opacity = '1.0';
        return false;
	}
	
    function dragDrop(ev) {
        dragTable.to = ev.target.parentNode.getAttribute('data-ordering');
        // Get the ID of element being dragged based on the "Text" key
        var src = ev.dataTransfer.getData("Text");

        // ev.target.style.opacity = '1.0'
        var dest = document.getElementById(src)
        dest.style.color = 'black' // reset the dragged element color after it gets dropped

        // Reset the displaced element (that got dropped on)
        ev.target.parentNode.style.opacity = '1.0';

        // Get the element and append it to the DOM of the target
        // ev.target.appendChild(document.getElementById(src));

        // We need to swap with the target cell's parent, not the cell itself
        exchangeElements(ev.target.parentNode, dest)

        // Stop, because we're done
        ev.stopPropagation();

        //change order
        changeOrdering();

        return false;
    }

    function changeOrdering(){
        if(dragTable.from != dragTable.to){
			//ajax


            document.querySelector(`.table [data-ordering='${dragTable.from}']`).setAttribute('data-ordering', dragTable.to);
            document.querySelector(`.table [data-ordering='${dragTable.to}']`).setAttribute('data-ordering', dragTable.from);
        }
    }