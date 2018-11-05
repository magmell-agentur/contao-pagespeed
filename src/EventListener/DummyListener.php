<?php

namespace Magmell\Contao\PageSpeed\EventListener;

use Symfony\Component\HttpKernel\Event\PostResponseEvent;

class DummyListener
{
    public function onKernelTerminate(PostResponseEvent $event)
    {
    }
}
