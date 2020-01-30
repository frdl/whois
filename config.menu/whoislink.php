<?php
use Psr\Container\ContainerInterface;
use Knp\Menu\Util\MenuManipulator;
use function DI\decorate;





return [
        
	'nav.block.hamburger' => decorate(function ($menu, ContainerInterface $c) {
        $preferences = json_decode(file_get_contents(__DIR__ .\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'preferences.json'));
		
           $menu->addChild('whois', ['uri' => $c->get('router')->generate($preferences->routes->whois->name), 'label' => 'Whois']);
		   $menu['whois']->setLinkAttribute('ui-sref', $preferences->routes->whois->name);

		
		return $menu;          
	}),	


];
