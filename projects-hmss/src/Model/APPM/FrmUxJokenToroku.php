<?php
/**
 * 説明：
 *
 *
 * @author WANGYING,LIQIUSHUANG
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

class FrmUxJokenToroku extends ClsComDb
{
    public $SessionComponent;
    public function FncAutoComplete($msgId, $postData)
    {
        $strSQL = $this->FncAutoComplete_sql($msgId, $postData);
        return parent::select($strSQL);
    }

    public function fncGetTCodeData($flg, $postData)
    {
        $strSQL = $this->fncGetTCodeData_sql($flg, $postData);
        return parent::select($strSQL);
    }

    public function fncGetTHBUSYOData()
    {
        $strSQL = $this->fncGetTHBUSYOData_sql();
        return parent::select($strSQL);
    }

    public function fncGetInformation($postData)
    {
        $strSQL = $this->fncGetInformation_sql($postData);
        return parent::select($strSQL);
    }

    public function fncCheckId($postData)
    {
        $strSQL = $this->fncCheckId_sql($postData);
        return parent::select($strSQL);
    }

    public function fncGetObjectNumber($postData)
    {
        $strSQL = $this->fncGetObjectNumber_sql($postData);
        return parent::select($strSQL);
    }

    public function fncGetSaiBan()
    {
        $strSQL = $this->fncGetSaiBan_sql();
        return parent::select($strSQL);
    }
    public function fncGetSaiBan1()
    {
        $strSQL = $this->fncGetSaiBan_sql1();
        return parent::select($strSQL);
    }

    public function fncInsData($postData)
    {
        $strSql = $this->fncInsData_sql($postData);
        return parent::insert($strSql);
    }

    public function fncInsSaiban($remban)
    {
        $strSql = $this->fncInsSaiban_sql($remban);
        return parent::insert($strSql);
    }

    public function fncUpdSaiban()
    {
        $strSql = $this->fncUpdSaiban_sql();
        return parent::update($strSql);
    }

    public function fncWkUXDelet($uxId)
    {
        $strSQL = $this->fncWkUXDelete_sql($uxId);
        return parent::delete($strSQL);
    }

    public function fncInsUXData($postData, $objData)
    {
        $strSql = $this->fncInsUXData_sql($postData, $objData);
        return parent::insert($strSql);

    }

    public function fncHaitaLogin($postData)
    {
        $strSQL = $this->fncHaitaLogin_sql($postData);
        return parent::select($strSQL);
    }

    public function fncUpdData($postData)
    {
        $strSql = $this->fncUpdData_sql($postData);
        return parent::update($strSql);
    }

    public function fncDelData($postData)
    {
        $strSql = $this->fncDelData_sql($postData);
        return parent::update($strSql);
    }

    //'**********************************************************************
    //'処 理 名：メッセージのオートコンプリート
    //'関 数 名：FncAutoComplete_sql
    //'引 数 1 ：なし
    //'戻 り 値：ＳＱＬ
    //'処理説明：メッセージのオートコンプリート
    //'**********************************************************************
    public function FncAutoComplete_sql($msgId, $postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT MESSEJI_ID" . " \r\n";
        $strSQL .= ", TAITORU" . " \r\n";
        $strSQL .= "FROM T_MESSEJI" . " \r\n";
        $strSQL .= "WHERE 1=1 " . " \r\n";
        if ($postData != "") {
            $strSQL .= " AND MESSEJI_RIYO_KIKAN_FROM <= '" . $postData['dateFrom'] . "'" . " \r\n";
            $strSQL .= " AND MESSEJI_RIYO_KIKAN_TO >= '" . $postData['dateFrom'] . "'" . " \r\n";
            $strSQL .= " AND MESSEJI_RIYO_KIKAN_FROM <= '" . $postData['dateTo'] . "'" . " \r\n";
            $strSQL .= " AND MESSEJI_RIYO_KIKAN_TO >= '" . $postData['dateTo'] . "'" . " \r\n";
        }
        $strSQL .= " AND NAIYO_KBN = '03'";
        $strSQL .= " AND UPD_STS_KBN != '09'";
        $strSQL .= " AND DEL_FLG = '00'";
        if ($msgId <> "") {
            $strSQL .= " AND MESSEJI_ID = '" . $msgId . "'";
        }

        return $strSQL;
    }

    //'***********************************************************************
    //'処 理 名：入力欄取得
    //'関 数 名：fncGetTCodeData_sql
    //'引  数  ：$flg
    //'戻 り 値：ＳＱＬ
    //'処理説明：入力欄取得
    //'***********************************************************************
    public function fncGetTCodeData_sql($flg, $postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT NAIBU_CD_MEISHO" . " \r\n";
        $strSQL .= ", NAIBU_CD" . " \r\n";
        $strSQL .= "FROM M_CODE" . " \r\n";
        $strSQL .= "WHERE" . " \r\n";
        //・性別
        if ($flg == "1") {
            $strSQL .= " GAIBU_CD = '009'";
        }
        //・カテゴリ
        if ($flg == "2") {
            $strSQL .= " GAIBU_CD = '010'";
        }
        //・年代
        if ($flg == "3") {
            $strSQL .= " GAIBU_CD = '006'";
        }
        //・メーカー名
        if ($flg == "4") {
            $strSQL .= " GAIBU_CD = '007'";
        }
        //・固定化区分
        if ($flg == "5") {
            $strSQL .= " GAIBU_CD = '011'";
        }
        //・パックdeメンテ現在加入 ・（DZM）延長保証現在加入 ・ボディーコーティング現在加入
        if ($flg == "6") {
            $strSQL .= " GAIBU_CD = '002'";
        }
        //・点検ステータス ・車検ステータス
        if ($flg == "7") {
            $strSQL .= " GAIBU_CD = '008'";
        }
        //・車点検ＤＭ発信結果タイプ名称
        if ($flg == "8") {
            $strSQL .= " GAIBU_CD = '013'";
        }
        //・点検
        if ($flg == "9") {
            $strSQL .= " GAIBU_CD = '012'";
        }
        //・車検
        if ($flg == "10") {
            $strSQL .= " GAIBU_CD = '014'";
        }

        $strSQL .= " AND YUKO_KAISHI_YMD <= '" . $postData['dateFrom'] . "'" . " \r\n";
        $strSQL .= " AND YUKO_SHURYO_YMD >= '" . $postData['dateFrom'] . "'" . " \r\n";
        $strSQL .= " AND YUKO_KAISHI_YMD <= '" . $postData['dateTo'] . "'" . " \r\n";
        $strSQL .= " AND YUKO_SHURYO_YMD >= '" . $postData['dateTo'] . "'" . " \r\n";

        return $strSQL;
    }

    //'***********************************************************************
    //'処 理 名：・管理拠点 ・サービス拠点
    //'関 数 名：fncGetTHBUSYOData_sql
    //'引  数  ：なし
    //'戻 り 値：ＳＱＬ
    //'処理説明：・管理拠点 ・サービス拠点
    //'***********************************************************************
    public function fncGetTHBUSYOData_sql()
    {
        $strSQL = "";
        $strSQL .= "SELECT BUSYO_CD,BUSYO_RYKNM" . " \r\n";
        $strSQL .= "FROM HBUSYO" . " \r\n";
        $strSQL .= "WHERE PRN_KB4 = 'O'" . " \r\n";
        $strSQL .= " AND DSP_SEQNO IS NOT NULL" . " \r\n";
        $strSQL .= " AND BUSYO_KB IN('S','C')";
        $strSQL .= "ORDER BY";
        $strSQL .= " DSP_SEQNO";

        return $strSQL;
    }

    //'***********************************************************************
    //'処 理 名：DB検索処理を実行する(画面初期化)
    //'関 数 名：fncGetInformation_sql
    //'引 数   ：$postData
    //'戻 り 値：ＳＱＬ
    //'処理説明：DB検索処理を実行する
    //'***********************************************************************
    public function fncGetInformation_sql($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT *" . " \r\n";
        $strSQL .= "FROM T_UX_JOKEN" . " \r\n";
        $strSQL .= "WHERE UX_JOKEN_ID = '@UX_JOKEN_ID'" . " \r\n";

        $strSQL = str_replace("@UX_JOKEN_ID", $postData['id'], $strSQL);

        return $strSQL;
    }

    //'***********************************************************************
    //'処 理 名：チェックMESSEJI_ID
    //'関 数 名：fncCheckId_sql
    //'引 数   ：$postData
    //'戻 り 値：ＳＱＬ
    //'処理説明：チェックMESSEJI_ID
    //'***********************************************************************
    public function fncCheckId_sql($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT MESSEJI_ID" . " \r\n";
        $strSQL .= "FROM T_MESSEJI" . " \r\n";
        $strSQL .= "WHERE MESSEJI_ID = '@MESSEJI_ID'" . " \r\n";
        $strSQL .= " AND MESSEJI_RIYO_KIKAN_FROM <= '" . $postData['dateFrom'] . "'" . " \r\n";
        $strSQL .= " AND MESSEJI_RIYO_KIKAN_TO >= '" . $postData['dateFrom'] . "'" . " \r\n";
        $strSQL .= " AND MESSEJI_RIYO_KIKAN_FROM <= '" . $postData['dateTo'] . "'" . " \r\n";
        $strSQL .= " AND MESSEJI_RIYO_KIKAN_TO >= '" . $postData['dateTo'] . "'" . " \r\n";
        $strSQL .= " AND NAIYO_KBN = '03'";
        $strSQL .= " AND UPD_STS_KBN != '09'";
        $strSQL .= " AND DEL_FLG = '00'";

        $strSQL = str_replace("@MESSEJI_ID", str_replace("'", "''", $postData['id']), $strSQL);

        return $strSQL;
    }

    //'***********************************************************************
    //'処 理 名：対象件数取得検索
    //'関 数 名：fncGetObjectNumber_sql
    //'引 数   ：$postData
    //'戻 り 値：ＳＱＬ
    //'処理説明：対象件数取得検索
    //'***********************************************************************
    public function fncGetObjectNumber_sql($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT M41C04.DLRCSRNO" . " \r\n";  //顧客番号
        $strSQL .= "  ,M41C04.VIN_WMIVDS" . " \r\n";  //車台型式
        $strSQL .= "  ,M41C04.VIN_VIS" . " \r\n";  //カーNO
        $strSQL .= "  ,CRM_LIST.SYOHIN01_EVT_YM" . " \r\n";  //点検年月
        $strSQL .= "  ,M41C03.VCLIPEDT" . " \r\n";  //車検満了日
        $strSQL .= "FROM t_apuri_riyo_kokyaku, M41C01, M41C03, M41C04,CRM_LIST," . " \r\n";
        $strSQL .= "    (SELECT" . " \r\n";
        $strSQL .= "       '○' as PDEM," . " \r\n";
        $strSQL .= "        M41C67.SOH_NM," . " \r\n";
        $strSQL .= "        BTH41C10.DLRCSRNO," . " \r\n";
        $strSQL .= "        BTH41C10.VIN_WMIVDS," . " \r\n";
        $strSQL .= "        BTH41C10.VIN_VIS," . " \r\n";
        $strSQL .= "        BTH41C10.SSC_STA_DT," . " \r\n";
        $strSQL .= "        BTH41C10.KYK_EXR_DT" . " \r\n";
        $strSQL .= "      FROM" . " \r\n";
        $strSQL .= "        M41C67,  BTH41C10" . " \r\n";
        $strSQL .= "      WHERE" . " \r\n";
        $strSQL .= "        M41C67.SOH_CD=BTH41C10.SOH_CD" . " \r\n";
        $strSQL .= "        AND BTH41C10.SSC_STA_DT<=TO_CHAR(SYSDATE,'YYYYMMDD')" . " \r\n";
        $strSQL .= "        AND BTH41C10.KYK_EXR_DT>=TO_CHAR(SYSDATE,'YYYYMMDD')" . " \r\n";
        $strSQL .= "        AND (M41C67.SOH_NM LIKE '%メンテ%') ) PDEM," . " \r\n";
        $strSQL .= "           (SELECT" . " \r\n";
        $strSQL .= "              '○' as ENCHO," . " \r\n";
        $strSQL .= "               M41C67.SOH_NM," . " \r\n";
        $strSQL .= "               BTH41C10.DLRCSRNO," . " \r\n";
        $strSQL .= "               BTH41C10.VIN_WMIVDS," . " \r\n";
        $strSQL .= "               BTH41C10.VIN_VIS," . " \r\n";
        $strSQL .= "               BTH41C10.SSC_STA_DT," . " \r\n";
        $strSQL .= "               BTH41C10.KYK_EXR_DT" . " \r\n";
        $strSQL .= "            FROM" . " \r\n";
        $strSQL .= "               M41C67,  BTH41C10" . " \r\n";
        $strSQL .= "            WHERE" . " \r\n";
        $strSQL .= "               M41C67.SOH_CD=BTH41C10.SOH_CD" . " \r\n";
        $strSQL .= "               AND BTH41C10.SSC_STA_DT<=TO_CHAR(SYSDATE,'YYYYMMDD')" . " \r\n";
        $strSQL .= "               AND BTH41C10.KYK_EXR_DT>=TO_CHAR(SYSDATE,'YYYYMMDD')" . " \r\n";
        $strSQL .= "               AND (M41C67.SOH_NM LIKE '%延長保証%') ) ENC," . " \r\n";
        $strSQL .= "                   (SELECT" . " \r\n";
        $strSQL .= "                         '○' as BODYCOAT," . " \r\n";
        $strSQL .= "                          M41C67.SOH_NM," . " \r\n";
        $strSQL .= "                          BTH41C10.DLRCSRNO," . " \r\n";
        $strSQL .= "                          BTH41C10.VIN_WMIVDS," . " \r\n";
        $strSQL .= "                          BTH41C10.VIN_VIS," . " \r\n";
        $strSQL .= "                          BTH41C10.SSC_STA_DT," . " \r\n";
        $strSQL .= "                          BTH41C10.KYK_EXR_DT" . " \r\n";
        $strSQL .= "                     FROM" . " \r\n";
        $strSQL .= "                          M41C67,  BTH41C10" . " \r\n";
        $strSQL .= "                     WHERE" . " \r\n";
        $strSQL .= "                          M41C67.SOH_CD=BTH41C10.SOH_CD" . " \r\n";
        $strSQL .= "                          AND BTH41C10.SSC_STA_DT<=TO_CHAR(SYSDATE,'YYYYMMDD')" . " \r\n";
        $strSQL .= "                          AND BTH41C10.KYK_EXR_DT>=TO_CHAR(SYSDATE,'YYYYMMDD')" . " \r\n";
        $strSQL .= "                          AND (M41C67.SOH_NM LIKE '%ボディ%') ) BODY" . " \r\n";
        $strSQL .= "WHERE" . " \r\n";
        $strSQL .= "   t_apuri_riyo_kokyaku.okyakusama_no = M41C01.DLRCSRNO" . " \r\n";
        $strSQL .= "   AND M41C01.DLRCSRNO = M41C04.DLRCSRNO" . " \r\n";
        $strSQL .= "   AND  M41C03.VIN_WMIVDS = M41C04.VIN_WMIVDS" . " \r\n";
        $strSQL .= "   AND  M41C03.VIN_VIS = M41C04.VIN_VIS" . " \r\n";
        $strSQL .= "   AND  CRM_LIST.C03_VIN_WMIVDS = M41C04.VIN_WMIVDS" . " \r\n";
        $strSQL .= "   AND  CRM_LIST.C03_VIN_VIS = M41C04.VIN_VIS" . " \r\n";
        $strSQL .= "   AND  rtrim(M41C01.MAS_DT)  IS NULL" . " \r\n";
        $strSQL .= "   AND  rtrim(M41C03.MAS_DT)  IS NULL" . " \r\n";
        $strSQL .= "   AND  rtrim(M41C04.MAS_DT)  IS NULL" . " \r\n";
        $strSQL .= "   AND  M41C04.DLRCSRNO = PDEM.DLRCSRNO(+)" . " \r\n";
        $strSQL .= "   AND  M41C04.VIN_WMIVDS = PDEM.VIN_WMIVDS(+)" . " \r\n";
        $strSQL .= "   AND  M41C04.VIN_VIS = PDEM.VIN_VIS(+)" . " \r\n";
        $strSQL .= "   AND  M41C04.DLRCSRNO = ENC.DLRCSRNO(+)" . " \r\n";
        $strSQL .= "   AND  M41C04.VIN_WMIVDS = ENC.VIN_WMIVDS(+)" . " \r\n";
        $strSQL .= "   AND  M41C04.VIN_VIS = ENC.VIN_VIS(+)" . " \r\n";
        $strSQL .= "   AND  M41C04.DLRCSRNO = BODY.DLRCSRNO(+)" . " \r\n";
        $strSQL .= "   AND  M41C04.VIN_WMIVDS = BODY.VIN_WMIVDS(+)" . " \r\n";
        $strSQL .= "   AND  M41C04.VIN_VIS = BODY.VIN_VIS(+)" . " \r\n";
        /*個人属性*/
        //性別
        if ($postData['gender'] <> "") {
            $strSQL .= "   AND  M41C01.CSRDOSID = '" . substr($postData['gender'], 1) . "'" . " \r\n";
        }
        //カテゴリ
        if ($postData['category'] <> "") {
            $strSQL .= "   AND  M41C01.CSRRANK = '" . $postData['category'] . "'" . " \r\n";
        }
        //年代
        if ($postData["eraFrom"] <> "" && $postData["eraTo"] == "") {
            $strSQL .= "   AND  TO_NUMBER( TO_CHAR(SYSDATE,'YYYY')) -  TO_NUMBER(SUBSTR(CASE M41C01.BRTDT  WHEN ' '  THEN '0000' ELSE M41C01.BRTDT END,1,4)) BETWEEN @eraFrom AND 89 " . " \r\n";
        }
        if ($postData["eraFrom"] == "" && $postData["eraTo"] <> "") {
            $strSQL .= "   AND  TO_NUMBER( TO_CHAR(SYSDATE,'YYYY')) -  TO_NUMBER(SUBSTR(CASE M41C01.BRTDT  WHEN ' '  THEN '0000' ELSE M41C01.BRTDT END,1,4)) BETWEEN 18 AND @eraTo " . " \r\n";
        }
        if ($postData["eraFrom"] <> "" && $postData["eraTo"] <> "") {
            $strSQL .= "   AND  TO_NUMBER( TO_CHAR(SYSDATE,'YYYY')) -  TO_NUMBER(SUBSTR(CASE M41C01.BRTDT  WHEN ' '  THEN '0000' ELSE M41C01.BRTDT END,1,4)) BETWEEN @eraFrom AND @eraTo " . " \r\n";
        }
        //誕生月
        if ($postData["birthday"] <> "") {
            $strSQL .= "   AND  TO_NUMBER(SUBSTR(CASE M41C01.BRTDT  WHEN ' '  THEN '0000' ELSE M41C01.BRTDT END,5,2)) = @birthday " . " \r\n";
        }
        /*車両属性*/
        //車種
        if ($postData["carName"] <> "") {
            $strSQL .= "   AND  M41C03.VCLNM LIKE '%@carName%' " . " \r\n";
        }
        //メーカー名
        if ($postData['manufacture'] <> "") {
            switch ($postData['manufacture']) {
                case '01':
                    $postData['manufacture'] = '10';
                    break;
                case '02':
                    $postData['manufacture'] = 'A9';
                    break;
                case '03':
                    $postData['manufacture'] = 'A1';
                    break;
            }
            $strSQL .= "   AND  M41C03.VCLBRDCD = '" . $postData['manufacture'] . "'" . " \r\n";
        }
        //固定化区分
        if ($postData['classification'] <> "") {
            $strSQL .= "   AND  M41C04.XG11KOTEIID = '" . $postData['classification'] . "'" . " \r\n";
        }
        //管理拠点
        if ($postData["management"] <> "") {
            $strSQL .= "   AND  SUBSTR(M41C04.KNR_STRCD,1,2) = SUBSTR('@management',0,2) " . " \r\n";
        }
        //サービス拠点
        if ($postData["serviceManagement"] <> "") {
            $strSQL .= "   AND  SUBSTR(M41C04.SRV_SRVSTRCD,1,2) = SUBSTR('@serviceManagement',0,2) " . " \r\n";
        }
        //初度登録
        if ($postData["loginYear"] <> "") {
            $strSQL .= "   AND  M41C03.FRGMH = '@loginYear' " . " \r\n";
        }
        //車検満了日
        if ($postData["expirationDateFrom"] <> "" && $postData["expirationDateTo"] == "") {
            $strSQL .= "   AND  M41C03.VCLIPEDT >= '@expirationDateFrom'  " . " \r\n";
        }
        if ($postData["expirationDateFrom"] == "" && $postData["expirationDateTo"] <> "") {
            $strSQL .= "   AND  M41C03.VCLIPEDT <= '@expirationDateTo' " . " \r\n";
        }
        if ($postData["expirationDateFrom"] <> "" && $postData["expirationDateTo"] <> "") {
            $strSQL .= "   AND  M41C03.VCLIPEDT BETWEEN '@expirationDateFrom' AND '@expirationDateTo'  " . " \r\n";
        }
        //パックdeメンテ現在加入
        if ($postData['packageMaintenance'] != "") {
            if ($postData['packageMaintenance'] == "01") {
                $strSQL .= "   AND  NVL(PDEM.PDEM,' ') = '○'" . " \r\n";
            }
            if ($postData['packageMaintenance'] != "01") {
                $strSQL .= "   AND  NVL(PDEM.PDEM,' ') != '○'" . " \r\n";
            }
        }

        //（DZM）延長保証現在加入
        if ($postData['masterMaintenance'] != "") {
            if ($postData['masterMaintenance'] == "01") {
                $strSQL .= "   AND  NVL(ENC.ENCHO,' ') = '○'" . " \r\n";
            }
            if ($postData['masterMaintenance'] != "01") {
                $strSQL .= "   AND  NVL(ENC.ENCHO,' ') != '○'" . " \r\n";
            }
        }
        //ボディコーティング現在加入
        if ($postData['bodyCoating'] != "") {
            if ($postData['bodyCoating'] == "01") {
                $strSQL .= "   AND  NVL(BODY.BODYCOAT,' ') = '○'" . " \r\n";
            }
            if ($postData['bodyCoating'] != "01") {
                $strSQL .= "   AND  NVL(BODY.BODYCOAT,' ') != '○'" . " \r\n";
            }
        }
        if ($postData["eraFrom"] == "10") {
            $strSQL = str_replace("@eraFrom", $postData['eraFrom'] + 8, $strSQL);
        } else {
            $strSQL = str_replace("@eraFrom", $postData['eraFrom'], $strSQL);
        }
        /*車検・点検属性*/
        //点検
        if ($postData['tenken'] != "") {
            $strSQL .= "   AND  CRM_LIST.SYOHIN01_CD = '" . $postData['tenken'] . "'" . " \r\n";
        }
        //点検年月
        if ($postData['tenkenymd'] != "") {
            $strSQL .= "   AND  CRM_LIST.SYOHIN01_EVT_YM = '" . $postData['tenkenymd'] . "'" . " \r\n";
        }
        //点検ステータス
        if ($postData['tenkensutetasu'] != "") {
            $strSQL .= "   AND  CRM_LIST.SYOHIN01_STATUS = '" . $postData['tenkensutetasu'] . "'" . " \r\n";
        }
        //車検
        if ($postData['shaken'] != "") {
            $strSQL .= "   AND  CRM_LIST.SYOHIN09_CD = '" . $postData['shaken'] . "'" . " \r\n";
        }
        //車検年月
        if ($postData['shakenymd'] != "") {
            $strSQL .= "   AND  CRM_LIST.SYOHIN09_EVT_YM = '" . $postData['shakenymd'] . "'" . " \r\n";
        }
        //車検ステータス
        if ($postData['shakensutetasu'] != "") {
            $strSQL .= "   AND  CRM_LIST.SYOHIN09_STATUS = '" . $postData['shakensutetasu'] . "'" . " \r\n";
        }
        //車検点検DM発信結果日時
        if ($postData["dmhasshinkekkaDateFrom"] != "" && $postData["dmhasshinkekkaDateTo"] == "") {
            $strSQL .= "   AND  CRM_LIST.DM_HASSIN_DATE >= TO_DATE('@dmhasshinkekkaDateFrom','YYYY-MM-DD')  " . " \r\n";
        }
        if ($postData["dmhasshinkekkaDateFrom"] == "" && $postData["dmhasshinkekkaDateTo"] != "") {
            $strSQL .= "   AND  CRM_LIST.DM_HASSIN_DATE <= TO_DATE('@dmhasshinkekkaDateTo','YYYY-MM-DD') " . " \r\n";
        }
        if ($postData["dmhasshinkekkaDateFrom"] != "" && $postData["dmhasshinkekkaDateTo"] != "") {
            $strSQL .= "   AND  CRM_LIST.DM_HASSIN_DATE BETWEEN TO_DATE('@dmhasshinkekkaDateFrom','YYYY-MM-DD') AND TO_DATE('@dmhasshinkekkaDateTo','YYYY-MM-DD')  " . " \r\n";
        }
        //車点検ＤＭ発信結果タイプ名称
        if ($postData['dmhasshinkekkameisho'] != "") {
            $strSQL .= "   AND  CRM_LIST.DM_HASSIN_TYPE_NAME = '" . $postData['dmhasshinkekkameisho'] . "'" . " \r\n";
        }
        $strSQL = str_replace("@dmhasshinkekkaDateFrom", $postData['dmhasshinkekkaDateFrom'], $strSQL);
        $strSQL = str_replace("@dmhasshinkekkaDateTo", $postData['dmhasshinkekkaDateTo'], $strSQL);
        $strSQL = str_replace("@eraTo", (int) $postData['eraTo'] + 9, $strSQL);
        $strSQL = str_replace("@birthday", $postData['birthday'], $strSQL);
        $strSQL = str_replace("@carName", str_replace("'", "''", $postData['carName']), $strSQL);
        $strSQL = str_replace("@management", $postData['management'], $strSQL);
        $strSQL = str_replace("@serviceManagement", $postData['serviceManagement'], $strSQL);
        $strSQL = str_replace("@loginYear", $postData['loginYear'], $strSQL);
        $strSQL = str_replace("@expirationDateFrom", str_replace("/", "", $postData['expirationDateFrom']), $strSQL);
        $strSQL = str_replace("@expirationDateTo", str_replace("/", "", $postData['expirationDateTo']), $strSQL);
        return $strSQL;
    }

    //'***********************************************************************
    //'処 理 名：採番取得
    //'関 数 名：fncGetSaiBan_sql
    //'引 数   ：$postData
    //'戻 り 値：ＳＱＬ
    //'処理説明：採番取得
    //'***********************************************************************
    public function fncGetSaiBan_sql()
    {
        $strSQL = "";
        $strSQL .= "SELECT REMBAN" . " \r\n";
        $strSQL .= "FROM T_YM_SAIBAN" . " \r\n";
        $strSQL .= "WHERE TEBURUMEI = 't_ux_joken' " . " \r\n";
        $strSQL .= "AND SAIBAN_YM =  TO_char(SYSDATE,'YYYYMM') " . " \r\n";
        $strSQL .= "FOR UPDATE NOWAIT " . " \r\n";
        return $strSQL;
    }

    public function fncGetSaiBan_sql1()
    {
        $strSQL = "";
        $strSQL .= "SELECT REMBAN" . " \r\n";
        $strSQL .= "FROM T_YM_SAIBAN" . " \r\n";
        $strSQL .= "WHERE TEBURUMEI = 't_ux_joken_saiban' " . " \r\n";
        $strSQL .= " AND  SAIBAN_YM = '999912'" . "\r\n";
        $strSQL .= "FOR UPDATE NOWAIT " . " \r\n";
        return $strSQL;
    }

    //'***********************************************************************
    //'処 理 名：UX条件データ新規登録
    //'関 数 名：fncInsData_sql
    //'引 数   ：$postData
    //'戻 り 値：ＳＱＬ
    //'処理説明：UX条件データ新規登録
    //'***********************************************************************
    public function fncInsData_sql($postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "INSERT " . "\r\n";
        $strSql .= "  INTO T_UX_JOKEN " . "\r\n";
        $strSql .= " ( " . "\r\n";
        $strSql .= " UX_JOKEN_ID " . "\r\n";
        $strSql .= " ,MESSEJI_ID " . "\r\n";
        $strSql .= " ,ZENKENSOFU_FLG " . "\r\n";
        $strSql .= " ,TAISHO_KENSU " . "\r\n";
        $strSql .= " ,SEIBETSU_KBN " . "\r\n";
        $strSql .= " ,KATEGORI " . "\r\n";
        $strSql .= " ,NENDAI_FROM " . "\r\n";
        $strSql .= " ,NENDAI_TO " . "\r\n";
        $strSql .= " ,TANJYO_TUKI " . "\r\n";
        $strSql .= " ,SHASHU " . "\r\n";
        $strSql .= " ,MAKER_CD " . "\r\n";
        $strSql .= " ,KOTEIKA_KBN " . "\r\n";
        $strSql .= " ,KANRI_CHIMU_CD " . "\r\n";
        $strSql .= " ,SABISU_CHIMU_CD " . "\r\n";
        $strSql .= " ,SHONENDO_TOROKU_YM " . "\r\n";
        $strSql .= " ,SHAKEN_MANRYO_YMD_FROM " . "\r\n";
        $strSql .= " ,SHAKEN_MANRYO_YMD_TO " . "\r\n";
        $strSql .= " ,PAKKUDEMENTE_KANYU_FLG " . "\r\n";
        $strSql .= " ,MATSUDAENCHOHOSHO_KANYU_FLG " . "\r\n";
        $strSql .= " ,BODEIKOTEINGU_KANYU_FLG " . "\r\n";
        $strSql .= " ,TENKEN1 " . "\r\n";
        $strSql .= " ,TENKEN_YMD " . "\r\n";
        $strSql .= " ,TENKEN_SUTETASU " . "\r\n";
        $strSql .= " ,SHAKEN9 " . "\r\n";
        $strSql .= " ,SHAKEN_YMD " . "\r\n";
        $strSql .= " ,SHAKEN_SUTETASU " . "\r\n";
        $strSql .= " ,DM_HASSHIN_KEKKA_DATE_FROM " . "\r\n";
        $strSql .= " ,DM_HASSHIN_KEKKA_DATE_TO " . "\r\n";
        $strSql .= " ,DM_HASSHIN_KEKKA_MEISHO " . "\r\n";
        $strSql .= " ,HYOJI_ST_YMD " . "\r\n";
        $strSql .= " ,HYOJI_ED_YMD " . "\r\n";
        $strSql .= " ,HYOJI_ST_HM " . "\r\n";
        $strSql .= " ,HYOJI_ED_HM " . "\r\n";
        $strSql .= " ,UPD_STS_KBN " . "\r\n";
        $strSql .= " ,RENKEI_KBN " . "\r\n";
        $strSql .= " ,UPD_DATE " . "\r\n";
        $strSql .= " ,UPD_USER_ID " . "\r\n";
        $strSql .= " ,CREATE_DATE " . "\r\n";
        $strSql .= " ,CREATE_USER_ID " . "\r\n";
        $strSql .= " ,DEL_FLG " . "\r\n";
        $strSql .= " ,DEL_USER_ID " . "\r\n";
        $strSql .= " ) " . "\r\n";
        $strSql .= " VALUES (" . "\r\n";
        $strSql .= "'" . $postData['uxId'] . "'," . "\r\n";
        $strSql .= "'" . $postData['msgId'] . "'," . "\r\n";
        $strSql .= "'" . $postData['sofu'] . "'," . "\r\n";
        $strSql .= "'" . $postData['objNum'] . "'," . "\r\n";
        $strSql .= "'" . $postData['gender'] . "'," . "\r\n";
        $strSql .= "'" . $postData['category'] . "'," . "\r\n";
        $strSql .= "'" . $postData['eraFrom'] . "'," . "\r\n";
        $strSql .= "'" . $postData['eraTo'] . "'," . "\r\n";
        $strSql .= "'" . $postData['birthday'] . "'," . "\r\n";
        $strSql .= "'" . str_replace("'", "''", $postData['carName']) . "'," . "\r\n";
        $strSql .= "'" . $postData['manufacture'] . "'," . "\r\n";
        $strSql .= "'" . $postData['classification'] . "'," . "\r\n";
        $strSql .= "'" . $postData['management'] . "'," . "\r\n";
        $strSql .= "'" . $postData['serviceManagement'] . "'," . "\r\n";
        $strSql .= "'" . $postData['loginYear'] . "'," . "\r\n";
        $strSql .= "'" . $postData['expirationDateFrom'] . "'," . "\r\n";
        $strSql .= "'" . $postData['expirationDateTo'] . "'," . "\r\n";
        $strSql .= "'" . $postData['packageMaintenance'] . "'," . "\r\n";
        $strSql .= "'" . $postData['masterMaintenance'] . "'," . "\r\n";
        $strSql .= "'" . $postData['bodyCoating'] . "'," . "\r\n";
        $strSql .= "'" . $postData['inspection'] . "'," . "\r\n";
        $strSql .= "'" . $postData['inspectionDate'] . "'," . "\r\n";
        $strSql .= "'" . $postData['inspectionStatus'] . "'," . "\r\n";
        $strSql .= "'" . $postData['vehicleInspection'] . "'," . "\r\n";
        $strSql .= "'" . $postData['vehicleInspectionDate'] . "'," . "\r\n";
        $strSql .= "'" . $postData['vehicleInspectionStatus'] . "'," . "\r\n";
        $strSql .= "'" . str_replace("/", "", $postData['vehicleInspectionResultDateFrom']) . "'," . "\r\n";
        $strSql .= "'" . str_replace("/", "", $postData['vehicleInspectionResultDateTo']) . "'," . "\r\n";
        $strSql .= "'" . $postData['vehicleInspectionName'] . "'," . "\r\n";
        $strSql .= "'" . $postData['displayDateFrom'] . "'," . "\r\n";
        $strSql .= "'" . $postData['displayDateTo'] . "'," . "\r\n";
        $strSql .= "'" . $postData['displayTimeFrom'] . "'," . "\r\n";
        $strSql .= "'" . $postData['displayTimeTo'] . "'," . "\r\n";
        $strSql .= " '01'," . "\r\n";
        $strSql .= " '00'," . "\r\n";
        $strSql .= "  SYSDATE," . "\r\n";
        $strSql .= " '" . $this->SessionComponent->read('login_user') . "'," . "\r\n";
        $strSql .= "  SYSDATE," . "\r\n";
        $strSql .= " '" . $this->SessionComponent->read('login_user') . "'," . "\r\n";
        $strSql .= " '00'," . "\r\n";
        $strSql .= " ''" . "\r\n";
        $strSql .= "  )" . "\r\n";
        return $strSql;
    }

    //'***********************************************************************
    //'処 理 名：年月基準採番テーブル．連番を 新规する
    //'関 数 名：fncInsSaiban_sql
    //'引 数   ：$postData
    //'戻 り 値：ＳＱＬ
    //'処理説明：年月基準採番テーブル．連番を 新规する
    //'***********************************************************************
    public function fncInsSaiban_sql($remban)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "INSERT " . "\r\n";
        $strSql .= "  INTO T_YM_SAIBAN" . "\r\n";
        $strSql .= "  (" . "\r\n";
        $strSql .= " TEBURUMEI " . "\r\n";
        $strSql .= " ,SAIBAN_YM " . "\r\n";
        $strSql .= " ,REMBAN " . "\r\n";
        $strSql .= " ,UPD_DATE " . "\r\n";
        $strSql .= " ,UPD_USER_ID " . "\r\n";
        $strSql .= " ,CREATE_DATE " . "\r\n";
        $strSql .= " ,CREATE_USER_ID " . "\r\n";
        $strSql .= " ,DEL_FLG " . "\r\n";
        $strSql .= " ,DEL_USER_ID " . "\r\n";
        $strSql .= "  )" . "\r\n";
        $strSql .= " VALUES" . "\r\n";
        $strSql .= "  (" . "\r\n";
        $strSql .= "  't_ux_joken'," . "\r\n";
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
    //'***********************************************************************
    //'処 理 名：年月基準採番テーブル．連番を 更新する
    //'関 数 名：fncUpdSaiban_sql
    //'引 数   ：$postData
    //'戻 り 値：ＳＱＬ
    //'処理説明：年月基準採番テーブル．連番を 更新する
    //'***********************************************************************
    public function fncUpdSaiban_sql()
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "UPDATE T_YM_SAIBAN" . " \r\n";
        $strSql .= "SET" . " \r\n";
        $strSql .= "REMBAN = REMBAN + 1 ," . " \r\n";
        $strSql .= "UPD_DATE = SYSDATE, " . " \r\n";
        $strSql .= "UPD_USER_ID = '" . $this->SessionComponent->read('login_user') . "'" . " \r\n";
        $strSql .= "WHERE TEBURUMEI = 't_ux_joken'" . " \r\n";
        $strSql .= "AND SAIBAN_YM = TO_CHAR(SYSDATE,'YYYYMM')" . " \r\n";
        return $strSql;

    }

    //'***********************************************************************
    //'処 理 名：UX条件ワークの削除SQL
    //'関 数 名：fncWkUXDelete_sql
    //'引 数   ：UX条件ID
    //'戻 り 値：SQL
    //'処理説明：UXワークの削除SQL
    //'***********************************************************************
    public function fncWkUXDelete_sql($uxId)
    {
        $strSql = "";
        $strSql .= "DELETE" . "\r\n";
        $strSql .= "  FROM  " . "\r\n";
        $strSql .= "  WK_UX " . "\r\n";
        $strSql .= "  WHERE" . "\r\n";
        $strSql .= " UXJOKEN_ID = '" . $uxId . "'" . "\r\n";

        return $strSql;
    }

    //'***********************************************************************
    //'処 理 名：UXワーク連番新規登録
    //'関 数 名：fncInsUXData_sql
    //'引 数   ：$postData
    //'戻 り 値：ＳＱＬ
    //'処理説明：UXワーク連番新規登録
    //'***********************************************************************
    public function fncInsUXData_sql($postData, $objData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "INSERT " . "\r\n";
        $strSql .= "  INTO WK_UX " . "\r\n";
        $strSql .= "  (" . "\r\n";
        $strSql .= " WK_UX_NO " . "\r\n";
        $strSql .= " ,OKYAKUSAMA_NO " . "\r\n";
        $strSql .= " ,UXJOKEN_ID " . "\r\n";
        $strSql .= " ,MESSEJI_ID " . "\r\n";
        $strSql .= " ,HYOJI_ST_YMD " . "\r\n";
        $strSql .= " ,HYOJI_ED_YMD " . "\r\n";
        $strSql .= " ,HYOJI_ST_HM " . "\r\n";
        $strSql .= " ,HYOJI_ED_HM " . "\r\n";
        $strSql .= " ,VIN_WMIVDS " . "\r\n";
        $strSql .= " ,VIN_VIS " . "\r\n";
        $strSql .= " ,TENKEN_YM " . "\r\n";
        $strSql .= " ,SHAKEN_MANRYO_YMD " . "\r\n";
        $strSql .= " ,UPD_DATE " . "\r\n";
        $strSql .= " ,UPD_USER_ID " . "\r\n";
        $strSql .= " ,CREATE_DATE " . "\r\n";
        $strSql .= " ,CREATE_USER_ID " . "\r\n";
        $strSql .= " ,DEL_FLG " . "\r\n";
        $strSql .= " ,DEL_USER_ID " . "\r\n";
        $strSql .= "  )" . "\r\n";
        $strSql .= " VALUES" . "\r\n";
        $strSql .= "  (" . "\r\n";
        $strSql .= "  Lpad(ux_jouken_seq.NEXTVAL,6,'0')," . "\r\n";
        $strSql .= "'" . $objData['DLRCSRNO'] . "'," . "\r\n";
        $strSql .= "'" . $postData['uxId'] . "'," . "\r\n";
        $strSql .= "'" . $postData['msgId'] . "'," . "\r\n";
        $strSql .= "'" . $postData['displayDateFrom'] . "'," . "\r\n";
        $strSql .= "'" . $postData['displayDateTo'] . "'," . "\r\n";
        $strSql .= "'" . $postData['displayTimeFrom'] . "'," . "\r\n";
        $strSql .= "'" . $postData['displayTimeTo'] . "'," . "\r\n";
        $strSql .= "'" . $objData['VIN_WMIVDS'] . "'," . "\r\n";
        $strSql .= "'" . $objData['VIN_VIS'] . "'," . "\r\n";
        $strSql .= "'" . $objData['SYOHIN01_EVT_YM'] . "'," . "\r\n";
        $strSql .= "'" . $objData['VCLIPEDT'] . "'," . "\r\n";
        $strSql .= "  SYSDATE," . "\r\n";
        $strSql .= " '" . $this->SessionComponent->read('login_user') . "'," . "\r\n";
        $strSql .= "  SYSDATE," . "\r\n";
        $strSql .= " '" . $this->SessionComponent->read('login_user') . "'," . "\r\n";
        $strSql .= " '00'," . "\r\n";
        $strSql .= " ''" . "\r\n";
        $strSql .= "  )" . "\r\n";

        return $strSql;
    }

    //'***********************************************************************
    //'処 理 名：排他ロック用
    //'関 数 名：fncHaitaLogin_sql
    //'引 数   ：$postData
    //'戻 り 値：ＳＱＬ
    //'処理説明：排他ロック用
    //'***********************************************************************
    public function fncHaitaLogin_sql($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT UX_JOKEN_ID,UPD_DATE" . " \r\n";
        $strSQL .= "FROM T_UX_JOKEN" . " \r\n";
        $strSQL .= "WHERE UX_JOKEN_ID = '@UXID' " . " \r\n";
        $strSQL .= "FOR UPDATE NOWAIT " . " \r\n";
        $strSQL = str_replace("@UXID", $postData['uxId'], $strSQL);
        return $strSQL;
    }

    //'***********************************************************************
    //'処 理 名：データ更新
    //'関 数 名：fncUpdData_sql
    //'引 数   ：$postData
    //'戻 り 値：ＳＱＬ
    //'処理説明：データ更新
    //'***********************************************************************
    public function fncUpdData_sql($postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "UPDATE T_UX_JOKEN" . " \r\n";
        $strSql .= "SET" . " \r\n";
        $strSql .= "MESSEJI_ID = '" . $postData['msgId'] . "' ," . " \r\n";
        $strSql .= "ZENKENSOFU_FLG = '" . $postData['sofu'] . "' ," . " \r\n";
        $strSql .= "TAISHO_KENSU = '" . $postData['objNum'] . "' ," . " \r\n";
        $strSql .= "SEIBETSU_KBN = '" . $postData['gender'] . "' ," . " \r\n";
        $strSql .= "KATEGORI = '" . $postData['category'] . "' ," . " \r\n";
        $strSql .= "NENDAI_FROM = '" . $postData['eraFrom'] . "' ," . " \r\n";
        $strSql .= "NENDAI_TO = '" . $postData['eraTo'] . "' ," . " \r\n";
        $strSql .= "TANJYO_TUKI = '" . $postData['birthday'] . "' ," . " \r\n";
        $strSql .= "SHASHU = '" . str_replace("'", "''", $postData['carName']) . "' ," . " \r\n";
        $strSql .= "MAKER_CD = '" . $postData['manufacture'] . "' ," . " \r\n";
        $strSql .= "KOTEIKA_KBN = '" . $postData['classification'] . "' ," . " \r\n";
        $strSql .= "KANRI_CHIMU_CD = '" . $postData['management'] . "' ," . " \r\n";
        $strSql .= "SABISU_CHIMU_CD = '" . $postData['serviceManagement'] . "' ," . " \r\n";
        $strSql .= "SHONENDO_TOROKU_YM = '" . $postData['loginYear'] . "' ," . " \r\n";
        $strSql .= "SHAKEN_MANRYO_YMD_FROM = '" . $postData['expirationDateFrom'] . "' ," . " \r\n";
        $strSql .= "SHAKEN_MANRYO_YMD_TO = '" . $postData['expirationDateTo'] . "' ," . " \r\n";
        $strSql .= "PAKKUDEMENTE_KANYU_FLG = '" . $postData['packageMaintenance'] . "' ," . " \r\n";
        $strSql .= "MATSUDAENCHOHOSHO_KANYU_FLG = '" . $postData['masterMaintenance'] . "' ," . " \r\n";
        $strSql .= "BODEIKOTEINGU_KANYU_FLG = '" . $postData['bodyCoating'] . "' ," . " \r\n";
        $strSql .= "TENKEN1 = '" . $postData['inspection'] . "' ," . " \r\n";
        $strSql .= "TENKEN_YMD = '" . $postData['inspectionDate'] . "' ," . " \r\n";
        $strSql .= "TENKEN_SUTETASU = '" . $postData['inspectionStatus'] . "' ," . " \r\n";
        $strSql .= "SHAKEN9 = '" . $postData['vehicleInspection'] . "' ," . " \r\n";
        $strSql .= "SHAKEN_YMD = '" . $postData['vehicleInspectionDate'] . "' ," . " \r\n";
        $strSql .= "SHAKEN_SUTETASU = '" . $postData['vehicleInspectionStatus'] . "' ," . " \r\n";
        $strSql .= "DM_HASSHIN_KEKKA_DATE_FROM = '" . str_replace("/", "", $postData['vehicleInspectionResultDateFrom']) . "' ," . " \r\n";
        $strSql .= "DM_HASSHIN_KEKKA_DATE_TO = '" . str_replace("/", "", $postData['vehicleInspectionResultDateTo']) . "' ," . " \r\n";
        $strSql .= "DM_HASSHIN_KEKKA_MEISHO = '" . $postData['vehicleInspectionName'] . "' ," . " \r\n";
        $strSql .= "HYOJI_ST_YMD = '" . $postData['displayDateFrom'] . "' ," . " \r\n";
        $strSql .= "HYOJI_ED_YMD = '" . $postData['displayDateTo'] . "' ," . " \r\n";
        $strSql .= "HYOJI_ST_HM = '" . $postData['displayTimeFrom'] . "' ," . " \r\n";
        $strSql .= "HYOJI_ED_HM = '" . $postData['displayTimeTo'] . "' ," . " \r\n";
        $strSql .= "UPD_STS_KBN = '02' ," . " \r\n";
        $strSql .= "RENKEI_KBN = '00' ," . " \r\n";
        $strSql .= "UPD_DATE = SYSDATE, " . " \r\n";
        $strSql .= "UPD_USER_ID = '" . $this->SessionComponent->read('login_user') . "'" . " \r\n";
        $strSql .= "WHERE UX_JOKEN_ID = '" . $postData['uxId'] . "'" . " \r\n";

        return $strSql;
    }

    //'***********************************************************************
    //'処 理 名：データ削除
    //'関 数 名：fncDelData_sql
    //'引 数   ：$postData
    //'戻 り 値：ＳＱＬ
    //'処理説明：データ削除
    //'***********************************************************************
    public function fncDelData_sql($postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "UPDATE T_UX_JOKEN" . " \r\n";
        $strSql .= "SET" . " \r\n";
        //更新区分
        $strSql .= "UPD_STS_KBN = '09'," . " \r\n";
        //更新日時
        $strSql .= " UPD_DATE = SYSDATE," . "\r\n";
        //削除フラグ
        $strSql .= "DEL_FLG = '01'," . " \r\n";
        //削除ユーザーID
        $strSql .= "DEL_USER_ID = '" . $this->SessionComponent->read('login_user') . "'" . " \r\n";
        $strSql .= "WHERE UX_JOKEN_ID = '" . $postData['uxId'] . "'" . " \r\n";

        return $strSql;
    }


}