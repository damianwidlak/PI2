<?php

namespace Flickr;

use Flickr\Model\Thumbnails;


class Module
{
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
