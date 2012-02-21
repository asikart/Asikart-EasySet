<?php
//Ak Tools

$task = JRequest::getVar('task');
AKTask::$task();

class AKTask {
	function deleteArticle (){
		$app = JFactory::getApplication();
		
		$id = JRequest::getVar('id');
		$article = JTable::getInstance('content');
		$article->load( $id );
		$article->state = -2 ;
		if($article->store()) $msg = "刪除文章：( {$article->title} ) 成功！";
		else 				  $msg = "刪除文章：( {$article->title} ) 失敗！";
		
		$app->redirect( base64_decode( JRequest::getVar('redirect') ) , $msg );
	}
}

?>
