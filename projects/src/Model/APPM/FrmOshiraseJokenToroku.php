<?php
/**
 * 説明：
 *
 *
 * @author YINHUAIYU
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

class FrmOshiraseJokenToroku extends ClsComDb
{
    public $SessionComponent;
    //'**********************************************************************
    //'処 理 名：メッセージのオートコンプリート
    //'関 数 名：FncAutoComplete
    //'引 数 1 ：なし
    //'戻 り 値：ＳＱＬ
    //'処理説明：メッセージのオートコンプリート
    //'**********************************************************************
    public function FncAutoComplete($hyojiymd)
    {
        $strSQL = $this->FncAutoComplete_sql($hyojiymd);
        return parent::select($strSQL);
    }

    //'***********************************************************************
    //'処 理 名：入力欄取得
    //'関 数 名：fncGetTCodeData
    //'引 数   ：$flg
    //'戻 り 値：コードデータ
    //'処理説明：入力欄取得
    //'***********************************************************************
    public function fncGetTCodeData($flg, $hyojiymd)
    {
        $strSQL = $this->fncGetTCodeData_sql($flg, $hyojiymd);
        return parent::select($strSQL);
    }

    //'***********************************************************************
    //'処 理 名：・管理拠点 ・サービス拠点取得
    //'関 数 名：fncGetTHBUSYOData
    //'引 数   ：なし
    //'戻 り 値：管理拠点 ・サービス拠点
    //'処理説明：・管理拠点 ・サービス拠点取得
    //'***********************************************************************
    public function fncGetTHBUSYOData()
    {
        $strSQL = $this->fncGetTHBUSYOData_sql();
        return parent::select($strSQL);
    }

    //'***********************************************************************
    //'処 理 名：画面初期化データ取得
    //'関 数 名：fncGetInformation
    //'引 数   ：お知らせ条件ID
    //'戻 り 値：画面初期化データ
    //'処理説明：画面初期化データ取得
    //'***********************************************************************
    public function fncGetInformation($oshiraseId)
    {
        $strSQL = $this->fncGetInformation_sql($oshiraseId);
        return parent::select($strSQL);
    }

    //'***********************************************************************
    //'処 理 名：メッセージ・コード存在チェック
    //'関 数 名：fncCheckId
    //'引 数   ：メッセージID
    //'戻 り 値：メッセージ・コード
    //'処理説明：メッセージ・コード存在チェック
    //'***********************************************************************
    public function fncCheckId($messid, $hyojiymd)
    {
        $strSQL = $this->fncCheckId_sql($messid, $hyojiymd);
        return parent::select($strSQL);
    }

    //'***********************************************************************
    //'処 理 名：対象件数取得
    //'関 数 名：fncGetObjectNumber
    //'引 数   ：画面上に入力された条件
    //'戻 り 値：対象件数
    //'処理説明：対象件数取得
    //'***********************************************************************
    public function fncGetObjectNumber($postData)
    {
        $strSQL = $this->fncGetObjectNumber_sql($postData);
        return parent::select($strSQL);
    }

    //'***********************************************************************
    //'処 理 名：年月基準採番
    //'関 数 名：fncRembanSelect
    //'引 数   ：なし
    //'戻 り 値：採番年月
    //'処理説明：年月基準採番
    //'***********************************************************************
    public function fncRembanSelect()
    {
        $strSQL = $this->fncRembanSelect_sql();
        return parent::select($strSQL);
    }

    //'***********************************************************************
    //'処 理 名：年月基準採番
    //'関 数 名：fncRembanSelect2
    //'引 数   ：なし
    //'戻 り 値：採番年月
    //'処理説明：年月基準採番
    //'***********************************************************************
    public function fncRembanSelect2()
    {
        $strSQL = $this->fncRembanSelect2_sql();
        return parent::select($strSQL);
    }

    //'***********************************************************************
    //'処 理 名：年月基準採番新規登録
    //'関 数 名：fncRembanInsert
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：年月基準採番新規登録
    //'***********************************************************************
    public function fncRembanInsert($remban)
    {
        $strSQL = $this->fncRembanInsert_sql($remban);
        return parent::insert($strSQL);
    }

    //'***********************************************************************
    //'処 理 名：お知らせ条件データ新規登録
    //'関 数 名：fncOshiraseJokenInsert
    //'引 数   ：お知らせ条件ID,メッセージID,全件送付,対象件数,画面上に入力された条件
    //'戻 り 値：なし
    //'処理説明：お知らせ条件データ新規登録
    //'***********************************************************************
    public function fncOshiraseJokenInsert($OsId, $messid, $zenkensofu, $ObjectNumber, $postData)
    {
        $strSQL = $this->fncOshiraseJokenInsert_sql($OsId, $messid, $zenkensofu, $ObjectNumber, $postData);
        return parent::insert($strSQL);
    }

    //'***********************************************************************
    //'処 理 名：年月基準採番テーブル．連番を1インクリメント更新する
    //'関 数 名：fncRembanUpdata
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：年月基準採番テーブル．連番を1インクリメント更新する
    //'***********************************************************************
    public function fncRembanUpdata()
    {
        $strSQL = $this->fncRembanUpdata_sql();
        return parent::update($strSQL);
    }

    //'***********************************************************************
    //'処 理 名：お知らせ条件ワーク新規登録
    //'関 数 名：fncWkOshiraseInsert
    //'引 数   ：お知らせ条件ID,メッセージID,対象データ,画面上に入力された条件
    //'戻 り 値：なし
    //'処理説明：お知らせ条件ワーク新規登録
    //'***********************************************************************
    public function fncWkOshiraseInsert($OsId, $messid, $ObjectData, $postData)
    {
        $strSQL = $this->fncWkOshiraseInsert_sql($OsId, $messid, $ObjectData, $postData);
        return parent::insert($strSQL);
    }

    //'***********************************************************************
    //'処 理 名：更新対象データを検索
    //'関 数 名：fncWkOshiraseInsert
    //'引 数   ：お知らせ条件ID
    //'戻 り 値：なし
    //'処理説明：更新対象データを検索
    //'***********************************************************************
    public function fncOshirasejokenIdSelect($oshiraseId)
    {
        $strSQL = $this->fncOshirasejokenIdSelect_sql($oshiraseId);
        return parent::select($strSQL);
    }

    //'***********************************************************************
    //'処 理 名：お知らせ条件データ更新
    //'関 数 名：fncOshiraseJokenUpdata
    //'引 数   ：お知らせ条件ID,メッセージID,全件送付,対象件数,画面上に入力された条件
    //'戻 り 値：なし
    //'処理説明：お知らせ条件データ更新
    //'***********************************************************************
    public function fncOshiraseJokenUpdata($oshiraseId, $messid, $zenkensofu, $ObjectNumber, $postData)
    {
        $strSQL = $this->fncOshiraseJokenUpdata_sql($oshiraseId, $messid, $zenkensofu, $ObjectNumber, $postData);
        return parent::update($strSQL);
    }

    //'***********************************************************************
    //'処 理 名：お知らせ条件ワークの削除
    //'関 数 名：fncWkOshiraseDelet
    //'引 数   ：お知らせ条件ID
    //'戻 り 値：なし
    //'処理説明：お知らせ条件ワークの削除
    //'***********************************************************************
    public function fncWkOshiraseDelet($oshiraseId)
    {
        $strSQL = $this->fncWkOshiraseDelet_sql($oshiraseId);
        return parent::delete($strSQL);
    }

    //'***********************************************************************
    //'処 理 名：お知らせ条件データを更新する
    //'関 数 名：fncOshiraseJokenDelet
    //'引 数   ：お知らせ条件ID
    //'戻 り 値：なし
    //'処理説明：お知らせ条件データを更新する
    //'***********************************************************************
    public function fncOshiraseJokenDelet($oshiraseId)
    {
        $strSQL = $this->fncOshiraseJokenDelet_sql($oshiraseId);
        return parent::update($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：メッセージのオートコンプリートSQL
    //'関 数 名：FncAutoComplete
    //'引 数 1 ：なし
    //'戻 り 値：ＳＱＬ
    //'処理説明：メッセージのオートコンプリートSQL
    //'**********************************************************************
    public function FncAutoComplete_sql($hyojiymd)
    {
        $strSQL = "";
        $strSQL .= "SELECT MESSEJI_ID" . " \r\n";
        $strSQL .= ", TAITORU" . " \r\n";
        $strSQL .= "FROM T_MESSEJI" . " \r\n";
        $strSQL .= "WHERE" . " \r\n";
        $strSQL .= " messeji_riyo_kikan_from <= '" . $hyojiymd . "'" . " \r\n";
        $strSQL .= " AND messeji_riyo_kikan_to >= '" . $hyojiymd . "'" . " \r\n";
        $strSQL .= " AND naiyo_kbn in ('01','02')" . " \r\n";
        $strSQL .= " AND upd_sts_kbn != '09'" . " \r\n";
        $strSQL .= " AND del_flg = '00'" . " \r\n";

        return $strSQL;
    }

    //'***********************************************************************
    //'処 理 名：入力欄取得SQL
    //'関 数 名：fncGetTCodeData
    //'引 数   ：$flg
    //'戻 り 値：SQL
    //'処理説明：入力欄取得SQL
    //'***********************************************************************
    public function fncGetTCodeData_sql($flg, $hyojiymd)
    {
        $strSQL = "";
        $strSQL .= "SELECT NAIBU_CD_MEISHO" . " \r\n";
        $strSQL .= ", NAIBU_CD" . " \r\n";
        $strSQL .= "FROM M_CODE" . " \r\n";
        $strSQL .= "WHERE" . " \r\n";
        //・性別
        if ($flg == "1") {
            $strSQL .= " GAIBU_CD = '009'" . " \r\n";
        }
        //・カテゴリ
        if ($flg == "2") {
            $strSQL .= " GAIBU_CD = '010'" . " \r\n";
        }
        //・年代
        if ($flg == "3") {
            $strSQL .= " GAIBU_CD = '006'" . " \r\n";
        }
        //・メーカー名
        if ($flg == "4") {
            $strSQL .= " GAIBU_CD = '007'" . " \r\n";
        }
        //・固定化区分
        if ($flg == "5") {
            $strSQL .= " GAIBU_CD = '011'" . " \r\n";
        }
        //・パックdeメンテ現在加入 ・（DZM）延長保証現在加入 ・ボディーコーティング現在加入
        if ($flg == "6") {
            $strSQL .= " GAIBU_CD = '002'" . " \r\n";
        }
        //・点検ステータス ・車検ステータス
        if ($flg == "7") {
            $strSQL .= " GAIBU_CD = '008'" . " \r\n";
        }
        //・車点検ＤＭ発信結果タイプ名称
        if ($flg == "8") {
            $strSQL .= " GAIBU_CD = '013'" . " \r\n";
        }
        //・点検
        if ($flg == "9") {
            $strSQL .= " GAIBU_CD = '012'" . " \r\n";
        }
        //・車検
        if ($flg == "10") {
            $strSQL .= " GAIBU_CD = '014'" . " \r\n";
        }

        $strSQL .= " AND YUKO_KAISHI_YMD <= '" . $hyojiymd . "'" . " \r\n";
        $strSQL .= " AND YUKO_SHURYO_YMD >= '" . $hyojiymd . "'" . " \r\n";
        $strSQL .= " AND del_flg = '00'";

        return $strSQL;
    }

    //'***********************************************************************
    //'処 理 名：・管理拠点 ・サービス拠点取得SQL
    //'関 数 名：fncGetTHBUSYOData_sql
    //'引 数   ：なし
    //'戻 り 値：SQL
    //'処理説明：・管理拠点 ・サービス拠点取得SQL
    //'***********************************************************************
    public function fncGetTHBUSYOData_sql()
    {
        $strSQL = "";
        $strSQL .= "SELECT  BUSYO_CD,BUSYO_RYKNM" . " \r\n";
        $strSQL .= "FROM HBUSYO" . " \r\n";
        $strSQL .= "WHERE PRN_KB4 = 'O'" . " \r\n";
        $strSQL .= " AND DSP_SEQNO IS NOT NULL" . " \r\n";
        $strSQL .= " AND BUSYO_KB IN('S','C')";
        $strSQL .= "ORDER BY";
        $strSQL .= " DSP_SEQNO";

        return $strSQL;
    }

    //'***********************************************************************
    //'処 理 名：画面初期化データ取得SQL
    //'関 数 名：fncGetInformation_sql
    //'引 数   ：お知らせ条件ID
    //'戻 り 値：SQL
    //'処理説明：画面初期化データ取得SQL
    //'***********************************************************************
    public function fncGetInformation_sql($oshiraseId)
    {
        $strSQL = "";
        $strSQL .= "SELECT T_OSHIRASEJOKEN.*" . " \r\n";
        $strSQL .= ",T_MESSEJI.TAITORU" . " \r\n";
        $strSQL .= "FROM T_OSHIRASEJOKEN" . " \r\n";
        $strSQL .= " LEFT JOIN T_MESSEJI " . " \r\n";
        $strSQL .= " ON T_OSHIRASEJOKEN.MESSEJI_ID = T_MESSEJI.MESSEJI_ID" . " \r\n";
        $strSQL .= "WHERE OSHIRASEJOKEN_ID = '@OSHIRASEJOKEN_ID'" . " \r\n";

        $strSQL = str_replace("@OSHIRASEJOKEN_ID", $oshiraseId, $strSQL);

        return $strSQL;
    }

    //'***********************************************************************
    //'処 理 名：メッセージ・コード存在チェックSQL
    //'関 数 名：fncCheckId_sql
    //'引 数   ：メッセージID
    //'戻 り 値：SQL
    //'処理説明：メッセージ・コード存在チェックSQL
    //'***********************************************************************
    public function fncCheckId_sql($messid, $hyojiymd)
    {
        $strSQL = "";
        $strSQL .= "SELECT MESSEJI_ID" . " \r\n";
        $strSQL .= "FROM T_MESSEJI" . " \r\n";
        $strSQL .= "WHERE MESSEJI_ID = '@MESSEJI_ID'" . " \r\n";
        $strSQL .= " AND messeji_riyo_kikan_from <= '" . $hyojiymd . "'" . " \r\n";
        $strSQL .= " AND messeji_riyo_kikan_to >= '" . $hyojiymd . "'" . " \r\n";
        $strSQL .= " AND naiyo_kbn in ('01','02')" . " \r\n";
        $strSQL .= " AND upd_sts_kbn != '09'" . " \r\n";
        $strSQL .= " AND del_flg = '00'" . " \r\n";

        $strSQL = str_replace("@MESSEJI_ID", str_replace("'", "''", $messid), $strSQL);

        return $strSQL;
    }

    //'***********************************************************************
    //'処 理 名：対象件数取得SQL
    //'関 数 名：fncGetObjectNumber_sql
    //'引 数   ：画面上に入力された条件
    //'戻 り 値：SQL
    //'処理説明：対象件数取得SQL
    //'***********************************************************************
    public function fncGetObjectNumber_sql($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT M41C04.DLRCSRNO" . " \r\n";
        $strSQL .= "  ,M41C04.VIN_WMIVDS" . " \r\n";
        $strSQL .= "  ,M41C04.VIN_VIS" . " \r\n";
        $strSQL .= "  ,CRM_LIST.SYOHIN01_EVT_YM" . " \r\n";
        $strSQL .= "  ,M41C03.VCLIPEDT" . " \r\n";
        $strSQL .= "FROM t_apuri_riyo_kokyaku, M41C01, M41C03, M41C04, CRM_LIST," . " \r\n";
        $strSQL .= "    (SELECT" . " \r\n";
        $strSQL .= "       '01' as PDEM," . " \r\n";
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
        $strSQL .= "        AND (M41C67.SOH_NM LIKE '%メンテ%')　) PDEM," . " \r\n";
        $strSQL .= "           (SELECT" . " \r\n";
        $strSQL .= "              '01' as ENCHO," . " \r\n";
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
        $strSQL .= "                         '01' as BODYCOAT," . " \r\n";
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
        $strSQL .= "   AND  M41C01.DLRCSRNO = M41C04.DLRCSRNO" . " \r\n";
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
        if ($postData['seibetsu'] != "") {
            $strSQL .= "   AND  M41C01.CSRDOSID = '" . substr($postData['seibetsu'], 1, 1) . "'" . " \r\n";
        }
        //カテゴリ
        if ($postData['kategori'] != "") {
            $strSQL .= "   AND  M41C01.CSRRANK = '" . $postData['kategori'] . "'" . " \r\n";
        }
        //年代
        if ($postData["nendaiFrom"] != "" && $postData["nendaiTo"] == "") {
            $strSQL .= "   AND  TO_NUMBER( TO_CHAR(SYSDATE,'YYYY')) -  TO_NUMBER(SUBSTR(CASE M41C01.BRTDT  WHEN ' '  THEN '0000' ELSE M41C01.BRTDT END,1,4)) BETWEEN @nendaiFrom AND 89" . " \r\n";
        }
        if ($postData["nendaiFrom"] == "" && $postData["nendaiTo"] != "") {
            $strSQL .= "   AND  TO_NUMBER( TO_CHAR(SYSDATE,'YYYY')) -  TO_NUMBER(SUBSTR(CASE M41C01.BRTDT  WHEN ' '  THEN '0000' ELSE M41C01.BRTDT END,1,4)) BETWEEN 18 AND @nendaiTo " . " \r\n";
        }
        if ($postData["nendaiFrom"] != "" && $postData["nendaiTo"] != "") {
            $strSQL .= "   AND  TO_NUMBER( TO_CHAR(SYSDATE,'YYYY')) -  TO_NUMBER(SUBSTR(CASE M41C01.BRTDT  WHEN ' '  THEN '0000' ELSE M41C01.BRTDT END,1,4)) BETWEEN @nendaiFrom AND @nendaiTo " . " \r\n";
        }
        //誕生月
        if ($postData["tanjyotuki"] != "") {
            $strSQL .= "   AND  TO_NUMBER(SUBSTR(CASE M41C01.BRTDT  WHEN ' '  THEN '0000' ELSE M41C01.BRTDT END,5,2)) = " . $postData['tanjyotuki'] . " \r\n";
        }
        /*車両属性*/
        //車種
        if ($postData["shashuNm"] != "") {
            $strSQL .= "   AND  M41C03.VCLNM LIKE '%@carName%' " . " \r\n";
        }
        //メーカー名
        switch ($postData["makerNm"]) {
            case '01':
                $strSQL .= "   AND  M41C03.VCLBRDCD = '10'" . " \r\n";
                break;
            case '02':
                $strSQL .= "   AND  M41C03.VCLBRDCD = 'A9'" . " \r\n";
                break;
            case '03':
                $strSQL .= "   AND  M41C03.VCLBRDCD = 'A1'" . " \r\n";
                break;
        }
        //固定化区分
        if ($postData['koteikakbn'] != "") {
            $strSQL .= "   AND  M41C04.XG11KOTEIID = '" . $postData['koteikakbn'] . "'" . " \r\n";
        }
        //管理拠点
        if ($postData['kanrichimu'] != "") {
            $strSQL .= "   AND  SUBSTR(M41C04.KNR_STRCD,1,2) = '" . substr($postData['kanrichimu'], 0, 2) . "'" . " \r\n";
        }
        //サービス拠点
        if ($postData['sabisuchimu'] != "") {
            $strSQL .= "   AND  SUBSTR(M41C04.SRV_SRVSTRCD,1,2) = '" . substr($postData['sabisuchimu'], 0, 2) . "'" . " \r\n";
        }
        //初度登録
        if ($postData['shonendotorokuym'] != "") {
            $strSQL .= "   AND  M41C03.FRGMH = '" . $postData['shonendotorokuym'] . "'" . " \r\n";
        }
        //車検満了日
        if ($postData["shakenmanryoFrom"] != "" && $postData["shakenmanryoTo"] == "") {
            $strSQL .= "   AND  M41C03.VCLIPEDT >= '@shakenmanryoFrom'  " . " \r\n";
        }
        if ($postData["shakenmanryoFrom"] == "" && $postData["shakenmanryoTo"] != "") {
            $strSQL .= "   AND  M41C03.VCLIPEDT <= '@shakenmanryoTo' " . " \r\n";
        }
        if ($postData["shakenmanryoFrom"] != "" && $postData["shakenmanryoTo"] != "") {
            $strSQL .= "   AND  M41C03.VCLIPEDT BETWEEN '@shakenmanryoFrom' AND '@shakenmanryoTo'  " . " \r\n";
        }
        //パックdeメンテ現在加入
        if ($postData['pakkudementekanyu'] != "") {
            if ($postData['pakkudementekanyu'] == "01") {
                $strSQL .= "   AND  nvl(PDEM.PDEM,' ') = '01'" . " \r\n";
            }
            if ($postData['pakkudementekanyu'] != "01") {
                $strSQL .= "   AND  nvl(PDEM.PDEM,' ')  != '01'" . " \r\n";
            }
        }
        //（DZM）延長保証現在加入
        if ($postData['matsudaenchohoshokanyu'] != "") {
            if ($postData['matsudaenchohoshokanyu'] == "01") {
                $strSQL .= "   AND   nvl(ENC.ENCHO,' ') = '01'" . " \r\n";
            }
            if ($postData['matsudaenchohoshokanyu'] != "01") {
                $strSQL .= "   AND   nvl(ENC.ENCHO,' ')  != '01'" . " \r\n";
            }
        }
        //ボディコーティング現在加入
        if ($postData['bodeikoteingukanyu'] != "") {
            if ($postData['bodeikoteingukanyu'] == "01") {
                $strSQL .= "   AND  nvl(BODY.BODYCOAT,' ') = '01'" . " \r\n";
            }
            if ($postData['bodeikoteingukanyu'] != "01") {
                $strSQL .= "   AND  nvl(BODY.BODYCOAT,' ')  != '01'" . " \r\n";
            }
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

        if ($postData["nendaiFrom"] == "10") {
            $strSQL = str_replace("@nendaiFrom", $postData['nendaiFrom'] + 8, $strSQL);
        } else {
            $strSQL = str_replace("@nendaiFrom", $postData['nendaiFrom'], $strSQL);
        }
        $strSQL = str_replace("@carName", str_replace("'", "''", $postData['shashuNm']), $strSQL);
        $strSQL = str_replace("@nendaiTo", (int) $postData['nendaiTo'] + 9, $strSQL);
        $strSQL = str_replace("@shakenmanryoFrom", $postData['shakenmanryoFrom'], $strSQL);
        $strSQL = str_replace("@shakenmanryoTo", $postData['shakenmanryoTo'], $strSQL);
        $strSQL = str_replace("@dmhasshinkekkaDateFrom", $postData['dmhasshinkekkaDateFrom'], $strSQL);
        $strSQL = str_replace("@dmhasshinkekkaDateTo", $postData['dmhasshinkekkaDateTo'], $strSQL);

        return $strSQL;
    }

    //'***********************************************************************
    //'処 理 名：年月基準採番SQL
    //'関 数 名：fncRembanSelect_sql
    //'引 数   ：なし
    //'戻 り 値：SQL
    //'処理説明：年月基準採番SQL
    //'***********************************************************************
    public function fncRembanSelect_sql()
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  REMBAN, " . "\r\n";
        $strSql .= "  TO_CHAR(SYSDATE,'YYYYMM') AS SAIBAN_YM " . "\r\n";
        $strSql .= "  FROM " . "\r\n";
        $strSql .= "  t_ym_saiban " . "\r\n";
        $strSql .= "  WHERE" . "\r\n";
        $strSql .= " teburumei = 't_oshirasejoken'" . "\r\n";
        $strSql .= " AND  saiban_ym = TO_CHAR(SYSDATE,'YYYYMM')" . "\r\n";
        $strSql .= " FOR UPDATE NOWAIT" . "\r\n";

        return $strSql;

    }

    //'***********************************************************************
    //'処 理 名：年月基準採番SQL
    //'関 数 名：fncRembanSelect2_sql
    //'引 数   ：なし
    //'戻 り 値：SQL
    //'処理説明：年月基準採番SQL
    //'***********************************************************************
    public function fncRembanSelect2_sql()
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  REMBAN, " . "\r\n";
        $strSql .= "  TO_CHAR(SYSDATE,'YYYYMM') AS SAIBAN_YM " . "\r\n";
        $strSql .= "  FROM " . "\r\n";
        $strSql .= "  t_ym_saiban " . "\r\n";
        $strSql .= "  WHERE" . "\r\n";
        $strSql .= " teburumei = 't_oshirasejoken_saiban'" . "\r\n";
        $strSql .= " AND  saiban_ym = '999912'" . "\r\n";
        $strSql .= " FOR UPDATE NOWAIT" . "\r\n";

        return $strSql;

    }

    //'***********************************************************************
    //'処 理 名：年月基準採番新規登録SQL
    //'関 数 名：fncRembanInsert_sql
    //'引 数   ：なし
    //'戻 り 値：SQL
    //'処理説明：年月基準採番新規登録SQL
    //'***********************************************************************
    public function fncRembanInsert_sql($remban)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "INSERT " . "\r\n";
        $strSql .= "  INTO T_YM_SAIBAN " . "\r\n";
        $strSql .= " (" . "\r\n";
        $strSql .= " TEBURUMEI" . "\r\n";
        $strSql .= " ,SAIBAN_YM" . "\r\n";
        $strSql .= " ,REMBAN" . "\r\n";
        $strSql .= " ,UPD_DATE" . "\r\n";
        $strSql .= " ,UPD_USER_ID" . "\r\n";
        $strSql .= " ,CREATE_DATE" . "\r\n";
        $strSql .= " ,CREATE_USER_ID" . "\r\n";
        $strSql .= " ,DEL_FLG" . "\r\n";
        $strSql .= " ,DEL_USER_ID" . "\r\n";
        $strSql .= " )" . "\r\n";
        $strSql .= " VALUES (" . "\r\n";
        $strSql .= "  't_oshirasejoken'," . "\r\n";
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
    //'処 理 名：お知らせ条件データ新規登録SQL
    //'関 数 名：fncOshiraseJokenInsert
    //'引 数   ：お知らせ条件ID,メッセージID,全件送付,対象件数,画面上に入力された条件
    //'戻 り 値：SQL
    //'処理説明：お知らせ条件データ新規登録SQL
    //'***********************************************************************
    public function fncOshiraseJokenInsert_sql($OsId, $messid, $zenkensofu, $ObjectNumber, $postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "INSERT " . "\r\n";
        $strSql .= "  INTO T_OSHIRASEJOKEN " . "\r\n";
        $strSql .= " (" . "\r\n";
        $strSql .= " OSHIRASEJOKEN_ID" . "\r\n";
        $strSql .= " ,MESSEJI_ID" . "\r\n";
        $strSql .= " ,ZENKENSOFU_FLG" . "\r\n";
        $strSql .= " ,TAISHO_KENSU" . "\r\n";
        $strSql .= " ,SEIBETSU_KBN" . "\r\n";
        $strSql .= " ,NENDAI_FROM" . "\r\n";
        $strSql .= " ,NENDAI_TO" . "\r\n";
        $strSql .= " ,TANJYO_TUKI" . "\r\n";
        $strSql .= " ,KATEGORI" . "\r\n";
        $strSql .= " ,SHASHU" . "\r\n";
        $strSql .= " ,MAKER_CD" . "\r\n";
        $strSql .= " ,KOTEIKA_KBN" . "\r\n";
        $strSql .= " ,KANRI_CHIMU_CD" . "\r\n";
        $strSql .= " ,SABISU_CHIMU_CD" . "\r\n";
        $strSql .= " ,SHONENDO_TOROKU_YM" . "\r\n";
        $strSql .= " ,SHAKEN_MANRYO_YMD_FROM" . "\r\n";
        $strSql .= " ,SHAKEN_MANRYO_YMD_TO" . "\r\n";
        $strSql .= " ,PAKKUDEMENTE_KANYU_FLG" . "\r\n";
        $strSql .= " ,MATSUDAENCHOHOSHO_KANYU_FLG" . "\r\n";
        $strSql .= " ,BODEIKOTEINGU_KANYU_FLG" . "\r\n";
        $strSql .= " ,TENKEN1" . "\r\n";
        $strSql .= " ,TENKEN_YMD" . "\r\n";
        $strSql .= " ,TENKEN_SUTETASU" . "\r\n";
        $strSql .= " ,SHAKEN9" . "\r\n";
        $strSql .= " ,SHAKEN_YMD" . "\r\n";
        $strSql .= " ,SHAKEN_SUTETASU" . "\r\n";
        $strSql .= " ,DM_HASSHIN_KEKKA_DATE_FROM" . "\r\n";
        $strSql .= " ,DM_HASSHIN_KEKKA_DATE_TO" . "\r\n";
        $strSql .= " ,DM_HASSHIN_KEKKA_MEISHO" . "\r\n";
        $strSql .= " ,HYOJI_YMD" . "\r\n";
        $strSql .= " ,HYOJI_HM" . "\r\n";
        $strSql .= " ,UPD_STS_KBN" . "\r\n";
        $strSql .= " ,RENKEI_KBN" . "\r\n";
        $strSql .= " ,UPD_DATE" . "\r\n";
        $strSql .= " ,UPD_USER_ID" . "\r\n";
        $strSql .= " ,CREATE_DATE" . "\r\n";
        $strSql .= " ,CREATE_USER_ID" . "\r\n";
        $strSql .= " ,DEL_FLG" . "\r\n";
        $strSql .= " ,DEL_USER_ID" . "\r\n";
        $strSql .= " )" . "\r\n";
        $strSql .= " VALUES (" . "\r\n";
        //お知らせ条件ID
        $strSql .= "  '" . $OsId . "'," . "\r\n";
        //メッセージID
        $strSql .= "  '" . $messid . "'," . "\r\n";
        //全件送付フラグ
        $strSql .= "  '" . $zenkensofu . "'," . "\r\n";
        //対象件数
        $strSql .= "  '" . $ObjectNumber . "'," . "\r\n";
        //性別区分
        $strSql .= "  '" . $postData['seibetsu'] . "'," . "\r\n";
        //年代（自）
        $strSql .= "  '" . $postData['nendaiFrom'] . "'," . "\r\n";
        //年代（至）
        $strSql .= "  '" . $postData['nendaiTo'] . "'," . "\r\n";
        //誕生月
        $strSql .= "  '" . $postData['tanjyotuki'] . "'," . "\r\n";
        //カテゴリ
        $strSql .= "  '" . $postData['kategori'] . "'," . "\r\n";
        //車種
        $strSql .= "  '" . $postData['shashuNm'] . "'," . "\r\n";
        //メーカーコード
        $strSql .= "  '" . $postData['makerNm'] . "'," . "\r\n";
        //固定化区分
        $strSql .= "  '" . $postData['koteikakbn'] . "'," . "\r\n";
        //管理チームコード
        $strSql .= "  '" . $postData['kanrichimu'] . "'," . "\r\n";
        //サービスチームコード
        $strSql .= "  '" . $postData['sabisuchimu'] . "'," . "\r\n";
        //初度登録年月
        $strSql .= "  '" . $postData['shonendotorokuym'] . "'," . "\r\n";
        //車検満了日（自）
        $strSql .= "  '" . $postData['shakenmanryoFrom'] . "'," . "\r\n";
        //車検満了日（至）
        $strSql .= "  '" . $postData['shakenmanryoTo'] . "'," . "\r\n";
        //パックdeメンテ現在加入フラグ
        $strSql .= "  '" . $postData['pakkudementekanyu'] . "'," . "\r\n";
        //（DZM）延長保証現在加入フラグ
        $strSql .= "  '" . $postData['matsudaenchohoshokanyu'] . "'," . "\r\n";
        //ボディコーティング現在加入フラグ
        $strSql .= "  '" . $postData['bodeikoteingukanyu'] . "'," . "\r\n";
        //商品1コード（点検）
        $strSql .= "  '" . $postData['tenken'] . "'," . "\r\n";
        //商品1（年月）
        $strSql .= "  '" . $postData['tenkenymd'] . "'," . "\r\n";
        //商品1（ステータス）
        $strSql .= "  '" . $postData['tenkensutetasu'] . "'," . "\r\n";
        //商品9コード（車検）
        $strSql .= "  '" . $postData['shaken'] . "'," . "\r\n";
        //商品9（年月）
        $strSql .= "  '" . $postData['shakenymd'] . "'," . "\r\n";
        //商品9（ステータス）
        $strSql .= "  '" . $postData['shakensutetasu'] . "'," . "\r\n";
        //車点検ＤＭ発信結果日時（自）
        $strSql .= "  '" . $postData['dmhasshinkekkaDateFrom'] . "'," . "\r\n";
        //車点検ＤＭ発信結果日時（至）
        $strSql .= "  '" . $postData['dmhasshinkekkaDateTo'] . "'," . "\r\n";
        //車点検ＤＭ発信結果タイプ名称
        $strSql .= "  '" . $postData['dmhasshinkekkameisho'] . "'," . "\r\n";
        //表示日
        $strSql .= "  '" . $postData['hyojiymd'] . "'," . "\r\n";
        //表示時間
        $strSql .= "  '" . $postData['hyojihm'] . "'," . "\r\n";
        //更新区分
        $strSql .= "  '01'," . "\r\n";
        //連携区分
        $strSql .= "  '00'," . "\r\n";
        //更新日時
        $strSql .= "  SYSDATE," . "\r\n";
        //更新ユーザーID
        $strSql .= " '" . $this->SessionComponent->read('login_user') . "'," . "\r\n";
        //作成日時
        $strSql .= "  SYSDATE," . "\r\n";
        //作成ユーザーID
        $strSql .= " '" . $this->SessionComponent->read('login_user') . "'," . "\r\n";
        //削除フラグ
        $strSql .= " '00'," . "\r\n";
        //削除ユーザーID
        $strSql .= "   ''" . "\r\n";
        $strSql .= "  )" . "\r\n";

        return $strSql;
    }

    //'***********************************************************************
    //'処 理 名：年月基準採番テーブル．連番を1インクリメント更新するSQL
    //'関 数 名：fncRembanUpdata_sql
    //'引 数   ：なし
    //'戻 り 値：SQL
    //'処理説明：年月基準採番テーブル．連番を1インクリメント更新するSQL
    //'***********************************************************************
    public function fncRembanUpdata_sql()
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "UPDATE T_YM_SAIBAN" . " \r\n";
        $strSql .= "SET" . " \r\n";
        $strSql .= "REMBAN = REMBAN + 1," . " \r\n";
        $strSql .= "UPD_DATE = SYSDATE," . " \r\n";
        $strSql .= "UPD_USER_ID = '" . $this->SessionComponent->read('login_user') . "'" . " \r\n";
        $strSql .= "WHERE TEBURUMEI = 't_oshirasejoken'" . " \r\n";
        $strSql .= "AND SAIBAN_YM = TO_CHAR(SYSDATE,'YYYYMM')" . " \r\n";

        return $strSql;
    }

    //'***********************************************************************
    //'処 理 名：お知らせ条件ワーク新規登録SQL
    //'関 数 名：fncWkOshiraseInsert_sql
    //'引 数   ：お知らせ条件ID,メッセージID,対象データ,画面上に入力された条件
    //'戻 り 値：SQL
    //'処理説明：お知らせ条件ワーク新規登録SQL
    //'***********************************************************************
    public function fncWkOshiraseInsert_sql($OsId, $messid, $ObjectData, $postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "INSERT " . "\r\n";
        $strSql .= "  INTO WK_OSHIRASE " . "\r\n";
        $strSql .= " ( " . "\r\n";
        $strSql .= " WK_OSHIRASE_NO " . "\r\n";
        $strSql .= ",OKYAKUSAMA_NO " . "\r\n";
        $strSql .= ",OSHIRASEJOKEN_ID " . "\r\n";
        $strSql .= ",MESSEJI_ID " . "\r\n";
        $strSql .= ",HYOJI_YMD " . "\r\n";
        $strSql .= ",HYOJI_HM " . "\r\n";
        $strSql .= ",VIN_WMIVDS " . "\r\n";
        $strSql .= ",VIN_VIS " . "\r\n";
        $strSql .= ",TENKEN_YM " . "\r\n";
        $strSql .= ",SHAKEN_MANRYO_YMD " . "\r\n";
        $strSql .= ",UPD_DATE " . "\r\n";
        $strSql .= ",UPD_USER_ID " . "\r\n";
        $strSql .= ",CREATE_DATE " . "\r\n";
        $strSql .= ",CREATE_USER_ID " . "\r\n";
        $strSql .= ",DEL_FLG " . "\r\n";
        $strSql .= ",DEL_USER_ID " . "\r\n";
        $strSql .= " )" . "\r\n";
        $strSql .= " VALUES (" . "\r\n";
        //お知らせワーク連番
        $strSql .= "  Lpad(oshirase_jouken_seq.NEXTVAL,6,'0')," . "\r\n";
        //お客様No
        $strSql .= "  '" . $ObjectData['DLRCSRNO'] . "'," . "\r\n";
        //お知らせ条件ID
        $strSql .= "  '" . $OsId . "'," . "\r\n";
        //メッセージID
        $strSql .= "  '" . $messid . "'," . "\r\n";
        //表示日
        $strSql .= "  '" . $postData['hyojiymd'] . "'," . "\r\n";
        //表示時間
        $strSql .= "  '" . $postData['hyojihm'] . "'," . "\r\n";
        //VIN-WMIVDS
        $strSql .= "  '" . $ObjectData['VIN_WMIVDS'] . "'," . "\r\n";
        //VIN_VIS
        $strSql .= "  '" . $ObjectData['VIN_VIS'] . "'," . "\r\n";
        //点検年月
        $strSql .= "  '" . $ObjectData['SYOHIN01_EVT_YM'] . "'," . "\r\n";
        //車検満了日
        $strSql .= "  '" . $ObjectData['VCLIPEDT'] . "'," . "\r\n";
        //更新日時
        $strSql .= "  sysdate," . "\r\n";
        //更新ユーザーID
        $strSql .= " '" . $this->SessionComponent->read('login_user') . "'," . "\r\n";
        //作成日時
        $strSql .= "  sysdate," . "\r\n";
        //作成ユーザーID
        $strSql .= " '" . $this->SessionComponent->read('login_user') . "'," . "\r\n";
        //削除フラグ
        $strSql .= " '00'," . "\r\n";
        //削除ユーザーID
        $strSql .= "   ''" . "\r\n";
        $strSql .= "  )" . "\r\n";

        return $strSql;
    }

    //'***********************************************************************
    //'処 理 名：更新対象データを検索SQL
    //'関 数 名：fncOshirasejokenIdSelect_sql
    //'引 数   ：お知らせ条件ID
    //'戻 り 値：SQL
    //'処理説明：更新対象データを検索SQL
    //'***********************************************************************
    public function fncOshirasejokenIdSelect_sql($oshiraseId)
    {
        $strSql = "";
        $strSql .= "SELECT" . "\r\n";
        $strSql .= "  OSHIRASEJOKEN_ID, " . "\r\n";
        $strSql .= "  RENKEI_KBN, " . "\r\n";
        $strSql .= "  UPD_DATE " . "\r\n";
        $strSql .= "  FROM " . "\r\n";
        $strSql .= "  t_oshirasejoken " . "\r\n";
        $strSql .= "  WHERE" . "\r\n";
        $strSql .= " OSHIRASEJOKEN_ID = '" . $oshiraseId . "'" . "\r\n";
        $strSql .= " FOR UPDATE NOWAIT" . "\r\n";

        return $strSql;
    }

    //'***********************************************************************
    //'処 理 名：お知らせ条件データ更新SQL
    //'関 数 名：fncOshiraseJokenUpdata_sql
    //'引 数   ：お知らせ条件ID,メッセージID,全件送付,対象件数,画面上に入力された条件
    //'戻 り 値：SQL
    //'処理説明：お知らせ条件データ更新SQL
    //'***********************************************************************
    public function fncOshiraseJokenUpdata_sql($oshiraseId, $messid, $zenkensofu, $ObjectNumber, $postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "UPDATE T_OSHIRASEJOKEN" . " \r\n";
        $strSql .= "SET" . " \r\n";
        //メッセージID
        $strSql .= "MESSEJI_ID = '" . $messid . "'," . " \r\n";
        //全件送付フラグ
        $strSql .= "ZENKENSOFU_FLG = '" . $zenkensofu . "'," . " \r\n";
        //対象件数
        $strSql .= "TAISHO_KENSU = '" . $ObjectNumber . "'," . " \r\n";
        //性別区分
        $strSql .= " SEIBETSU_KBN = '" . $postData['seibetsu'] . "'," . "\r\n";
        //年代（自）
        $strSql .= " NENDAI_FROM = '" . $postData['nendaiFrom'] . "'," . "\r\n";
        //年代（至）
        $strSql .= " NENDAI_TO = '" . $postData['nendaiTo'] . "'," . "\r\n";
        //誕生月
        $strSql .= " TANJYO_TUKI = '" . $postData['tanjyotuki'] . "'," . "\r\n";
        //カテゴリ
        $strSql .= " KATEGORI = '" . $postData['kategori'] . "'," . "\r\n";
        //車種
        $strSql .= " SHASHU = '" . $postData['shashuNm'] . "'," . "\r\n";
        //メーカーコード
        $strSql .= " MAKER_CD = '" . $postData['makerNm'] . "'," . "\r\n";
        //固定化区分
        $strSql .= " KOTEIKA_KBN = '" . $postData['koteikakbn'] . "'," . "\r\n";
        //管理チームコード
        $strSql .= " KANRI_CHIMU_CD = '" . $postData['kanrichimu'] . "'," . "\r\n";
        //サービスチームコード
        $strSql .= " SABISU_CHIMU_CD = '" . $postData['sabisuchimu'] . "'," . "\r\n";
        //初度登録年月
        $strSql .= " SHONENDO_TOROKU_YM = '" . $postData['shonendotorokuym'] . "'," . "\r\n";
        //車検満了日（自）
        $strSql .= " SHAKEN_MANRYO_YMD_FROM = '" . $postData['shakenmanryoFrom'] . "'," . "\r\n";
        //車検満了日（至）
        $strSql .= " SHAKEN_MANRYO_YMD_TO = '" . $postData['shakenmanryoTo'] . "'," . "\r\n";
        //パックdeメンテ現在加入フラグ
        $strSql .= " PAKKUDEMENTE_KANYU_FLG = '" . $postData['pakkudementekanyu'] . "'," . "\r\n";
        //（DZM）延長保証現在加入フラグ
        $strSql .= " MATSUDAENCHOHOSHO_KANYU_FLG = '" . $postData['matsudaenchohoshokanyu'] . "'," . "\r\n";
        //ボディコーティング現在加入フラグ
        $strSql .= " BODEIKOTEINGU_KANYU_FLG = '" . $postData['bodeikoteingukanyu'] . "'," . "\r\n";
        //商品1コード（点検）
        $strSql .= " TENKEN1 = '" . $postData['tenken'] . "'," . "\r\n";
        //商品1（年月）
        $strSql .= " TENKEN_YMD = '" . $postData['tenkenymd'] . "'," . "\r\n";
        //商品1（ステータス）
        $strSql .= " TENKEN_SUTETASU = '" . $postData['tenkensutetasu'] . "'," . "\r\n";
        //商品9コード（車検）
        $strSql .= " SHAKEN9 = '" . $postData['shaken'] . "'," . "\r\n";
        //商品9（年月）
        $strSql .= " SHAKEN_YMD = '" . $postData['shakenymd'] . "'," . "\r\n";
        //商品9（ステータス）
        $strSql .= " SHAKEN_SUTETASU = '" . $postData['shakensutetasu'] . "'," . "\r\n";
        //車点検ＤＭ発信結果日時（自）
        $strSql .= " DM_HASSHIN_KEKKA_DATE_FROM = '" . $postData['dmhasshinkekkaDateFrom'] . "'," . "\r\n";
        //車点検ＤＭ発信結果日時（至）
        $strSql .= " DM_HASSHIN_KEKKA_DATE_TO = '" . $postData['dmhasshinkekkaDateTo'] . "'," . "\r\n";
        //車点検ＤＭ発信結果タイプ名称
        $strSql .= " DM_HASSHIN_KEKKA_MEISHO = '" . $postData['dmhasshinkekkameisho'] . "'," . "\r\n";
        //表示日
        $strSql .= " HYOJI_YMD = '" . $postData['hyojiymd'] . "'," . "\r\n";
        //表示時間
        $strSql .= " HYOJI_HM = '" . $postData['hyojihm'] . "'," . "\r\n";
        //更新区分
        $strSql .= " UPD_STS_KBN = '02'," . "\r\n";
        //連携区分
        $strSql .= " RENKEI_KBN = '00'," . "\r\n";
        //更新日時
        $strSql .= " UPD_DATE = SYSDATE," . "\r\n";
        //更新ユーザーID
        $strSql .= " UPD_USER_ID = '" . $this->SessionComponent->read('login_user') . "'" . "\r\n";
        $strSql .= "WHERE OSHIRASEJOKEN_ID = '" . $oshiraseId . "'" . " \r\n";

        return $strSql;
    }

    //'***********************************************************************
    //'処 理 名：お知らせ条件ワークの削除SQL
    //'関 数 名：fncWkOshiraseDelet_sql
    //'引 数   ：お知らせ条件ID
    //'戻 り 値：SQL
    //'処理説明：お知らせ条件ワークの削除SQL
    //'***********************************************************************
    public function fncWkOshiraseDelet_sql($oshiraseId)
    {
        $strSql = "";
        $strSql .= "DELETE" . "\r\n";
        $strSql .= "  FROM  " . "\r\n";
        $strSql .= "  wk_oshirase " . "\r\n";
        $strSql .= "  WHERE" . "\r\n";
        $strSql .= " OSHIRASEJOKEN_ID = '" . $oshiraseId . "'" . "\r\n";

        return $strSql;
    }

    //'***********************************************************************
    //'処 理 名：お知らせ条件データを更新するSQL
    //'関 数 名：fncOshiraseJokenDelet_sql
    //'引 数   ：お知らせ条件ID
    //'戻 り 値：SQL
    //'処理説明：お知らせ条件データを更新するSQL
    //'***********************************************************************
    public function fncOshiraseJokenDelet_sql($oshiraseId)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSql = "";
        $strSql .= "UPDATE T_OSHIRASEJOKEN" . " \r\n";
        $strSql .= "SET" . " \r\n";
        //更新区分
        $strSql .= "UPD_STS_KBN = '09'," . " \r\n";
        //更新日時
        $strSql .= " UPD_DATE = SYSDATE," . "\r\n";
        //削除フラグ
        $strSql .= "DEL_FLG = '01'," . " \r\n";
        //削除ユーザーID
        $strSql .= "DEL_USER_ID = '" . $this->SessionComponent->read('login_user') . "'" . " \r\n";
        $strSql .= "WHERE OSHIRASEJOKEN_ID = '" . $oshiraseId . "'" . " \r\n";

        return $strSql;
    }

}