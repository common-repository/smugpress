var TinyMCE_smugmugscript = {

    getInfo : function() {
        return {
            longname : 'SmugMug Gallery',
            author : 'Bill French',
            authorurl : 'http://www.grepsedia.com',
            infourl : 'http://www.grepsedia.com',
            version : "0.1"
        };
    },

    getControlHTML : function(cn) {
        switch (cn) {
            case "smugmug_picture":
                return tinyMCE.getButtonHTML(cn, 'lang_smugmug_add_picture', '{$pluginurl}/../../images/picture-button.png', 'mcesmugmug_picture');
            case "smugmug_gallery":
                return tinyMCE.getButtonHTML(cn, 'lang_smugmug_add_gallery', '{$pluginurl}/../../images/gallery-button.png', 'mcesmugmug_gallery');
        }
        return "";
    },

    execCommand : function(editor_id, element, command, user_interface, value) {
		var moreText;

        var inst = tinyMCE.getInstanceById(editor_id);
        var focusElm = inst.getFocusElement();

        if (focusElm.nodeName.toLowerCase() == "img" ) {
            moreText = tinyMCE.getAttrib(focusElm, 'moretext');
        }

        switch (command) {
            case "mcesmugmug_picture":
                smugmug_button_add_picture( moreText );
                return true;
            case "mcesmugmug_gallery":
                smugmug_button_add_gallery( moreText );
                return true;
        }
        return false;
    },

    cleanup : function( type, content ) {
        switch(type) {
            case 'insert_to_editor':
                var regex = new RegExp( '<!-- *SmugMug(Picture|Gallery)(.+?)-->', 'i' );
                var match;
                var startPos;
                var contentAfter;
                var length;

                while( match = regex.exec(content) ) {

                    length = match[0].length;
                    startPos = match.index;
                 	contentAfter = content.substring( startPos + length );
					content = content.substring(0, startPos);

                    content += this._getImageHTML( match[0] );

                    content += contentAfter;
                }
                break;

            case 'get_from_editor':
                var startPos = -1;
				while ((startPos = content.indexOf('<img', startPos+1)) != -1) {
					var endPos = content.indexOf('/>', startPos);
					var attribs = this._parseAttributes(content.substring(startPos + 4, endPos));

					if (attribs['id'] == "mce_plugin_smugmug_img") {
						endPos += 2;

						var moreText = attribs['moretext'] ? attribs['moretext'] : '';
						var embedHTML = unescape(attribs['moretext']);

						// Insert embed/object chunk
						chunkBefore = content.substring(0, startPos);
						chunkAfter = content.substring(endPos);
						content = chunkBefore + embedHTML + chunkAfter;
					}
				}
				break;

        }

        return content;
    },

	_getImageHTML : function( block ) {
        var alt   = "SmugMug";
        var id    = "mce_plugin_smugmug";
        var default_picture = '{$pluginurl}/../../images/smugmug_picture.png';
        var default_gallery = '{$pluginurl}/../../images/smugmug_gallery.png';
        var regex = new RegExp( '<!-- *SmugMug(Picture|Gallery)(.+?)-->', 'i' );
        var match = regex.exec(block);
        var moretext = escape(match[0]);
        var content = '';

        var image;
        var command;
        var options;
		
		var key = '';

        if( match[1] ) {
            options = smugmug_parse_block( match[2] );
            command = match[1].toLowerCase();
            if( command == 'picture' ) {
                id += "_img";
                image = default_picture;
            } else {
                id += "_gallery";
                image = default_gallery;
            }
        }

        if( options['key'] )
		    key = "_" + options['key'];

        // If this is a picture and we have an image id and gallery
        // then display it.  Otherwise show the default image.
        if( command && command == 'picture' && options['image'] && options['size'] ) {
            content += '<img ';
            content += "src='http://www.smugmug.com/photos/" +
                        options['image'] + key + "-" + options['size'] + ".jpg' ";

            if( options['align'] )  content += 'align="'  + options['align']  + '"';
            if( options['height'] ) content += 'height="' + options['height'] + '"';
            if( options['width'] )  content += 'width="'  + options['width']  + '"';
            if( options['hspace'] ) content += 'hspace="' + options['hspace'] + '"';
            if( options['vspace'] ) content += 'vspace="' + options['vspace'] + '"';
            if( options['style'] )  content += 'style="'  + options['style']  + '"';

            content += 'moretext="' + moretext + '" ';
            content += 'id="' + id + '" ';
            content += ' />';
        }

        // default images
        else {
            match = escape(match[0]);
            content += '<img ';
            content += 'src="' + image + '" ';
            content += 'border="1" ';
            content += 'moretext="' + match + '" ';
            content += 'alt="'+alt+'" title="'+alt+'" id="'+id+'" />';
        }

        return content;
    },

	handleNodeChange : function(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {

		tinyMCE.switchClass(editor_id + '_smugmug_picture', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_smugmug_gallery', 'mceButtonNormal');

		if (node == null)
			return false;

		do {
			if (node.nodeName.toLowerCase() == "img" && tinyMCE.getAttrib(node, 'id').indexOf('mce_plugin_smugmug_img') == 0) {
				tinyMCE.switchClass(editor_id + '_smugmug_picture', 'mceButtonSelected');
			}
			if (node.nodeName.toLowerCase() == "img" && tinyMCE.getAttrib(node, 'id').indexOf('mce_plugin_smugmug_gallery') == 0) {
				tinyMCE.switchClass(editor_id + '_smugmug_gallery', 'mceButtonSelected');
			}
		} while ((node = node.parentNode));

		return true;
	},


    _parseAttributes : function(attribute_string) {
        var attributeName = "";
        var attributeValue = "";
        var withInName;
        var withInValue;
        var attributes = new Array();
        var whiteSpaceRegExp = new RegExp('^[ \n\r\t]+', 'g');
        if (attribute_string == null || attribute_string.length < 2)
            return null;
        withInName = withInValue = false;
        for (var i=0; i<attribute_string.length; i++) {
            var chr = attribute_string.charAt(i);
            if ((chr == '"' || chr == "'") && !withInValue)
                withInValue = true;
            else if ((chr == '"' || chr == "'") && withInValue) {
                withInValue = false;
            var pos = attributeName.lastIndexOf(' ');
            if (pos != -1)
                attributeName = attributeName.substring(pos+1);
                attributes[attributeName.toLowerCase()] = attributeValue.substring(1);
                attributeName = "";
                attributeValue = "";
            } else if (!whiteSpaceRegExp.test(chr) && !withInName && !withInValue)
            withInName = true;
            if (chr == '=' && withInName)
                withInName = false;
            if (withInName)
                attributeName += chr;
            if (withInValue)
                attributeValue += chr;
        }
        return attributes;
    }

};


if(window.tinyMCE) {
    tinyMCE.importPluginLanguagePack('smugmug', 'en');

    // Adds the plugin class to the list of available TinyMCE plugins
    tinyMCE.addPlugin("smugmug", TinyMCE_smugmugscript );
}
