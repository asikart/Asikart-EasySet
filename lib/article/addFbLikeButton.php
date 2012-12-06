<?php

function addFbLikeButton ($context, $article) {
	$context ;
	$context = explode( '.' , $context );
	if( $context[0] != 'com_content' ) return ;
	
	// set Route
	$uri = AK::_('jcontent.getArticleLink', "{$article->id}:{$article->alias}" , $article->catid , 1 );
	
	// set like	
	$es = AKEasyset::getInstance();
	$position = $es->params->get('fbLikePosition' , 1 ) ;
	
	$like = <<<LIKE
	<div class="asikart-fb-like">
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) {return;}
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/zh_TW/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		
		<fb:like href="{$uri}" send="false" show_faces="false"></fb:like>
	</div>
LIKE;
	
	
	$get = JRequest::get();
	
	if( JRequest::getVar('view') == 'featured' || JRequest::getVar('layout') == 'blog' ) {
		
		if( $es->params->get('fbLikeOnBlog' , 0 ) ){
			$article->introtext = $like.$article->introtext ;
		}

	}elseif( $get['view'] == 'article' ) {
	
		switch( $position ) {
			// After Title
			case 1 :
				$article->text = $like.$article->text ;
			break;
			
			// After Content
			case 2 :
				$article->text = $article->text.$like ;
			break;
			
			// Both
			case 3 :
				$article->text = $like.$article->text.$like ;
			break;
		}
	
	}
	
}
