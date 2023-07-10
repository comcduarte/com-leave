<?php
namespace Leave\Controller\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Leave\Controller\LeaveConfigController;

class LeaveConfigControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new LeaveConfigController();
        $adapter = $container->get('leave-model-adapter');
        $controller->setDbAdapter($adapter);
        return $controller;
    }
}