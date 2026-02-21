<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use App\Model\R4\Component\ClsKeieiSeika;
use Cake\Controller\ComponentRegistry;

class ClsKeieiSeikaComponent extends Component
{
    public $ClsKeieiSeika;
    public $ClsComFnc;
    public function __construct(ComponentRegistry $registry)
    {
        parent::__construct($registry);
        $this->ClsComFnc = $registry->load('ClsComFnc');
    }

    // public $FncKeiriDataMake = "";

    // function initialize()
    // {
    //     $this->ClsKeieiSeika = new ClsKeieiSeika();
    // }
    public function initialize($config): void
    {
        $this->ClsKeieiSeika = new ClsKeieiSeika();
    }

    public function fncCreateJissekiWK($dtlSyoriYM, $dtlKisyuYM, $strUpdUser, $strUpdCltNm, $strUpdPro, $strBusyoCDF = "", $strBusyoCDT = "", $intPatternNo = 0, $intProNo = 0)
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $this->ClsKeieiSeika = new ClsKeieiSeika();
            $result = $this->ClsKeieiSeika->Do_conn();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //ﾏｽﾀに登録開始
            //ﾄﾗﾝｻﾞｸｼｮﾝ開始
            $this->ClsKeieiSeika->Do_transaction();
            //部署別集計ﾜｰｸを削除する
            $result = $this->ClsKeieiSeika->fncDeleteWkKanr();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //'部署別実績処理①
            //'集計・部署別集計
            $result = $this->ClsKeieiSeika->fncSyukeiToBusyo($this->ClsComFnc->FncNv($dtlSyoriYM), $this->ClsComFnc->FncNv($dtlKisyuYM), $strUpdUser, $strUpdCltNm, $strUpdPro);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //'部署別実績処理②
            //'ライン集計
            $result = $this->ClsKeieiSeika->fncSyukeiLine($strUpdUser, $strUpdCltNm, $strUpdPro);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //'経営成果対象でないものをﾜｰｸﾃｰﾌﾞﾙから削除する
            $result = $this->ClsKeieiSeika->fncDeleteKanr($intPatternNo, $strBusyoCDF, $strBusyoCDT, $intProNo, $strUpdUser);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //コミット
            $this->ClsKeieiSeika->Do_commit();

            //DB接続解除
            $this->ClsKeieiSeika->Do_close();
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $this->ClsKeieiSeika->Do_rollback();
            //DB接続解除
            $this->ClsKeieiSeika->Do_close();
        }

        return $result;
    }

    public function fncSihyouLine($strSyoriNengetu, $strKi, $intPtnNo)
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $result = $this->ClsKeieiSeika->fncSihyouLine($strSyoriNengetu, $strKi, $intPtnNo);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        return $result;
    }

    public function fncRankingSelect($strSyoriYM, $strKI, $intNinzu, $intDaisu, $intKind)
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $result = $this->ClsKeieiSeika->fncRankingSelect($strSyoriYM, $strKI, $intNinzu, $intDaisu, $intKind);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        return $result;
    }
    //20150819 Add Start
    public function fncCreateJissekiWK_NEW($dtlSyoriYM, $dtlKisyuYM, $strUpdUser, $strUpdCltNm, $strUpdPro, $strBusyoCDF = "", $strBusyoCDT = "", $intPatternNo = 0, $intProNo = 0)
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $this->ClsKeieiSeika = new ClsKeieiSeika();
            $result = $this->ClsKeieiSeika->Do_conn();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //ﾏｽﾀに登録開始
            //ﾄﾗﾝｻﾞｸｼｮﾝ開始
            $this->ClsKeieiSeika->Do_transaction();
            //部署別集計ﾜｰｸを削除する
            $result = $this->ClsKeieiSeika->fncDeleteWkKanr_NEW();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //'部署別実績処理①
            //'集計・部署別集計
            $result = $this->ClsKeieiSeika->fncSyukeiToBusyo_NEW($this->ClsComFnc->FncNv($dtlSyoriYM), $this->ClsComFnc->FncNv($dtlKisyuYM), $strUpdUser, $strUpdCltNm, $strUpdPro);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //'部署別実績処理②
            //'ライン集計
            $result = $this->ClsKeieiSeika->fncSyukeiLine_NEW($strUpdUser, $strUpdCltNm, $strUpdPro);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //'経営成果対象でないものをﾜｰｸﾃｰﾌﾞﾙから削除する
            $result = $this->ClsKeieiSeika->fncDeleteKanr_NEW($intPatternNo, $strBusyoCDF, $strBusyoCDT, $intProNo, $strUpdUser);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //コミット
            $this->ClsKeieiSeika->Do_commit();

            //DB接続解除
            $this->ClsKeieiSeika->Do_close();
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $this->ClsKeieiSeika->Do_rollback();
            //DB接続解除
            $this->ClsKeieiSeika->Do_close();
        }

        return $result;
    }

    public function fncSihyouLine_NEW($strSyoriNengetu, $strKi, $intPtnNo)
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $result = $this->ClsKeieiSeika->fncSihyouLine_NEW($strSyoriNengetu, $strKi, $intPtnNo);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        return $result;
    }

    public function fncRankingSelect_NEW($strSyoriYM, $strKI, $intNinzu, $intDaisu, $intKind)
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $result = $this->ClsKeieiSeika->fncRankingSelect_NEW($strSyoriYM, $strKI, $intNinzu, $intDaisu, $intKind);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        return $result;
    }

    //20150819 Add End
}
