<?php
header('Content-Type:text/html;charset=utf-8');//防止中文信息有亂碼
header('Cache-Control:no-cache');//防止瀏覽器緩存，導致按F5刷新不管用

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.archive');
JLoader::import('joomla.filesystem.archive');

$mainframe = JFactory::getApplication();

// Create backup folder if not exists.
JFolder::create( JPATH_ROOT.DS.'backup' );

// List all Joomla! files
$files = JFolder::files(JPATH_ROOT,'.',true,true,array('.svn', 'CVS','.DS_Store','__MACOSX','error_log' ),array('.*~'));
$files2 = array();

// List Installation files
$installPath = AK_ADMIN_LIB_PATH.DS.'installation' ;
$installFile = $installPath.DS.'installation.tar.gz' ;

if( JFile::exists($installFile) ): // unzip installation files first
	if(JArchive::extract( $installFile , $installPath) ){
		JFile::delete( $installFile );
	}
endif;

// List installation files
$installFiles = JFolder::files($installPath,'.',true,true,
				array('.svn', 'CVS','.DS_Store','__MACOSX','error_log','backup','tmp','cache' ),array('.*~'));

?>
<h1>壓縮中，完成將自動下載 - ASIKART Backup System</h1>

<textarea  cols="120" rows="20">

<?php
$optput = '';
foreach($files as $key=>$val):
	//$files[$key] = str_replace('\\', '/', $val);
	//$files[$key] = strstr($files[$key],'./');
	//$output = '<filename>'.$files[$key].'</filename>';
	//$files[$key] = trim($files[$key],".");
	
	//$optput .= $key.' - '.htmlentities($files[$key])."\n";
endforeach;
echo $optput ;

//jexit();
?>

</textarea>
<br /><br /><br />
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
$backupFileName = $dbname . date("Y-m-d-H-i-s") . '.sql';
$backupFile = JPATH_ROOT.DS.'backup'.DS.$backupFileName ;
$command = "mysqldump -h $dbhost -u $dbuser -p --password=$dbpass $ignore $dbname > $backupFile";
echo system($command);

echo '<pre style="height: 350px;
overflow: auto;
border: 1px solid black;
padding: 10px;
width: 960px;">'.str_replace( '--ignore' , "\n\t--ignore" , $command ).'</pre><br /><br />' ;

// fix USING BTREE bug
$sqlTmp = JFile::read( $backupFile, false, 0, filesize($backupFile) );
$sqlTmp = str_replace( ' (`lft`,`rgt`) USING BTREE' , ' USING BTREE (`lft`,`rgt`)' , $sqlTmp );
JFile::write( $backupFile , $sqlTmp );


//ZIP files
$zip = new ZipArchive;
$zipFileName = 'asikart-backup-'.date("Y-m-d-H-i-s").'.zip' ;
$zipFile = JPATH_ROOT.DS.'backup'.DS.$zipFileName ;
if ($zip->open( $zipFile ,ZipArchive::CREATE) === TRUE) {
    
	foreach ($files as $file):	
		$filename = str_replace( JPATH_ROOT.'/' , '' , $file );
		if( $filename == 'configuration.php' ) continue;
		
		$dirs = explode( DS , $filename ) ;
		if( $dirs[0] == 'tmp' || $dirs[0] == 'cache' || $dirs[0] == 'backup' ) continue ;
		
		$zip->addFile($file , $filename );
		
    endforeach;
    
    foreach ($installFiles as $file):	
		$filename = str_replace( $installPath , 'installation/' , $file );
		$zip->addFile($file , $filename );
    endforeach;
    
    $zip->addFile($backupFile , 'installation/sql/mysql/joomla.sql' );
    $zip->addFile(JPATH_ROOT.DS.'configuration.php' , 'installation/configuration.php' );
    
	$zip->close();
    echo '<br />ZIP ok';
} else {
    echo '<br />failed';
}

$mainframe->redirect( JURI::root().'backup/' . $zipFileName);


?>