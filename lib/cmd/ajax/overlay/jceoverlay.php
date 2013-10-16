<?php

$db = JFactory::getDbo();
jimport( 'joomla.filesystem.path' );

$sql = <<<SQL
DROP TABLE IF EXISTS `#__wf_profiles`;
CREATE TABLE IF NOT EXISTS `#__wf_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `users` text NOT NULL,
  `types` text NOT NULL,
  `components` text NOT NULL,
  `area` tinyint(3) NOT NULL,
  `device` varchar(255) NOT NULL,
  `rows` text NOT NULL,
  `plugins` text NOT NULL,
  `published` tinyint(3) NOT NULL,
  `ordering` int(11) NOT NULL,
  `checked_out` tinyint(3) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `#__wf_profiles` (`id`, `name`, `description`, `users`, `types`, `components`, `area`, `device`, `rows`, `plugins`, `published`, `ordering`, `checked_out`, `checked_out_time`, `params`) VALUES
(1, 'Default', 'Default Profile for ASIKART / SMS', '', '6,7,3,4,5,10,8', '', 0, 'desktop,tablet,phone', 'cleanup,removeformat,spacer,bold,italic,underline,strikethrough,justifyfull,justifycenter,justifyleft,justifyright,spacer,fontselect,formatselect,styleselect,fontsizeselect;clipboard,forecolor,backcolor,spacer,style,blockquote,outdent,indent,lists,sub,sup,textcase,hr,imgmanager,anchor,link,unlink,article;nonbreaking,visualblocks,preview,source,searchreplace,table,visualaid,fullscreen', 'cleanup,clipboard,style,lists,textcase,imgmanager,anchor,link,article,nonbreaking,visualblocks,preview,source,searchreplace,table,fullscreen,browser,contextmenu,inlinepopups,media', 1, 1, 0, '0000-00-00 00:00:00', '{"editor":{"height":"500px","theme_advanced_font_sizes":"8px,10px,12px,13px,15px,16px,18px,24px,36px","allow_javascript":"1","allow_css":"1","allow_php":"1"},"clipboard":{"paste_remove_empty_paragraphs":"0"},"imgmanager":{"extensions":"image=PNG,JPG,jpeg,jpg,png,gif","margin_top":"10","margin_right":"10","margin_bottom":"10","margin_left":"10"},"media":{"iframes":"1"}}'),
(2, 'Front End', 'Sample Front-end Profile', '', '3,4,10,5', '', 1, 'desktop,tablet,phone', 'help,newdocument,undo,redo,spacer,bold,italic,underline,strikethrough,justifyfull,justifycenter,justifyleft,justifyright,spacer,formatselect,styleselect;clipboard,searchreplace,indent,outdent,lists,cleanup,charmap,removeformat,hr,sub,sup,textcase,nonbreaking,visualchars,visualblocks;fullscreen,preview,print,visualaid,style,xhtmlxtras,anchor,unlink,link,imgmanager,spellchecker,article', 'charmap,contextmenu,inlinepopups,help,clipboard,searchreplace,fullscreen,preview,print,style,textcase,nonbreaking,visualchars,visualblocks,xhtmlxtras,imgmanager,anchor,link,spellchecker,article,lists', 0, 2, 0, '0000-00-00 00:00:00', ''),
(3, 'Blogger', 'Simple Blogging Profile', '', '3,4,10,5,6,8,7', '', 0, 'desktop,tablet,phone', 'bold,italic,strikethrough,lists,blockquote,spacer,justifyleft,justifycenter,justifyright,spacer,link,unlink,imgmanager,article,spellchecker,fullscreen,kitchensink;formatselect,underline,justifyfull,forecolor,clipboard,removeformat,charmap,indent,outdent,undo,redo,help', 'link,imgmanager,article,spellchecker,fullscreen,kitchensink,clipboard,contextmenu,inlinepopups,lists', 0, 3, 0, '0000-00-00 00:00:00', '{"editor":{"toggle":"0"}}'),
(4, 'Mobile', 'Sample Mobile Profile', '', '3,4,10,5,6,8,7', '', 0, 'tablet,phone', 'undo,redo,spacer,bold,italic,underline,formatselect,spacer,justifyleft,justifycenter,justifyfull,justifyright,spacer,fullscreen,kitchensink;styleselect,lists,spellchecker,article,link,unlink', 'fullscreen,kitchensink,spellchecker,article,link,inlinepopups,lists', 0, 4, 0, '0000-00-00 00:00:00', '{"editor":{"toolbar_theme":"mobile","resizing":"0","resize_horizontal":"0","resizing_use_cookie":"0","toggle":"0","links":{"popups":{"default":"","jcemediabox":{"enable":"0"},"window":{"enable":"0"}}}}}');
SQL;

$queries = $db->splitSql($sql);
foreach($queries as $query){
    $db->setQuery($query);
    $db->execute();
}

$config = JFactory::getConfig();
$config->set('editor' , 'jce' ) ;
$configFile = JPATH_ROOT.DS.'configuration.php' ;

JPath::setPermissions( $configFile , 644 );

$file = $config->toString('php' , array( 'class'=>'JConfig' ));
if(JFile::write( $configFile , $file ))
	echo '覆蓋完成';
else
	echo '覆蓋失敗，請檢查 SQL' ;

