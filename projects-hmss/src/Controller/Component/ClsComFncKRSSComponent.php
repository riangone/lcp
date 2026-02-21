<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use App\Model\R4\Component\ClsComFncKRSS;

class ClsComFncKRSSComponent extends Component
{
    public $ClsComFncKRSS;
    //**********************************************************************
    //処 理 名：権限付与
    //関 数 名：fncAuthorityInvest
    //引    数：CurrentForm　　  (I)対象ﾌｫｰﾑ
    //　　　　　strSyainNo　　   (I)社員番号
    //　　　　　strBusyoCD       (I)部署コード
    //戻 り 値：True:正常終了 False:異常終了
    //処理説明：対象ﾌｫｰﾑ上に存在するｺﾝﾄﾛｰﾙに対して
    //          該当社員が該当部署に対して権限がある場合はｺﾝﾄﾛｰﾙを活性に、権限がない場合は不活性にする
    //**********************************************************************
    public function fncAuthorityInvest($CurrentForm, $strSyainNo, $strBusyoCD)
    {
        $result = array('result' => FALSE, 'data' => array());
        $strAuthID = "";
        // $strChildAuthID = "";
        $tempResult = array();
        try {
            foreach ($CurrentForm as $value) {
                //権限ID取得
                $strAuthID = str_replace("cmd", "", str_replace("txt", "", $value));
                //権限対象ｺﾝﾄﾛｰﾙかのﾁｪｯｸ
                if ($this->fncTargetChk($strAuthID) == TRUE) {
                    //権限データを取得
                    $tempResult = $this->fncSelAuthority($strAuthID, $strSyainNo, $strBusyoCD);
                    if ($tempResult['result'] == FALSE) {
                        throw new \Exception($tempResult['data']);
                    }
                    if (count((array) $tempResult['data']) > 0) {
                        //権限あり
                        $result['data'][$value] = 1;
                    } else {
                        //権限なし
                        $result['data'][$value] = 0;
                    }
                }
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    public function fncTargetChk($strAuthID)
    {
        $result = array();
        try {
            $this->ClsComFncKRSS = new ClsComFncKRSS();
            $result = $this->ClsComFncKRSS->fncTargetChk($strAuthID);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if (count((array) $result['data']) == 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            return FALSE;
        }
    }

    public function fncSelAuthority($strAuthID, $strSyainNo, $strBusyoCD)
    {
        $result = array();
        try {
            $this->ClsComFncKRSS = new ClsComFncKRSS();
            $result = $this->ClsComFncKRSS->fncSelAuthority($strAuthID, $strSyainNo, $strBusyoCD);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

}
