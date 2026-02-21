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

namespace App\Model\PPRM;

use App\Model\Component\ClsComDb;

class PPRM203DCMonyKindInput extends ClsComDb
{

    //'**********************************************************************
    //'処 理 名：日締№取得
    //'関 数 名：getRenban
    //'引 数 1 ：$strTCD(店舗コード)
    //'引 数 2 ：$strHNO(日締№)
    //'戻 り 値：ＳＱＬ
    //'処理説明：getHJMNO
    //'**********************************************************************
    public function getRenban($strTCD, $strHNO)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  MAX(TEN_HJM_NO) AS TEN_HJM_NO " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  PPRHJMMONEYKINDHED " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  TENPO_CD = '" . $strTCD . "'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  TEN_HJM_NO LIKE '" . $strHNO . "%'";

        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：日締№取得
    //'関 数 名：getHJMNO
    //'引 数 1 ：$strTCD(店舗コード)
    //'引 数 2 ：$strHNO(日締№)
    //'戻 り 値：ＳＱＬ
    //'処理説明：getHJMNO
    //'**********************************************************************
    public function getHJMNO($strTCD, $strHNO)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  MAX(TEN_HJM_NO) AS TEN_HJM_NO " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  PPRHJMMONEYKINDHED " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  TENPO_CD = '" . $strTCD . "'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  TEN_HJM_NO LIKE '" . $strHNO . "%'";

        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：経理承認確認
    //'関 数 名：managerConfirm
    //'引 数 1 ：$txtTenpoCD(店舗コード)
    //'引 数 2 ：$txtHJMNo(日締№)
    //'戻 り 値：ＳＱＬ
    //'処理説明：経理承認確認
    //'**********************************************************************
    public function managerConfirm($txtTenpoCD, $txtHJMNo)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  KEIRI_SNN_FLG " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  PPRHJMAPPROVEDATA " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  TENPO_CD = '" . $txtTenpoCD . "'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  HJM_KIND = '1'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  TEN_HJM_NO = '" . $txtHJMNo . "'";

        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：金種別残高データ取得
    //'関 数 名：setKinsyuData
    //'引 数 1：$txtTenpoCD(店舗コード)
    //'引 数 2：$txtHJMNo(日締№)
    //'戻 り 値：ＳＱＬ
    //'処理説明：金種別残高データ取得
    //'**********************************************************************
    public function setKinsyuData($txtTenpoCD, $txtHJMNo)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  MNY_KIND, " . "\r\n";
        $strSql .= "  MEISAI_NO, " . "\r\n";
        $strSql .= "  KINSYU, " . "\r\n";
        $strSql .= "  MAISU, " . "\r\n";
        $strSql .= "  ZANDAKA " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  PPRHJMMONEYKINDDETAIL " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  TENPO_CD = '" . $txtTenpoCD . "'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  TEN_HJM_NO = '" . $txtHJMNo . "'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  EGK_KMY_KBN = '0'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  MNY_KIND IN(0,1) ";

        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：小切手データ取得
    //'関 数 名：setKogiteData
    //'引 数 1：$txtTenpoCD(店舗コード)
    //'引 数 2：$txtHJMNo(日締№)
    //'戻 り 値：ＳＱＬ
    //'処理説明：小切手データ取得
    //'**********************************************************************
    public function setKogiteData($txtTenpoCD, $txtHJMNo)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  KINSYU, " . "\r\n";
        $strSql .= "  ZANDAKA " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  PPRHJMMONEYKINDDETAIL " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  TENPO_CD = '" . $txtTenpoCD . "'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  TEN_HJM_NO = '" . $txtHJMNo . "'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  EGK_KMY_KBN = '0'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  MNY_KIND = '2'";

        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：帳簿上の残高取得
    //'関 数 名：getTyouboZandaka
    //'引 数 1：$txtTenpoCD(店舗コード)
    //'引 数 2：$txtHJMNo(日締№)
    //'戻 り 値：ＳＱＬ
    //'処理説明：帳簿上の残高取得
    //'**********************************************************************
    public function getTyouboZandaka($txtTenpoCD, $txtHJMNo)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  NVL(KON_HJM_EGK_KKS_GK,0) AS KON_HJM_EGK_KKS_GK " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  M41F11 " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  TENPO_CD = '" . $txtTenpoCD . "'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  TEN_HJM_NO = '" . $txtHJMNo . "'";

        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：実際の残高取得
    //'関 数 名：getJissaiZandaka
    //'引 数 1：$txtTenpoCD(店舗コード)
    //'引 数 2：$txtHJMNo(日締№)
    //'戻 り 値：ＳＱＬ
    //'処理説明：実際の残高取得
    //'**********************************************************************
    public function getJissaiZandaka($txtTenpoCD, $txtHJMNo)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  NVL(ZAN_GK,0) AS ZAN_GK, " . "\r\n";
        //20180410 YIN UPD S
        // $strSql .= "  UPD_DATE, " . "\r\n";
        $strSql .= "  TO_CHAR(UPD_DATE,'YYYYMMDD HH24MISS') UPD_DATE, " . "\r\n";
        //20180410 YIN UPD E
        $strSql .= "  FUICHI_RIYU " . "\r\n";
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  PPRHJMMONEYKINDHED " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  TENPO_CD = '" . $txtTenpoCD . "'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  TEN_HJM_NO = '" . $txtHJMNo . "'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  EGK_KMY_KBN = '0'";

        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：日締№検索（関数）
    //'関 数 名：FncUpdDate
    //'引 数 1：$strTCD(店舗コード)
    //'引 数 2：$strHJMNo(日締№)
    //'戻 り 値：ＳＱＬ
    //'処理説明：日締№の有無をチェック
    //'**********************************************************************
    public function FncUpdDate($strTCD, $strHJMNo)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  TEN_HJM_NO, " . "\r\n";
        //20180410 YIN UPD S
        // $strSql .= "  UPD_DATE " . "\r\n";
        $strSql .= "  TO_CHAR(UPD_DATE,'YYYYMMDD HH24MISS') UPD_DATE " . "\r\n";
        //20180410 YIN UPD E
        $strSql .= "FROM " . "\r\n";
        $strSql .= "  PPRHJMMONEYKINDHED " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  TENPO_CD = '" . $strTCD . "'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  TEN_HJM_NO LIKE '" . $strHJMNo . "'";

        return parent::select($strSql);
    }

    //'**********************************************************************
    //'処 理 名：店舗日締金種明細データ削除
    //'関 数 名：DeleteMeisai
    //'引 数 1：$txtTenpoCD(店舗コード)
    //'引 数 2：$txtHJMNo(日締№)
    //'戻 り 値：ＳＱＬ
    //'処理説明：店舗日締金種明細データ削除
    //'**********************************************************************
    public function DeleteMeisai($txtTenpoCD, $txtHJMNo)
    {
        $strSql = "";
        $strSql .= "DELETE PPRHJMMONEYKINDDETAIL " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  TENPO_CD = '" . $txtTenpoCD . "'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  TEN_HJM_NO = '" . $txtHJMNo . "'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  EGK_KMY_KBN = '0'";

        return parent::delete($strSql);
    }

    //'**********************************************************************
    //'処 理 名：店舗日締金種ヘッダーデータ削除
    //'関 数 名：DeleteHeader
    //'引 数 1：$txtTenpoCD(店舗コード)
    //'引 数 2：$txtHJMNo(日締№)
    //'戻 り 値：ＳＱＬ
    //'処理説明：店舗日締金種ヘッダーデータ削除
    //'**********************************************************************
    public function DeleteHeader($txtTenpoCD, $txtHJMNo)
    {
        $strSql = "";
        $strSql .= "DELETE PPRHJMMONEYKINDHED " . "\r\n";
        $strSql .= "WHERE " . "\r\n";
        $strSql .= "  TENPO_CD = '" . $txtTenpoCD . "'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  TEN_HJM_NO = '" . $txtHJMNo . "'" . "\r\n";
        $strSql .= "AND " . "\r\n";
        $strSql .= "  EGK_KMY_KBN = '0'";

        return parent::delete($strSql);
    }

    //'**********************************************************************
    //'処 理 名：店舗日締金種明細データ登録
    //'関 数 名：InsertMeisai
    //'引 数 1：$txtTenpoCD(店舗コード)
    //'引 数 2：$txtHJMNo(日締№)
    //'引 数 3：$strKind(紙幣0/硬貨1/小切手2)
    //'引 数 4：$lngMNO(1-6行)
    //'引 数 5：$strKinsyu(金種)
    //'引 数 6：$lngMaisu(枚数)
    //'引 数 7：$lngZandaka(残高)
    //'引 数 7：$sessionData
    //'戻 り 値：ＳＱＬ
    //'処理説明：金種別残高用
    //'**********************************************************************
    public function InsertMeisai($txtTenpoCD, $txtHJMNo, $strKind, $lngMNO, $strKinsyu, $lngMaisu, $lngZandaka, $sessionData)
    {
        $strSql = "";
        $strSql .= "INSERT INTO PPRHJMMONEYKINDDETAIL" . "\r\n";
        $strSql .= "           (TENPO_CD" . "\r\n";
        $strSql .= ",           TEN_HJM_NO" . "\r\n";
        $strSql .= ",           EGK_KMY_KBN" . "\r\n";
        $strSql .= ",           MNY_KIND" . "\r\n";
        $strSql .= ",           MEISAI_NO" . "\r\n";
        $strSql .= ",           KINSYU" . "\r\n";
        $strSql .= ",           MAISU" . "\r\n";
        $strSql .= ",           ZANDAKA" . "\r\n";
        $strSql .= ",           CRE_BUSYO_CD" . "\r\n";
        $strSql .= ",           CRE_SYA_CD" . "\r\n";
        $strSql .= ",           CRE_CLT_NM" . "\r\n";
        $strSql .= ",           CRE_DATE" . "\r\n";
        $strSql .= ",           CRE_PRG_ID" . "\r\n";
        $strSql .= ",           UPD_BUSYO_CD" . "\r\n";
        $strSql .= ",           UPD_SYA_CD" . "\r\n";
        $strSql .= ",           UPD_CLT_NM" . "\r\n";
        $strSql .= ",           UPD_DATE" . "\r\n";
        $strSql .= ",           UPD_PRG_ID)" . "\r\n";
        $strSql .= " VALUES     " . "\r\n";
        $strSql .= "(           '@TENPO_CD'" . "\r\n";
        $strSql .= ",           '@TEN_HJM_NO'" . "\r\n";
        $strSql .= ",           '0'" . "\r\n";
        $strSql .= ",           '@MNY_KIND'" . "\r\n";
        $strSql .= ",           '@MEISAI_NO'" . "\r\n";
        $strSql .= ",           '@KINSYU'" . "\r\n";
        $strSql .= ",           '@MAISU'" . "\r\n";
        $strSql .= ",           '@ZANDAKA'" . "\r\n";
        $strSql .= ",           '@CRE_BUSYO_CD'" . "\r\n";
        $strSql .= ",           '@CRE_SYA_CD'" . "\r\n";
        $strSql .= ",           '@CRE_CLT_NM'" . "\r\n";
        $strSql .= ",           @CRE_DATE" . "\r\n";
        $strSql .= ",           '@CRE_PRG_ID'" . "\r\n";
        $strSql .= ",           '@UPD_BUSYO_CD'" . "\r\n";
        $strSql .= ",           '@UPD_SYA_CD'" . "\r\n";
        $strSql .= ",           '@UPD_CLT_NM'" . "\r\n";
        $strSql .= ",           @UPD_DATE" . "\r\n";
        $strSql .= ",           '@UPD_PRG_ID'" . "\r\n";
        $strSql .= ")" . "\r\n";

        $strSql = str_replace("@TENPO_CD", $txtTenpoCD, $strSql);
        $strSql = str_replace("@TEN_HJM_NO", $txtHJMNo, $strSql);
        $strSql = str_replace("@MNY_KIND", $strKind, $strSql);
        $strSql = str_replace("@MEISAI_NO", $lngMNO, $strSql);
        $strSql = str_replace("@KINSYU", $strKinsyu, $strSql);
        $strSql = str_replace("@MAISU", $lngMaisu, $strSql);
        $strSql = str_replace("@ZANDAKA", $lngZandaka, $strSql);
        $strSql = str_replace("@CRE_BUSYO_CD", $sessionData["BusyoCD"], $strSql);
        $strSql = str_replace("@CRE_SYA_CD", $sessionData["UserId"], $strSql);
        $strSql = str_replace("@CRE_CLT_NM", $sessionData["MachineNM"], $strSql);
        $strSql = str_replace("@CRE_DATE", "sysdate", $strSql);
        $strSql = str_replace("@CRE_PRG_ID", "DC_MonyKindInput", $strSql);
        $strSql = str_replace("@UPD_BUSYO_CD", $sessionData["BusyoCD"], $strSql);
        $strSql = str_replace("@UPD_SYA_CD", $sessionData["UserId"], $strSql);
        $strSql = str_replace("@UPD_CLT_NM", $sessionData["MachineNM"], $strSql);
        $strSql = str_replace("@UPD_DATE", "sysdate", $strSql);
        $strSql = str_replace("@UPD_PRG_ID", "DC_MonyKindInput", $strSql);

        return parent::insert($strSql);
    }

    //'**********************************************************************
    //'処 理 名：店舗日締金種明細データ登録
    //'関 数 名：InsertKinsyu
    //'引 数 1：$txtTenpoCD(店舗コード)
    //'引 数 2：$txtHJMNo(日締№)
    //'引 数 3：$strKinsyu(小切手№)
    //'引 数 4：$lngZandaka(金額)
    //'引 数 5：$lngMNO
    //'引 数 6：$sessionData
    //'戻 り 値：ＳＱＬ
    //'処理説明：小切手用
    //'**********************************************************************
    public function InsertKinsyu($txtTenpoCD, $txtHJMNo, $strKinsyu, $lngZandaka, $lngMNO, $sessionData)
    {
        $strSql = "";
        $strSql .= "INSERT INTO PPRHJMMONEYKINDDETAIL" . "\r\n";
        $strSql .= "           (TENPO_CD" . "\r\n";
        $strSql .= ",           TEN_HJM_NO" . "\r\n";
        $strSql .= ",           EGK_KMY_KBN" . "\r\n";
        $strSql .= ",           MNY_KIND" . "\r\n";
        $strSql .= ",           MEISAI_NO" . "\r\n";
        $strSql .= ",           KINSYU" . "\r\n";
        $strSql .= ",           MAISU" . "\r\n";
        $strSql .= ",           ZANDAKA" . "\r\n";
        $strSql .= ",           CRE_BUSYO_CD" . "\r\n";
        $strSql .= ",           CRE_SYA_CD" . "\r\n";
        $strSql .= ",           CRE_CLT_NM" . "\r\n";
        $strSql .= ",           CRE_DATE" . "\r\n";
        $strSql .= ",           CRE_PRG_ID" . "\r\n";
        $strSql .= ",           UPD_BUSYO_CD" . "\r\n";
        $strSql .= ",           UPD_SYA_CD" . "\r\n";
        $strSql .= ",           UPD_CLT_NM" . "\r\n";
        $strSql .= ",           UPD_DATE" . "\r\n";
        $strSql .= ",           UPD_PRG_ID)" . "\r\n";
        $strSql .= " VALUES     " . "\r\n";
        $strSql .= "(           '@TENPO_CD'" . "\r\n";
        $strSql .= ",           '@TEN_HJM_NO'" . "\r\n";
        $strSql .= ",           '0'" . "\r\n";
        $strSql .= ",           '2'" . "\r\n";
        $strSql .= ",           '@MEISAI_NO'" . "\r\n";
        $strSql .= ",           '@KINSYU'" . "\r\n";
        $strSql .= ",           NULL" . "\r\n";
        $strSql .= ",           '@ZANDAKA'" . "\r\n";
        $strSql .= ",           '@CRE_BUSYO_CD'" . "\r\n";
        $strSql .= ",           '@CRE_SYA_CD'" . "\r\n";
        $strSql .= ",           '@CRE_CLT_NM'" . "\r\n";
        $strSql .= ",           @CRE_DATE" . "\r\n";
        $strSql .= ",           '@CRE_PRG_ID'" . "\r\n";
        $strSql .= ",           '@UPD_BUSYO_CD'" . "\r\n";
        $strSql .= ",           '@UPD_SYA_CD'" . "\r\n";
        $strSql .= ",           '@UPD_CLT_NM'" . "\r\n";
        $strSql .= ",           @UPD_DATE" . "\r\n";
        $strSql .= ",           '@UPD_PRG_ID'" . "\r\n";
        $strSql .= ")" . "\r\n";

        $strSql = str_replace("@TENPO_CD", $txtTenpoCD, $strSql);
        $strSql = str_replace("@TEN_HJM_NO", $txtHJMNo, $strSql);
        $strSql = str_replace("@MEISAI_NO", $lngMNO, $strSql);
        $strSql = str_replace("@KINSYU", $strKinsyu, $strSql);
        $strSql = str_replace("@ZANDAKA", $lngZandaka, $strSql);
        $strSql = str_replace("@CRE_BUSYO_CD", $sessionData["BusyoCD"], $strSql);
        $strSql = str_replace("@CRE_SYA_CD", $sessionData["UserId"], $strSql);
        $strSql = str_replace("@CRE_CLT_NM", $sessionData["MachineNM"], $strSql);
        $strSql = str_replace("@CRE_DATE", "sysdate", $strSql);
        $strSql = str_replace("@CRE_PRG_ID", "DC_MonyKindInput", $strSql);
        $strSql = str_replace("@UPD_BUSYO_CD", $sessionData["BusyoCD"], $strSql);
        $strSql = str_replace("@UPD_SYA_CD", $sessionData["UserId"], $strSql);
        $strSql = str_replace("@UPD_CLT_NM", $sessionData["MachineNM"], $strSql);
        $strSql = str_replace("@UPD_DATE", "sysdate", $strSql);
        $strSql = str_replace("@UPD_PRG_ID", "DC_MonyKindInput", $strSql);

        return parent::insert($strSql);
    }

    //'**********************************************************************
    //'処 理 名：店舗日締金種ヘッダーデータ登録
    //'関 数 名：InsertHeader
    //'引 数 1：$txtTenpoCD(店舗コード)
    //'引 数 2：$txtHJMNo(日締№)
    //'引 数 3：$lblShiheiGoukei(小計①)
    //'引 数 4：$lblKoukaGoukei(小計②)
    //'引 数 5：$lblKogiteGoukei(小計③)
    //'引 数 6：$genkinzangk(小計①+小計②)
    //'引 数 7：$lblJissaiGoukei(実際の残高)
    //'引 数 8：$txtRiyu(帳簿上の残高と実際の残高の不一致の理由)
    //'引 数 9：$sessionData
    //'戻 り 値：ＳＱＬ
    //'処理説明：店舗日締金種ヘッダーデータ登録
    //'**********************************************************************
    public function InsertHeader($txtTenpoCD, $txtHJMNo, $lblShiheiGoukei, $lblKoukaGoukei, $lblKogiteGoukei, $genkinzangk, $lblJissaiGoukei, $txtRiyu, $sessionData)
    {
        $strSql = "";
        $strSql .= "INSERT INTO PPRHJMMONEYKINDHED" . "\r\n";
        $strSql .= "           (TENPO_CD" . "\r\n";
        $strSql .= ",           TEN_HJM_NO" . "\r\n";
        $strSql .= ",           EGK_KMY_KBN" . "\r\n";
        $strSql .= ",           KINSYU_ZAN_SHIHEI" . "\r\n";
        $strSql .= ",           KINSYU_ZAN_KOUKA" . "\r\n";
        $strSql .= ",           KGT_ZAN_GK" . "\r\n";
        $strSql .= ",           INSHI_ZAN_GK" . "\r\n";
        $strSql .= ",           GENKIN_ZAN_GK" . "\r\n";
        $strSql .= ",           ZAN_GK" . "\r\n";
        $strSql .= ",           CRE_BUSYO_CD" . "\r\n";
        $strSql .= ",           CRE_SYA_CD" . "\r\n";
        $strSql .= ",           CRE_CLT_NM" . "\r\n";
        $strSql .= ",           CRE_DATE" . "\r\n";
        $strSql .= ",           CRE_PRG_ID" . "\r\n";
        $strSql .= ",           UPD_BUSYO_CD" . "\r\n";
        $strSql .= ",           UPD_SYA_CD" . "\r\n";
        $strSql .= ",           UPD_CLT_NM" . "\r\n";
        $strSql .= ",           UPD_DATE" . "\r\n";
        $strSql .= ",           UPD_PRG_ID" . "\r\n";
        $strSql .= ",           FUICHI_RIYU)" . "\r\n";
        $strSql .= " VALUES     " . "\r\n";
        $strSql .= "(           '@TENPO_CD'" . "\r\n";
        $strSql .= ",           '@TEN_HJM_NO'" . "\r\n";
        $strSql .= ",           '0'" . "\r\n";
        $strSql .= ",           @KINSYU_ZAN_SHIHEI" . "\r\n";
        $strSql .= ",           @KINSYU_ZAN_KOUKA" . "\r\n";
        $strSql .= ",           @KGT_ZAN_GK" . "\r\n";
        $strSql .= ",           0" . "\r\n";
        $strSql .= ",           @GENKIN_ZAN_GK" . "\r\n";
        $strSql .= ",           @ZAN_GK" . "\r\n";
        $strSql .= ",           '@CRE_BUSYO_CD'" . "\r\n";
        $strSql .= ",           '@CRE_SYA_CD'" . "\r\n";
        $strSql .= ",           '@CRE_CLT_NM'" . "\r\n";
        $strSql .= ",           sysdate" . "\r\n";
        $strSql .= ",           '@CRE_PRG_ID'" . "\r\n";
        $strSql .= ",           '@UPD_BUSYO_CD'" . "\r\n";
        $strSql .= ",           '@UPD_SYA_CD'" . "\r\n";
        $strSql .= ",           '@UPD_CLT_NM'" . "\r\n";
        $strSql .= ",           sysdate" . "\r\n";
        $strSql .= ",           '@UPD_PRG_ID'" . "\r\n";
        $strSql .= ",           '@FUICHI_RIYU'" . "\r\n";
        $strSql .= ")" . "\r\n";

        $strSql = str_replace("@TENPO_CD", $txtTenpoCD, $strSql);
        $strSql = str_replace("@TEN_HJM_NO", $txtHJMNo, $strSql);
        $strSql = str_replace("@KINSYU_ZAN_SHIHEI", $lblShiheiGoukei, $strSql);
        $strSql = str_replace("@KINSYU_ZAN_KOUKA", $lblKoukaGoukei, $strSql);
        $strSql = str_replace("@KGT_ZAN_GK", $lblKogiteGoukei, $strSql);
        $strSql = str_replace("@GENKIN_ZAN_GK", $genkinzangk, $strSql);
        $strSql = str_replace("@ZAN_GK", $lblJissaiGoukei, $strSql);
        $strSql = str_replace("@CRE_BUSYO_CD", $sessionData["BusyoCD"], $strSql);
        $strSql = str_replace("@CRE_SYA_CD", $sessionData["UserId"], $strSql);
        $strSql = str_replace("@CRE_CLT_NM", $sessionData["MachineNM"], $strSql);
        $strSql = str_replace("@CRE_PRG_ID", "DC_MonyKindInput", $strSql);
        $strSql = str_replace("@UPD_BUSYO_CD", $sessionData["BusyoCD"], $strSql);
        $strSql = str_replace("@UPD_SYA_CD", $sessionData["UserId"], $strSql);
        $strSql = str_replace("@UPD_CLT_NM", $sessionData["MachineNM"], $strSql);
        $strSql = str_replace("@UPD_PRG_ID", "DC_MonyKindInput", $strSql);
        $strSql = str_replace("@FUICHI_RIYU", $txtRiyu, $strSql);

        return parent::insert($strSql);
    }

}