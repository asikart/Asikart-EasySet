<?php
header('Content-Type:text/html;charset=utf-8');//防止中文信息有亂碼
header('Cache-Control:no-cache');//防止瀏覽器緩存，導致按F5刷新不管用

define( '_JEXEC', 1 );
define('JPATH_BASE', dirname(__FILE__) );
define( 'DS', DIRECTORY_SEPARATOR );

require_once 'configuration.php';
require_once 'libraries/joomla/base/object.php';
require_once 'libraries/joomla/database/database.php';
require_once 'libraries/joomla/database/database/mysql.php';

$config = new JConfig() ;
$as_option['host'] = $config->host ;
$as_option['user'] = $config->user ;
$as_option['password'] = $config->password ;
$as_option['database'] = $config->db;
$as_option['prefix'] = $config->dbprefix ;
$db     = new JDatabaseMySQL($as_option);

$content_config = <<<CONFIG
show_noauth=0
show_title=1
link_titles=1
show_intro=1
show_section=0
link_section=0
show_category=0
link_category=0
show_author=0
show_create_date=0
show_modify_date=0
show_item_navigation=0
show_readmore=1
show_vote=0
show_icons=0
show_pdf_icon=0
show_print_icon=0
show_email_icon=0
show_hits=0
feed_summary=0
filter_groups=29
filter_type=BL
filter_tags=
filter_attritbutes=
CONFIG;

$com = new JObject();
$com->params = $content_config;
$com->id	 = 20 ;

if($db->updateObject('#__components',$com,'id')) echo '覆蓋成功';
else echo '失敗';

?>