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
namespace App\Model\APPM;

use App\Model\Component\ClsComDb;
use Cake\Routing\Router;

class FrmAkauntoHakko extends ClsComDb
{
    public $SessionComponent;
    //'**********************************************************************
    //'処 理 名：アカウント発行済チェック
    //'関 数 名：FncKyakuCheck
    //'引 数 　：お客様No
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function FncKyakuCheck($CustNo)
    {
        $strSql = $this->FncKyakuCheck_sql($CustNo);
        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：お客様情報取得
    //'関 数 名：FncGetSelect_Keiyakusya
    //'引 数 　：お客様No
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function FncGetSelect_Keiyakusya($CustNo)
    {
        $strSql = $this->FncGetSelect_Keiyakusya_sql($CustNo);
        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：採番取得
    //'関 数 名：fncRembanSelect
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncRembanSelect()
    {
        $strSql = $this->fncRembanSelect_sql();
        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：採番取得
    //'関 数 名：fncRembanSelect2
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncRembanSelect2()
    {
        $strSql = $this->fncRembanSelect2_sql();
        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：採番登録
    //'関 数 名：fncRembanInsert
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncRembanInsert($remban)
    {
        $strSql = $this->fncRembanInsert_sql($remban);
        return parent::insert($strSql);
    }

    //'**********************************************************************
    //'処 理 名：アプリ利用顧客登録
    //'関 数 名：fncKokyakuInsert
    //'引 数 　：アプリ利用顧客ID,お客様No
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncKokyakuInsert($KokyakuId, $txtCusNo)
    {
        $strSql = $this->fncKokyakuInsert_sql($KokyakuId, $txtCusNo);
        return parent::insert($strSql);
    }

    //'**********************************************************************
    //'処 理 名：ログインID重複チェック
    //'関 数 名：fncIdCheck
    //'引 数 　：$idC
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncIdCheck($idC)
    {
        $strSql = $this->fncIdCheck_sql($idC);
        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：ID/仮PW 発行
    //'関 数 名：fncIssueInsert
    //'引 数 　：お客様No,ログインID,仮パスワード
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncIssueInsert($idC, $pwdC, $KokyakuId)
    {
        $strSql = $this->fncIssueInsert_sql($idC, $pwdC, $KokyakuId);
        return parent::insert($strSql);
    }

    //'**********************************************************************
    //'処 理 名：採番更新
    //'関 数 名：fncRembanUpdata
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncRembanUpdata()
    {
        $strSql = $this->fncRembanUpdata_sql();
        return parent::update($strSql);
    }

    public function FncKyakuCheck_sql($CustNo)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  t_apuri_riyo_kokyaku.apuri_riyo_kokyaku_id " . "\r\n";
        $strSql .= "  FROM " . "\r\n";
        $strSql .= " t_apuri_riyo_kokyaku " . "\r\n";
        $strSql .= "  WHERE" . "\r\n";
        $strSql .= "  t_apuri_riyo_kokyaku.okyakusama_no = '" . $CustNo . "'" . "\r\n";
        return $strSql;
    }

    public function FncGetSelect_Keiyakusya_sql($CustNo)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  M41C01.DLRCSRNO, " . "\r\n";
        $strSql .= "  M41C01.CSRNM1 || M41C01.CSRNM2 AS CSRNM, " . "\r\n";
        $strSql .= "  M41C01.CUS_HOM_TEL_ACD, " . "\r\n";
        $strSql .= "  M41C01.CUS_HOM_TEL_CCD, " . "\r\n";
        $strSql .= "  M41C01.CUS_HOM_TEL_KNY_NO, " . "\r\n";
        $strSql .= "  M41C01.MOB_TEL_ACD, " . "\r\n";
        $strSql .= "  M41C01.MOB_TEL_CCD, " . "\r\n";
        $strSql .= "  M41C01.MOB_TEL_KNY_NO, " . "\r\n";
        $strSql .= "  M41C01.CSRAD1, " . "\r\n";
        $strSql .= "  M41C01.CSRAD2, " . "\r\n";
        $strSql .= "  M41C01.CSRAD3, " . "\r\n";
        $strSql .= "  M27M14.SCD_NM, " . "\r\n";
        $strSql .= "  M41C04.VCLRGTNO_SYU, " . "\r\n";
        $strSql .= "  M41C04.VCLRGTNO_KANA, " . "\r\n";
        $strSql .= "  M41C04.VCLRGTNO_REN, " . "\r\n";
        $strSql .= "  M41C04.VIN_WMIVDS, " . "\r\n";
        $strSql .= "  M41C04.VIN_VIS, " . "\r\n";
        $strSql .= "  M41C03.VCLNM " . "\r\n";
        $strSql .= "FROM" . "\r\n";
        $strSql .= "  M41C01, " . "\r\n";
        $strSql .= "  M27M14, " . "\r\n";
        $strSql .= "  M41C04, " . "\r\n";
        $strSql .= "  M41C03" . "\r\n";
        $strSql .= " WHERE " . "\r\n";
        $strSql .= "  M41C01.DLRCSRNO = M41C04.DLRCSRNO " . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  M41C04.VIN_WMIVDS = M41C03.VIN_WMIVDS " . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  M41C04.VIN_VIS = M41C03.VIN_VIS " . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  M41C01.DLRCSRNO = '" . $CustNo . "'" . "\r\n";
        $strSql .= " AND M27M14.SCD_ID(+)    = 'RIKUJI' " . "\r\n";
        $strSql .= " AND M27M14.SCD_VAL(+)   = M41C04.VCLRGTNO_LAND " . "\r\n";
        $strSql .= " AND trim(M41C04.MAS_DT) is null " . "\r\n";
        return $strSql;
    }

    public function fncRembanSelect_sql()
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  REMBAN, " . "\r\n";
        $strSql .= "  TO_CHAR(SYSDATE,'YYYYMM') AS SAIBAN_YM " . "\r\n";
        $strSql .= "  FROM " . "\r\n";
        $strSql .= "  t_ym_saiban " . "\r\n";
        $strSql .= "  WHERE" . "\r\n";
        $strSql .= " teburumei = 't_apuri_riyo_kokyaku'" . "\r\n";
        $strSql .= " AND  saiban_ym = TO_CHAR(SYSDATE,'YYYYMM')" . "\r\n";
        $strSql .= " FOR UPDATE NOWAIT" . "\r\n";
        return $strSql;
    }

    public function fncRembanSelect2_sql()
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  REMBAN, " . "\r\n";
        $strSql .= "  TO_CHAR(SYSDATE,'YYYYMM') AS SAIBAN_YM " . "\r\n";
        $strSql .= "  FROM " . "\r\n";
        $strSql .= "  t_ym_saiban " . "\r\n";
        $strSql .= "  WHERE" . "\r\n";
        $strSql .= " teburumei = 't_apuri_riyo_kokyaku_saiban'" . "\r\n";
        $strSql .= " AND  saiban_ym = '999912'" . "\r\n";
        $strSql .= " FOR UPDATE NOWAIT" . "\r\n";
        return $strSql;
    }

    public function fncRembanInsert_sql($remban)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "INSERT " . "\r\n";
        $strSql .= "  INTO T_YM_SAIBAN VALUES" . "\r\n";
        $strSql .= "  (" . "\r\n";
        $strSql .= "  't_apuri_riyo_kokyaku'," . "\r\n";
        $strSql .= "  TO_CHAR(SYSDATE,'YYYYMM')," . "\r\n";
        $strSql .= $remban . "," . "\r\n";
        $strSql .= "  sysdate," . "\r\n";
        $strSql .= " '" . $this->SessionComponent->read('login_user') . "'," . "\r\n";
        $strSql .= "  sysdate," . "\r\n";
        $strSql .= " '" . $this->SessionComponent->read('login_user') . "'," . "\r\n";
        $strSql .= " '00'," . "\r\n";
        $strSql .= "   ''" . "\r\n";
        $strSql .= "  )" . "\r\n";
        return $strSql;
    }

    public function fncKokyakuInsert_sql($KokyakuId, $txtCusNo): string
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "INSERT " . "\r\n";
        $strSql .= "  INTO T_APURI_RIYO_KOKYAKU VALUES" . "\r\n";
        $strSql .= "  (" . "\r\n";
        $strSql .= "  '" . $KokyakuId . "'," . "\r\n";
        $strSql .= "  '" . $txtCusNo . "'," . "\r\n";
        $strSql .= "  '01'," . "\r\n";
        $strSql .= "  sysdate," . "\r\n";
        $strSql .= " '" . $this->SessionComponent->read('login_user') . "'," . "\r\n";
        $strSql .= "  sysdate," . "\r\n";
        $strSql .= " '" . $this->SessionComponent->read('login_user') . "'," . "\r\n";
        $strSql .= " '00'," . "\r\n";
        $strSql .= "   ''" . "\r\n";
        $strSql .= "  )" . "\r\n";
        return $strSql;
    }

    public function fncIdCheck_sql($idC)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  t_akauntojoho.roguin_id " . "\r\n";
        $strSql .= "  FROM " . "\r\n";
        $strSql .= "  t_akauntojoho " . "\r\n";
        $strSql .= "  WHERE " . "\r\n";
        $strSql .= " t_akauntojoho.roguin_id = '" . $idC . "'" . "\r\n";
        return $strSql;
    }

    public function fncIssueInsert_sql($idC, $pwdC, $KokyakuId)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "INSERT " . "\r\n";
        $strSql .= "  INTO T_AKAUNTOJOHO VALUES" . "\r\n";
        $strSql .= "  (" . "\r\n";
        $strSql .= " '" . $idC . "'," . "\r\n";
        $strSql .= " '" . $pwdC . "'," . "\r\n";
        $strSql .= "  TO_CHAR(SYSDATE,'YYYYMMDD')," . "\r\n";
        $strSql .= "  (SELECT" . "\r\n";
        $strSql .= "  CASE" . "\r\n";
        $strSql .= "  WHEN KYOTN_CD ='261'" . "\r\n";
        $strSql .= "  THEN '261'" . "\r\n";
        $strSql .= "  ELSE SUBSTR(KYOTN_CD,1,2)" . "\r\n";
        $strSql .= "   ||'0'" . "\r\n";
        $strSql .= "  END" . "\r\n";
        $strSql .= " FROM M29MA4" . "\r\n";
        $strSql .= "  WHERE M29MA4.SYAIN_NO = '" . $this->SessionComponent->read('login_user') . "'" . "\r\n";
        $strSql .= " )," . "\r\n";
        $strSql .= " '" . $KokyakuId . "'," . "\r\n";
        $strSql .= "  sysdate," . "\r\n";
        $strSql .= " '" . $this->SessionComponent->read('login_user') . "'," . "\r\n";
        $strSql .= "  sysdate," . "\r\n";
        $strSql .= " '" . $this->SessionComponent->read('login_user') . "'," . "\r\n";
        $strSql .= " '00'," . "\r\n";
        $strSql .= "   ''" . "\r\n";
        $strSql .= "  )" . "\r\n";
        return $strSql;
    }

    public function fncRembanUpdata_sql()
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "UPDATE T_YM_SAIBAN" . " \r\n";
        $strSql .= "SET" . " \r\n";
        $strSql .= "REMBAN = REMBAN + 1," . " \r\n";
        $strSql .= "UPD_DATE = SYSDATE," . " \r\n";
        $strSql .= "UPD_USER_ID = '" . $this->SessionComponent->read('login_user') . "'" . " \r\n";
        $strSql .= "WHERE TEBURUMEI = 't_apuri_riyo_kokyaku'" . " \r\n";
        $strSql .= "AND SAIBAN_YM = TO_CHAR(SYSDATE,'YYYYMM')" . " \r\n";
        return $strSql;
    }

}