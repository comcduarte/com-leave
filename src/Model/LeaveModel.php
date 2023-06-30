<?php
namespace Leave\Model;

use Components\Model\AbstractBaseModel;

class LeaveModel extends AbstractBaseModel
{
    public $EMP_NUM;
    public $CODE;
    public $BEGIN;
    public $ACCRUAL;
    public $TAKEN;
    public $FORFEIT;
    public $PAID;
    public $BALANCE;
    
    public function __construct($adapter = NULL)
    {
        parent::__construct($adapter);
        $this->setTableName('employee_leave');
    }
}