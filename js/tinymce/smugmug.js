function smugmug_button_add_picture( encoded_data ) {
	var template = new Array();
	var uri = location.href;
	var baseURI = location.href.replace( /(.*)\/wp-admin.*/, "$1" );

    template['file'] = baseURI + "/wp-content/plugins/smugpress/js/tinymce/" + 'picture.php';
    template['width'] = 520;
    template['height'] = 435 + (tinyMCE.isMSIE ? 25 : 0);

	var args = {
	  gallery  : '',
	  image    : '',
	  key      : '',
	  size     : '',
	  align    : '',
	  style    : '',
	  width    : '',
	  height   : '',
	  alt      : '',
	  link     : '',
	  hspace   : '',
	  vspace   : '',
      action   : 'insert',

      inline   : 'yes'
	};

	var success = function(t) {
        var dataString = t.responseText;
        var data = eval('(' + dataString + ')');
        data['action'] = 'edit';
        data['inline'] = 'yes';
        tinyMCE.openWindow(template, data);
	}

	var failure = function(t) {
        alert( "Failed to parse encoded data" );
	}

	if( encoded_data ) {
        var url =  "../wp-content/plugins/smugpress/include/ajax.php?cmd=data_parse&data=" + encoded_data;
        var myAjax = new Ajax.Request(url, {method:'get', onSuccess:success, onFailure:failure});
    }
    else {
        tinyMCE.openWindow(template, args);
    }
}

function smugmug_button_add_gallery( encoded_data ) {
    alert( "Saving this feature for rev. 2.0" );
}

function smugmug_parse_block( block ) {
    var options = new Array();

	if( ! block )
	    return options;

	// Get ride of any html comment marks and the command
    block = block.replace( /<!--\s+SmugMug(Picture|Gallery)\s+/, "" );
    block = block.replace( /\s*-->/, "" );
    block = block.replace( /(\w+\s*=>)/g, "%%SMUGMUG_DELIM%%$1" );
    block = block.replace(/^\s+|\s+$/g,"");

    var aOptions = block.split( /%%SMUGMUG_DELIM%%/ );

    for( i = 0; i < aOptions.length; i++ ) {
        if( aOptions[i].length ) {
           var pairs = aOptions[i].split( /\s*=>\s*/ );
           var key = pairs[0].toLowerCase();
           key = key.replace(/^\s+|\s+$/g,"");
           if( pairs[1] )
		       pairs[1] = pairs[1].replace(/^\s+|\s+$/g,"");
           if( key )
		       options[key] = pairs[1];
        }
    }

    return options;
}
