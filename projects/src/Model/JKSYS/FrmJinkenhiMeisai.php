<?php
/**
 * 説明：
 *
 *
 * @author
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：FrmJinkenhiMeisai
// * 関数名	：FrmJinkenhiMeisai
// * 処理説明	：共通クラスの読込み
//*************************************
class FrmJinkenhiMeisai extends ClsComDb
{
    //部署コード取得
    public function FncGetBusyoMstValue()
    {
        $strSQL = "";

        $strSQL .= "SELECT BUSYO_NM" . "\r\n";
        $strSQL .= ",      BUSYO_CD" . "\r\n";
        $strSQL .= "FROM   JKBUMON" . "\r\n";

        return parent::select($strSQL);

    }

    // * 処理名	：fncGetJKCMST
    // * 関数名	：fncGetJKCMST
    // * 処理説明	：人事コントロールマスタ
    public function fncGetJKCMST()
    {
        $strSql = $this->fncGetJKCMST_sql();
        return parent::select($strSql);
    }

    // * 処理名	：fncGetJKCMST_sql
    // * 関数名	：fncGetJKCMST_sql
    // * 処理説明	：人事コントロールマスタsql
    private function fncGetJKCMST_sql()
    {
        $strSQL = "";
        $strSQL = "SELECT" . "\r\n";
        $strSQL .= "    SYORI_YM" . "\r\n";
        $strSQL .= "FROM " . "\r\n";
        $strSQL .= "    JKCONTROLMST JKC" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    JKC.ID = '01'" . "\r\n";

        return $strSQL;
    }

    // * 処理名	：fncGetPASSMST
    // * 関数名	：fncGetPASSMST
    // * 処理説明	：人事コントロールマスタ
    public function fncGetPASSMST()
    {
        $strSql = $this->fncGetPASSMSTSQL();
        return parent::select($strSql);
    }

    public function fncGetPASSMSTSQL()
    {
        $strSQL = "";
        $strSQL = " SELECT PASS" . "\r\n";
        $strSQL .= " FROM JKPASSMST JKP " . "\r\n";
        $strSQL .= " WHERE JKP.PRO_NO = 9 " . "\r\n";
        return $strSQL;

    }

    // * 処理名	：fncGetJinkenhi
    // * 関数名	：fncGetJinkenhi
    // * 処理説明	：人件費データ
    public function fncGetJinkenhi($taisyouYM, $busyoFrom, $busyoTo)
    {
        $strSql = $this->fncGetJinkenhiSQL($taisyouYM, $busyoFrom, $busyoTo);
        return parent::select($strSql);
    }

    public function fncGetJinkenhiSQL($taisyouYM, $busyoFrom, $busyoTo)
    {
        $strSQL = "";
        $strSQL .= " SELECT " . "\r\n";
        $strSQL .= " JKH.BUSYO_CD" . "\r\n";
        $strSQL .= ",JKH.SYAIN_NO" . "\r\n";
        $strSQL .= ",JKH.TEIJIKAN_GESSYU" . "\r\n";
        $strSQL .= ",JKH.ZANGYOU_TEATE" . "\r\n";
        $strSQL .= ",JKH.GYOUSEKI_SYOUREI" . "\r\n";
        $strSQL .= ",JKH.HOKA_GSK_SYOUREI" . "\r\n";
        $strSQL .= ",JKH.SONOTA_TEATE" . "\r\n";
        $strSQL .= ",(JKH.TEIJIKAN_GESSYU + JKH.ZANGYOU_TEATE + JKH.GYOUSEKI_SYOUREI + JKH.HOKA_GSK_SYOUREI + JKH.SONOTA_TEATE) KYUUYO_KEI" . "\r\n";
        $strSQL .= ",(JKH.KENKO_HKN_RYO + JKH.KAIGO_HKN_RYO + JKH.KOUSEINENKIN + JKH.KOYOU_HKN_RYO + JKH.ROUSAI_HKN_RYO + JKH.JIDOUTEATE + JKH.TAISYOKU_KYUFU) SYAHO" . "\r\n";
        $strSQL .= ",JKH.BNS_MITUMORI" . "\r\n";
        $strSQL .= ",(JKH.BNS_KENKO_HKN_RYO + JKH.BNS_KAIGO_HKN_RYO + JKH.BNS_KOUSEI_NENKIN + JKH.BNS_JIDOU_TEATE) SYOUYO_SYAHO" . "\r\n";
        $strSQL .= ",JKB.BUSYO_NM" . "\r\n";
        $strSQL .= ",JKS.SYAIN_NM" . "\r\n";
        $strSQL .= ",JKK.KUBUN_NM" . "\r\n";
        $strSQL .= ",JKC.MEISYOU" . "\r\n";

        //人件費データ
        $strSQL .= " FROM JKJINKENHI JKH " . "\r\n";
        //社員マスタ
        $strSQL .= " LEFT JOIN JKSYAIN JKS" . "\r\n";
        $strSQL .= " ON JKH.SYAIN_NO = JKS.SYAIN_NO" . "\r\n";
        //部門マスタ
        $strSQL .= " LEFT JOIN JKBUMON JKB" . "\r\n";
        $strSQL .= " ON JKH.BUSYO_CD = JKB.BUSYO_CD" . "\r\n";
        //異動履歴マスタ
        $strSQL .= " LEFT JOIN (SELECT SYAIN_NO,SHIKAKU_CD" . "\r\n";
        $strSQL .= "            FROM JKIDOURIREKI A" . "\r\n";
        $strSQL .= "            WHERE ANNOUNCE_DT = " . "\r\n";
        $strSQL .= "	                           (SELECT MAX(ANNOUNCE_DT)" . "\r\n";
        $strSQL .= "		                        FROM JKIDOURIREKI " . "\r\n";
        $strSQL .= "                             WHERE ANNOUNCE_DT <= LAST_DAY(TO_DATE('@TAISYOU_YM' || '/01','yyyy-mm-dd'))" . "\r\n";
        $strSQL .= "	                            AND A.SYAIN_NO = SYAIN_NO" . "\r\n";
        $strSQL .= "	                            GROUP BY SYAIN_NO" . "\r\n";
        $strSQL .= "	                            )" . "\r\n";
        $strSQL .= "            ) JKI" . "\r\n";
        $strSQL .= " ON JKH.SYAIN_NO = JKI.SYAIN_NO" . "\r\n";
        //コードマスタ
        $strSQL .= " LEFT JOIN JKCODEMST JKC" . "\r\n";
        $strSQL .= " ON JKI.SHIKAKU_CD = JKC.CODE" . "\r\n";
        $strSQL .= " AND JKC.ID = 'SHIKAKU'" . "\r\n";
        //区分マスタ
        $strSQL .= " LEFT JOIN JKKUBUNMST JKK" . "\r\n";
        $strSQL .= " ON JKH.KOYOU_KB = JKK.KUBUN_CD" . "\r\n";
        $strSQL .= " AND JKK.KUBUN_ID = 'KOYOU'" . "\r\n";

        $strSQL .= " WHERE JKH.TAISYOU_YM = @TAISYOU_YM " . "\r\n";
        //部署コードFromが入力されている場合
        if ($busyoFrom <> "") {
            $strSQL .= " AND JKH.BUSYO_CD >= '@BUSYO_FROM'" . "\r\n";
        }
        //部署コードToが入力されている場合
        if ($busyoTo <> "") {
            $strSQL .= " AND JKH.BUSYO_CD <= '@BUSYO_TO'" . "\r\n";
        }
        $strSQL .= " ORDER BY JKH.BUSYO_CD, JKH.SYAIN_NO" . "\r\n";

        //条件を設定
        $strSQL = str_replace("@TAISYOU_YM", $taisyouYM, $strSQL);
        $strSQL = str_replace("@BUSYO_FROM", $busyoFrom, $strSQL);
        $strSQL = str_replace("@BUSYO_TO", $busyoTo, $strSQL);
        return $strSQL;
    }

}
