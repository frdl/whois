<?php


return function($Event){
      
           $preferences = json_decode(file_get_contents(__DIR__ .\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'preferences.json'));
	


	
return [
	
	[
        "nav_links", 
		[
			"name" => 'whois',
			"parent_name" => '',
			"isCustom" => 0,
			"label" => 'Whois',
			"uri" => $Event->getArgument('container')->get('router')->generate($preferences->routes->whois->name),
			'sref'=> $preferences->routes->whois->name,
			"route_name" =>$preferences->routes->whois->name,
			"block" => 'hamburger',
			'position' => 10,
			
		],
		
		
	],
	
	
	
];
	
};
