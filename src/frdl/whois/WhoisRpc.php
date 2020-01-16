<?php
declare(strict_types=1);

namespace frdl\whois;





class WhoisRpc implements \UMA\JsonRpc\Procedure
{
  
	public function __construct(\Psr\Container\ContainerInterface $container){
	   $this->container=$container;
	}	
	
	
    /**
     * {@inheritdoc}
     */
    public function __invoke(\UMA\JsonRpc\Request $request): \UMA\JsonRpc\Response
    {
		$params = $request->params();
		$query = $params[0];
		

		$FloodProtection = new \frdl\security\floodprotection\FloodProtection(__METHOD__, 15, 45);
        if($FloodProtection->check($_SERVER['REMOTE_ADDR'])){
            return new \UMA\JsonRpc\Error($request->id(), 'Too many requests, please wait a moment!');
        }			
		

		
		$query = trim(strip_tags($query));
		  
		$h = explode('.', $query);
		if(count($h) < 2){
			 //throw new \Exception('Invalid hostname, top level domain missing');	
			return new \UMA\JsonRpc\Error($request->id(), 'Invalid hostname, top level domain missing');
		}
		  
		$h = array_reverse($h);  
		$query = $h[1].'.'.$h[0];  
		  
		  $cache_key = 'whois.query.0.'.strlen($query).'.'.sha1($query);
		 $cache = $this->container->get('cache');		
		 // create a new item by trying to get it from the cache
         $actioninfo= $cache->getItem($cache_key);
         $actioninfo->expiresAfter(15 * 60);

			 if($actioninfo->isHit() ){
				$result = $actioninfo->get();
				
			   return new \UMA\JsonRpc\Success($request->id(), $result);
			 }
		
		   $whois = new \phpWhois\Whois();
		   $whois->deepWhois = true;
		   $_Result = $whois->lookup($query);
		
	
		      
	        if (!empty($_Result['rawdata'])) {
                $utils = new \phpWhois\Utils;
                $winfo = $utils->showHTML($_Result);
            } else {
                if (isset($whois->query['errstr']))
                    $winfo = implode($whois->query['errstr'], "\n</br></br>");
                else
                    $winfo = 'Unexpected error';
            } 	
			
			if(isset($_Result['regrinfo']['domain']['nserver']) && is_array($_Result['regrinfo']['domain']['nserver'])
			    && count($_Result['regrinfo']['domain']['nserver']) >= 2){
				$_Result['regrinfo']['registered'] = 'yes';
			}
			
			$result = [
				    'registered' =>  $_Result['regrinfo']['registered'] === 'yes' && 'free'!==$_Result['regrinfo']['domain']['status'] && null!==$_Result['regrinfo']['domain']['status'],
				    'info'=>  base64_encode($winfo),
				];
			
			
			if(preg_match("/\<b\>Status\:\s\<\/b\>connect\<br\/\>/", $winfo)){
				$result['registered']=true;
			}
			
			$actioninfo->set($result); 
	        $cache->save($actioninfo);
			
					
		//$result['test'] = $_Result['regrinfo']['domain']['status'];		
       return new \UMA\JsonRpc\Success($request->id(), $result);					
    }


    public function getSpec(): ?\stdClass
    {
        return \json_decode(<<<'JSON'
{
  "$schema": "https://json-schema.org/draft-07/schema#",
  "type": ["array"],
  "minItems": 1,
  "maxItems": 1,
  "items": { "type": ["string"] }
}
JSON
        );
    }
}
