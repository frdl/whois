<?php
use function DI\add;
//'GET|POST|PUT|DELETE|OPTIONS', '/bar/[:controller]/[:action].[:type]?', 'bar_action', 'bar_route'

 //$preferences = json_decode(file_get_contents(__DIR__ .\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'preferences.json'));

   $f = rtrim(dirname(dirname(dirname(dirname(__FILE__)))), \DIRECTORY_SEPARATOR.'/ ')
				   .\DIRECTORY_SEPARATOR.'config'.\DIRECTORY_SEPARATOR.'module-configs'.\DIRECTORY_SEPARATOR.basename(dirname(dirname(__FILE__))).\DIRECTORY_SEPARATOR.'preferences.json';

        $preferences = json_decode(file_get_contents($f));

return  [

	
   'routes' => add([
                  ['GET', $preferences->routes->whois->route, [\frdl\whois\WhoisController::class, 'action'], $preferences->routes->whois->name],
                 
	
         ]),
		
];
