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
use Cake\Routing\Router;
class FrmAkauntoIchiranSansho extends ClsComDb
{
    public $SessionComponent;
    //'**********************************************************************
    //'処 理 名：アカウント情報取得
    //'関 数 名：getListDataSel
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function getListDataSel($postData, $sortStr)
    {
        $strSql = $this->getListDataSel_sql($postData, $sortStr);

        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：店舗取得
    //'関 数 名：getTenpoData
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function getTenpoData()
    {
        $strSql = $this->getTenpoData_sql();

        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：自店舗取得
    //'関 数 名：getMeTenpo
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function getMeTenpo()
    {
        $strSql = $this->getMeTenpo_sql();

        return parent::select($strSql);
    }

    public function getListDataSel_sql($postData, $sortStr)
    {
        $sortString = "  ";
        if (trim($sortStr) != "") {
            $sortString .= " ORDER BY " . $sortStr . "";
        }

        $strSql = "";
        $strSql .= "SELECT 　HBUSYO.BUSYO_NM," . "\r\n";
        $strSql .= "  T_APURI_RIYO_KOKYAKU.OKYAKUSAMA_NO," . "\r\n";
        $strSql .= "  T_AKAUNTOJOHO.ROGUIN_ID," . "\r\n";
        $strSql .= "  M41C01.DLRCSRNO," . "\r\n";
        $strSql .= "  M41C01.CSRNM1 || M41C01.CSRNM2 AS CSRNM," . "\r\n";
        $strSql .= "  　M41C01.CSRAD1  || M41C01.CSRAD2  || M41C01.CSRAD3 AS CSRAD," . "\r\n";
        $strSql .= "  　M41C01.CUS_HOM_TEL_ACD  || '-'  || M41C01.CUS_HOM_TEL_CCD  || '-' || M41C01.CUS_HOM_TEL_KNY_NO AS CUS_HOM_TEL," . "\r\n";
        $strSql .= "  　M41C01.MOB_TEL_ACD  || '-'  || M41C01.MOB_TEL_CCD || '-'  || M41C01.MOB_TEL_KNY_NO AS MOB_TEL," . "\r\n";
        $strSql .= "  TO_CHAR(TO_DATE(T_AKAUNTOJOHO.HAKKO_YMD,'YYYY/MM/DD'),'YYYY/MM/DD') AS HAKKO_YMD," . "\r\n";
        $strSql .= "  T_AKAUNTOJOHO.KARI_PASUWADO" . "\r\n";
        $strSql .= "FROM T_APURI_RIYO_KOKYAKU," . "\r\n";
        $strSql .= "  　T_AKAUNTOJOHO," . "\r\n";
        $strSql .= "  M41C01,  " . "\r\n";
        $strSql .= "  HBUSYO  " . "\r\n";

        // 検索条件
        $strSql .= " WHERE " . "\r\n";
        $strSql .= " T_APURI_RIYO_KOKYAKU.APURI_RIYO_KOKYAKU_ID = T_AKAUNTOJOHO.APURI_RIYO_KOKYAKU_ID" . "\r\n";
        $strSql .= "AND T_APURI_RIYO_KOKYAKU.OKYAKUSAMA_NO           = M41C01.DLRCSRNO" . "\r\n";
        $strSql .= "AND T_APURI_RIYO_KOKYAKU.DEL_FLG                 = '00'" . "\r\n";
        $strSql .= "AND T_AKAUNTOJOHO.DEL_FLG                        = '00'" . "\r\n";
        $strSql .= "AND trim(M41C01.MAS_DT) IS NULL" . "\r\n";
        $strSql .= "AND T_AKAUNTOJOHO.HAKKO_TEMPO_CD = HBUSYO.BUSYO_CD" . "\r\n";

        // 店舗
        if ($postData['txtTenpo'] != "") {
            $strSql .= "AND T_AKAUNTOJOHO.HAKKO_TEMPO_CD = '" . $postData['txtTenpo'] . "'" . "\r\n";
        }
        // 発行日
        if ($postData['txtDTFrom'] != "") {
            $strSql .= "AND T_AKAUNTOJOHO.HAKKO_YMD >= '" . $postData['txtDTFrom'] . "'" . "\r\n";
        }
        if ($postData['txtDTTo'] != "") {
            $strSql .= "AND T_AKAUNTOJOHO.HAKKO_YMD <= '" . $postData['txtDTTo'] . "'" . "\r\n";
        }
        // お客様名
        if ($postData['txtCusNM'] != NULL && $postData['txtCusNM'] != "") {
            $strSql .= "AND M41C01.CSRNM1 || M41C01.CSRNM2　like '%" . str_replace("'", "''", $postData['txtCusNM']) . "%'" . "\r\n";
        }
        // お客様No
        if ($postData['txtCusNo'] != NULL && $postData['txtCusNo'] != "") {
            $strSql .= "AND T_APURI_RIYO_KOKYAKU.OKYAKUSAMA_NO = '" . $postData['txtCusNo'] . "'" . "\r\n";
        }
        $strSql .= $sortString;

        return $strSql;
    }

    public function getTenpoData_sql()
    {
        $strSql = "";
        $strSql .= " SELECT " . "\r\n";
        $strSql .= "  BUSYO_CD, " . "\r\n";
        $strSql .= "  BUSYO_RYKNM, " . "\r\n";
        $strSql .= "  DSP_SEQNO " . "\r\n";
        $strSql .= " FROM " . "\r\n";
        $strSql .= " HBUSYO " . "\r\n";
        $strSql .= " WHERE  " . "\r\n";
        $strSql .= "  BUSYO_CD>='220' " . "\r\n";
        $strSql .= "  and BUSYO_CD not in ('470','270') " . "\r\n";
        $strSql .= "  and substr(busyo_cd,3,1)='0'  " . "\r\n";
        $strSql .= "  and DSP_SEQNO is not null " . "\r\n";
        $strSql .= " UNION ALL " . "\r\n";
        $strSql .= " SELECT   " . "\r\n";
        $strSql .= "   BUSYO_CD, " . "\r\n";
        $strSql .= "   BUSYO_RYKNM, " . "\r\n";
        $strSql .= "   CASE WHEN DSP_SEQNO IS NULL THEN '999' ELSE DSP_SEQNO END " . "\r\n";
        $strSql .= " FROM " . "\r\n";
        $strSql .= "  HBUSYO " . "\r\n";
        $strSql .= " WHERE  " . "\r\n";
        $strSql .= "  BUSYO_CD in ('180', '220','261') " . "\r\n";
        $strSql .= " ORDER BY " . "\r\n";
        $strSql .= "  DSP_SEQNO " . "\r\n";

        return $strSql;
    }

    public function getMeTenpo_sql()
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= " SELECT " . "\r\n";
        $strSql .= "  CASE KYOTN_CD " . "\r\n";
        $strSql .= "  WHEN '261'" . "\r\n";
        $strSql .= "    THEN '261' " . "\r\n";
        $strSql .= "  ELSE SUBSTR(KYOTN_CD,1,2) " . "\r\n";
        $strSql .= "    || '0'" . "\r\n";
        $strSql .= " END AS KYOTN_CD " . "\r\n";
        $strSql .= "FROM M29MA4" . "\r\n";
        $strSql .= "  WHERE M29MA4.SYAIN_NO = '" . $this->SessionComponent->read('login_user') . "'" . "\r\n";
        return $strSql;
    }

}