<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

/**
 * Class plgSystemAsikart_easyset
 */
class plgSystemAsikart_easyset extends JPlugin
{
	/**
	 * @var plgSystemAsikart_easyset
	 */
	static public $instance;

	/**
	 * @var JApplicationCms
	 */
	public $app;

	/**
	 * @var boolean
	 */
	public $isHome;

	/**
	 * @var string
	 */
	public $_metaDesc;

	/**
	 * @var string
	 */
	public $_siteTitle;

	/**
	 * @var string
	 */
	public $_catName;

	/**
	 * @var string
	 */
	public $ogImage;

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 * @since   1.5
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();

		$this->app = JFactory::getApplication();

		AKHelper::_('include.addCSS', 'windwalker-admin.css', 'ww');

		self::$instance = $this;
	}

	// =========================== Events ======================================

	/**
	 * System init.
	 *
	 * @return void
	 */
	public function onAfterInitialise()
	{
		$this->getFunction('doCmd');

		if ($this->params->get('tranAlias', 1))
		{
			$this->getFunction('article.tranAlias', $this);
		}

		if ($this->params->get('languageOrphan', 0))
		{
			$this->getFunction('system.languageOrphan');
		}

		@include $this->includeEvent(__FUNCTION__);
	}

	/**
	 * System Route.
	 *
	 * @return void
	 */
	public function onAfterRoute()
	{
		@include $this->includeEvent(__FUNCTION__);
	}

	/**
	 * System dispatch.
	 *
	 * @return void
	 */
	public function onAfterDispatch()
	{
		$this->getFunction('system.secure');

		$this->getFunction('seo.setDocument', $this);

		$this->getFunction('includes.setScript');

		@include $this->includeEvent(__FUNCTION__);
	}

	/**
	 * System render.
	 *
	 * @return void
	 */
	public function onAfterRender()
	{
		$this->getFunction('includes.insertHeader');
		$this->getFunction('includes.setStyle');

		if ($this->params->get('cacheManagerEnabled', 0) && $this->app->isSite())
		{
			$this->getFunction('system.cacheManager');
		}

		@include $this->includeEvent(__FUNCTION__);
	}

	public function onContentPrepare($context, $article, $params, $page = 0)
	{
		// getMeta
		//if( $this->params->get( 'getMeta' , 1 ) )
		$this->getFunction('seo.setContentMeta', $article, $this);

		// OpenGraph
		if ($this->params->get('openGraph', 1))
		{
			$this->getFunction('seo.setOpenGraph', $context, $article, $this);
		}

		// Auto Thumb
		if ($this->params->get('autoThumbnail', 1))
		{
			$this->getFunction('article.autoThumbnail', $context, $article, $params);
		}

		// Input Code
		$this->getFunction('article.inputCode', $article, $this);

		// Custom Code
		$this->getFunction('article.customCode', 'insertArticleTop', true, $article);
		$this->getFunction('article.customCode', 'insertContentTop', true, $article);

		@include $this->includeEvent(__FUNCTION__);
	}

	public function onContentBeforeDisplay($context, $article, $params, $limitstart = 0)
	{
		$result = null;

		// Blog View Clearly
		if ($this->params->get('blogViewClearly', 1))
		{
			$this->getFunction('article.blogViewClearly', $context, $article, $params);
		}

		@include $this->includeEvent(__FUNCTION__);

		return $result;
	}

	public function onContentAfterDisplay($context, $article, $params, $limitstart = 0)
	{
		$result = null;

		// Custom Code
		if (JRequest::getVar('view') == 'article')
		{
			$result = $this->getFunction('article.customCode', 'insertContentBottom');
		}

		// FB Like
		if ($this->params->get('fbLike'))
		{
			$this->getFunction('article.addFbLikeButton', $context, $article);
		}

		@include $this->includeEvent(__FUNCTION__);

		return $result;
	}

	public function onContentAfterTitle($context, $article, $params, $limitstart = 0)
	{
		$result = $this->getFunction('article.customCode', 'insertTitleBottom');
		@include $this->includeEvent(__FUNCTION__);

		return $result;
	}

	public function onContentBeforeSave($context, $article, $isNew)
	{
		$result = array();

		if ('com_categories.category' !== $context)
		{
			if ($this->params->get('tidyRepair', 1))
			{
				$this->getFunction('article.tidyRepair', $article, $this);
			}
		}

		@include $this->includeEvent(__FUNCTION__);

		return $this->resultBool($result);
	}

	public function onContentAfterSave($context, $article, $isNew)
	{
		$result = array();

		if ($this->params->get('getImages', 1))
		{
			$this->getFunction('article.saveImages', $context, $article);
		}

		@include $this->includeEvent(__FUNCTION__);

		return $this->resultBool($result);
	}

	public function onContentBeforeDelete($context, $data)
	{
		$result = array();

		@include $this->includeEvent(__FUNCTION__);

		return $this->resultBool($result);
	}

	public function onContentAfterDelete($context, $data)
	{
		$result = array();

		@include $this->includeEvent(__FUNCTION__);

		return $this->resultBool($result);
	}

	public function onContentChangeState($context, $pks, $value)
	{
		$result = array();

		@include $this->includeEvent(__FUNCTION__);

		return $this->resultBool($result);
	}

	// Utilities
	// ================================================================================

	public function getInstance()
	{
		if (plgSystemAsikart_easyset::$instance)
		{
			return plgSystemAsikart_easyset::$instance;
		}
	}

	public function callFunction($func)
	{
		return $this->getFunction($func);
	}

	/**
	 * function call
	 *
	 * A proxy to call class and functions
	 * Example: $this->call('folder1.folder2.function', $args) ; OR $this->call('folder1.folder2.Class::function', $args)
	 *
	 * @param    string $uri The class or function file path.
	 *
	 */

	public function getFunction($uri)
	{
		// Split paths
		$uri  = explode('::', $uri);
		$path = explode('.', $uri[0]);

		if (isset($uri[1]))
		{
			$class    = array_pop($path);
			$function = $uri[1];
			$file     = $class;
		}
		else
		{
			$function = array_pop($path);
			$file     = $function;
		}

		$func_path = implode(DS, $path) . '/' . $file;
		$file      = AK_PATH . DS . $func_path . '.php';

		// include file.
		if (!empty($class))
		{
			if (!class_exists($class))
			{
				if (!file_exists($file))
				{
					$file = AK_ADMIN_LIB_PATH . DS . $func_path . '.php';
				}

				if (file_exists($file))
				{
					include_once($file);
				}
			}
		}
		else
		{
			if (!function_exists($function))
			{
				if (!file_exists($file))
				{
					$file = AK_ADMIN_LIB_PATH . DS . $func_path . '.php';
				}

				if (file_exists($file))
				{
					include_once($file);
				}
			}
		}

		// Handle args
		$args = func_get_args();
		array_shift($args);

		// Call Function
		if (!empty($class) && method_exists($class, $function))
		{
			return call_user_func_array(array($class, $function), $args);
		}
		elseif (function_exists($function))
		{
			return call_user_func_array($function, $args);
		}

	}

	public function includeEvent($func)
	{
		$event = AK_PATH . '/events/' . $func . '.php';
		if (file_exists($event))
		{
			return $event;
		}
	}

	public function resultBool($result = array())
	{
		foreach ($result as $result):
			if (!$result)
			{
				return false;
			}
		endforeach;

		return true;
	}
}

/**
 * Class AKEasyset
 */
class AKEasyset
{
	/**
	 * Get Easyset Instance.
	 *
	 * @return plgSystemAsikart_easyset d
	 */
	public static function getInstance()
	{
		return plgSystemAsikart_easyset::$instance;
	}
}

