tinymce.init({
    selector: "textarea.wk_tinymce",
    theme: "modern",
    plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality emoticons template paste textcolor"
   	],
   	toolbar : "code,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,|,blockquote,colorpicker,pasteword,|,bullist,numlist,|,outdent,indent,|,link,unlink,|,cleanup,|,media,image",
	statusbar: false,
	relative_urls : false,
	language: iso,
	extended_valid_elements : "em[class|name|id]",
  	menu: {
		edit: {title: 'Edit', items: 'undo redo | cut copy paste | selectall'},
		insert: {title: 'Insert', items: 'media image link | pagebreak'},
		view: {title: 'View', items: 'visualaid'},
		format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
		table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
		tools: {title: 'Tools', items: 'code'}
	}
 });