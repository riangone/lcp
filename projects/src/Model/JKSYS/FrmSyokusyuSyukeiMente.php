<?php
// 共通クラスの読込み
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmSyokusyuSyukeiMente extends ClsComDb
{
    private $ClsComFncJKSYS;
    //評語職種集計区分マスタ
    public function FncGetHSSTTLKBNMST($txtKbn)
    {
        $strSQL = "SELECT";
        $strSQL .= "     SYOKUSYU_TTL_KB";
        $strSQL .= "    ,SYOKUSYU_TTL_KB_NM";
        $strSQL .= "    ,ORDER_NO";
        $strSQL .= " FROM JKHYOUGOSKSTTLKBNMST TTLNM";

        if ($txtKbn <> '') {
            $strSQL .= " WHERE  SYOKUSYU_TTL_KB = '@TTL_KB' ";
        }
        $strSQL .= " ORDER BY SYOKUSYU_TTL_KB";

        //条件を設定
        $strSQL = str_replace("@TTL_KB", $txtKbn, $strSQL);

        return parent::select($strSQL);
    }

    //コードマスタ(職種)
    public function FncGetCODEMST()
    {
        $strSQL = "SELECT";
        $strSQL .= "     CODE";
        $strSQL .= "    ,MEISYOU";
        $strSQL .= "    ,'' As display_code";
        $strSQL .= " FROM JKCODEMST JKC";
        $strSQL .= " WHERE ID = 'SYOKUSYU'";
        $strSQL .= " ORDER BY CODE";

        return parent::select($strSQL);
    }

    //評語職種集計マスタ(SELECT)
    public function FncGetHSSTTLMST($txtKbn)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "  SELECT";
        $strSQL .= "     MST.SYOKUSYU_CD,MEISYOU";
        $strSQL .= " FROM JKHYOUGOSKSTTLMST MST";
        $strSQL .= " LEFT JOIN JKCODEMST JKC";
        $strSQL .= " ON JKC.CODE = MST.SYOKUSYU_CD AND JKC.ID = 'SYOKUSYU'";
        $strSQL .= " WHERE  SYOKUSYU_TTL_KB = @TTLKB";
        $strSQL .= " ORDER BY SYOKUSYU_CD";

        $strSQL = str_replace("@TTLKB", $this->ClsComFncJKSYS->FncSqlNv($txtKbn), $strSQL);

        return parent::select($strSQL);
    }

    //評語職種集計区分マスタ(INSERT)
    public function FncInsHSSTTLKBNMST($postData)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "INSERT INTO JKHYOUGOSKSTTLKBNMST(";
        $strSQL .= "     SYOKUSYU_TTL_KB";
        $strSQL .= "    ,SYOKUSYU_TTL_KB_NM";
        $strSQL .= "    ,ORDER_NO";
        $strSQL .= "    ,CREATE_DATE";
        $strSQL .= "    ,CRE_SYA_CD";
        $strSQL .= "    ,CRE_PRG_ID";
        $strSQL .= "    ,UPD_DATE";
        $strSQL .= "    ,UPD_SYA_CD";
        $strSQL .= "    ,UPD_PRG_ID";
        $strSQL .= "    ,UPD_CLT_NM";
        $strSQL .= " )VALUES(";
        $strSQL .= "  @TTLKB";
        $strSQL .= " ,@KBNM";
        $strSQL .= " ,@ORDER";
        $strSQL .= " ,SYSDATE";
        $strSQL .= " ,'" . $this->GS_LOGINUSER['strUserID'] . "'";
        $strSQL .= " ,'SyokusyuSyukeiMente'";
        $strSQL .= " ,SYSDATE";
        $strSQL .= " ,'" . $this->GS_LOGINUSER['strUserID'] . "'";
        $strSQL .= " ,'SyokusyuSyukeiMente'";
        $strSQL .= " ,'" . $this->GS_LOGINUSER['strClientNM'] . "'";
        $strSQL .= " )";

        $strSQL = str_replace("@TTLKB", $this->ClsComFncJKSYS->FncSqlNv($postData['txtKbn']), $strSQL);
        $strSQL = str_replace("@KBNM", $this->ClsComFncJKSYS->FncSqlNv($postData['txtKbnNM']), $strSQL);
        $strSQL = str_replace("@ORDER", $this->ClsComFncJKSYS->FncSqlNv($postData['txtOrder']), $strSQL);

        return parent::insert($strSQL);
    }

    //評語職種集計区分マスタ(UPDATE)
    public function FncUpdHSSTTLKBNMST($postData)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "UPDATE JKHYOUGOSKSTTLKBNMST SET";
        $strSQL .= "     SYOKUSYU_TTL_KB = @TTLKB";
        $strSQL .= "    ,SYOKUSYU_TTL_KB_NM = @KBNM";
        $strSQL .= "    ,ORDER_NO = @ORDER";
        $strSQL .= "    ,UPD_DATE = SYSDATE";
        $strSQL .= "    ,UPD_SYA_CD = '" . $this->GS_LOGINUSER['strUserID'] . "'";
        $strSQL .= "    ,UPD_PRG_ID = 'SyokusyuSyukeiMente'";
        $strSQL .= "    ,UPD_CLT_NM = '" . $this->GS_LOGINUSER['strClientNM'] . "'";
        $strSQL .= " WHERE";
        $strSQL .= "    SYOKUSYU_TTL_KB = @TTLKB";

        $strSQL = str_replace("@TTLKB", $this->ClsComFncJKSYS->FncSqlNv($postData['txtKbn']), $strSQL);
        $strSQL = str_replace("@KBNM", $this->ClsComFncJKSYS->FncSqlNv($postData['txtKbnNM']), $strSQL);
        $strSQL = str_replace("@ORDER", $this->ClsComFncJKSYS->FncSqlNv($postData['txtOrder']), $strSQL);

        return parent::update($strSQL);
    }

    //評語職種集計マスタ(DELETE)
    public function FncDelHSSTTLMST($txtKbn)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "DELETE FROM JKHYOUGOSKSTTLMST TTL";
        $strSQL .= "  WHERE  TTL.SYOKUSYU_TTL_KB = @TTLKB";

        $strSQL = str_replace("@TTLKB", $this->ClsComFncJKSYS->FncSqlNv($txtKbn), $strSQL);

        return parent::delete($strSQL);
    }

    //評語職種集計区分マスタ(DELETE)
    public function FncDelHSSTTLKBNMST($txtKbn)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "DELETE FROM JKHYOUGOSKSTTLKBNMST TTLNM";
        $strSQL .= "  WHERE  TTLNM.SYOKUSYU_TTL_KB = @TTLKB";

        $strSQL = str_replace("@TTLKB", $this->ClsComFncJKSYS->FncSqlNv($txtKbn), $strSQL);

        return parent::delete($strSQL);

    }

    //評語職種集計マスタ(INSERT)
    public function FncInsHSSTTLMST($value, $txtKbn)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "INSERT INTO JKHYOUGOSKSTTLMST(";
        $strSQL .= "  SYOKUSYU_TTL_KB";
        $strSQL .= " ,SYOKUSYU_CD";
        $strSQL .= " ,CREATE_DATE";
        $strSQL .= " ,CRE_SYA_CD";
        $strSQL .= " ,CRE_PRG_ID";
        $strSQL .= " ,UPD_DATE";
        $strSQL .= " ,UPD_SYA_CD";
        $strSQL .= " ,UPD_PRG_ID";
        $strSQL .= " ,UPD_CLT_NM";
        $strSQL .= " )VALUES(";
        $strSQL .= " @TTLKB";
        $strSQL .= " ,'@SYOKUCD'";
        $strSQL .= " ,SYSDATE";
        $strSQL .= " ,'" . $this->GS_LOGINUSER['strUserID'] . "'";
        $strSQL .= " ,'SyokusyuSyukeiMente'";
        $strSQL .= " ,SYSDATE";
        $strSQL .= " ,'" . $this->GS_LOGINUSER['strUserID'] . "'";
        $strSQL .= " ,'SyokusyuSyukeiMente'";
        $strSQL .= " ,'" . $this->GS_LOGINUSER['strClientNM'] . "'";
        $strSQL .= " )";

        $strSQL = str_replace("@TTLKB", $this->ClsComFncJKSYS->FncSqlNv($txtKbn), $strSQL);
        $strSQL = str_replace("@SYOKUCD", $value, $strSQL);

        return parent::insert($strSQL);

    }

}
