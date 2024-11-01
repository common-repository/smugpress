<?php
include_once( '../../../../../wp-config.php' );
include_once(ABSPATH . WPINC . '/classes.php');
include_once(ABSPATH . WPINC . '/functions.php');
require_once(ABSPATH . '/wp-content/plugins/smugpress/smugpress.php');
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{$lang_smugmug_insert_picture_title}</title>

<link rel="stylesheet" href="<?php echo SMUGMUGURL ?>/css/tinymce.css" type="text/css" />

<script type='text/javascript'>
/* <![CDATA[ */
	WPAjaxL10n = {
		defaultUrl: "/",
		permText: "You don\'t have permission to do that.",
		strangeText: "Something strange happened.  Try refreshing the page.",
		whoaText: "Slow down, I\'m still sending your data!"
	}
/* ]]> */
</script>

    <script type='text/javascript' src='<?php bloginfo( 'url' ) ?>/wp-includes/js/prototype.js?ver=1.5.1.1'></script>
    <script type='text/javascript' src='<?php bloginfo( 'url' ) ?>/wp-includes/js/jquery/jquery.js?ver=1.1.4'></script>
    <script type='text/javascript' src='<?php bloginfo( 'url' ) ?>/wp-includes/js/wp-ajax.js?ver=20070306'></script>

	<script language="javascript" type="text/javascript" src="<?php bloginfo( 'url' ) ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php bloginfo( 'url' ) ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php bloginfo( 'url' ) ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo SMUGMUGURL ?>/js/tinymce/picture.js"></script>

    <script type="text/javascript">
       var picture = new SmugMugPicture( { plugin_uri: '<?php echo SMUGMUGURL ?>', plugin_dir: "<?php echo SMUGMUGPATH ?>" } );
    </script>

	<base target="_self" />
</head>

<!-- WordPress: extra onload stuff is WP -->
<body id="smugmug_picture_insert" onload="tinyMCEPopup.executeOnLoad('picture.init();');" style="display: none">

<form  action="#">
	<div class="tabs">
		<ul>
			<li id="general_tab" class="current"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onmousedown="return false;">{$lang_smugmug_insert_picture_tab}</a></span></li>
		</ul>
	</div>

	<div class="panel_wrapper">
		<div id="general_panel" class="panel current">
     <table border="0" cellpadding="4" cellspacing="0">

		  <tr>
            <td nowrap="nowrap"><label for="gallery">{$lang_smugmug_insert_picture_gallery}</label></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
                <tr>
				  <td>
                    <div id="srcbrowsercontainer" class="smugmug_gallery_select"> </div>
                    <div id="smugmug_gallery_select" class="smugmug_gallery_select"> </div>
					<input id="gallery" type="hidden" />
					<input id="key" type="hidden" />
				  </td>
                </tr>
              </table></td>
          </tr>

		  <!-- Image Select -->
		  <tr>
            <td nowrap="nowrap"><label for="image">{$lang_smugmug_insert_picture_image}</label></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
					<input id="image" type="hidden" />
				    <div id="smugmug_image_select" class="image_select"> </div>
				  </td>
                </tr>
              </table></td>
          </tr>

		  <!-- Size Select -->
		  <tr>
            <td nowrap="nowrap"><label for="size">{$lang_smugmug_insert_picture_size}</label></td>
            <td><select id="size" name="size">
                <option value="Th">{$lang_smugmug_insert_picture_size_thumbnail}</option>
                <option value="S">{$lang_smugmug_insert_picture_size_small}</option>
                <option value="M">{$lang_smugmug_insert_picture_size_medium}</option>
                <option value="L">{$lang_smugmug_insert_picture_size_large}</option>
                <option value="XL">{$lang_smugmug_insert_picture_size_extra}</option>
                <option value="X2">{$lang_smugmug_insert_picture_size_extra2}</option>
                <option value="X3">{$lang_smugmug_insert_picture_size_extra3}</option>
                <option value="D">{$lang_smugmug_insert_picture_size_full}</option>
              </select>
			</td>
          </tr>

		  <!--  Alignment Select -->
          <tr>
            <td nowrap="nowrap"><label for="align">{$lang_smugmug_insert_picture_align}</label></td>
            <td><select id="align" name="align">
                <option value="">{$lang_smugmug_insert_picture_align_default}</option>
                <option value="baseline">{$lang_smugmug_insert_picture_align_baseline}</option>
                <option value="top">{$lang_smugmug_insert_picture_align_top}</option>
                <option value="middle">{$lang_smugmug_insert_picture_align_middle}</option>
                <option value="bottom">{$lang_smugmug_insert_picture_align_bottom}</option>
                <option value="texttop">{$lang_smugmug_insert_picture_align_texttop}</option>
                <option value="absmiddle">{$lang_smugmug_insert_picture_align_absmiddle}</option>
                <option value="absbottom">{$lang_smugmug_insert_picture_align_absbottom}</option>
                <option value="left">{$lang_smugmug_insert_picture_align_left}</option>
                <option value="right">{$lang_smugmug_insert_picture_align_right}</option>
              </select>
			</td>
          </tr>

		<!-- Class Select -->
		<tr>
		  <td nowrap="nowrap"><label for="style">{$lang_smugmug_insert_picture_style}</label></td>
                  <td><input id="style" name="style" type="text" value="" style="width: 300px" /></td>
		</tr>

		  <!-- Dimensions -->
		  <tr>
            <td nowrap="nowrap"><label for="dimensions">{$lang_smugmug_insert_picture_dimensions}</label></td>
            <td><input id="width" name="width" type="text" value="" size="3" maxlength="5" />&nbsp;w
              x
              <input id="height" name="height" type="text" value="" size="3" maxlength="5" />&nbsp;h</td>
          </tr>
          </tr>

		  <!-- Spacing -->
		  <tr>
            <td nowrap="nowrap"><label for="dimensions">{$lang_smugmug_insert_picture_spacing}</label></td>
            <td><input id="hspace" name="hspace" type="text" value="" size="3" maxlength="5" />&nbsp;{$lang_smugmug_insert_picture_spacing_horz}
              &nbsp;
              <input id="vspace" name="vspace" type="text" value="" size="3" maxlength="5" />&nbsp;{$lang_smugmug_insert_picture_spacing_vert}</td>
          </tr>
          </tr>

          <tr>
            <td nowrap="nowrap"><label for="alt">{$lang_smugmug_insert_picture_description}</label></td>
            <td><input id="alt" name="alt" type="text" value="" style="width: 300px" /></td>
          </tr>

          <tr>
            <td colspan="2" nowrap="nowrap"><label for="link">{$lang_smugmug_insert_picture_link}</label>
              <input id="link" name="link" type="checkbox" value="yes" /></td>
          </tr>
        </table>
		</div>
	</div>

	<div class="mceActionPanel">
		<!-- WordPress: buttons reversed! -->
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="{$lang_cancel}" onclick="tinyMCEPopup.close();" />
		</div>

		<div style="float: right">
			<input type="button" id="insert" name="insert" value="{$lang_insert}" onclick="picture.image_insert_submit();" />
		</div>
	</div>
</form>
</body>
</html>
