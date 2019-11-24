<?php

namespace frdl\whois;

use GuzzleHttp\Psr7\Response;


class WhoisController
{
	 protected $twig;
	 protected $project;
     public function __construct(\Twig\Environment $twig, \compiled\project $project){
		 $this->project = $project;
		 $this->twig = $twig;
	 }
	
     public function action() : Response {
           $status = 200;
           // $headers = ['X-Foo' => 'Bar'];
		  $headers = [];
        //   $body = 'hello!';
		 
		     $body = $this->twig->render('@modules/whois.twig', [
                 'document' => [
					    'title' => 'Whois - ' . $this->project->title,
					 ],
	
			 ]);
           $protocol = '2.0';
           $response = new Response($status, $headers, $body, $protocol);		
		 
		 return $response;
	 }
	
	
}