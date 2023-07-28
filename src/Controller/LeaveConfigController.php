<?php
namespace Leave\Controller;

use Components\Controller\AbstractConfigController;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Ddl\CreateTable;
use Laminas\Db\Sql\Ddl\DropTable;
use Laminas\Db\Sql\Ddl\Column\Datetime;
use Laminas\Db\Sql\Ddl\Column\Decimal;
use Laminas\Db\Sql\Ddl\Column\Integer;
use Laminas\Db\Sql\Ddl\Column\Varchar;
use Laminas\Db\Sql\Ddl\Constraint\PrimaryKey;
use Laminas\Db\Sql\Ddl\Index\Index;

class LeaveConfigController extends AbstractConfigController
{
    public function clearDatabase()
    {
        $sql = new Sql($this->adapter);
        $ddl = [];
        
        $ddl[] = new DropTable('employee_leave');
        $ddl[] = new DropTable('update_employeeleave');
        
        foreach ($ddl as $obj) {
            $this->adapter->query($sql->buildSqlString($obj), $this->adapter::QUERY_MODE_EXECUTE);
        }
    }
    
    public function createDatabase()
    {
        $sql = new Sql($this->adapter);
        
        /******************************
         * LEAVE
         ******************************/
        $ddl = new CreateTable('employee_leave');
        
        $ddl->addColumn(new Varchar('UUID', 36));
        $ddl->addColumn(new Integer('STATUS', TRUE));
        $ddl->addColumn(new Datetime('DATE_CREATED', TRUE));
        $ddl->addColumn(new Datetime('DATE_MODIFIED', TRUE));
        
        $ddl->addColumn(new Varchar('EMP_NUM', 10, TRUE));
        $ddl->addColumn(new Varchar('CODE', 10, TRUE));
        $ddl->addColumn(new Decimal('BEGIN', 8, 2, TRUE));
        $ddl->addColumn(new Decimal('ACCRUAL', 8, 2, TRUE));
        $ddl->addColumn(new Decimal('TAKEN', 8, 2, TRUE));
        $ddl->addColumn(new Decimal('FORFEIT', 8, 2, TRUE));
        $ddl->addColumn(new Decimal('PAID', 8, 2, TRUE));
        $ddl->addColumn(new Decimal('BALANCE', 8, 2, TRUE));
        
        $ddl->addConstraint(new PrimaryKey('UUID'));
        $ddl->addConstraint(new Index(['EMP_NUM','CODE'], 'IDX_LEAVE'));
        
        $this->adapter->query($sql->buildSqlString($ddl), $this->adapter::QUERY_MODE_EXECUTE);
                
        /******************************
         * LEAVE UPDATE
         ******************************/
        $ddl->setTable('update_employeeleave');
        $this->adapter->query($sql->buildSqlString($ddl), $this->adapter::QUERY_MODE_EXECUTE);
        
        unset($ddl);
    }
}