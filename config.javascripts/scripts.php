<?php



$stateProvider = '$stateProvider';
$urlRouterProvider='$urlRouterProvider';
$locationProvider='$locationProvider';

$title =htmlentities('Whois - '.(new \compiled\project())->title);


$c=<<<JSCODE

require.main.app.root	
	 .config(['$stateProvider', '$urlRouterProvider', '$locationProvider',  
  function( $stateProvider, $urlRouterProvider, $locationProvider) {

      var base=require.main.Webfan.m.frdlweb.baseUrl.substr(0,require.main.Webfan.m.frdlweb.baseUrl.length-1);
	  
	  
     $stateProvider
      .state( {	
		 name : require.main.Webfan.m['frdl/whois'].routes.whois.name, 
		  url : require.main.Webfan.m['frdl/whois'].routes.whois.route,
		 	
		 views : {
             'centerView' : {
              templateUrl: base +require.main.Webfan.m['frdl/whois'].routes.whois.route
           }
		 },
		 data : {
			 xFrdlGetView : 'centerView',
			 meta : {
				title :  '$title'
			 }
		 }
	 });
	  

	  
  }]);

JSCODE;



 return $c;
