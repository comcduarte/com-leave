<?php
namespace Leave\Form;

use Components\Form\AbstractBaseForm;
use Laminas\Form\Element\Text;

class LeaveRequestForm extends AbstractBaseForm
{
    public function init()
    {
        $this->add([
            'name' => 'BANK',
            'type' => Text::class,
            'attributes' => [
                'id' => 'BANK',
                'class' => 'form-control',
                'required' => 'true',
            ],
            'options' => [
                'label' => 'Bank',
            ],
        ],['priority' => 100]);
    }
}