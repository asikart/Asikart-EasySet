<?php
/**
 * php document by Asika
 */ 

defined('_JEXEC') or die;

function secure()
{
    $app 	    = JFactory::getApplication();
    $user 		= JFactory::getUser();
    $session	= JFactory::getSession();
    $es         = AKEasyset::getInstance();
    $mode       = $es->params->get('adminSecure');
    
    if (!$app->isAdmin() || !$mode ||
        !$es->params->get('adminSecureCode') || !$user->get('guest') ||
        $session->get('aksecure'))
    {
        return;
    }
    
    $logged = false;
    
    // http
    if ($mode == 'auth' || $mode == 'auth_user')
    {
        if (substr(php_sapi_name(), 0, 3) == 'cgi')
        {
            JError::raiseWarning(500, JText::_('Not Apache Handler'));
            return true;
        }
        
        if(!$session->get('tried_login'))
        {
            $_SERVER['PHP_AUTH_USER'] = null;
            $session->set('tried_login', true);
        }
        
        try
        {
            $username = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
            $password = isset($_SERVER['PHP_AUTH_PW'])   ? $_SERVER['PHP_AUTH_PW']   : null;
            
            if($mode == 'auth_user')
            {
                if (!$app->login(array('username' => $username, 'password' => $password), array('remember' => true)))
                {
                    throw new Exception();
                }
                
                $session->set('user', JFactory::getUser($username));
            }
            else
            {
                if ($password != $es->params->get('adminSecureCode'))
                {
                    throw new Exception();
                }
            }
            
            $logged = true;
        }
        catch(Exception $e)
        {
            header('WWW-Authenticate: Basic realm="'.$app->getCfg('sitename').'"');
            header('HTTP/1.0 401 Unauthorized');
            die();
        }
    }
    // compat
    elseif($mode == 'url')
    {
        $logged = isset($_GET[$es->params->get('adminSecureCode')]);
        
        if (!$logged)
        {
            $app->redirect(JURI::root());
        }
    }
    
    if ($logged)
    {
        $session->set('aksecure', true);
        $session->set('tried_login', false);
        $app->redirect(JFactory::getURI()->toString());
    }
}