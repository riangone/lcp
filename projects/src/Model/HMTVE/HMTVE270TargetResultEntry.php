<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20240326    		受入検証.xlsx NO2     					車種を追加してください             		 LHB
 * 20240611    		202406_データ集計システム_CX-80追加        CX-80追加            		 		 LHB
 * 20240712    		CX-80追加判断                           CX-80追加判断            		 	     LHB
 * 20240909    		20240909_error.log                           20240909_error.log            	   LHB
 * 20251118         202511_データ集計システム_機能追加要望   目標と実績＿改修イメージ                caina
 * -------------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
use App\Model\HMTVE\Component\ClsComFncHMTVE;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE270TargetResultEntry extends ClsComDb
{
    private $ClsComFncHMTVE;
    //店舗名を抽出する
    public function GET_BUSYO_CD($BusyoCD)
    {
        $strSQL = "";
        $strSQL .= "SELECT MST.BUSYO_CD ,CASE WHEN BUS.BUSYO_CD IN ('181','183') THEN BUS.BUSYO_RYKNM ELSE MST.BUSYO_RYKNM END AS BUSYO_RYKNM " . "\r\n";
        $strSQL .= "FROM HBUSYO MST	INNER JOIN (SELECT BUSYO_CD ,BUSYO_RYKNM, (CASE WHEN HDT_TENPO_CD IS NOT NULL THEN HDT_TENPO_CD ELSE BUSYO_CD END) V_TENPO FROM HBUSYO) BUS " . "\r\n";
        $strSQL .= "ON MST.BUSYO_CD = BUS.V_TENPO " . "\r\n";
        $strSQL .= "WHERE MST.HDT_TENPO_DISP_NO IS NOT NULL " . "\r\n";
        $strSQL .= "AND BUS.BUSYO_CD = '@BUSYOCD'" . "\r\n";

        $strSQL = str_replace("@BUSYOCD", $BusyoCD, $strSQL);

        return parent::select($strSQL);
    }

    //登録データを取得する
    public function strSQL2($params)
    {

        $strSQL = "";
        $strSQL .= " SELECT TR.TAISYOU_YM" . "\r\n";
        $strSQL .= " ,      TR.BUSYO_CD " . "\r\n";
        if ($params['PatternID'] == $params['CONST_ADMIN_PTN_NO'] || $params['PatternID'] == $params['CONST_HONBU_PTN_NO'] || $params['PatternID'] == $params['CONST_TESTER_PTN_NO']) {
        } else {
            $strSQL .= " ,      MST.BUSYO_RYKNM " . "\r\n";
        }
        $strSQL .= " ,      TR.GENRI_MOKUHYO " . "\r\n";
        $strSQL .= " ,      TR.GENRI_YOSOU " . "\r\n";
        $strSQL .= " ,      (TR.GENRI_JISSEKI - TR.GENRI_YOSOU) GENRI_SABUN " . "\r\n";
        $strSQL .= " ,      TR.GENRI_JISSEKI " . "\r\n";
        $strSQL .= " ,      TR.URIMOKU_MAIN " . "\r\n";
        $strSQL .= " ,      TR.URIMOKU_TACHANEL " . "\r\n";
        $strSQL .= " ,      TR.URIYOSOU_MAIN_Y " . "\r\n";
        $strSQL .= " ,      TR.URIYOSOU_MAIN_S " . "\r\n";
        $strSQL .= " ,      TR.URIYOSOU_KEI_Y " . "\r\n";
        $strSQL .= " ,      TR.URIYOSOU_KEI_S " . "\r\n";
        $strSQL .= " ,      TR.URIYOSOU_VOLVO_Y " . "\r\n";
        $strSQL .= " ,      TR.URIYOSOU_VOLVO_S " . "\r\n";
        $strSQL .= " ,      TR.URIYOSOU_SONOTA_Y " . "\r\n";
        $strSQL .= " ,      TR.URIYOSOU_SONOTA_S " . "\r\n";
        $strSQL .= " ,      (TR.URIYOSOU_MAIN_Y + TR.URIYOSOU_KEI_Y + URIYOSOU_VOLVO_Y + TR.URIYOSOU_SONOTA_Y) URI_Y_GK " . "\r\n";
        $strSQL .= " ,      (TR.URIYOSOU_MAIN_S + TR.URIYOSOU_KEI_S + URIYOSOU_VOLVO_S + TR.URIYOSOU_SONOTA_S) URI_S_GK " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_JIJI_Y " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_JIJI_S " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_FUKUSHI_Y " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_FUKUSHI_S " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_TAJI_Y " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_TAJI_S " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_JITA_Y " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_JITA_S " . "\r\n";
        $strSQL .= " ,      (TR.TRKDAISU_JIJI_Y + TR.TRKDAISU_FUKUSHI_Y + TR.TRKDAISU_TAJI_Y - TR.TRKDAISU_JITA_Y) TRK_Y_GK " . "\r\n";
        $strSQL .= " ,      (TR.TRKDAISU_JIJI_S + TR.TRKDAISU_FUKUSHI_S + TR.TRKDAISU_TAJI_S - TR.TRKDAISU_JITA_S) TRK_S_GK " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_KEI_JIJI_Y " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_KEI_JIJI_S " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_KEI_TAJI_Y " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_KEI_TAJI_S " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_KEI_JITA_Y " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_KEI_JITA_S " . "\r\n";
        $strSQL .= " ,      NVL(TR.TRKDAISU_KEI_FUKUSHI_Y,0) TRKDAISU_KEI_FUKUSHI_Y " . "\r\n";
        $strSQL .= " ,      NVL(TR.TRKDAISU_KEI_FUKUSHI_S,0) TRKDAISU_KEI_FUKUSHI_S " . "\r\n";
        $strSQL .= " ,      TR.KEI_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.KEI_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_RENTA_Y " . "\r\n";
        $strSQL .= " ,      TR.TRKDAISU_RENTA_S " . "\r\n";
        $strSQL .= " ,      TR.DEMIO_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.DEMIO_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.M2G_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.M2G_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.CX3_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.CX3_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.VRW_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.VRW_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.M3S_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.M3S_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.M3H_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.M3H_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.M6S_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.M6S_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.M6W_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.M6W_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.ATENZA_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.ATENZA_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.AXS_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.AXS_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.PREMACY_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.PREMACY_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.BIANTE_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.BIANTE_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.MPV_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.MPV_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.CX5_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.CX5_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      NVL(TR.CX8_TRK_DAISU_Y,0) CX8_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      NVL(TR.CX8_TRK_DAISU_S,0) CX8_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      NVL(TR.CX30_TRK_DAISU_Y,0) CX30_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      NVL(TR.CX30_TRK_DAISU_S,0) CX30_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      NVL(TR.MX30_TRK_DAISU_Y,0) MX30_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      NVL(TR.MX30_TRK_DAISU_S,0) MX30_TRK_DAISU_S " . "\r\n";
        // 20240326 LHB INS S
        $strSQL .= " ,      NVL(TR.CX60_TRK_DAISU_Y,0) CX60_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      NVL(TR.CX60_TRK_DAISU_S,0) CX60_TRK_DAISU_S " . "\r\n";
        // 20240326 LHB INS E
        // 20240611 LHB INS S
        // 20240712 LHB UPD S
        // $strSQL .= " ,      NVL(TR.CX80_TRK_DAISU_Y,0) CX80_TRK_DAISU_Y " . "\r\n";
        // $strSQL .= " ,      NVL(TR.CX80_TRK_DAISU_S,0) CX80_TRK_DAISU_S " . "\r\n";
        $isExit = $this->checkCX80SQL();
        if ($isExit['data'][0]["ISEXIT"] === '1') {
            $strSQL .= " ,      NVL(TR.CX80_TRK_DAISU_Y,0) CX80_TRK_DAISU_Y " . "\r\n";
            $strSQL .= " ,      NVL(TR.CX80_TRK_DAISU_S,0) CX80_TRK_DAISU_S " . "\r\n";
        }
        // 20240712 LHB UPD E
        // 20240611 LHB INS E
        $strSQL .= " ,      TR.LDSTAR_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.LDSTAR_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.FMV_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.FMV_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.BONGO_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.BONGO_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.TT_TRK_DAISU_Y " . "\r\n";
        $strSQL .= " ,      TR.TT_TRK_DAISU_S " . "\r\n";
        $strSQL .= " ,      TR.URIMOKU_CHUKO_CHOKU " . "\r\n";
        $strSQL .= " ,      TR.URIMOKU_CHUKO_GYOBAI " . "\r\n";
        $strSQL .= " ,      TR.URIYOSOU_CHUKO_CHOKU_Y " . "\r\n";
        $strSQL .= " ,      TR.URIYOSOU_CHUKO_CHOKU_S " . "\r\n";
        $strSQL .= " ,      TR.URIYOSOU_CHUKO_GYOBAI_Y " . "\r\n";
        $strSQL .= " ,      TR.URIYOSOU_CHUKO_GYOBAI_S " . "\r\n";
        $strSQL .= " ,      (TR.URIYOSOU_CHUKO_CHOKU_Y + TR.URIYOSOU_CHUKO_GYOBAI_Y) URI_Y_CK " . "\r\n";
        $strSQL .= " ,      (TR.URIYOSOU_CHUKO_CHOKU_S + TR.URIYOSOU_CHUKO_GYOBAI_S) URI_S_CF " . "\r\n";
        $strSQL .= " ,      TR.SHURI_HOKEN " . "\r\n";
        $strSQL .= " ,      TR.SHURI_LEASE " . "\r\n";
        $strSQL .= " ,      TR.SHURI_LOAN " . "\r\n";
        $strSQL .= " ,      TR.SHURI_KIBOU " . "\r\n";
        $strSQL .= " ,      TR.SHURI_P753 " . "\r\n";
        $strSQL .= " ,      TR.SHURI_PMENTE " . "\r\n";
        $strSQL .= " ,      TR.SHURI_BODYCOAT " . "\r\n";
        $strSQL .= " ,      TR.SHURI_JAF " . "\r\n";
        $strSQL .= " ,      TR.SHURI_OSS " . "\r\n";
        $strSQL .= " ,      to_char(TR.CREATE_DATE,'yyyy/mm/dd hh24:mi:ss') as CREATE_DATE " . "\r\n";
        $strSQL .= " FROM   HDTTARGETRESULT TR " . "\r\n";
        if ($params['PatternID'] == $params['CONST_ADMIN_PTN_NO'] || $params['PatternID'] == $params['CONST_HONBU_PTN_NO'] || $params['PatternID'] == $params['CONST_TESTER_PTN_NO']) {
        } else {
            $strSQL .= " INNER JOIN HBUSYO MST " . "\r\n";
            $strSQL .= " ON     MST.HDT_TENPO_DISP_NO IS NOT NULL " . "\r\n";
            $strSQL .= " INNER JOIN (SELECT BUSYO_CD " . "\r\n";
            $strSQL .= "            ,      HDT_TENPO_CD V_TENPO " . "\r\n";
            $strSQL .= "            FROM HBUSYO) BUS " . "\r\n";
            $strSQL .= " ON     MST.BUSYO_CD = BUS.V_TENPO " . "\r\n";
            $strSQL .= " AND    TR.BUSYO_CD = BUS.BUSYO_CD " . "\r\n";
        }
        $strSQL .= " WHERE TR.TAISYOU_YM = '@TAISYOU_YM'" . "\r\n";
        $strSQL .= " AND    TR.BUSYO_CD = '@BUSYOCD' " . "\r\n";
        if ($params['PatternID'] == $params['CONST_ADMIN_PTN_NO'] || $params['PatternID'] == $params['CONST_HONBU_PTN_NO'] || $params['PatternID'] == $params['CONST_TESTER_PTN_NO']) {
            $strSQL = str_replace("@BUSYOCD", "999", $strSQL);
        } else {
            $strSQL = str_replace("@BUSYOCD", $params['BUSYOCD'], $strSQL);
        }
        $strSQL = str_replace("@TAISYOU_YM", $params['TAISYOU_YM'], $strSQL);

        return parent::select($strSQL);
    }

    //存在チェックを行う
    public function strcheckSQL($params)
    {
        $strSQL = "";
        $strSQL .= " SELECT UPD_PRG_ID" . "\r\n";
        $strSQL .= " FROM   HDTTARGETRESULT" . "\r\n";
        $strSQL .= " WHERE	TAISYOU_YM = '@TAISYOU_YM'" . "\r\n";
        $strSQL .= " AND    BUSYO_CD = '@BUSYOCD' " . "\r\n";

        $strSQL = str_replace("@TAISYOU_YM", $params['TAISYOU_YM'], $strSQL);
        if ($params['PatternID'] == $params['CONST_ADMIN_PTN_NO'] || $params['PatternID'] == $params['CONST_HONBU_PTN_NO'] || $params['PatternID'] == $params['CONST_TESTER_PTN_NO']) {
            $strSQL = str_replace("@BUSYOCD", "999", $strSQL);
        } else {
            $strSQL = str_replace("@BUSYOCD", $params['BUSYOCD'], $strSQL);
        }
        return parent::select($strSQL);
    }

    //目標と実績データの更新処理を行う
    public function DEL_SQL($params)
    {
        $strSQL = "";
        $strSQL .= "	DELETE FROM HDTTARGETRESULT	 " . "\r\n";
        $strSQL .= "     WHERE TAISYOU_YM ='@TAISYOU_YM' " . "\r\n";
        $strSQL .= " AND    BUSYO_CD = '@BUSYOCD' " . "\r\n";

        $strSQL = str_replace("@TAISYOU_YM", $params['TAISYOU_YM'], $strSQL);
        if ($params['PatternID'] == $params['CONST_ADMIN_PTN_NO'] || $params['PatternID'] == $params['CONST_HONBU_PTN_NO'] || $params['PatternID'] == $params['CONST_TESTER_PTN_NO']) {
            $strSQL = str_replace("@BUSYOCD", "999", $strSQL);
        } else {
            $strSQL = str_replace("@BUSYOCD", $params['BUSYOCD'], $strSQL);
        }
        return parent::delete($strSQL);
    }

    //目標と実績データに追加するSQLを作成する
    public function insertSQL($params, $datas)
    {
        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = "";
        $strSQL .= " INSERT INTO HDTTARGETRESULT " . "\r\n";
        $strSQL .= " (TAISYOU_YM,                " . "\r\n";
        $strSQL .= "  BUSYO_CD,                  " . "\r\n";
        $strSQL .= "  GENRI_MOKUHYO,             " . "\r\n";
        $strSQL .= "  GENRI_YOSOU,               " . "\r\n";
        $strSQL .= "  GENRI_JISSEKI,             " . "\r\n";
        $strSQL .= "  URIMOKU_MAIN,              " . "\r\n";
        $strSQL .= "  URIMOKU_TACHANEL,          " . "\r\n";
        $strSQL .= "  URIYOSOU_MAIN_Y,            " . "\r\n";

        $strSQL .= "  URIYOSOU_MAIN_S,            " . "\r\n";
        $strSQL .= "  URIYOSOU_KEI_Y ,            " . "\r\n";
        $strSQL .= "  URIYOSOU_KEI_S ,            " . "\r\n";
        $strSQL .= "  URIYOSOU_VOLVO_Y,           " . "\r\n";
        $strSQL .= "  URIYOSOU_VOLVO_S,           " . "\r\n";
        $strSQL .= "  URIYOSOU_SONOTA_Y,          " . "\r\n";
        $strSQL .= "  URIYOSOU_SONOTA_S,          " . "\r\n";
        $strSQL .= "  TRKDAISU_JIJI_Y  ,          " . "\r\n";
        $strSQL .= "  TRKDAISU_JIJI_S  ,          " . "\r\n";
        $strSQL .= "  TRKDAISU_FUKUSHI_Y ,        " . "\r\n";
        $strSQL .= "  TRKDAISU_FUKUSHI_S ,        " . "\r\n";

        $strSQL .= "  TRKDAISU_TAJI_Y ,           " . "\r\n";
        $strSQL .= "  TRKDAISU_TAJI_S ,           " . "\r\n";
        $strSQL .= "  TRKDAISU_JITA_Y  ,          " . "\r\n";
        $strSQL .= "  TRKDAISU_JITA_S   ,         " . "\r\n";

        $strSQL .= "  TRKDAISU_KEI_JIJI_Y   ,     " . "\r\n";
        $strSQL .= "  TRKDAISU_KEI_JIJI_S   ,     " . "\r\n";
        $strSQL .= "  TRKDAISU_KEI_TAJI_Y   ,     " . "\r\n";
        $strSQL .= "  TRKDAISU_KEI_TAJI_S   ,     " . "\r\n";
        $strSQL .= "  TRKDAISU_KEI_JITA_Y   ,     " . "\r\n";
        $strSQL .= "  TRKDAISU_KEI_JITA_S   ,     " . "\r\n";

        $strSQL .= "  TRKDAISU_KEI_FUKUSHI_Y   ,     " . "\r\n";
        $strSQL .= "  TRKDAISU_KEI_FUKUSHI_S   ,     " . "\r\n";

        $strSQL .= "  TRKDAISU_RENTA_Y   ,        " . "\r\n";
        $strSQL .= "  TRKDAISU_RENTA_S   ,        " . "\r\n";

        $strSQL .= "  DEMIO_TRK_DAISU_Y ,         " . "\r\n";
        $strSQL .= "  DEMIO_TRK_DAISU_S  ,        " . "\r\n";

        $strSQL .= "  M2G_TRK_DAISU_Y ,         " . "\r\n";
        $strSQL .= "  M2G_TRK_DAISU_S  ,        " . "\r\n";

        $strSQL .= "  CX3_TRK_DAISU_Y,         " . "\r\n";
        $strSQL .= "  CX3_TRK_DAISU_S,         " . "\r\n";

        $strSQL .= "  M3S_TRK_DAISU_Y   ,         " . "\r\n";
        $strSQL .= "  M3S_TRK_DAISU_S   ,         " . "\r\n";
        $strSQL .= "  M3H_TRK_DAISU_Y   ,         " . "\r\n";
        $strSQL .= "  M3H_TRK_DAISU_S   ,         " . "\r\n";

        $strSQL .= "  M6S_TRK_DAISU_Y   ,         " . "\r\n";
        $strSQL .= "  M6S_TRK_DAISU_S   ,         " . "\r\n";
        $strSQL .= "  M6W_TRK_DAISU_Y   ,         " . "\r\n";
        $strSQL .= "  M6W_TRK_DAISU_S   ,         " . "\r\n";

        $strSQL .= "  ATENZA_TRK_DAISU_Y  ,       " . "\r\n";
        $strSQL .= "  ATENZA_TRK_DAISU_S ,        " . "\r\n";
        $strSQL .= "  AXS_TRK_DAISU_Y  ,          " . "\r\n";
        $strSQL .= "  AXS_TRK_DAISU_S ,           " . "\r\n";
        $strSQL .= "  BIANTE_TRK_DAISU_Y,         " . "\r\n";
        $strSQL .= "  BIANTE_TRK_DAISU_S,         " . "\r\n";
        $strSQL .= "  PREMACY_TRK_DAISU_Y ,       " . "\r\n";
        $strSQL .= "  PREMACY_TRK_DAISU_S,        " . "\r\n";
        $strSQL .= "  MPV_TRK_DAISU_Y  ,          " . "\r\n";
        $strSQL .= "  MPV_TRK_DAISU_S   ,         " . "\r\n";
        $strSQL .= "  CX5_TRK_DAISU_Y,         " . "\r\n";
        $strSQL .= "  CX5_TRK_DAISU_S,         " . "\r\n";
        $strSQL .= "  CX8_TRK_DAISU_Y,         " . "\r\n";
        $strSQL .= "  CX8_TRK_DAISU_S,         " . "\r\n";

        $strSQL .= "  CX30_TRK_DAISU_Y,         " . "\r\n";
        $strSQL .= "  CX30_TRK_DAISU_S,         " . "\r\n";

        $strSQL .= "  MX30_TRK_DAISU_Y,         " . "\r\n";
        $strSQL .= "  MX30_TRK_DAISU_S,         " . "\r\n";
        // 20240326 LHB INS S
        $strSQL .= "  CX60_TRK_DAISU_Y,         " . "\r\n";
        $strSQL .= "  CX60_TRK_DAISU_S,         " . "\r\n";
        // 20240326 LHB INS E
        // 20240611 LHB INS S
        // 20240712 LHB UPD S
        // $strSQL .= "  CX80_TRK_DAISU_Y,         " . "\r\n";
        // $strSQL .= "  CX80_TRK_DAISU_S,         " . "\r\n";
        $isExit = $this->checkCX80SQL();
        if ($isExit['data'][0]["ISEXIT"] === '1') {
            $strSQL .= "  CX80_TRK_DAISU_Y,         " . "\r\n";
            $strSQL .= "  CX80_TRK_DAISU_S,         " . "\r\n";
        }
        // 20240712 LHB UPD S
        // 20240611 LHB INS E
        $strSQL .= "  LDSTAR_TRK_DAISU_Y ,        " . "\r\n";
        $strSQL .= "  LDSTAR_TRK_DAISU_S,         " . "\r\n";
        $strSQL .= "  FMV_TRK_DAISU_Y  ,          " . "\r\n";
        $strSQL .= "  FMV_TRK_DAISU_S  ,          " . "\r\n";
        $strSQL .= "  BONGO_TRK_DAISU_Y ,         " . "\r\n";
        $strSQL .= "  BONGO_TRK_DAISU_S ,         " . "\r\n";
        $strSQL .= "  TTD_TRK_DAISU_Y  ,          " . "\r\n";

        $strSQL .= "  TTD_TRK_DAISU_S  ,          " . "\r\n";
        $strSQL .= "  TT_TRK_DAISU_Y  ,           " . "\r\n";
        $strSQL .= "  TT_TRK_DAISU_S  ,           " . "\r\n";
        $strSQL .= "  KEI_TRK_DAISU_Y  ,          " . "\r\n";
        $strSQL .= "  KEI_TRK_DAISU_S,            " . "\r\n";

        $strSQL .= "  URIMOKU_CHUKO_CHOKU,              " . "\r\n";
        $strSQL .= "  URIMOKU_CHUKO_GYOBAI,          " . "\r\n";
        $strSQL .= "  URIYOSOU_CHUKO_CHOKU_Y,            " . "\r\n";
        $strSQL .= "  URIYOSOU_CHUKO_CHOKU_S,            " . "\r\n";
        $strSQL .= "  URIYOSOU_CHUKO_GYOBAI_Y,            " . "\r\n";
        $strSQL .= "  URIYOSOU_CHUKO_GYOBAI_S,            " . "\r\n";

        $strSQL .= "  SHURI_HOKEN,                " . "\r\n";
        $strSQL .= "  SHURI_LEASE,                " . "\r\n";
        $strSQL .= "  SHURI_LOAN,                 " . "\r\n";
        $strSQL .= "  SHURI_KIBOU,                " . "\r\n";
        $strSQL .= "  SHURI_P753,                 " . "\r\n";
        $strSQL .= "  SHURI_PMENTE,               " . "\r\n";
        $strSQL .= "  SHURI_BODYCOAT,             " . "\r\n";
        $strSQL .= "  SHURI_JAF,                  " . "\r\n";
        $strSQL .= "  SHURI_OSS,               " . "\r\n";

        $strSQL .= "  UPD_DATE    ,               " . "\r\n";
        $strSQL .= "  CREATE_DATE ,               " . "\r\n";
        $strSQL .= "  UPD_SYA_CD  ,               " . "\r\n";
        $strSQL .= "  UPD_PRG_ID  ,               " . "\r\n";
        $strSQL .= "  UPD_CLT_NM )               " . "\r\n";

        $strSQL .= "  VALUES(                    " . "\r\n";
        $strSQL .= "  '@TAISYOU_YM'," . "\r\n";
        $strSQL .= "  '@BUSYO_CD' ,                 " . "\r\n";
        $strSQL .= "  @GENRI_MOKUHYO,            " . "\r\n";
        $strSQL .= "  @GENRI_YOSOU,              " . "\r\n";
        $strSQL .= "  @GENRI_JISSEKI,            " . "\r\n";
        $strSQL .= "  @URIMOKU_MAIN,             " . "\r\n";
        $strSQL .= "  @URIMOKU_TACHANEL,         " . "\r\n";
        $strSQL .= "  @URIYOSOU_MAIN_Y ,          " . "\r\n";

        $strSQL .= "  @URIYOSOU_MAIN_S  ,         " . "\r\n";
        $strSQL .= "  @URIYOSOU_KEI_Y   ,         " . "\r\n";
        $strSQL .= "  @URIYOSOU_KEI_S   ,         " . "\r\n";
        $strSQL .= "  @URIYOSOU_VOLVO_Y ,         " . "\r\n";
        $strSQL .= "  @URIYOSOU_VOLVO_S ,         " . "\r\n";
        $strSQL .= "  @URIYOSOU_SONOTA_Y ,        " . "\r\n";
        $strSQL .= "  @URIYOSOU_SONOTA_S ,        " . "\r\n";
        $strSQL .= "  @TRKDAISU_JIJI_Y  ,         " . "\r\n";
        $strSQL .= "  @TRKDAISU_JIJI_S   ,        " . "\r\n";
        $strSQL .= "  @TRKDAISU_FUKUSHI_Y  ,      " . "\r\n";
        $strSQL .= "  @TRKDAISU_FUKUSHI_S ,       " . "\r\n";

        $strSQL .= "  @TRKDAISU_TAJI_Y  ,         " . "\r\n";
        $strSQL .= "  @TRKDAISU_TAJI_S  ,         " . "\r\n";
        $strSQL .= "  @TRKDAISU_JITA_Y   ,        " . "\r\n";
        $strSQL .= "  @TRKDAISU_JITA_S   ,        " . "\r\n";

        $strSQL .= "  @TRKDAISU_KEI_JIJI_Y   ,    " . "\r\n";
        $strSQL .= "  @TRKDAISU_KEI_JIJI_S   ,    " . "\r\n";
        $strSQL .= "  @TRKDAISU_KEI_TAJI_Y   ,    " . "\r\n";
        $strSQL .= "  @TRKDAISU_KEI_TAJI_S   ,    " . "\r\n";
        $strSQL .= "  @TRKDAISU_KEI_JITA_Y   ,    " . "\r\n";
        $strSQL .= "  @TRKDAISU_KEI_JITA_S   ,    " . "\r\n";
        $strSQL .= "  @TRKDAISU_KEI_FUKUSHI_Y   ,    " . "\r\n";
        $strSQL .= "  @TRKDAISU_KEI_FUKUSHI_S   ,    " . "\r\n";

        $strSQL .= "  @TRKDAISU_RENTA_Y   ,       " . "\r\n";
        $strSQL .= "  @TRKDAISU_RENTA_S   ,       " . "\r\n";

        $strSQL .= "  @DEMIO_TRK_DAISU_Y  ,       " . "\r\n";
        $strSQL .= "  @DEMIO_TRK_DAISU_S  ,       " . "\r\n";

        $strSQL .= "  @M2G_TRK_DAISU_Y  ,       " . "\r\n";
        $strSQL .= "  @M2G_TRK_DAISU_S  ,       " . "\r\n";

        $strSQL .= "  @CX3_TRK_DAISU_Y,        " . "\r\n";
        $strSQL .= "  @CX3_TRK_DAISU_S,        " . "\r\n";
        $strSQL .= "  @M3S_TRK_DAISU_Y   ,        " . "\r\n";
        $strSQL .= "  @M3S_TRK_DAISU_S   ,        " . "\r\n";
        $strSQL .= "  @M3H_TRK_DAISU_Y   ,        " . "\r\n";
        $strSQL .= "  @M3H_TRK_DAISU_S   ,        " . "\r\n";
        $strSQL .= "  @M6S_TRK_DAISU_Y   ,        " . "\r\n";
        $strSQL .= "  @M6S_TRK_DAISU_S   ,        " . "\r\n";
        $strSQL .= "  @M6W_TRK_DAISU_Y   ,        " . "\r\n";
        $strSQL .= "  @M6W_TRK_DAISU_S   ,        " . "\r\n";
        $strSQL .= "  @ATENZA_TRK_DAISU_Y ,       " . "\r\n";
        $strSQL .= "  @ATENZA_TRK_DAISU_S  ,      " . "\r\n";
        $strSQL .= "  @AXS_TRK_DAISU_Y   ,        " . "\r\n";
        $strSQL .= "  @AXS_TRK_DAISU_S  ,         " . "\r\n";
        $strSQL .= "  @BIANTE_TRK_DAISU_Y,        " . "\r\n";
        $strSQL .= "  @BIANTE_TRK_DAISU_S,        " . "\r\n";
        $strSQL .= "  @PREMACY_TRK_DAISU_Y ,      " . "\r\n";
        $strSQL .= "  @PREMACY_TRK_DAISU_S ,      " . "\r\n";
        $strSQL .= "  @MPV_TRK_DAISU_Y   ,        " . "\r\n";
        $strSQL .= "  @MPV_TRK_DAISU_S   ,        " . "\r\n";
        $strSQL .= "  @CX5_TRK_DAISU_Y,        " . "\r\n";
        $strSQL .= "  @CX5_TRK_DAISU_S,        " . "\r\n";
        $strSQL .= "  @CX8_TRK_DAISU_Y,        " . "\r\n";
        $strSQL .= "  @CX8_TRK_DAISU_S,        " . "\r\n";

        $strSQL .= "  @CX30_TRK_DAISU_Y,        " . "\r\n";
        $strSQL .= "  @CX30_TRK_DAISU_S,        " . "\r\n";
        $strSQL .= "  @MX30_TRK_DAISU_Y,        " . "\r\n";
        $strSQL .= "  @MX30_TRK_DAISU_S,        " . "\r\n";
        // 20240326 LHB INS S
        $strSQL .= "  @CX60_TRK_DAISU_Y,         " . "\r\n";
        $strSQL .= "  @CX60_TRK_DAISU_S,         " . "\r\n";
        // 20240326 LHB INS E
        // 20240611 LHB INS S
        // 20240712 LHB UPD S
        // $strSQL .= "  @CX80_TRK_DAISU_Y,         " . "\r\n";
        // $strSQL .= "  @CX80_TRK_DAISU_S,         " . "\r\n";
        if ($isExit['data'][0]["ISEXIT"] === '1') {
            $strSQL .= "  @CX80_TRK_DAISU_Y,         " . "\r\n";
            $strSQL .= "  @CX80_TRK_DAISU_S,         " . "\r\n";
        }
        // 20240712 LHB UPD E
        // 20240611 LHB INS E
        $strSQL .= "  @LDSTAR_TRK_DAISU_Y  ,      " . "\r\n";
        $strSQL .= "  @LDSTAR_TRK_DAISU_S  ,      " . "\r\n";
        $strSQL .= "  @FMV_TRK_DAISU_Y   ,        " . "\r\n";
        $strSQL .= "  @FMV_TRK_DAISU_S  ,         " . "\r\n";
        $strSQL .= "  @BONGO_TRK_DAISU_Y ,        " . "\r\n";
        $strSQL .= "  @BONGO_TRK_DAISU_S ,        " . "\r\n";
        $strSQL .= "  @TTD_TRK_DAISU_Y  ,         " . "\r\n";

        $strSQL .= "  @TTD_TRK_DAISU_S ,          " . "\r\n";
        $strSQL .= "  @TT_TRK_DAISU_Y  ,          " . "\r\n";
        $strSQL .= "  @TT_TRK_DAISU_S  ,          " . "\r\n";
        $strSQL .= "  @KEI_TRK_DAISU_Y ,          " . "\r\n";
        $strSQL .= "  @KEI_TRK_DAISU_S ,          " . "\r\n";

        $strSQL .= "  @URIMOKU_CHUKO_CHOKU,             " . "\r\n";
        $strSQL .= "  @URIMOKU_CHUKO_GYOBAI,         " . "\r\n";
        $strSQL .= "  @URIYOSOU_CHUKO_CHOKU_Y,          " . "\r\n";
        $strSQL .= "  @URIYOSOU_CHUKO_CHOKU_S,         " . "\r\n";
        $strSQL .= "  @URIYOSOU_CHUKO_GYOBAI_Y,         " . "\r\n";
        $strSQL .= "  @URIYOSOU_CHUKO_GYOBAI_S,         " . "\r\n";

        $strSQL .= "  @SHURI_HOKEN ,              " . "\r\n";
        $strSQL .= "  @SHURI_LEASE ,              " . "\r\n";
        $strSQL .= "  @SHURI_LOAN ,               " . "\r\n";
        $strSQL .= "  @SHURI_KIBOU ,              " . "\r\n";
        $strSQL .= "  @SHURI_P753 ,               " . "\r\n";
        $strSQL .= "  @SHURI_PMENTE ,             " . "\r\n";
        $strSQL .= "  @SHURI_BODYCOAT ,           " . "\r\n";
        $strSQL .= "  @SHURI_JAF ,                " . "\r\n";
        $strSQL .= "  @SHURI_OSS ,             " . "\r\n";

        $strSQL .= "  SYSDATE   ,             " . "\r\n";
        if ($params['hidCreateTime'] == "") {
            $strSQL .= "  SYSDATE  ,             " . "\r\n";
        } else {
            $strSQL .= "  TO_DATE('" . $params['hidCreateTime'] . "', 'YYYY-MM-DD HH24:MI:SS')  ,             " . "\r\n";
        }
        $strSQL .= "  '@UPD_SYA_CD'   ,             " . "\r\n";
        $strSQL .= "  '@UPD_PRG_ID'    ,            " . "\r\n";
        $strSQL .= "  '@UPD_CLT_NM' )              " . "\r\n";
        $strSQL = str_replace("@TAISYOU_YM", $params['TAISYOU_YM'], $strSQL);
        if ($params['PatternID'] == $params['CONST_ADMIN_PTN_NO'] || $params['PatternID'] == $params['CONST_HONBU_PTN_NO'] || $params['PatternID'] == $params['CONST_TESTER_PTN_NO']) {
            $strSQL = str_replace("@BUSYO_CD", "999", $strSQL);
        } else {
            $strSQL = str_replace("@BUSYO_CD", $params['BUSYOCD'], $strSQL);
        }

        $strSQL = str_replace("@GENRI_MOKUHYO", $this->ClsComFncHMTVE->FncNz($datas['txtGoal']), $strSQL);
        $strSQL = str_replace("@GENRI_YOSOU", $this->ClsComFncHMTVE->FncNz($datas['txtYosou']), $strSQL);
        $strSQL = str_replace("@GENRI_JISSEKI", $this->ClsComFncHMTVE->FncNz($datas['txtJisski']), $strSQL);
        $strSQL = str_replace("@URIMOKU_MAIN", $this->ClsComFncHMTVE->FncNz($datas['txtMain']), $strSQL);
        $strSQL = str_replace("@URIMOKU_TACHANEL", $this->ClsComFncHMTVE->FncNz($datas['txtTaChanel']), $strSQL);
        $strSQL = str_replace("@URIYOSOU_MAIN_Y", $this->ClsComFncHMTVE->FncNz($datas['txtMainY']), $strSQL);

        $strSQL = str_replace("@URIYOSOU_MAIN_S", $this->ClsComFncHMTVE->FncNz($datas['txtMainS']), $strSQL);
        $strSQL = str_replace("@URIYOSOU_KEI_Y", $this->ClsComFncHMTVE->FncNz($datas['txtKeiY']), $strSQL);
        $strSQL = str_replace("@URIYOSOU_KEI_S ", $this->ClsComFncHMTVE->FncNz($datas['txtKeiS']), $strSQL);
        $strSQL = str_replace("@URIYOSOU_VOLVO_Y", $this->ClsComFncHMTVE->FncNz($datas['txtVolvoY']), $strSQL);
        $strSQL = str_replace("@URIYOSOU_VOLVO_S", $this->ClsComFncHMTVE->FncNz($datas['txtVolvoS']), $strSQL);
        $strSQL = str_replace("@URIYOSOU_SONOTA_Y", $this->ClsComFncHMTVE->FncNz($datas['txtSonotaY']), $strSQL);
        $strSQL = str_replace("@URIYOSOU_SONOTA_S", $this->ClsComFncHMTVE->FncNz($datas['txtSonotaS']), $strSQL);
        $strSQL = str_replace("@TRKDAISU_JIJI_Y", $this->ClsComFncHMTVE->FncNz($datas['txtJijiY']), $strSQL);
        $strSQL = str_replace("@TRKDAISU_JIJI_S ", $this->ClsComFncHMTVE->FncNz($datas['txtJijiS']), $strSQL);
        $strSQL = str_replace("@TRKDAISU_FUKUSHI_Y", $this->ClsComFncHMTVE->FncNz($datas['txtFukushiY']), $strSQL);
        $strSQL = str_replace("@TRKDAISU_FUKUSHI_S ", $this->ClsComFncHMTVE->FncNz($datas['txtFukushiS']), $strSQL);

        $strSQL = str_replace("@TRKDAISU_TAJI_Y", $this->ClsComFncHMTVE->FncNz($datas['txtTajiY']), $strSQL);
        $strSQL = str_replace("@TRKDAISU_TAJI_S", $this->ClsComFncHMTVE->FncNz($datas['txtTajiS']), $strSQL);
        $strSQL = str_replace("@TRKDAISU_JITA_Y", $this->ClsComFncHMTVE->FncNz($datas['txtJitaY']), $strSQL);
        $strSQL = str_replace("@TRKDAISU_JITA_S", $this->ClsComFncHMTVE->FncNz($datas['txtJitaS']), $strSQL);

        $strSQL = str_replace("@TRKDAISU_KEI_JIJI_Y", $this->ClsComFncHMTVE->FncNz($datas['txtKJijiY']), $strSQL);
        $strSQL = str_replace("@TRKDAISU_KEI_JIJI_S", $this->ClsComFncHMTVE->FncNz($datas['txtKJijiS']), $strSQL);
        $strSQL = str_replace("@TRKDAISU_KEI_TAJI_Y", $this->ClsComFncHMTVE->FncNz($datas['txtKTajiY']), $strSQL);
        $strSQL = str_replace("@TRKDAISU_KEI_TAJI_S", $this->ClsComFncHMTVE->FncNz($datas['txtKTajiS']), $strSQL);
        $strSQL = str_replace("@TRKDAISU_KEI_JITA_Y", $this->ClsComFncHMTVE->FncNz($datas['txtKJitaY']), $strSQL);
        $strSQL = str_replace("@TRKDAISU_KEI_JITA_S", $this->ClsComFncHMTVE->FncNz($datas['txtKJitaS']), $strSQL);
        $strSQL = str_replace("@TRKDAISU_KEI_FUKUSHI_Y", $this->ClsComFncHMTVE->FncNz($datas['txtKFukushiY']), $strSQL);
        $strSQL = str_replace("@TRKDAISU_KEI_FUKUSHI_S", $this->ClsComFncHMTVE->FncNz($datas['txtKFukushiS']), $strSQL);

        $strSQL = str_replace("@TRKDAISU_RENTA_Y", $this->ClsComFncHMTVE->FncNz($datas['txtRentaY']), $strSQL);
        $strSQL = str_replace("@TRKDAISU_RENTA_S", $this->ClsComFncHMTVE->FncNz($datas['txtRentaS']), $strSQL);

        $strSQL = str_replace("@DEMIO_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtTDaisuY']), $strSQL);
        $strSQL = str_replace("@DEMIO_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtTDaisuS']), $strSQL);

        $strSQL = str_replace("@M2G_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtM2GDaisuY']), $strSQL);
        $strSQL = str_replace("@M2G_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtM2GDaisuS']), $strSQL);

        $strSQL = str_replace("@CX3_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtCX3DaisuY']), $strSQL);
        $strSQL = str_replace("@CX3_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtCX3DaisuS']), $strSQL);

        $strSQL = str_replace("@M3S_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtM3SDaisuY']), $strSQL);
        $strSQL = str_replace("@M3S_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtM3SDaisuS']), $strSQL);
        $strSQL = str_replace("@M3H_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtM3HDaisuY']), $strSQL);
        $strSQL = str_replace("@M3H_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtM3HDaisuS']), $strSQL);

        $strSQL = str_replace("@M6S_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtM6SDaisuY']), $strSQL);
        $strSQL = str_replace("@M6S_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtM6SDaisuS']), $strSQL);
        $strSQL = str_replace("@M6W_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtM6WDaisuY']), $strSQL);
        $strSQL = str_replace("@M6W_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtM6WDaisuS']), $strSQL);

        $strSQL = str_replace("@ATENZA_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtATDaiSuY']), $strSQL);
        $strSQL = str_replace("@ATENZA_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtATDaiSuS']), $strSQL);
        $strSQL = str_replace("@AXS_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtAXTDaisuY']), $strSQL);
        $strSQL = str_replace("@AXS_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtAXTDaisuS']), $strSQL);
        $strSQL = str_replace("@BIANTE_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtBianteY']), $strSQL);
        $strSQL = str_replace("@BIANTE_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtBianteS']), $strSQL);
        $strSQL = str_replace("@PREMACY_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtPTDaisuY']), $strSQL);
        $strSQL = str_replace("@PREMACY_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtPTDaisuS']), $strSQL);
        $strSQL = str_replace("@MPV_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtMTDaisuY']), $strSQL);
        $strSQL = str_replace("@MPV_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtMTDaisuS']), $strSQL);
        $strSQL = str_replace("@CX5_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtCX5Y']), $strSQL);
        $strSQL = str_replace("@CX5_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtCX5S']), $strSQL);
        $strSQL = str_replace("@CX8_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtCX8Y']), $strSQL);
        $strSQL = str_replace("@CX8_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtCX8S']), $strSQL);

        $strSQL = str_replace("@CX30_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtCX30Y']), $strSQL);
        $strSQL = str_replace("@CX30_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtCX30S']), $strSQL);
        $strSQL = str_replace("@MX30_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtMX30Y']), $strSQL);
        $strSQL = str_replace("@MX30_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtMX30S']), $strSQL);
        // 20240326 LHB INS S
        $strSQL = str_replace("@CX60_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtCX60Y']), $strSQL);
        $strSQL = str_replace("@CX60_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtCX60S']), $strSQL);
        // 20240326 LHB INS E
        // 20240611 LHB INS S
        // 20240712 LHB UPD S
        // $strSQL = str_replace("@CX80_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtCX80Y']), $strSQL);
        // $strSQL = str_replace("@CX80_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtCX80S']), $strSQL);
        if ($isExit['data'][0]["ISEXIT"] === '1') {
            $strSQL = str_replace("@CX80_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtCX80Y']), $strSQL);
            $strSQL = str_replace("@CX80_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtCX80S']), $strSQL);
        }
        // 20240712 LHB UPD E
        // 20240611 LHB INS E
        $strSQL = str_replace("@LDSTAR_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtLTDaisuY']), $strSQL);
        $strSQL = str_replace("@LDSTAR_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtLTDaisuS']), $strSQL);
        $strSQL = str_replace("@FMV_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtSTDaisuY']), $strSQL);
        $strSQL = str_replace("@FMV_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtSTDaisuS']), $strSQL);
        $strSQL = str_replace("@BONGO_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtBTaisuY']), $strSQL);
        $strSQL = str_replace("@BONGO_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtBTaisuS']), $strSQL);

        $strSQL = str_replace("@TTD_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz("0"), $strSQL);
        $strSQL = str_replace("@TTD_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz("0"), $strSQL);
        $strSQL = str_replace("@TT_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtTTTDaisuY']), $strSQL);
        $strSQL = str_replace("@TT_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtTTTDaisuS']), $strSQL);
        $strSQL = str_replace("@KEI_TRK_DAISU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtKTDaisuY']), $strSQL);
        $strSQL = str_replace("@KEI_TRK_DAISU_S", $this->ClsComFncHMTVE->FncNz($datas['txtKTDaisuS']), $strSQL);

        $strSQL = str_replace("@URIMOKU_CHUKO_CHOKU", $this->ClsComFncHMTVE->FncNz($datas['txtChoku']), $strSQL);
        $strSQL = str_replace("@URIMOKU_CHUKO_GYOBAI", $this->ClsComFncHMTVE->FncNz($datas['txtGyobai']), $strSQL);
        $strSQL = str_replace("@URIYOSOU_CHUKO_CHOKU_Y", $this->ClsComFncHMTVE->FncNz($datas['txtChokuY']), $strSQL);
        $strSQL = str_replace("@URIYOSOU_CHUKO_CHOKU_S", $this->ClsComFncHMTVE->FncNz($datas['txtChokuS']), $strSQL);
        $strSQL = str_replace("@URIYOSOU_CHUKO_GYOBAI_Y", $this->ClsComFncHMTVE->FncNz($datas['txtGyobaiY']), $strSQL);
        $strSQL = str_replace("@URIYOSOU_CHUKO_GYOBAI_S", $this->ClsComFncHMTVE->FncNz($datas['txtGyobaiS']), $strSQL);

        $strSQL = str_replace("@SHURI_HOKEN", $this->ClsComFncHMTVE->FncNz($datas['txtHoken']), $strSQL);
        $strSQL = str_replace("@SHURI_LEASE", $this->ClsComFncHMTVE->FncNz($datas['txtLease']), $strSQL);
        $strSQL = str_replace("@SHURI_LOAN", $this->ClsComFncHMTVE->FncNz($datas['txtLoan']), $strSQL);
        $strSQL = str_replace("@SHURI_KIBOU", $this->ClsComFncHMTVE->FncNz($datas['txtKibou']), $strSQL);
        $strSQL = str_replace("@SHURI_P753", $this->ClsComFncHMTVE->FncNz($datas['txtP753']), $strSQL);
        $strSQL = str_replace("@SHURI_PMENTE", $this->ClsComFncHMTVE->FncNz($datas['txtPMente']), $strSQL);
        $strSQL = str_replace("@SHURI_BODYCOAT", $this->ClsComFncHMTVE->FncNz($datas['txtBodycoat']), $strSQL);
        $strSQL = str_replace("@SHURI_JAF", $this->ClsComFncHMTVE->FncNz($datas['txtJaf']), $strSQL);
        $strSQL = str_replace("@SHURI_OSS", $this->ClsComFncHMTVE->FncNz($datas['txtOss']), $strSQL);

        $strSQL = str_replace("@UPD_DATE", $params["UPD_DATE"], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "TarEntry", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::insert($strSQL);
    }

    // 20240712 LHB INS S
    //CX80存在チェックを行う
    // 20240909 LHB upd s
    // public function checkCX80SQL($BusyoCD)
    // {
    public function checkCX80SQL()
    {
        // 20240909 LHB upd e
        $strSQL = "";
        $strSQL .= "SELECT count(*) as isEXIT" . "\r\n";
        $strSQL .= "FROM USER_TAB_COLUMNS " . "\r\n";
        $strSQL .= "WHERE TABLE_NAME = 'HDTTARGETRESULT' " . "\r\n";
        $strSQL .= "AND column_name='CX80_TRK_DAISU_Y' " . "\r\n";

        return parent::select($strSQL);
    }
    // 20240712 LHB INS E
}
