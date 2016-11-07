<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class PhpSession {

    // constructor
    function PhpSession() {
	    if (substr_count ($_SERVER['HTTP_HOST'], ".") == 3)
		{
			$cookie_domain = preg_replace ('/^([^.])*/i', null, $_SERVER['HTTP_HOST']);
			$cookie_domain = substr($cookie_domain, 1);
		}
		else
		{
			$cookie_domain = $_SERVER['HTTP_HOST'];
		}
		
		ini_set ("session.cookie_domain", $cookie_domain);
        session_start();
    }
    
    function save($var, $val, $namespace = 'default') {
        $_SESSION[$namespace][$var] = $val;
    }
    
    function get($var = null, $namespace = 'default') {
        if(isset($var))
            return isset($_SESSION[$namespace][$var]) ? $_SESSION[$namespace][$var] : null;
        else
            return isset($_SESSION[$namespace]) ? $_SESSION[$namespace] : null;
    }
    
    function clear($var = null, $namespace = 'default') {
        if(isset($var))
            unset($_SESSION[$namespace][$var]);
        else
            unset($_SESSION[$namespace]);
    }
    
}
?>