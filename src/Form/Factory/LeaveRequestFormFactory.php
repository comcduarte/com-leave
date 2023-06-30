<?php
namespace Leave\Form\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Leave\Form\LeaveRequestForm;

class LeaveRequestFormFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $form = new LeaveRequestForm();
        $adapter = $container->get('leave-model-adapter');
        return $form;
    }
}