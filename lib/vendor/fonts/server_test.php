<?php

echo '<h1>GD Font Renderer</h1>';
echo '<p>Copyright '. date('Y') .' Nick Schaffner<br />
<a href="http://53x11.com">http://53x11.com</a></p>';

echo '<p>This script will check your GD Library and PHP configurations to see if they are compatible with the GD Font Renderer Script.  Any listed errors probally means the script will not work with you current configuration.';

$success = array();
$error = array();

## Check GD Library
if(function_exists('gd_info')) {
	$gd_info = gd_info();
	if((preg_replace('/[^0-9.]/','',$gd_info['GD Version'])*1) >= 2 and $gd_info['PNG Support'])
		$success[] = 'Your GD Library (version '. preg_replace('/[^0-9.]/','',$gd_info['GD Version']) .') appears to be capable of running GD Font Renderer Script.';
	else
		$error[] = 'Your GD Library (version '. preg_replace('/[^0-9.]/','',$gd_info['GD Version']) .') doesn\'t appear to be capable of running GD Font Renderer Script.  <a href="http://www.libgd.org">Time to upgrade</a>!';
} else
	$error[] = 'GD Library doesn\'t appear to be installed.  This script requires the PHP <a href="http://www.libgd.org">GD Library</a>.';
	
## Check PHP Version
if(phpversion() < 4.3)
	$error[] = 'GD Font Renderer Script requires PHP version 4.3 or higher.  <a href="http://php.net">Time to upgrade</>!';
else
	$success[] = 'You are running PHP version '. phpversion() .'.  Horray!';
	
foreach ($error as $value)
	echo '<h3 style="color: red">'. $value .'</h3><br />';
if(!count($error))
	$success[] = 'It appears that your server can run GD Font Renderer Script.  Game on.';
foreach ($success as $value)
	echo '<h3 style="color: green">'. $value .'</h3><br />';

?>