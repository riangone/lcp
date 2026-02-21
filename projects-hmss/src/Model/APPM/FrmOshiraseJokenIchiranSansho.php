<?php
/**
 * 説明：
 *
 *
 * @author yinhuaiyu
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
class FrmOshiraseJokenIchiranSansho extends ClsComDb
{
    //'**********************************************************************
    //'処 理 名：連携区分の取得
    //'関 数 名：FncGetNaiBu
    //'引 数 1 ：なし
    //'戻 り 値：連携区分データ
    //'処理説明：連携区分の取得
    //'**********************************************************************
    public function FncGetNaiBu($flg)
    {
        $strSQL = $this->FncGetNaiBu_sql($flg);
        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：メッセージ取得
    //'関 数 名：FncGetNaiBu
    //'引 数 1 ：なし
    //'戻 り 値：メッセージデータ
    //'処理説明：メッセージ取得
    //'**********************************************************************
    public function fncGetMesseji()
    {
        $strSQL = $this->fncGetMesseji_sql();
        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：お知らせ条件一覧データの取得
    //'関 数 名：fncGetOshiraseData
    //'引 数 1 ：$txtHyoJiFrom(表示日From),$txtHyoJiTo(表示日To)
    //'引 数 2 ：$chkZenkensofuFlg(全件送付)
    //'引 数 3 ：$txtMesseJi(メッセージ)
    //'引 数 4 ：$ddlRenKeiKbn(連携区分)
    //'引 数 5 ：$ddlDelFlg(削除表示)
    //'引 数 6 ：$sortStr
    //'戻 り 値：お知らせ条件一覧データ
    //'処理説明：お知らせ条件一覧データの取得
    //'**********************************************************************
    public function fncGetOshiraseData($txtHyoJiFrom, $txtHyoJiTo, $chkZenkensofuFlg, $txtMesseJi, $ddlRenKeiKbn, $ddlDelFlg, $sortStr)
    {
        $strSQL = $this->fncGetOshiraseData_sql($txtHyoJiFrom, $txtHyoJiTo, $chkZenkensofuFlg, $txtMesseJi, $ddlRenKeiKbn, $ddlDelFlg, $sortStr);
        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：連携区分の取得SQL
    //'関 数 名：FncGetNaiBu_sql
    //'引 数 1 ：なし
    //'戻 り 値：ＳＱＬ
    //'処理説明：連携区分の取得SQL
    //'**********************************************************************
    public function FncGetNaiBu_sql($flg)
    {
        $strSQL = "";
        $strSQL .= "SELECT  DISTINCT NAIBU_CD," . " \r\n";
        $strSQL .= " NAIBU_CD_MEISHO " . " \r\n";
        $strSQL .= "FROM M_CODE" . " \r\n";
        $strSQL .= "WHERE" . " \r\n";
        if ($flg == "1") {
            $strSQL .= "  GAIBU_CD = '005'" . " \r\n";
        }
        if ($flg == "2") {
            $strSQL .= "  GAIBU_CD = '016'" . " \r\n";
        }
        $strSQL .= " AND del_flg = '00'" . " \r\n";

        return $strSQL;
    }

    //'**********************************************************************
    //'処 理 名：メッセージのオートコンプリート
    //'関 数 名：fncGetMesseji_sql
    //'引 数 1 ：なし
    //'戻 り 値：ＳＱＬ
    //'処理説明：メッセージのオートコンプリート
    //'**********************************************************************
    public function fncGetMesseji_sql()
    {
        $strSQL = "";
        $strSQL .= "SELECT MESSEJI_ID" . " \r\n";
        $strSQL .= ", TAITORU" . " \r\n";
        $strSQL .= "FROM T_MESSEJI" . " \r\n";
        $strSQL .= "WHERE" . " \r\n";
        $strSQL .= " MESSEJI_RIYO_KIKAN_FROM <= TO_CHAR(SYSDATE,'YYYYMMDD')" . " \r\n";
        $strSQL .= " AND MESSEJI_RIYO_KIKAN_TO >= TO_CHAR(SYSDATE,'YYYYMMDD')" . " \r\n";
        $strSQL .= " AND NAIYO_KBN IN ('01','02')";
        $strSQL .= " AND UPD_STS_KBN != '09'";
        $strSQL .= " AND DEL_FLG = '00'";

        return $strSQL;
    }

    //'**********************************************************************
    //'処 理 名：お知らせ条件一覧データの取得SQL
    //'関 数 名：fncGetOshiraseData_sql
    //'引 数 1 ：$txtHyoJiFrom(表示日From),$txtHyoJiTo(表示日To)
    //'引 数 2 ：$chkZenkensofuFlg(全件送付)
    //'引 数 3 ：$txtMesseJi(メッセージ)
    //'引 数 4 ：$ddlRenKeiKbn(連携区分)
    //'引 数 5 ：$ddlDelFlg(削除表示)
    //'引 数 6 ：$sortStr
    //'戻 り 値：ＳＱＬ
    //'処理説明：お知らせ条件一覧データの取得SQL
    //'**********************************************************************
    public function fncGetOshiraseData_sql($txtHyoJiFrom, $txtHyoJiTo, $chkZenkensofuFlg, $txtMesseJi, $ddlRenKeiKbn, $ddlDelFlg, $sortStr)
    {

        $sortString = "  ";
        if (trim($sortStr) != "") {
            $sortString .= " ORDER BY " . $sortStr . "";
        }

        $strSQL = "";
        $strSQL .= "SELECT" . " \r\n";
        $strSQL .= "  T_OSHIRASEJOKEN.OSHIRASEJOKEN_ID,  " . " \r\n";
        $strSQL .= "  T_OSHIRASEJOKEN.MESSEJI_ID,  " . " \r\n";
        $strSQL .= "  T_MESSEJI.TAITORU,  " . " \r\n";
        $strSQL .= "  TO_CHAR(TO_DATE(T_OSHIRASEJOKEN.HYOJI_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') AS HYOJI_YMD," . " \r\n";
        $strSQL .= "  TO_CHAR(TO_DATE(T_OSHIRASEJOKEN.HYOJI_HM,'HH24:mi'),'HH24:mi') AS HYOJI_HM," . " \r\n";
        $strSQL .= "  T_OSHIRASEJOKEN.TAISHO_KENSU," . " \r\n";
        $strSQL .= "  M_CODE.NAIBU_CD_MEISHO," . " \r\n";
        $strSQL .= "  CASE WHEN T_OSHIRASEJOKEN.ZENKENSOFU_FLG = '00' THEN ''" . " \r\n";
        $strSQL .= "  WHEN T_OSHIRASEJOKEN.ZENKENSOFU_FLG = '01' THEN '〇'" . " \r\n";
        $strSQL .= "  ELSE '' END AS ZENKENSOFU_FLG," . " \r\n";
        $strSQL .= " T_OSHIRASEJOKEN.DEL_FLG," . " \r\n";
        $strSQL .= " T_OSHIRASEJOKEN.RENKEI_KBN" . " \r\n";
        $strSQL .= " FROM" . " \r\n";
        $strSQL .= "  T_OSHIRASEJOKEN," . " \r\n";
        $strSQL .= "  T_MESSEJI," . " \r\n";
        $strSQL .= "  M_CODE" . " \r\n";
        $strSQL .= "WHERE" . " \r\n";
        $strSQL .= "  T_OSHIRASEJOKEN.MESSEJI_ID = T_MESSEJI.MESSEJI_ID" . " \r\n";
        $strSQL .= "  AND T_OSHIRASEJOKEN.RENKEI_KBN = M_CODE.NAIBU_CD" . " \r\n";
        $strSQL .= "  AND M_CODE.GAIBU_CD = '005'" . " \r\n";
        $strSQL .= "  AND M_CODE.YUKO_KAISHI_YMD <= T_OSHIRASEJOKEN.HYOJI_YMD" . " \r\n";
        $strSQL .= "  AND M_CODE.YUKO_SHURYO_YMD >= T_OSHIRASEJOKEN.HYOJI_YMD" . " \r\n";
        $strSQL .= "  AND M_CODE.DEL_FLG = '00'" . " \r\n";
        //削除表示
        if ($ddlDelFlg == "01") {
            $strSQL .= "AND T_OSHIRASEJOKEN.UPD_STS_KBN = '09'" . " \r\n";
        }
        if ($ddlDelFlg == "00") {
            $strSQL .= "AND T_OSHIRASEJOKEN.UPD_STS_KBN != '09'" . " \r\n";
            $strSQL .= "AND T_OSHIRASEJOKEN.DEL_FLG = '00'" . " \r\n";
        }
        //表示日from
        if ($txtHyoJiFrom != "") {
            $strSQL .= "AND T_OSHIRASEJOKEN.HYOJI_YMD >= '" . $txtHyoJiFrom . "'" . " \r\n";
        }
        //表示日to
        if ($txtHyoJiTo != "") {
            $strSQL .= "AND T_OSHIRASEJOKEN.HYOJI_YMD <= '" . $txtHyoJiTo . "'" . " \r\n";
        }
        //連携区分
        if ($ddlRenKeiKbn != "") {
            $strSQL .= "AND T_OSHIRASEJOKEN.RENKEI_KBN = '" . $ddlRenKeiKbn . "'" . " \r\n";
        }
        //全件送付
        if ($chkZenkensofuFlg != "") {
            $strSQL .= "AND T_OSHIRASEJOKEN.ZENKENSOFU_FLG = '" . $chkZenkensofuFlg . "'" . " \r\n";
        }
        //メッセージ
        if ($txtMesseJi != "") {
            $strSQL .= "AND T_OSHIRASEJOKEN.MESSEJI_ID like '%" . str_replace("'", "''", $txtMesseJi) . "%'" . " \r\n";
        }

        $strSQL .= $sortString;


        return $strSQL;
    }

}