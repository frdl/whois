exports = module.exports = function(config){


	if('undefined'===typeof config.m){
	  config.m = {};	
	}
	
	config.m['frdl/whois'] = require('./../preferences.json');
	
/*
 config.hps.scriptengine.lazy.push({
        name : 'frdl-whois',
        files : [
			'module-assets/frdl/whois/whois.component'
		   
		]	
  });		
*/
	 config.hps.scriptengine.lazy.push({
        name : 'frdl-whois',
        files : [
			"${this.Webfan.hps.scriptengine.requirejs.paths['module-assets']}" + 'frdl/whois/whois.component.js'
		   
		]	
  });		
	
};