define(['angular-frdl', 'ng-sanitize','@frdl/webfan', 'angular-jsonrpc-client/angular-jsonrpc-client', 'jquery', 'angular-messages/angular-messages', 'webfan/lang-legacy'], 
	   function(angular, ngSanitize,  Webfan, jsonrpcClientModule, $, ngMessages, lang){	 
	
	
 'use strict';	
	
 var main = Webfan.hps.scriptengine.webpack.main;	
 var frdlweb = require.main;	
 var moduleName = main + '.frdl.whois';		
	
	angular.module(moduleName, [main, jsonrpcClientModule.name, ngMessages.name])
    .config(['jsonrpcConfigProvider', function(jsonrpcConfigProvider) {
        jsonrpcConfigProvider.set({
            servers: [
                {
                    name: 'whois',
                    url:  Webfan.m.frdlweb.baseUrl + Webfan.m.frdlweb.routes.rpc.route.substr(1,  Webfan.m.frdlweb.routes.rpc.route.length),
                    returnHttpPromise: false
                },
				{
				    name : 'workspace',
					url : ((1===parseInt(Webfan.hps.scriptengine.server.ssl )) ? 'https://' : 'http://') + Webfan.hps.scriptengine.server.host + Webfan.hps.rpc.server.url,
                    returnHttpPromise: false
				}
				/*
                {
                    name: 'first',
                    url: 'http://example.com:8080/rpc'
                },
                {
                    name: 'second',
                    url: 'http://example.net:4444/api',
                    headers: {
                        'Authorization': 'Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ=='
                    }
                }
				*/
            ]
        });
    }])	
  .component('frdlWhois', { 


     selector : 'frdl-whois',
     template : `
<form>

	
<label for="whois-check" ng-bind="$ctrl.widget.preferences.msg.label"></label>
<div class="input-group mb-3">
  <div class="input-group-prepend" style="display:inline;">
    <span class="input-group-text" id="whois-addon" style="display:inline;">https://www.</span>
	<input type="text" autocomplete="domain" class="form-control" data-clear-btn="true"  ng-disabled="$ctrl.$searching"
		   ng-model="$ctrl.query" placeholder="{{$ctrl.widget.preferences.placeholder}}" 
		   id="whois-check" aria-describedby="whois-addon" style="display:inline;max-width:300px;">  
  </div>
  
	<button class="btn btn-primary" ng-bind="$ctrl.widget.preferences.msg.button" ng-disabled="$ctrl.$searching" ng-click="$ctrl.queryWhoisServer($ctrl.query)"></button>
  <div>	
	<a ng-href="{{$ctrl.domain.link_buy}}" ng-show="!$ctrl.$searching && true===$ctrl.domain.registered && true===$ctrl.domain.valid" class="btn btn-primary">
	   {{$ctrl.domain.host}} zu uns umziehen
	</a>
	
	<a ng-href="{{$ctrl.domain.link_buy}}" ng-show="!$ctrl.$searching && false===$ctrl.domain.registered && true===$ctrl.domain.valid" class="btn btn-success">
	  {{$ctrl.domain.host}} jetzt sichern
	</a>	
  </div>	
	<div ng-show="true!==$ctrl.domain.valid || (false!=$ctrl.widget.preferences.show.whois.info && !$ctrl.$searching && $ctrl.domain.info)" ng-bind-html="$ctrl.domain.info"></div>	
</div>
	
</form> 
`, 

     controller : ['$q', '$scope', '$element', '$compile',  '$Webfan', '$sce', '$timeout', 'jsonrpc',
				   function frdlwebWhoisController($q, $scope, $element, $compile, $Webfan, $sce, $timeout, jsonrpc){
                  
               var self = this, $frdl = frdlweb.frdl;
               self.widget={};
			   self.widget.preferences = Webfan.m['frdl/whois'];
			   self.widget.preferences.show.whois.info =
				   ('true' === self.widget.preferences.show.whois.info || '1' === self.widget.preferences.show.whois.info || 'on' === self.widget.preferences.show.whois.info)
					   ? true : false;
					   
               self.$searching = 0;    
					   
                 self.queryWhoisServer = function(q){
	
					if(!$frdl.strpos(q, '.') ){
					    $frdl.alert.error( lang.inX.__(self.widget.preferences.errors.invalid.domain, [q]) + '!');
						return;
					}
					
					self.domain = {
					         tld : q.split(/\./).reverse()[0],
						     host : q.split(/\./).reverse()[1] + '.' + q.split(/\./).reverse()[0]
					};
					
					
					self.$searching++;
					 
                  return jsonrpc.request({
							  serverName : 'whois', 
							  methodName:  'whois', 
							  methodArgs :[self.domain.host],                      
							  config: {                         
								 //timeout: $scope.canceller_getConfig.promise
                              }										 
							}).then(function(r){
					     	self.domain.registered = r.registered;
							    self.domain.info = $sce.trustAsHtml($frdl.base64_decode(r.info));
							    self.domain.link_buy = self.widget.preferences.link.buy.href.replace(new RegExp(self.widget.preferences.link.buy.placeholder), self.domain.tld);
							    self.domain.link_buy = self.domain.link_buy.replace(new RegExp(self.widget.preferences.link.buy.placeholder_domain), self.domain.host);
							    self.domain.valid = !(/not supported/.test(self.domain.info));
							    
							    if(!self.domain.valid){
									 $frdl.alert.error( lang.inX.__(self.widget.preferences.errors.invalid.domain, [q]) + '!');
									 self.domain.info = $sce.trustAsHtml('<error>'+lang.inX.__(self.widget.preferences.errors.invalid.domain, [q])+'</error>');
								}
					  
						 self.$searching--;
					  
					//	$scope.$digest();				   	 
					}).catch(function(e){
					   self.$searching--;
					   self.domain.info = $sce.trustAsHtml('<error>'+e.toString() + '</error>');
				  });   
					 
					 
				 };
}
] 
})	
	
	
	/*
    .component('frdlwebWhois', { 


     selector : 'frdlweb-whois',
     templateUrl : Widget.directory + 'templates/whois.html', 

     controller : ['$q', '$scope', '$element', '$compile', '$frdl', '$Webfan', '$sce', '$timeout',
				   function frdlwebWhoisController($q, $scope, $element, $compile, $frdl, $Webfan, $sce, $timeout){
                  
               var self = this;

		       var p = ($frdl.UI && 'function' === typeof $frdl.UI.progress) ? $frdl.UI.progress() : false;

			
		 
		 
		 
		 
		 
               self.widget = widget;
               self.$searching = 0;  
               self.path = function(name, params){			
	              return location.protocol + '//' + widget.preferences.host + $Webfan.hps.Router.generate(name, params); 			
	           };	


                self.queryWhoisServer = function(q){
	
					if(!$frdl.strpos(q, '.') ){
					    $frdl.alert.error( $frdl.inX.__(widget.preferences.errors.invalid.domain, [q]) + '!');
						return;
					}
					
					self.domain = {
					         tld : q.split(/\./).reverse()[0],
						     host : q.split(/\./).reverse()[1] + '.' + q.split(/\./).reverse()[0]
					};
					
					
					self.$searching++;
					p && p.start();
					return $Webfan.hps.rpc.call('whois', [self.domain.host],function(e,r){
						self.$searching--;
						p && p.complete();
						if(e){
						    frdl.alert.error(e);
							return;
						}
					}).then(function(r){
						if('undefined'!==typeof r.result){
						    	self.domain.registered = r.result.registered;
							    self.domain.info = $sce.trustAsHtml($frdl.base64_decode(r.result.info));
							    self.domain.link_buy = widget.preferences.link.buy.href.replace(new RegExp(widget.preferences.link.buy.placeholder), self.domain.tld);
							    self.domain.link_buy = self.domain.link_buy.replace(new RegExp(widget.preferences.link.buy.placeholder_domain), self.domain.host);
							    self.domain.valid = !(/not supported/.test(self.domain.info));
							    
							    if(!self.domain.valid){
									 $frdl.alert.error( $frdl.inX.__(widget.preferences.errors.invalid.domain, [q]) + '!');
									 self.domain.info = $sce.trustAsHtml('<error>'+$frdl.inX.__(widget.preferences.errors.invalid.domain, [q])+'</error>');
								}
						}else{
							
							self.domain.info = $sce.trustAsHtml('<error>Unknown error</error>');
						}
						
						$scope.$digest();
					});
				};


 
  
}
] 
});	
*/
	;
	
	
	
	//alert('whois.component.js');
	
 return angular.module(moduleName);	
});