<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
$user =& JFactory::getUser();
if ($user->id > 0)
{
$data = $this->data;

//Addding Main CSS/JS VM_Theme files to header
JHTML::stylesheet("theme.css", VM_THEMEURL);
JHTML::stylesheet("template.css", "components/com_wishlist/");

global $mm_action_url;
$my_page =& JFactory::getDocument();
$conf =& JFactory::getConfig();
$sitename = $conf->getValue('config.sitename');
$my_page->setTitle($sitename. ' - ' .JText::_( 'VM_SHARE_FAVORITES' )); 
?>
<table width="100%">
	<thead>
		<tr>
			<th>
				<span class="fav_title"><?php echo JText::_( 'VM_SHARE_FAVORITES' ); ?></span>
            </th>
		</tr>
        <tr>
        	<th>
<?php
$mode = JRequest::getCmd('mode');
$share_date = $data->share_date;
switch ($mode) {
				case "share":
					echo "<div class='fav_header'>". JText::_('VM_FAVORITE_SH_UPDATED');
					if ($share_date > "1900-01-01") echo ' '.JText::_('VM_FAVORITE_SH_PUBLIC');
					else echo ' '.JText::_('VM_FAVORITE_SH_PRIVATE');
					echo "</div>";
        			break;
    			case "unshare":
					echo "<div class='fav_header'>". JText::_('VM_FAVORITE_USH_UPDATED')."</div>";
        			break;
}
?>
			</th>
		</tr>
        <tr>
        	<th>
            </th>
        </tr>
    </thead>
    <tbody>
    <tr>
    	<td>
<?php
$option = JRequest::getString('option',  "");
$view = JRequest::getString('view',  "");
$itemid = JRequest::getInt('Itemid',  1);
if (!empty($data)) $share_title = $data->share_title;
else $share_title = JText::_('VM_FAVORITE_LIST');
$share_desc = $data->share_desc;
$iswishlist = $data->isWishList;
if ($share_date > "1900-01-01" || !$share_date) $acc_opt = "<option value=\"public\" selected=\"selected\">".JText::_('VM_SHARE_PUBLIC')."</option><option value=\"private\">".JText::_('VM_SHARE_PRIVATE')."</option>";
else $acc_opt = "<option value=\"public\">".JText::_('VM_SHARE_PUBLIC')."</option><option value=\"private\" selected=\"selected\">".JText::_('VM_SHARE_PRIVATE')."</option>";
if ($iswishlist == 0) $wish_opt = "<option value=\"0\" selected=\"selected\">".JText::_('VM_WISHLIST_NO')."</option><option value=\"1\">".JText::_('VM_WISHLIST_YES')."</option>";
else $wish_opt = "<option value=\"0\">".JText::_('VM_WISHLIST_NO')."</option><option value=\"1\" selected=\"selected\">".JText::_('VM_WISHLIST_YES')."</option>";
$form_share_favorites = "<script language=\"javascript\" type=\"text/javascript\">\n <!--\n function imposeMaxLength(Object, MaxLen)\n {\n return (Object.value.length <= MaxLen);\n }\n -->\n </script>\n";
$form_share_favorites .= "<div align=\"left\">\n<form action=\"". $mm_action_url ."index.php\" method=\"POST\" name=\"share\" id=\"share\">\n
				<input type=\"hidden\" name=\"option\" value=\"$option\" />\n
				<input type=\"hidden\" name=\"view\" value=\"$view\" />\n
				<input type=\"hidden\" name=\"Itemid\" value=\"$itemid\" />\n
				<input type=\"hidden\" name=\"mode\" value=\"share\" />\n"
				  .JText::_('VM_SHARE_ACCESS')."&nbsp;<select name=\"acc_type\" id=\"acc_type\">".$acc_opt."</select>&nbsp;
				 ".JText::_('VM_IS_WISHLIST')."&nbsp;<select name=\"is_wishlist\" id=\"is_wishlist\">".$wish_opt."</select><br /><br />
                ".JText::_('VM_SHARE_TITLE')."<br /><input id=\"share_title\" class=\"inputbox\" size=\"35\" maxlength=\"32\" name=\"share_title\" value=\"". $share_title ."\" /><br /><br />
				".JText::_('VM_SHARE_DESC')."<br /><textarea id=\"share_desc\" class=\"inputbox\" cols=\"50\" rows=\"2\" name=\"share_desc\" onkeypress=\"return imposeMaxLength(this, 100);\" >". $share_desc ."</textarea><br /><br />
                  <input type=\"submit\" class=\"addtofav_button\" value=\"".JText::_('VM_SHARE_BUTTON')."\" title=\"".JText::_('VM_SHARE_BUTTON')."\" />
              </form>\n</div>\n";
$form_unshare_favorites = "<div align=\"left\">\n<form action=\"". $mm_action_url ."index.php\" method=\"POST\" name=\"unshare\" id=\"unshare\">\n
				<input type=\"hidden\" name=\"option\" value=\"$option\" />\n
				<input type=\"hidden\" name=\"view\" value=\"$view\" />\n
				<input type=\"hidden\" name=\"Itemid\" value=\"$itemid\" />\n
				<input type=\"hidden\" name=\"mode\" value=\"unshare\" />\n
                <input type=\"submit\" class=\"deletefav_button\" value=\"".JText::_('VM_UNSHARE_BUTTON')."\" title=\"".JText::_('VM_SHARE_BUTTON')."\" onclick=\"return confirm('".JText::_('VM_FAVORITE_UNSHARE_MSG')."')\" />
              </form>\n</div>\n";
//Email Form
$form_share_link = JURI::base().'?option=com_wishlist&view=sharelist&user_id='.$data->user_id;
$form_email_favorites = "<p><span class=\"fav_title\">". JText::_( 'VM_EMAIL_SHARE' ). "</span><div align=\"left\">\n<form action=\"". $mm_action_url ."index.php\" method=\"POST\" name=\"sendmail\" id=\"sendmail\">\n
				<input type=\"hidden\" name=\"option\" value=\"$option\" />\n
				<input type=\"hidden\" name=\"view\" value=\"$view\" />\n
				<input type=\"hidden\" name=\"Itemid\" value=\"$itemid\" />\n
				<input type=\"hidden\" name=\"mode\" value=\"sendmail\" />\n"
				.JText::_('VM_EMAIL_TO')."<br /><input id=\"email_to\" class=\"inputbox\" size=\"35\" name=\"email_to\"/><br /><br />"
				.JText::_('VM_EMAIL_SUBJECT')."<br /><input id=\"email_subj\" class=\"inputbox\" size=\"35\" maxlength=\"32\" name=\"email_subj\" value=\"". $share_title ."\" /><br /><br />"
				.JText::_('VM_EMAIL_BODY')."<br /><textarea id=\"email_body\" class=\"inputbox\" cols=\"50\" rows=\"2\" name=\"email_body\" onkeypress=\"return imposeMaxLength(this, 100);\" >". $share_desc ."</textarea><br />".JText::_('VM_EMAIL_BODY_NOTE')."<br /><br />
                  <input type=\"submit\" class=\"modns button art-button art-button addtocart_button\" value=\"".JText::_('VM_EMAIL_SEND')."\" title=\"".JText::_('VM_EMAIL_SEND')."\" />
              </form>\n</div>\n</p>\n";
$form_social_fb = '<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=143548565733526&amp;xfbml=1"></script><fb:like href="'.$form_share_link.'" send="true" layout="button_count" width="100" show_faces="false" action="recommend"></fb:like>';
$form_social_tw ='<a href="http://twitter.com/share" class="twitter-share-button" data-url="'.$form_share_link.'" data-text="'.$share_title.'" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
$form_social_gp ='<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script><g:plusone size="medium" href="'.$form_share_link.'"></g:plusone>';

echo $form_share_favorites;
echo $form_unshare_favorites;
if (empty($data)) echo "<p><div class='fav_header'>". JText::_('VM_SHARELIST_MANDATORY')."</div></p>";
else{
echo '<div style="clear:both"><span class="fav_title">'. JText::_( 'VM_SOCIAL_SHARE' ). '</span></div>';
echo $form_social_fb;
echo $form_social_tw;
echo $form_social_gp;
echo '<p><span class="fav_title">'. JText::_( 'VM_SOCIAL_LINK' ). '</span></p>';
echo '<span class="highlighted_txt">'.$form_share_link.'</span>';
echo $form_email_favorites;
}
?>
		</td>
	</tr>
<tbody>
</table>
<?php
}
else { ?>
<table width="100%">
	<thead>
		<tr>
			<th>
				<span class="fav_title"><?php echo JText::_( 'VM_SHARELIST_ERROR' ); ?></span>
			</th>
		</tr>
		<tr>
        	<th>
               	<?php echo "<div class='fav_header'>". JText::_('VM_SHARELIST_DENY')."</div>"; ?>
        	</th>
       	</tr>
    </thead>
</table>
<p style="padding:20px 0 20px 0"><input type="button" class="modns button art-button art-button addtocart_button" value="<?php echo JText::_( 'VM_SHARELIST_BACK' ); ?>" title="<?php echo JText::_( 'VM_SHARELIST_BACK' ); ?>" onclick="javascript:history.back()" /></p>
<?php
}
?>