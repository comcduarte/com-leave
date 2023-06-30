<?php
namespace Leave\Controller;

use Components\Controller\AbstractBaseController;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Update;
use Laminas\Db\Sql\Where;
use Laminas\Log\LoggerAwareTrait;
use Exception;

class LeaveController extends AbstractBaseController
{
    use LoggerAwareTrait;
    
    public function cronAction()
    {
//         $messages = [];
        
        $sql = new Sql($this->adapter);
        
        $select = new Select();
        $select->from('update_employeeleave');
        $select->limit(1000);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = new ResultSet();
        
        try {
            $results = $statement->execute();
            $resultSet->initialize($results);
        } catch (Exception $e) {
//             $messages[] = $e->getMessage();
            $this->getLogger()->info($e->getMessage());
        }
        
        foreach ($resultSet as $record) {
            /**
             * Update record from temporary table.
             * @var \Laminas\Db\Sql\Update $update
             */
            $update = new Update();
            $values = [
                'BEGIN' => $record['BEGIN'],
                'ACCRUAL' => $record['ACCRUAL'],
                'TAKEN' => $record['TAKEN'],
                'FORFEIT' => $record['FORFEIT'],
                'PAID' => $record['PAID'],
                'BALANCE' => $record['BALANCE'],
            ];
            
            
            try {
                $update->table('employee_leave')->set($values)->where(['EMP_NUM' => $record['EMP_NUM'], 'CODE' => $record['CODE']]);
                $statement = $sql->prepareStatementForSqlObject($update);
                $results = $statement->execute();
            } catch (Exception $e) {
//                 $messages[] = $e->getMessage();
                $this->getLogger()->info($e->getMessage());
            }
            
            /**
             * Remove record from temporary table.
             * @var \Laminas\Db\Sql\Delete $delete
             */
            $delete = new Delete();
            $delete->from('update_employeeleave');
            
            
            $where = new Where();
            $where->equalTo('UUID', $record['UUID']);
            $delete->where($where);
            
            $statement = $sql->prepareStatementForSqlObject($delete);
            try {
                $results = $statement->execute();
            } catch (Exception $e) {
//                 $messages[] = $e->getMessage();
                $this->getLogger()->info($e->getMessage());
            }
        }
        return;
    }
}