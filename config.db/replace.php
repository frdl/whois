<?php


return function($Event){
      
           $preferences = json_decode(file_get_contents(__DIR__ .\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'preferences.json'));
	


return [
	
	[
        "nav_links", 
		[

			"uri" => $Event->getArgument('container')->get('router')->generate($preferences->routes->whois->name),
			'sref'=> $preferences->routes->whois->name,
             'position' => 10,
			
		],
		[
			"name" => 'whois',
			"parent_name" => '',
			
		]
		
	],
	
	
	
];
	
};
