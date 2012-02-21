<?php
header('Content-Type:text/html;charset=utf-8');//防止中文信息有亂碼
header('Cache-Control:no-cache');//防止瀏覽器緩存，導致按F5刷新不管用

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

$folders = JFolder::listFolderTree(JPATH_PLUGINS.DS.'system'.DS.'asikart_easy_set','.',10);
$files = JFolder::files('.','.',true,true,array('.svn','asikart-backup.zip'));
$files2 = array();

?>
<h1>壓縮中，完成將自動下載 - ASIKART Backup System</h1>

<textarea  cols="120" rows="20">

<?php

foreach($files as $key=>$val):
	//$files[$key] = str_replace('\\', '/', $val);
	//$files[$key] = strstr($files[$key],'./');
	//$output = '<filename>'.$files[$key].'</filename>';
	//$files[$key] = trim($files[$key],".");
	echo $key.' - '.htmlentities($files[$key])."\n";
endforeach;

?>

</textarea>
<?php

//dump sql
$db = JFactory::getDBO();

$dbname = $mainframe->getCfg('db');
$dbhost = $mainframe->getCfg('host');
$dbuser = $mainframe->getCfg('user');
$dbpass = $mainframe->getCfg('password');

$prefix = $db->getPrefix();
$tables = $db->getTableList();
$ignore = array();

//set ignore cmd
foreach( $tables as $table ) {
	if( !strstr($table , $prefix) ) {
		$ignore[] = "--ignore-table={$dbname}.$table" ;
	}
}

$ignore = implode(' ',$ignore);

//dump file
$backupFile = $dbname . date("Y-m-d-H-i-s") . '.sql';
echo $command = "mysqldump -h $dbhost -u $dbuser -p --password=$dbpass $ignore $dbname > $backupFile";
echo system($command);


//ZIP files
$zip = new ZipArchive;
if ($zip->open('asikart-backup.zip',ZipArchive::CREATE) === TRUE) {
    
	foreach ($files as $file)
		$zip->addFile($file);
    
    $zip->addFile($backupFile);
    
	$zip->close();
    echo '<br />ZIP ok';
} else {
    echo '<br />failed';
}

global $mainframe ;
$mainframe->redirect(JURI::base().DS.'asikart-backup.zip');


?>