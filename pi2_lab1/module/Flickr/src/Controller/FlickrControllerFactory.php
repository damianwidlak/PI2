<?php

namespace Flickr\Controller;

use Flickr\Form\SearchForm;
use Flickr\Model\Thumbnails;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class FlickrControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,  $requestedName, array $options = null)
    {
        $thumbnails = $container->get(Thumbnails::class);
        $searchForm = $container->get(SearchForm::class);

        return new FlickrController($thumbnails, $searchForm);
    }
}
