<?php
namespace Leave\Controller;

use Components\Controller\AbstractBaseController;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Update;
use Laminas\Db\Sql\Where;
use Laminas\Hydrator\ArraySerializableHydrator;
use Laminas\Log\LoggerAwareTrait;
use Leave\Model\LeaveModel;
use Exception;

class LeaveController extends AbstractBaseController
{
    use LoggerAwareTrait;
    
    public function cronAction()
    {
        $columns = [
            'EMP_NUM',
            'CODE',
            'BEGIN',
            'ACCRUAL',
            'TAKEN',
            'FORFEIT',
            'PAID',
            'BALANCE',
        ];
        
        $sql = new Sql($this->adapter);
        
        $select = new Select();
        $select->from('update_employeeleave');
        $select->columns($columns);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = new ResultSet();
        
        try {
            $results = $statement->execute();
            $resultSet->initialize($results);
        } catch (Exception $e) {
            $this->getLogger()->info($e->getMessage());
        }
        
        foreach ($resultSet as $record) {
            /**
             * Update record from temporary table.
             * @var \Laminas\Db\Sql\Update $update
             */
            $update = new Update();
            
            $leave = new LeaveModel($this->adapter);
            $record_exists = $leave->read(['EMP_NUM' => $record['EMP_NUM'], 'CODE' => $record['CODE']]);
            $hydrator = new ArraySerializableHydrator();
            
            $leave = $hydrator->hydrate($record->getArrayCopy(), $leave);
            
            switch ($leave->CODE) {
                case "COMP":
                case "CTEARNED":
                case "FS3C":
                case "LDD":
                    $leave->STATUS = $leave::INACTIVE_STATUS;
            }
            
            try {
                if ($record_exists) {
                    $leave->update();
                } else {
                    $leave->create();
                }
            } catch (Exception $e) {
                $this->getLogger()->info($e->getMessage());
            }
            
            /**
             * Remove record from temporary table.
             * @var \Laminas\Db\Sql\Delete $delete
             */
            $delete = new Delete();
            $delete->from('update_employeeleave');
            
            $where = new Where();
            $where->equalTo('EMP_NUM', $record['EMP_NUM'])->and->equalTo('CODE', $record['CODE']);
            $delete->where($where);
            
            $statement = $sql->prepareStatementForSqlObject($delete);
            try {
                $results = $statement->execute();
            } catch (Exception $e) {
                $this->getLogger()->info($e->getMessage());
            }
        }
        return;
    }
}