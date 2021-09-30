<?php

namespace LaminasTest\Mvc\Plugin\FilePrg;

use Laminas\Form\Element\Collection;
use Laminas\Form\Form;
use Laminas\Http\Request;
use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Plugin\FilePrg\FilePostRedirectGet;
use Laminas\Router\Http\Literal as LiteralRoute;
use Laminas\Router\Http\Segment as SegmentRoute;
use Laminas\Router\RouteMatch;
use Laminas\Router\SimpleRouteStack;

trait CommonSetupTrait
{
    public $form;
    public $controller;
    public $event;
    public $plugin;
    public $request;
    public $response;
    public $collection;

    protected function setUp() : void
    {
        $this->form = new Form();

        $this->collection = new Collection('links', [
                'count' => 1,
                'allow_add' => true,
                'target_element' => [
                    'type' => TestAsset\LinksFieldset::class,
                ],
        ]);

        $router = new SimpleRouteStack;
        $router->addRoute('home', LiteralRoute::factory([
            'route'    => '/',
            'defaults' => [
                'controller' => TestAsset\SampleController::class,
            ]
        ]));

        $router->addRoute('sub', SegmentRoute::factory([
            'route' => '/foo/:param',
            'defaults' => [
                'param' => 1
            ]
        ]));

        $router->addRoute('ctl', SegmentRoute::factory([
            'route' => '/ctl/:controller',
            'defaults' => [
                '__NAMESPACE__' => 'LaminasTest\Mvc\Controller\TestAsset',
            ]
        ]));

        $this->controller = new TestAsset\SampleController();
        $this->request    = new Request();
        $this->event      = new MvcEvent();
        $this->routeMatch = new RouteMatch(['controller' => 'controller-sample', 'action' => 'postPage']);

        $this->event->setRequest($this->request);
        $this->event->setRouteMatch($this->routeMatch);
        $this->event->setRouter($router);

        $this->controller->setEvent($this->event);

        $this->plugin = new FilePostRedirectGet();
        $this->plugin->setController($this->controller);
    }
}
