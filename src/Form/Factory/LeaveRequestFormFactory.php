<?php
namespace Leave\Form\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Leave\Form\LeaveRequestForm;

class LeaveRequestFormFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $form = new LeaveRequestForm();
        return $form;
    }
}