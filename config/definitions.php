<?php
use Psr\Container\ContainerInterface;
use function DI\create;
use function DI\get;
use function DI\decorate;


return [
           \frdl\whois\WhoisRpc::class => function (ContainerInterface $c) {
                return new \frdl\whois\WhoisRpc($c);
           },
	
           'rpc.server' => decorate(function ($server, ContainerInterface $c) {
               $server->set('whois', \frdl\whois\WhoisRpc::class);  
              return $server;
           }),		
	
	     \frdl\whois\WhoisController::class => function(ContainerInterface $c){
		    return new \frdl\whois\WhoisController($c->get('twig'), $c->get('project'));
	     },
	
       ];
