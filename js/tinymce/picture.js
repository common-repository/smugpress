function SmugMugPicture ( options ) {
    this.plugin_uri = options.plugin_uri;
    this.plugin_dir = options.plugin_dir;

    var args = new Array();
    args['gallery'] = '';
	args['image'] = '';
	args['key'] = '';
	args['size'] = '';
	args['alt'] = '';
	args['align'] = '';
	args['style'] = '';
	args['width'] = '';
	args['height'] = '';
	args['link'] = 'No';
	args['hspace'] = '';
	args['vspace'] = '';

    this.args = args;
}


SmugMugPicture.prototype.gallery_select_save = function() {
	  var value = document.forms[0].gallery_select.options[document.forms[0].gallery_select.selectedIndex].value;
	  var element = document.getElementById( 'gallery' );
	  element.value = value;
}


SmugMugPicture.prototype.gallery_select_load = function() {
    var self = this;
	var formObj = document.forms[0];
	var url =  this.plugin_uri + "/include/ajax.php?cmd=gallery_select";
	var select = document.getElementById( "smugmug_gallery_select" );

    if( formObj.gallery.value )
        url += "&gallery_id=" + formObj.gallery.value;

	select.innerHTML = "... LOADING ...";

	var success = function(t) {
    	var s = document.getElementById( "smugmug_gallery_select" );
	  	s.innerHTML = t.responseText;
        self.gallery_select_save();
        self.image_select_load();
	}

	var failure = function(t) {
        var s = document.getElementById( "smugmug_gallery_select" );
	    s.innerHTML = t.responseText;
	    s.className = select.className + ' error';
	}

	var myAjax = new Ajax.Request(url, {method:'get', onSuccess:success, onFailure:failure});
	return false;
}

SmugMugPicture.prototype.image_select_save = function() {
	  var index;
	  var form = document.forms[0];
	  var aImageSelect = document.forms[0].image_select;
	  var value;

	  for( i = 0; i < aImageSelect.length; i++ ) {
	      if( aImageSelect[i].checked ) {
			  value = aImageSelect[i].value;
			  break;
		  }
      }

	  var element = document.getElementById( 'image' );
	  element.value = value;
}

SmugMugPicture.prototype.image_select_load = function() {
    var form = document.forms[0];
    var gallery_id = form.gallery.value;

    if( ! gallery_id )
	    return false;

	var url =  this.plugin_uri + "/include/ajax.php?cmd=image_select&gallery_id=" + gallery_id;
	var select = document.getElementById( "smugmug_image_select" );

    if( form.image.value )
	    url += "&image_id=" + form.image.value;

    select.innerHTML = "... LOADING ...";

	var success = function(t) {
		select.innerHTML = t.responseText;
        smugmug_image_select_save();
	}

	var failure = function(t) {
		select.innerHTML = t.responseText;
		select.className = select.className + ' error';
	}

	var myAjax = new Ajax.Request(url, {method:'post', onSuccess:success, onFailure:failure});
	return false;
}

SmugMugPicture.prototype.insert_image = function() {
	if( ! this.args['gallery'] ) {
		alert( "You must select a gallery" );
		return false;
	}

	if( ! this.args['image'] ) {
		alert( "You must select an image" );
		return false;
	}

	if( ! this.args['size'] ) {
		alert( "You must select a size" );
		return false;
	}

	var out = "<!-- SmugMugPicture ";

	out += " Gallery => "     + this.args['gallery'];
	out += " Image => "       + this.args['image'];
	out += " Key => "         + this.args['key'];
	out += " Size => "        + this.args['size'];
	out += " Description => " + this.args['alt'];
	out += " Align => "       + this.args['align'];
	out += " Style => "       + this.args['style'];
	out += " Width => "       + this.args['width'];
	out += " Height => "      + this.args['height'];
	out += " Link => "        + this.args['link'];
	out += " HSpace => "      + this.args['hspace'];
	out += " VSpace => "      + this.args['vspace'];

	out += "-->"

    window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, out);
    tinyMCE.execCommand("mceCleanup");

	return true;
}


SmugMugPicture.prototype.image_insert_submit = function() {
    var link = document.forms[0].link;
	this.args['gallery'] = document.forms[0].gallery.value,
	this.args['image']   = document.forms[0].image.value,
	this.args['key']     = document.forms[0].key.value,
	this.args['size']    = document.forms[0].size.options[document.forms[0].size.selectedIndex].value,
    this.args['align']   = document.forms[0].align.options[document.forms[0].align.selectedIndex].value,
	this.args['style']   = document.forms[0].style.value,
    this.args['width']   = document.forms[0].width.value,
	this.args['height']  = document.forms[0].height.value,
    this.args['alt']     = document.forms[0].alt.value,
    this.args['hspace']  = document.forms[0].hspace.value,
	this.args['vspace']  = document.forms[0].vspace.value,
    this.args['link']    = document.forms[0].link.checked ? "Yes" : "No"

	if( this.insert_image() ) {
    	tinyMCEPopup.restoreSelection();
	    tinyMCEPopup.close();
	}
}

SmugMugPicture.prototype.init = function() {
	tinyMCEPopup.resizeToInnerSize();

	document.getElementById('srcbrowsercontainer').innerHTML = getBrowserHTML('srcbrowser','src','image','theme_advanced_image');

	var formObj = document.forms[0];

	for (var i=0; i<document.forms[0].align.options.length; i++) {
		if (document.forms[0].align.options[i].value == tinyMCE.getWindowArg('align'))
			document.forms[0].align.options.selectedIndex = i;
	}

	for (var i=0; i<document.forms[0].size.options.length; i++) {
		if (document.forms[0].size.options[i].value == tinyMCE.getWindowArg('size'))
			document.forms[0].size.options.selectedIndex = i;
	}

    var link = tinyMCE.getWindowArg( 'link' );
    var regex = new RegExp( "^(n|no|false|0)$", "i" );
    var exclude_link = regex.exec( link );

    if( ! link )
        exclude_link = true;

	formObj.gallery.value = tinyMCE.getWindowArg('gallery');
	formObj.key.value     = tinyMCE.getWindowArg('key');
	formObj.alt.value     = tinyMCE.getWindowArg('alt');
	formObj.image.value   = tinyMCE.getWindowArg('image');
	formObj.align.value   = tinyMCE.getWindowArg('align');
	formObj.style.value   = tinyMCE.getWindowArg('style');
	formObj.width.value   = tinyMCE.getWindowArg('width');
	formObj.height.value  = tinyMCE.getWindowArg('height');
	formObj.link.checked  = exclude_link ? false : true;
	formObj.hspace.value  = tinyMCE.getWindowArg('hspace');
	formObj.vspace.value  = tinyMCE.getWindowArg('vspace');
	formObj.insert.value  = tinyMCE.getLang('lang_' + tinyMCE.getWindowArg('action'), 'Insert', true);

    this.gallery_select_load();
}

var preloadImg = new Image();

function resetImageData() {
	var formObj = document.forms[0];
	formObj.width.value = formObj.height.value = "";
}

function updateImageData() {
	var formObj = document.forms[0];

	if (formObj.width.value == "")
		formObj.width.value = preloadImg.width;

	if (formObj.height.value == "")
		formObj.height.value = preloadImg.height;
}

function getImageData() {
	preloadImg = new Image();
	tinyMCE.addEvent(preloadImg, "load", updateImageData);
	tinyMCE.addEvent(preloadImg, "error", function () {var formObj = document.forms[0];formObj.width.value = formObj.height.value = "";});
	preloadImg.src = tinyMCE.convertRelativeToAbsoluteURL(tinyMCE.settings['base_href'], document.forms[0].src.value);
}
