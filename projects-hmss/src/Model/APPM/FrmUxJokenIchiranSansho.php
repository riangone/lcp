<?php
/**
 * 説明：
 *
 *
 * @author YANGYANG
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\APPM;

use App\Model\Component\ClsComDb;
class FrmUxJokenIchiranSansho extends ClsComDb
{
    public function FncGetNaiBu($flg)
    {
        $strSQL = $this->FncGetNaiBu_sql($flg);
        return parent::select($strSQL);
    }

    public function FncAutoComplete()
    {
        $strSQL = $this->FncAutoComplete_sql();
        return parent::select($strSQL);
    }

    public function FncSearch($txtHyoJI, $chkZenkensofuFlg, $txtMesseJi, $ddlRenKeiKbn, $ddlDelFlg, $sortStr)
    {
        $strSQL = $this->FncSearch_sql($txtHyoJI, $chkZenkensofuFlg, $txtMesseJi, $ddlRenKeiKbn, $ddlDelFlg, $sortStr);
        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：連携区分の取得
    //'関 数 名：FncGetNaiBu_sql
    //'引 数 1 ：なし
    //'戻 り 値：ＳＱＬ
    //'処理説明：連携区分の取得
    //'**********************************************************************
    public function FncGetNaiBu_sql($flg)
    {
        $strSQL = "";
        $strSQL .= "SELECT NAIBU_CD_MEISHO" . " \r\n";
        $strSQL .= ", NAIBU_CD" . " \r\n";
        $strSQL .= "FROM M_CODE" . " \r\n";
        $strSQL .= "WHERE" . " \r\n";
        if ($flg == "1") {
            $strSQL .= "  GAIBU_CD = '016'" . " \r\n";
        } else {
            $strSQL .= "  GAIBU_CD = '005'" . " \r\n";
        }

        return $strSQL;
    }

    //'**********************************************************************
    //'処 理 名：メッセージのオートコンプリート
    //'関 数 名：FncAutoComplete_sql
    //'引 数 1 ：なし
    //'戻 り 値：ＳＱＬ
    //'処理説明：メッセージのオートコンプリート
    //'**********************************************************************
    public function FncAutoComplete_sql()
    {
        $strSQL = "";
        $strSQL .= "SELECT MESSEJI_ID" . " \r\n";
        $strSQL .= ", TAITORU" . " \r\n";
        $strSQL .= "FROM T_MESSEJI" . " \r\n";
        $strSQL .= "WHERE" . " \r\n";
        $strSQL .= "  NAIYO_KBN = '03'";
        $strSQL .= " AND UPD_STS_KBN != '09'";
        $strSQL .= " AND DEL_FLG = '00'";

        return $strSQL;
    }

    //'**********************************************************************
    //'処 理 名：UX条件一覧データの取得
    //'関 数 名：FncSearch_sql
    //'引 数  ：$txtHyoJI(表示日),$chkZenkensofuFlg(全件送付),$txtMesseJi(メッセージ),$ddlRenKeiKbn, $ddlDelFlg,$sortStr
    //'戻 り 値：ＳＱＬ
    //'処理説明：UX条件一覧データの取得
    //'**********************************************************************
    public function FncSearch_sql($txtHyoJI, $chkZenkensofuFlg, $txtMesseJi, $ddlRenKeiKbn, $ddlDelFlg, $sortStr)
    {
        $sortString = "  ";
        if (trim($sortStr) != "") {
            $sortString .= " ORDER BY " . $sortStr . "";
        } else {
            $sortString .= " ORDER BY TJ.HYOJI_ST_YMD DESC";
        }
        $strSQL = "";
        $strSQL .= "SELECT" . " \r\n";
        $strSQL .= "  TJ.UX_JOKEN_ID" . " \r\n";
        $strSQL .= ", TJ.MESSEJI_ID" . " \r\n";
        $strSQL .= ", TM.TAITORU" . " \r\n";
        $strSQL .= ", TO_CHAR(TO_DATE(TJ.HYOJI_ST_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') AS HYOJI_ST_YMD" . " \r\n";
        $strSQL .= ", TO_CHAR(TO_DATE(TJ.HYOJI_ED_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') AS HYOJI_ED_YMD" . " \r\n";
        $strSQL .= ", TJ.TAISHO_KENSU" . " \r\n";
        $strSQL .= " ,TJ.RENKEI_KBN" . " \r\n";
        $strSQL .= " ,MC.NAIBU_CD_MEISHO AS RENKEI_NAME " . " \r\n";
        $strSQL .= ", CASE WHEN TJ.ZENKENSOFU_FLG = '00' THEN ''" . " \r\n";
        $strSQL .= "  WHEN TJ.ZENKENSOFU_FLG = '01' THEN '〇'" . " \r\n";
        $strSQL .= "  ELSE '' END AS ZENKENSOFU_FLG" . " \r\n";
        $strSQL .= ", TJ.DEL_FLG" . " \r\n";
        $strSQL .= "FROM" . " \r\n";
        $strSQL .= "  T_UX_JOKEN TJ" . " \r\n";
        $strSQL .= "LEFT JOIN" . " \r\n";
        $strSQL .= "  T_MESSEJI TM" . " \r\n";
        $strSQL .= "ON " . " \r\n";
        $strSQL .= "  TJ.MESSEJI_ID = TM.MESSEJI_ID" . " \r\n";
        $strSQL .= " AND TM.NAIYO_KBN = '03'" . " \r\n";
        $strSQL .= " AND TM.UPD_STS_KBN != '09'" . " \r\n";
        $strSQL .= " AND TM.DEL_FLG = '00'" . " \r\n";
        $strSQL .= "LEFT JOIN" . " \r\n";
        $strSQL .= " M_CODE MC" . " \r\n";
        $strSQL .= "ON " . " \r\n";
        $strSQL .= "  TJ.RENKEI_KBN = MC.NAIBU_CD" . " \r\n";
        $strSQL .= "AND" . " \r\n";
        $strSQL .= " MC.GAIBU_CD = '005'" . " \r\n";
        $strSQL .= "WHERE 1=1 " . " \r\n";
        //表示日
        if ($txtHyoJI != "") {
            $strSQL .= "AND" . " \r\n";
            $strSQL .= "  TJ.HYOJI_ST_YMD <= '@HYOJI_ST_YMD'" . " \r\n";
            $strSQL .= "AND" . " \r\n";
            $strSQL .= "  TJ.HYOJI_ED_YMD >= '@HYOJI_ED_YMD'" . " \r\n";
        }
        //連携区分
        if ($ddlRenKeiKbn != "") {
            $strSQL .= "AND" . " \r\n";
            $strSQL .= "  TJ.RENKEI_KBN = '@RENKEI_KBN'" . " \r\n";
        }
        //全件送付
        if ($chkZenkensofuFlg != "") {
            $strSQL .= "AND" . " \r\n";
            $strSQL .= "  TJ.ZENKENSOFU_FLG = '@ZENKENSOFU_FLG'" . " \r\n";
        }
        //削除表示
        if ($ddlDelFlg != "") {
            $strSQL .= "AND" . " \r\n";
            $strSQL .= "  TJ.DEL_FLG = '@DEL_FLG'" . " \r\n";
        }
        //メッセージ
        if ($txtMesseJi != "") {
            $strSQL .= "AND" . " \r\n";
            $strSQL .= "  TJ.MESSEJI_ID LIKE '@MESSEJI_ID%'";
        }
        $strSQL .= $sortString;

        $strSQL = str_replace("@HYOJI_ST_YMD", $txtHyoJI = str_replace("/", "", $txtHyoJI), $strSQL);
        $strSQL = str_replace("@HYOJI_ED_YMD", $txtHyoJI = str_replace("/", "", $txtHyoJI), $strSQL);
        $strSQL = str_replace("@RENKEI_KBN", $ddlRenKeiKbn, $strSQL);
        $strSQL = str_replace("@ZENKENSOFU_FLG", $chkZenkensofuFlg, $strSQL);
        $strSQL = str_replace("@DEL_FLG", $ddlDelFlg, $strSQL);
        $strSQL = str_replace("@MESSEJI_ID", str_replace("'", "''", $txtMesseJi), $strSQL);

        return $strSQL;
    }

}