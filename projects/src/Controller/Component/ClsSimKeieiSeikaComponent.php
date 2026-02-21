<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use App\Model\R4\Component\ClsSimKeieiSeika;

class ClsSimKeieiSeikaComponent extends Component
{
    public $ClsSimKeieiSeika;
    // var $components = array('ClsComFnc');

    // function initialize()
    // {

    //     $this->ClsSimKeieiSeika = new ClsSimKeieiSeika();

    // }
    public function initialize($config): void
    {
        $this->ClsSimKeieiSeika = new ClsSimKeieiSeika();
    }
    public function fncCreateJissekiWK($dtlSyoriYM, $strUpdUser, $strUpdCltNm, $strUpdPro)
    {

        $result = array('result' => FALSE, 'data' => 'ErrorInfo', 'row' => '');

        try {
            //$this -> ClsSimKeieiSeika = new ClsSimKeieiSeika();
            // $result = $this -> ClsSimKeieiSeika -> Do_conn();

            //ﾄﾗﾝｻﾞｸｼｮﾝ開始
            //  $this -> ClsSimKeieiSeika -> Do_transaction();
            //部署別集計ﾜｰｸを削除する
            $result = $this->ClsSimKeieiSeika->fncDeleteWkKanr();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //部署別実績処理①
            //集計・部署別集計
            $result = $this->ClsSimKeieiSeika->fncSyukeiToBusyo($dtlSyoriYM, $strUpdUser, $strUpdCltNm, $strUpdPro);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //部署別実績処理②
            //ライン集計
            $result = $this->ClsSimKeieiSeika->fncSyukeiLine($strUpdUser, $strUpdCltNm, $strUpdPro);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //経営成果対象でないものをﾜｰｸﾃｰﾌﾞﾙから削除する
            $result = $this->ClsSimKeieiSeika->fncDeleteKanr();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //コミット
            //  $this -> ClsSimKeieiSeika -> Do_commit();

            //DB接続解除
            // $this -> ClsSimKeieiSeika -> Do_close();

            $result['result'] = TRUE;
        } catch (\Exception $e) {

            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            //  $this -> ClsSimKeieiSeika -> Do_rollback();
            //DB接続解除
            //  $this -> ClsSimKeieiSeika -> Do_close();
        }

        return $result;

    }

}