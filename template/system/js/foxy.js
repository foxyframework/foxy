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

if ($('.dropzone').length) { Dropzone.autoDiscover = false; }

$(document).ready(function() {

	if ($('.dropzone').length) {
		var myDropzone = new Dropzone(".dropzone", { url: domain+"?task=expedients.upload&mode=raw"});
		myDropzone.on("complete", function (file) {
			if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
				location.reload();
			}
		});
	}

	if ($('.editor').length) {
		wysiwyg( '.editor', {
			toolbar: 'demand',                        // 'top','bottom','demand',null
			buttons: [buttons],                       // buttons on toolbar
			selectionbuttons: [selectionbuttons],     // buttons on selection-toolbar
			suggester: suggester( open_suggestion ),  // handle suggestions
			interceptenter: interceptenter(),         // intercept 'enter'
			hijackmenu: false                         // toolbar instead context menu
	 	 });
	}

	//tooltips
	$(".hasTip").tooltip();

	//save cookie with language
	$('.lang').click(function() {
		var lang = $(this).attr('data-lang');
		setCookie('language', lang, 10);
  	});

  	$('.saveandclose').click(function(e) {
    	e.preventDefault();
    	$('#sortir').val(1);
    	$('.submit').click();
	});

	//select all checkbox
	$('#selectAll').change(function() {
		var checkboxes = $(this).closest('form').find(':checkbox');
		checkboxes.prop('checked', $(this).is(':checked'));
	});

  	if ($('.input-datepicker-autoclose').length) {
		$('.input-datepicker-autoclose').datepicker({
		autoclose: true,
		format: 'yyyy-mm-dd'
		});
  	}

	//delete
	$('#btn_delete').click(function(e) {

		e.preventDefault();
		var items = [];

		$(':checkbox').each(function() {
		if(this.checked) {
				var id    = $(this).attr('data-id');
				items.push(id);
			}
		});

		var view = $(this).attr('data-view');
		var pageURL = $(this).attr("href");

		if(items == 0) { alert('Please check one item at least'); return false; } else { if(!confirm('Are you sure you want to delete this item?')) return false; }

		var list = JSON.stringify(items);

		$.ajax({
			url: pageURL,
			type: "post",
			datatype: 'json',
			data: {'items': list},
			success: function(data){
				Messenger().post({message: view+' success deleted', type: 'success', hideAfter: 10});
					items.forEach(item => {
						$(`tr[data-id="${item}"]`).remove();
					})
			},
			error: function(data){
				Messenger().post({message: 'Sembla que tenim algun problema', type: 'error', hideAfter: 10});
			}
      	});
	});

	//delete image
	$('.deleteImage').click(function(e) {

		e.preventDefault();

		var id = $(this).attr('data-id');

		$.ajax({
			url: domain+'?task=expedients.deleteImage&mode=raw&id='+id,
			type: "post",
			datatype: 'json',
			data: {},
			success: function(data){
				Messenger().post({message: 'Imatge esborrada correctament', type: 'success', hideAfter: 10});
				$('.image'+id).remove();
			},
			error: function(data){
				Messenger().post({message: 'Sembla que tenim algun problema', type: 'error', hideAfter: 10});
			}
      	});
	 });

	//new
	$('#btn_new').click(function(e) {

		e.preventDefault();

		var pageURL = $(this).attr("href");
		var projId = getParameterByName('filter_equal_projecteId');

		if(projId == ''){
			document.location.href = domain+'/'+pageURL;
		} else {
			document.location.href = domain+'/'+pageURL+'&projecteId='+projId;
		}
 	});

	function deleteAccount(username, domain) {
		if($('#proceed').val().toLowerCase() == username) {
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

});
