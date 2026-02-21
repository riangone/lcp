<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
// App::uses('ClsComControl', 'Model/R4/Component');
use App\Model\Component\ClsComDoRefresh;

class ClsComDoRefreshComponent extends Component
{
    public function DoRefresh($ArrSql)
    {
        $ClsComDoRefresh = new ClsComDoRefresh();
        return $ClsComDoRefresh->DoRefresh($ArrSql);
    }

}