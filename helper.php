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


	public static function getHTMLNavigation($host, $token)
	{
		$ch = curl_init($host . (is_null($token) ? '' : '&access_token='.$token) );
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($ch);
		curl_close($ch);
		$html = json_decode($result);
		
		if(!is_object($html) || empty($html))
		{
			return self::getFallbackNavbar();
		}

		if ( empty($html->result) ){
			//print_r($result); //{"error":"invalid_token","error_description":"The access token is invalid or expired"}
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



	    	<!-- UWUM NAVIGATION BAR BEGINS -->
	          <style>
			html, body {
				margin: 0;
			}
	    
			/* Reset some styles */
			.wgn-nav-bar a {
				text-decoration: none;
			}
			.wgn-nav-bar ul {
				margin: 0;
				padding: 0;
			}
			.wgn-nav-bar ul li {
				list-style: none;
			}

			.wgn-nav-bar {
				position: fixed;
				right: 0;
				left: 0;
				z-index: 1000;
				font-size: 0;
				background-color: #ffffff;
				-webkit-box-shadow: 1px 1px 3px #666;
				   -moz-box-shadow: 1px 1px 3px #666;
						box-shadow: 1px 1px 3px #666;
			}
			.wgn-nav-bar a {
				display: block;
				padding: 5px 10px;
				color: #000000;
				-webkit-transition: all 0.25s ease;
				   -moz-transition: all 0.25s ease;
					-ms-transition: all 0.25s ease;
					 -o-transition: all 0.25s ease;
						transition: all 0.25s ease;

				outline: none !important;
				outline-offset: 0 !important;
			}
			.wgn-nav-bar a:hover,
			.wgn-nav-bar a:focus,
			.wgn-nav-bar li.active a,
			.wgn-nav-bar li.active a:hover {
				color: #ffffff;
				background-color: #666666;
			}
			.wgn-nav-bar li.active a {
				background-color: #cccccc;
			}

			.wgn-nav-bar .brand,
			.wgn-nav-bar .nav-menu {
				display: inline-block;
			}
			.wgn-nav-bar .logo,
			.wgn-nav-bar .nav-menu ul li,
			.wgn-nav-bar .account-btn {
				display: inline-block;
				font: normal 10pt / 120% sans-serif;
	      font-weight: bold;
				color: #000000;
			}

	    .wgn-nav-bar .nav-menu ul li a {
	      font-weight: bold;
	    }
			.wgn-nav-bar .brand {
				border-right: 1px solid #ccc;
			}
			.wgn-nav-bar .logo img {
				display: inline-block;
				height: 34px;
	      margin-bottom: -7px;
				margin-right: 1px;
				vertical-align: top;
			}
			.wgn-nav-bar .logo .slogan {
				display: inline-block;
				margin-left: 5px;
			}

			.wgn-nav-bar .nav-menu ul {}
			.wgn-nav-bar .nav-menu ul li {
				border-right: 1px solid #ccc;
			}
			.wgn-nav-bar .anchor-name {
				display: block;
			}
			.wgn-nav-bar .anchor-slogan {
				display: block;
				font-weight: normal;
			}

			.wgn-nav-bar .account-btn {
				position: absolute;
				top: 0;
				right: 0;
				z-index: 1001;
				text-align: right;
				border-left: 1px solid #ccc;
			}


			/*** Mobile menu ***/

			/* Humburger button */
			.wgn-nav-bar .mobile-menu-btn {
				position: absolute;
				top: 8px;
				right: 10px;
				z-index: 1001;

				display: none;
				margin: 0;
				padding: 0;
				background-color: transparent;
				border: none;
				border-radius: 0;
				cursor: pointer;

				outline: none !important;
				outline-offset: 0 !important;
			}
			.wgn-nav-bar .mobile-menu-btn .custom-icon-bar {
				display: block;
				width: 24px;
				height: 2px;
				margin: 5px 0;
				background: #333;
				border-radius: 1px;
				-webkit-transition: all 250ms cubic-bezier(0.19, 1, 0.22, 1);
				   -moz-transition: all 250ms cubic-bezier(0.19, 1, 0.22, 1);
				    -ms-transition: all 250ms cubic-bezier(0.19, 1, 0.22, 1);
					 -o-transition: all 250ms cubic-bezier(0.19, 1, 0.22, 1);
						transition: all 250ms cubic-bezier(0.19, 1, 0.22, 1);
			}
			.wgn-nav-bar .mobile-menu-btn.menu-opened .custom-icon-bar:nth-child(1) {
				-webkit-transform: rotate(45deg) translate(5px, 5px);
				   -moz-transform: rotate(45deg) translate(5px, 5px);
					-ms-transform: rotate(45deg) translate(5px, 5px);
					 -o-transform: rotate(45deg) translate(5px, 5px);
						transform: rotate(45deg) translate(5px, 5px);
			}
			.wgn-nav-bar .mobile-menu-btn.menu-opened .custom-icon-bar:nth-child(2) { opacity: 0; }
			.wgn-nav-bar .mobile-menu-btn.menu-opened .custom-icon-bar:nth-child(3) {
				-webkit-transform: rotate(-45deg) translate(5px, -5px);
				   -moz-transform: rotate(-45deg) translate(5px, -5px);
					-ms-transform: rotate(-45deg) translate(5px, -5px);
					 -o-transform: rotate(-45deg) translate(5px, -5px);
						transform: rotate(-45deg) translate(5px, -5px);
			}
			.wgn-nav-bar .mobile-menu-wrapper .menu-btn > span {
				display: inline-block;
				font-size: 11px;
			}


			@media (max-width: 899px) {
				.wgn-nav-bar .brand {
					border-right: none;
				}


				.wgn-nav-bar .mobile-menu-btn {
					display: inline-block;
				}
				.wgn-nav-bar .nav-menu {
					display: none;
					padding: 0 20px;
				}
				.wgn-nav-bar .nav-menu.nav-menu-open {
					display: block;
				}
				.wgn-nav-bar .nav-menu ul li,
				.wgn-nav-bar .account-btn {
					position: static;
					display: block;
					text-align: left;
					border: none;
					border-top: 1px solid #ccc;
				}
			}
			</style>




			<header class="wgn-nav-bar">
		      <div class="brand">
		        <a class="logo" href="https://sandona.wegovnow.eu/">
		          <img src="https://sandona.liquidfeedback.net/static/wegovnow/sandona-logo-icon.png" alt="San Dona Logo">
		          <span class="slogan">San Donà di Piave<br>WeGovNow Labs</span>
		        </a>
		      </div>
		      <nav id="nav-menu" class="nav-menu">
		        <ul>

		  			<li><a href="https://sandona.wegovnow.firstlife.org/"><span class="anchor-name">FirstLife</span><span class="anchor-slogan">map &amp; plan</span></a></li><li class="active"><a href="https://sandona-imc.infalia.com/"><span class="anchor-name">Improve My City</span><span class="anchor-slogan">report local issues</span></a></li><li><a href="https://sandona.liquidfeedback.net/"><span class="anchor-name">LiquidFeedback</span><span class="anchor-slogan">debate &amp; decide</span></a></li><li><a href="https://sandona-tmp.infalia.com/"><span class="anchor-name">Trusted Marketplace</span><span class="anchor-slogan">offers &amp; demands</span></a></li><li class="account-btn"><a href="https://sandona-imc.infalia.com/component/slogin/provider/uwum/auth"><span class="anchor-name">Login</span><span class="anchor-slogan">or register</span></a></li>       
		  		</ul>
		      </nav>
					<button id="mobile-menu-btn" class="mobile-menu-btn">
						<div>
							<span class="custom-icon-bar"></span>
							<span class="custom-icon-bar"></span>
							<span class="custom-icon-bar"></span>
						</div>
					</button>
		    </header>

		';

        return $str;
    }

}
