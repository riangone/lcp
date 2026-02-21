<?php
// 共通クラスの読込み
namespace App\Model\R4\Component;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：ClsComFncKRSS
// * 処理説明：共通関数
//*************************************


// 共通クラスの読込み

class ClsComFncKRSS extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    // protected $conn_orl = "";
    // protected $Sel_Array = "";
    // 20131004 kamei add end

    //---20150629 fan upd s.
    //const GSYSTEM_KB = 1;
    const GSYSTEM_KB = 11;
    //---20150629 fan upd e.

    public function fncTargetChkSQL($strAuthID)
    {
        $strSQL = "";
        $strSQL .= "SELECT *" . "\r\n";
        $strSQL .= "FROM   HAUTHORITY" . "\r\n";
        $strSQL .= "WHERE  HAUTH_ID = '@AuthID'" . "\r\n";
        $strSQL .= "AND    SYS_KB = '@SYS_KB'" . "\r\n";
        $strSQL = str_replace("@AuthID", $strAuthID, $strSQL);
        $strSQL = str_replace("@SYS_KB", self::GSYSTEM_KB, $strSQL);
        return $strSQL;
    }

    public function fncTargetChk($strAuthID)
    {
        return parent::select($this->fncTargetChkSQL($strAuthID));
    }

    public function fncSelAuthoritySQL($strAuthID, $strSyainNo, $strBusyoCD)
    {
        $strSQL = "";
        $strSQL .= "SELECT SYAIN_NO" . "\r\n";
        $strSQL .= ",      BUSYO_CD" . "\r\n";
        $strSQL .= ",      HAUTH_ID" . "\r\n";
        $strSQL .= "FROM   HAUTHORITY_CTL" . "\r\n";
        $strSQL .= "WHERE  SYAIN_NO = '@SYAINNO'" . "\r\n";
        $strSQL .= "AND    BUSYO_CD = '@BUSYOCD'" . "\r\n";
        $strSQL .= "AND    HAUTH_ID = '@AUTHID'" . "\r\n";
        $strSQL .= "AND    SYS_KB = '@SYS_KB'" . "\r\n";

        $strSQL = str_replace("@BUSYOCD", $strBusyoCD, $strSQL);
        $strSQL = str_replace("@SYAINNO", $strSyainNo, $strSQL);
        $strSQL = str_replace("@AUTHID", $strAuthID, $strSQL);
        $strSQL = str_replace("@SYS_KB", self::GSYSTEM_KB, $strSQL);
        return $strSQL;
    }

    public function fncSelAuthority($strAuthID, $strSyainNo, $strBusyoCD)
    {
        return parent::select($this->fncSelAuthoritySQL($strAuthID, $strSyainNo, $strBusyoCD));
    }

}