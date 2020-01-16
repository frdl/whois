<?php
use Psr\Container\ContainerInterface;
use function DI\create;
use function DI\get;
use function DI\decorate;


return [
           'twig.files-loader' => decorate(function ($loader, ContainerInterface $c) {
                   $loader->addPath(__DIR__.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'templates'.\DIRECTORY_SEPARATOR.'custom', 'modules');
		   $loader->addPath(__DIR__.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'templates'.\DIRECTORY_SEPARATOR.'views', 'modules');
		
              return $loader;
           }),		
       ];
