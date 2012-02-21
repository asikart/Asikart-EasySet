<?php

$db = JFactory::getDbo();
jimport( 'joomla.filesystem.path' );

$sql = <<<SQL

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
	
	DROP TABLE IF EXISTS `#__wf_profiles`;
	CREATE TABLE IF NOT EXISTS `#__wf_profiles` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) NOT NULL,
	  `description` varchar(255) NOT NULL,
	  `users` text NOT NULL,
	  `types` varchar(255) NOT NULL,
	  `components` text NOT NULL,
	  `area` tinyint(3) NOT NULL,
	  `rows` text NOT NULL,
	  `plugins` text NOT NULL,
	  `published` tinyint(3) NOT NULL,
	  `ordering` int(11) NOT NULL,
	  `checked_out` tinyint(3) NOT NULL,
	  `checked_out_time` datetime NOT NULL,
	  `params` text NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
	
	INSERT INTO `#__wf_profiles` (`id`, `name`, `description`, `users`, `types`, `components`, `area`, `rows`, `plugins`, `published`, `ordering`, `checked_out`, `checked_out_time`, `params`) VALUES
	(1, 'Default', 'Asikart 專用', '', '6,7,3,4,5,10,8', '', 0, 'cleanup,removeformat,bold,italic,underline,strikethrough,justifyfull,justifycenter,justifyleft,justifyright,spacer,formatselect,styleselect,fontselect,fontsizeselect;paste,forecolor,backcolor,style,spacer,blockquote,indent,outdent,numlist,bullist,sub,sup,textcase,hr,imgmanager,anchor,link,unlink,article;nonbreaking,preview,source,searchreplace,spacer,table,visualaid,fullscreen', 'cleanup,paste,style,textcase,imgmanager,link,article,nonbreaking,preview,source,searchreplace,table,fullscreen,browser,contextmenu,inlinepopups,media', 1, 1, 0, '0000-00-00 00:00:00', '{"editor":{"height":"500px","toolbar_theme":"default","toolbar_align":"left","toolbar_location":"top","statusbar_location":"bottom","path":"1","resizing":"1","resize_horizontal":"1","resizing_use_cookie":"1","dialog_theme":"jce","profile_content_css":"2","relative_urls":"1","theme_advanced_blockformats":["p","div","h1","h2","h3","h4","h5","h6","code","pre","span"],"theme_advanced_fonts_add":"\\u5fae\\u8edf\\u6b63\\u9ed1\\u9ad4","theme_advanced_font_sizes":"8px,10px,12px,13px,15px,16px,18px,24px,36px","inline_styles":"1","cdata":"1","allow_javascript":"1","allow_css":"1","allow_php":"1","allow_applet":"1","visualchars":"1","toggle":"1","toggle_state":"1","toggle_label":"[show\\/hide]","filesystem":{"name":"joomla","joomla":{"allow_root":"0","restrict_dir":"administrator,cache,components,includes,language,libraries,logs,media,modules,plugins,templates,xmlrpc"}},"upload_conflict":"overwrite","upload_runtimes":["html5","flash","silverlight"],"browser_position":"bottom","folder_tree":"1","list_limit":"all","validate_mimetype":"0"},"paste":{"use_dialog":"0","dialog_width":"450","dialog_height":"400","force_cleanup":"1","strip_class_attributes":"all","remove_spans":"0","remove_styles":"0","remove_empty_paragraphs":"0","remove_styles_if_webkit":"0","html":"1","text":"1"},"imgmanager":{"extensions":"image=jpeg,jpg,png,gif","hide_xtd_btns":"0","margin_top":"10","margin_right":"10","margin_bottom":"10","margin_left":"10","border":"0","border_width":"1","border_style":"solid","border_color":"#000000","tabs_rollover":"1","tabs_advanced":"1","attributes_dimensions":"1","attributes_align":"1","attributes_margin":"1","attributes_border":"1","upload":"1","folder_new":"1","folder_delete":"1","folder_rename":"1","folder_move":"1","file_delete":"1","file_rename":"1","file_move":"1"},"link":{"file_browser":"1","tabs_advanced":"1","attributes_anchor":"1","attributes_target":"1","links":{"joomlalinks":{"enable":"1","article_alias":"1","content":"1","static":"1","contacts":"1","weblinks":"1","menu":"1"}},"popups":{"jcemediabox":{"enable":"1"},"window":{"enable":"1"}}},"article":{"show_readmore":"1","show_pagebreak":"1","hide_xtd_btns":"0"},"source":{"highlight":"1","numbers":"1","wrap":"1","theme":"textmate"},"browser":{"extensions":"xml=xml;html=htm,html;office=doc,docx,ppt,xls;text=txt,rtf;image=gif,jpeg,jpg,png;acrobat=pdf;archive=zip,tar,gz,rar;flash=swf;quicktime=mov,mp4,qt;windowsmedia=wmv,asx,asf,avi;audio=wav,mp3,aiff;openoffice=odt,odg,odp,ods,odf","upload":"1","folder_new":"1","folder_delete":"1","folder_rename":"1","folder_move":"1","file_delete":"1","file_rename":"1","file_move":"1"},"media":{"strict":"1","iframes":"1","audio":"1","video":"1","object":"1","embed":"1","version_flash":"10,1,53,64","version_windowsmedia":"10,00,00,3646","version_quicktime":"7,3,0,0","version_java":"1,5,0,0","version_shockwave":"10,2,0,023"}}'),
	(2, 'Front End', 'Sample Front-end Profile', '', '3,4,10,5', '', 1, 'help,newdocument,undo,redo,spacer,bold,italic,underline,strikethrough,justifyfull,justifycenter,justifyleft,justifyright,spacer,formatselect,styleselect;paste,searchreplace,indent,outdent,numlist,bullist,cleanup,charmap,removeformat,hr,sub,sup,textcase,nonbreaking,visualchars;fullscreen,preview,print,visualaid,style,xhtmlxtras,anchor,unlink,link,imgmanager,spellchecker,article', 'contextmenu,inlinepopups,help,paste,searchreplace,fullscreen,preview,print,style,textcase,nonbreaking,visualchars,xhtmlxtras,imgmanager,link,spellchecker,article', 0, 2, 0, '0000-00-00 00:00:00', '');

SQL;

$db->setQuery($sql);
$db->queryBatch();

$config = JFactory::getConfig();
$config->set('editor' , 'jce' ) ;
$configFile = JPATH_ROOT.DS.'configuration.php' ;

JPath::setPermissions( $configFile , 644 );

$file = $config->toString('php' , array( 'class'=>'JConfig' ));
if(JFile::write( $configFile , $file ))
	echo '覆蓋完成';
else
	echo '覆蓋失敗，請檢查configuration.php權限' ;

