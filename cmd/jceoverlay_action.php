<?php
header('Content-Type:text/html;charset=utf-8');//防止中文信息有亂碼
header('Cache-Control:no-cache');//防止瀏覽器緩存，導致按F5刷新不管用

$db = JFactory::getDBO();

$sql = <<<SQL

DROP TABLE IF EXISTS `#__jce_groups`;
CREATE TABLE IF NOT EXISTS `#__jce_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `users` text NOT NULL,
  `types` varchar(255) NOT NULL,
  `components` text NOT NULL,
  `rows` text NOT NULL,
  `plugins` varchar(255) NOT NULL,
  `published` tinyint(3) NOT NULL,
  `ordering` int(11) NOT NULL,
  `checked_out` tinyint(3) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `#__jce_groups` (`id`, `name`, `description`, `users`, `types`, `components`, `rows`, `plugins`, `published`, `ordering`, `checked_out`, `checked_out_time`, `params`) VALUES
(1, 'Default', 'Default group for all users with edit access', '', '19,20,21,23,24,25', '', '6,7,8,9,10,11,12,13,14,15,16,17,18,19;20,21,22,23,24,25,26,27,28,30,31,32,33,36;37,38,39,40,41,42,43,44,45,46,47,48;49,50,51,52,53,54,55,57,58', '1,2,3,4,5,6,20,21,37,38,39,40,41,42,49,50,51,52,53,54,55,57,58', 1, 1, 0, '0000-00-00 00:00:00', ''),
(2, 'Front End', 'Sample Group for Authors, Editors, Publishers', '', '19,20,21', '', '6,7,8,9,10,13,14,15,16,17,18,19,27,28;20,21,25,26,30,31,32,36,43,44,45,47,48,50,51;24,33,39,40,42,46,49,52,53,54,55,57,58', '6,20,21,50,51,1,3,5,39,40,42,49,52,53,54,55,57,58', 0, 2, 0, '0000-00-00 00:00:00', ''),
(4, 'Asikart', '飛鳥工作室專用', '', '0,18,19,20,21,23,24,25', '', '8,9,10,14,15,16,17,18,27,28,12,11,13,19;48,20,32,21,22,23,49,53,54,24,33,25,26,30,31,43,44,45;41,57,39,58', '1,2,3,4,5,20,21,39,41,49,53,54,57,58', 1, 0, 0, '0000-00-00 00:00:00', 'editor_width=\neditor_height=\neditor_toggle=1\neditor_theme_advanced_toolbar_location=top\neditor_theme_advanced_toolbar_align=center\neditor_skin=default\neditor_skin_variant=default\neditor_inlinepopups_skin=clearlooks2\neditor_relative_urls=1\neditor_invalid_elements=\neditor_extended_elements=\neditor_event_elements=a,img\neditor_allow_javascript=1\neditor_allow_css=1\neditor_allow_php=1\neditor_theme_advanced_blockformats=p,div,h1,h2,h3,h4,h5,h6,blockquote,dt,dd,code,samp,pre\neditor_theme_advanced_fonts_add=微軟正黑體=微軟正黑體,arial;\neditor_theme_advanced_fonts_remove=\neditor_font_size_style_values=8px,10px,12px,13px,15px,17px,18px,22px,26px\neditor_dir=images/stories\neditor_max_size=1024\neditor_upload_conflict=\neditor_preview_height=550\neditor_preview_width=750\neditor_custom_colors=\nbrowser_dir=$username\nbrowser_max_size=\nbrowser_extensions=xml=xml;html=htm,html;word=doc,docx;powerpoint=ppt;excel=xls;text=txt,rtf;image=gif,jpeg,jpg,png;acrobat=pdf;archive=zip,tar,gz;flash=swf;winrar=rar;quicktime=mov,mp4,qt;windowsmedia=wmv,asx,asf,avi;audio=wav,mp3,aiff;openoffice=odt,odg,odp,ods,odf\nbrowser_extensions_viewable=html,htm,doc,docx,ppt,rtf,xls,txt,gif,jpeg,jpg,png,pdf,swf,mov,mpeg,mpg,avi,asf,asx,dcr,flv,wmv,wav,mp3\nbrowser_upload=1\nbrowser_upload_conflict=\nbrowser_folder_new=1\nbrowser_folder_delete=1\nbrowser_folder_rename=1\nbrowser_file_delete=1\nbrowser_file_rename=1\nbrowser_file_move=1\nmedia_use_script=0\nmedia_strict=1\nmedia_version_flash=9,0,124,0\nmedia_version_windowsmedia=5,1,52,701\nmedia_version_quicktime=6,0,2,0\nmedia_version_realmedia=7,0,0,0\nmedia_version_shockwave=11,0,0,458\npaste_create_paragraphs=1\npaste_create_linebreaks=1\npaste_use_dialog=0\npaste_auto_cleanup_on_paste=0\npaste_strip_class_attributes=all\npaste_remove_spans=1\npaste_remove_styles=1\nimgmanager_dir=$usertype\nimgmanager_max_size=\nimgmanager_extensions=image=jpeg,jpg,png,gif,JPG,PNG\nimgmanager_margin_top=10\nimgmanager_margin_right=10\nimgmanager_margin_bottom=10\nimgmanager_margin_left=10\nimgmanager_border=0\nimgmanager_border_width=default\nimgmanager_border_style=default\nimgmanager_border_color=#000000\nimgmanager_align=default\nimgmanager_upload=1\nimgmanager_upload_conflict=\nimgmanager_folder_new=1\nimgmanager_folder_delete=1\nimgmanager_folder_rename=1\nimgmanager_file_delete=1\nimgmanager_file_rename=1\nimgmanager_file_move=1\nadvlink_target=default\nadvlink_content=1\nadvlink_static=1\nadvlink_contacts=1\nadvlink_weblinks=1\nadvlink_menu=1\n\n');

SQL;

$db->setQuery($sql);
$response = $db->queryBatch();

if($response) echo '覆蓋成功';
else echo '失敗';
?>