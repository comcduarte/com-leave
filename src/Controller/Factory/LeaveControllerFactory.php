<?php
namespace Leave\Controller\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Leave\Controller\LeaveController;
use Leave\Form\LeaveRequestForm;
use Leave\Model\LeaveModel;

class LeaveControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new LeaveController();
        
        $adapter = $container->get('leave-model-adapter');
        $controller->setDbAdapter($adapter);
        
        $model = new LeaveModel($adapter);
        $controller->setmodel($model);
        
        $form = $container->get('FormElementManager')->get(LeaveRequestForm::class);
        $controller->setform($form);
        
        $logger = $container->get('syslogger');
        $controller->setLogger($logger);
        return $controller;
    }
}