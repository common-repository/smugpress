<?php
include_once( '../../../../wp-config.php' );
include_once(ABSPATH . WPINC . '/classes.php');
include_once(ABSPATH . WPINC . '/functions.php');
require_once("../smugmug.php");
if (isset($smugmug)) {
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type='text/javascript'>
/* <![CDATA[ */
	WPAjaxL10n = {
		defaultUrl: "http://testing.family-french.org/wp-admin/admin-ajax.php",
		permText: "You don\'t have permission to do that.",
		strangeText: "Something strange happened.  Try refreshing the page.",
		whoaText: "Slow down, I\'m still sending your data!"
	}
/* ]]> */
</script>
<script type='text/javascript' src='http://testing.family-french.org/wp-includes/js/prototype.js?ver=1.5.1.1'></script>
<script type='text/javascript' src='http://testing.family-french.org/wp-includes/js/jquery/jquery.js?ver=1.1.4'></script>
<script type='text/javascript' src='http://testing.family-french.org/wp-includes/js/wp-ajax.js?ver=20070306'></script>
<script language="javascript" type="text/javascript" src="/wp-content/plugins/smugmug/js/tinymce/picture.js"></script>

</head>

<body onLoad="picture.init();">
	
<td><div id="gallery"></div></td>
</tr>
</table></td>
</tr>

<form id="tts">
<div id="smugmug_gallery_select" class="smugmug_gallery_select"> </div>
<div id="smugmug_image_select" class="smugmug_gallery_select"> </div>
</form>

<body>
</html>
