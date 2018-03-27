<?php
/**
 * Created by PhpStorm.
 * User: Wassim
 * Date: 04/03/2018
 * Time: 20:01
 */

namespace GaussBundle\EventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent ;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



class ErrorRedirect
{

    protected $router;

    public function __construct($router)
    {
        $this->router = $router;
    }

    public function onKernelException(GetResponseForExceptionEvent  $event)
    {
        $request = $event->getRequest();
        $exception = $event->getException();
        //HTTP_NOT_FOUND

        if($exception instanceof NotFoundHttpException) {
            $request = $event->getRequest();
            $route = 'homepage_404';
                if($route === $event->getRequest()->get('_route')) {
                    return;
                }

                $url = $this->router->generate($route);

                $response = new RedirectResponse($url);
                $event->setResponse($response);
        }
    }

}