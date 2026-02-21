<?php
// 共通クラスの読込み
namespace App\Model\R4\R4G;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmControl extends ClsComDb
{
    // **********************************************************************
    // '関 数 名：SQL作成
    // '引    数：無し
    // '戻 り 値：無し
    // '処理説明：M_CONTROLの状態を取得するSQL作成
    // '**********************************************************************
    function selectSql()
    {
        $strsql = "SELECT LOCK_ID_1,LOCK_ID_2,LOCK_ID_3,LOCK_ID_4,LOCK_ID_5,LOCK_ID_6,LOCK_ID_7,LOCK_ID_8,LOCK_ID_9 FROM M_CONTROL";
        return $strsql;
    }

    function updataSql($temp)
    {
        $num = count($temp);
        $strSQL = "UPDATE M_CONTROL　SET";
        for ($i = 0; $i < $num; $i++) {
            if ($temp[$i] != 0) {
                $strSQL = $strSQL . " " . "LOCK_ID_" . $temp[$i] . "=0,";
            }
        }
        $lenth = strlen($strSQL);
        $strSQL1 = substr($strSQL, 0, $lenth - 1);
        return $strSQL1;
    }

    public function fncControlDateSelect()
    {
        return parent::select($this->selectSql());
    }

    public function fncUpdateControl($temp)
    {
        return parent::update($this->updataSql($temp));
    }

}
