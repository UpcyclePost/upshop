tinymce.init({
    selector: "textarea.wk_tinymce",
    theme: "modern",
    plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table directionality emoticons template paste textcolor"
   	],
   	toolbar : "code,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,|,blockquote,colorpicker,pasteword,|,bullist,numlist,|,outdent,indent,|,link,unlink,|,cleanup",
	statusbar: false,
	relative_urls : false,
	language: iso,
	extended_valid_elements : "em[class|name|id]",
	browser_spellcheck : true,
  	menu: {
		edit: {title: 'Edit', items: 'undo redo | cut copy paste | selectall'},
		insert: {title: 'Insert', items: 'media image link | pagebreak'},
		view: {title: 'View', items: 'visualaid'},
		format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
		table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
		tools: {title: 'Tools', items: 'code'}
	},
    setup : function(ed) {
		//peform this action every time a key is pressed
        ed.on('keyUp',function(ed, e) {
	        //define local variables
	        var tinymax, tinylen, htmlcount;
	        //manually setting our max character limit
	        tinymax = $("#" + tinymce.activeEditor.id).attr("maxlength");;
	        //grabbing the length of the curent editors content
	        tinylen = tinymce.activeEditor.getContent().length;
	        //setting up the text string that will display in div
	        htmlcount = "HTML Character Count: " + tinylen + "/" + tinymax;
	        //if the user has exceeded the max turn the path bar red.
	        if (tinylen>tinymax && tinymce.activeEditor.keyCode!=8 && tinymce.activeEditor.keyCode !=46){
		        // place text string in path bar
		        if ( $('#max_char_string').size() ){
	          		$("."+tinymce.activeEditor.id+"_length").html('<span id="max_char_string" style="color:#F00;font-weight:bold">'+htmlcount+'</span>')
		          //$('#max_char_string').html( '' + htmlcount);
		        }
	        }
	        else {
	          $("."+tinymce.activeEditor.id+"_length").html('<span id="max_char_string">'+htmlcount+'</span>')
	        }
        });
	}
 });
