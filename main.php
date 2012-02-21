<?php

jimport('joomla.plugin.plugin');

class plgSystemAsikart_easyset extends JPlugin
{
	
	public $app 		;
	
	public $isHome 		;
	
	public $_metaDesc 	;
	
	public $_siteTitle 	;
	
	public $_catName 	;
	
	/**
	 * Constructor
	 *
	 * @access      public
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.6
	 */
    public function __construct(& $subject, $config)
    {
		parent::__construct( $subject, $config );
		$this->loadLanguage();
		$this->app = JFactory::getApplication();
    }
    
    public function getFunction( $func ) {
		$func_name = explode( '.' , $func );
		$func_name = array_pop($func_name);
		$func_path = str_replace( '.' , DS , $func );
		
		if( !function_exists ( $func_name ) ) :			
			$file = AK_PATH.DS.$func_path.'.php' ;
			
			if( !file_exists($file) ) 
				$file = AK_ADMIN_LIB_PATH.DS.$func_path.'.php' ;
			
			if( file_exists($file) ) 
				include_once( $file ) ;
		endif;
		
		$args = func_get_args();
        array_shift( $args );
        
		if( function_exists ( $func_name ) )
			return call_user_func_array( $func_name , $args );
	}
	
	public function includeEvent($func) {
		$event = AK_PATH.DS.'events'.DS.$func.'.php' ;
		if(file_exists( $event )) return $event ;
	}
	
	// =========================== Events ======================================
	
	public function onAfterInitialise() {
		$this->getFunction( 'doCmd' ) ;
		if( $this->params->get( 'tranAlias' , 1 ) ) $this->getFunction( 'article.tranAlias' , $this );
		
		@include $this->includeEvent(__FUNCTION__);
	}
	
	
	public function onAfterRoute() {
		@include $this->includeEvent(__FUNCTION__);
	}
	
	
	public function onAfterDispatch() {
		if( $this->params->get( 'getMeta' , 1 ) ) $this->getFunction( 'seo.setDocument' , $this );
		$this->getFunction( 'includes.setScript' );
		
		@include $this->includeEvent(__FUNCTION__);
	}
	
	
	public function onAfterRender() {
		$this->getFunction( 'includes.insertHeader' );
		$this->getFunction( 'includes.setStyle' );
		@include $this->includeEvent(__FUNCTION__);
	}
	
	
	public function onContentPrepare($context, &$article, &$params, $page = 0) {
		if( $this->params->get( 'getMeta' , 1 ) ) $this->getFunction( 'seo.setContentMeta' , $article , $this );
		if( $this->params->get( 'openGraph' , 1 ) ) $this->getFunction( 'seo.setOpenGraph' , $context , $article , $this );
		
		if( $this->params->get( 'autoThumbnail' , 1 ) ) $this->getFunction( 'article.autoThumbnail' , $context, $article, $params ) ;
		
		$this->getFunction( 'article.inputCode' , $article , $this );
		
		$this->getFunction( 'article.customCode' , 'insertArticleTop' , true , $article );
		$this->getFunction( 'article.customCode' , 'insertContentTop' , true , $article );
		
		@include $this->includeEvent(__FUNCTION__);
	}
	
	
	public function onContentBeforeDisplay($context, &$article, &$params, $limitstart=0)
	{
		$result = null ;
		
		if( $this->params->get( 'blogViewClearly' , 1 ) )
			$this->getFunction( 'article.blogViewClearly' , $context, $article, $params );
		
		if( $this->params->get( 'fbLike' ) )
			$this->getFunction( 'article.addFbLikeButton' , $context, $article);
		
		@include $this->includeEvent(__FUNCTION__);
		
		return $result ;
	}
	
	
	public function onContentAfterDisplay($context, &$article, &$params, $limitstart=0)
	{
		$result = null ;
		
		if( JRequest::getVar('view') == 'article' )
			$result = $this->getFunction( 'article.customCode' , 'insertContentBottom' );
		
		@include $this->includeEvent(__FUNCTION__);
		
		return $result ;
	}
	
	
	public function onContentAfterTitle($context, &$article, &$params, $limitstart=0)
	{
		$result = $this->getFunction( 'article.customCode' , 'insertTitleBottom' );
		@include $this->includeEvent(__FUNCTION__);
		
		return $result ;
	}
	
	
	public function onContentBeforeSave($context, &$article, $isNew)
	{
		if( 'com_categories.category' !== $context ):
			if( $this->params->get( 'tidyRepair' , 1 ) ) $this->getFunction( 'article.tidyRepair' , $article , $this );
		endif;
		
		@include $this->includeEvent(__FUNCTION__);
	}
	
	
	public function onContentAfterSave($context, &$article, $isNew)
	{
		if( $this->params->get( 'getImages' , 1 ) ) $this->getFunction( 'article.saveImages' , $context , $article );
		
		@include $this->includeEvent(__FUNCTION__);

		return true;
	}
	
	
	public function onContentBeforeDelete($context, $data)
	{
		@include $this->includeEvent(__FUNCTION__);
		
		return true;
	}
	
	
	public function onContentAfterDelete($context, $data)
	{
		@include $this->includeEvent(__FUNCTION__);
		
		return true;
	}
	
	
	public function onContentChangeState($context, $pks, $value)
	{
		@include $this->includeEvent(__FUNCTION__);
		
		return true;
	}
	
}

