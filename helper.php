<?php

/**
 * @version     1.0.1
 * @package     mod_uwumnavigator
 * @copyright   Copyright (C) 2016-2017. All rights reserved. Infalia Private Company
 * @license     MIT; see LICENSE
 * @author      Ioannis Tsampoulatidis <itsam@infalia.com> - https://github.com/infalia
 */

defined('_JEXEC') or die;

class ModUwumnavigatorHelper {


	public static function getHTMLNavigation($host)
	{
		$ch = curl_init($host);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($ch);
		curl_close($ch);
		$html = json_decode($result);
		
		if(!is_object($html))
		{
			return self::getFallbackNavbar();
		}

		return $html->result;
	}

	/**
	* 	DEPRECATED Method
	*/	
	public static function quickLogout()
	{
		$return = base64_encode('https://wegovnow.infalia.com');
		$url = JRoute::_('index.php?option=com_users&task=user.logout&' . JSession::getFormToken() . '=1&return=' . $return, false);
		header('Location: ' . $url);
		exit;
	}

	/**
	* 	DEPRECATED Method
	*/
	public static function quickLogin()
	{
		$url = 'https://wegovnow.infalia.com/component/slogin/provider/uwum/auth';
		header('Location: ' . $url);
		exit;
	}

	/**
	* 	DEPRECATED Method
	*/
	public static function makeNavigation($host, $token)
	{

		$ch = curl_init($host . (is_null($token) ? '' : '&access_token='.$token) );
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($ch);
		curl_close($ch);
		$json = json_decode($result);

		if(!is_object($json))
		{
			return self::getFallbackNavbar();
		}

		if(isset($json->error))
		{
			echo 'Debug:'.$json->error.' '.$json->error_description;
			return self::getFallbackNavbar();
		}

		$html = '';
		$html .= '<div class="meta_navigation">';
		$html .= '  <ul class="nav">';
		$count = count($json->result);

		foreach ($json->result as $item)
		{
			$count--;
			if($count)
			{
				$html .= (isset($item->active) ? '<li class="active">' : '<li>');
			}
			else
			{
				$html .= '<li class="member_account">';
				if(is_null($token))
				{
					$item->url = 'https://wegovnow.infalia.com/component/slogin/provider/uwum/auth';
				}
			}
			$html .= '    <a href="'.$item->url.'">';
			$html .= '<em>'. $item->name . '</em><br />';
			$html .= $item->description;
			$html .= '    </a></li>';
		}

		$html .= '  </ul>';
		$html .= '</div>';

		return $html;
	}

	/**
	* 	DEPRECATED Method
	*/	
	private static function getCookie($host)
	{
		$ch = curl_init($host);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Accept:*/*",
			"Accept-Encoding:gzip, deflate, br",
			"Accept-Language:en-US,en;q=0.8,el;q=0.6,fr;q=0.4",
			"Connection:keep-alive",
			"Content-Length:0",
			"DNT:1",
			"Host:wegovnow.liquidfeedback.com",
			"Origin:https://wegovnow.infalia.com",
			"Referer:https://wegovnow.infalia.com/",
			"User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36"
		));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		// get cookie
		preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
		$cookies = array();
		foreach($matches[1] as $item) {
			parse_str($item, $cookie);
			$cookies = array_merge($cookies, $cookie);
		}
		var_dump($cookies);
		return $cookies;
	}


	/**
	* 	DEPRECATED Method
	*/	
	/*
	 * Simulates xhr with cookie
	 * http://stackoverflow.com/questions/5972443/php-simulate-xhr-using-curl
	 * http://stackoverflow.com/questions/895786/how-to-get-the-cookies-from-a-php-curl-into-a-variable
	 * */
	public static function checkCORS()
	{
		$host = "https://wegovnow.liquidfeedback.com/api/1/session";
		$cookies = self::getCookie($host);
		$session_LF = $cookies['liquid_feedback_session'];
		$cookie = "Cookie:liquid_feedback_session=$session_LF";

		$process = curl_init($host);
		curl_setopt($process, CURLOPT_HTTPHEADER, array(
			"Accept:*/*",
			"Accept-Encoding:gzip, deflate, br",
			"Accept-Language:en-US,en;q=0.8,el;q=0.6,fr;q=0.4",
			"Connection:keep-alive",
			"Content-Length:0",
			$cookie,
			//"Cookie:liquid_feedback_session=dummysessionfromIMC",
			"DNT:1",
			"Host:wegovnow.liquidfeedback.com",
			"Origin:https://wegovnow.infalia.com",
			"Referer:https://wegovnow.infalia.com/",
			"User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36"
		));
		curl_setopt($process, CURLOPT_POST, 1);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		$file = __DIR__ . '/itsam.txt';
		echo $file;
		curl_setopt($process, CURLOPT_COOKIESESSION, 1);
		curl_setopt($process, CURLOPT_COOKIEJAR, $file);
		curl_setopt($process, CURLOPT_COOKIEFILE, $file);

		$result = curl_exec($process);
		curl_close($process);

		$json = json_decode($result);

		return (is_object($json) ? $json : $result);
	}

    private static function getFallbackNavbar()
    {
		$str = '
            <!-- UWUM fallback STATIC navigation bar -->
            <div class="meta_navigation">
              <ul class="nav">
                CANNOT CONNECT TO UWUM
              </ul>
            </div>		
		';

        return $str;
    }

}
