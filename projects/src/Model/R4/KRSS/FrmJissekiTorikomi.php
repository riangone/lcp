<?php
/**
 * 説明：
 *
 *
 * @author li
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                                 担当
 * YYYYMMDD           #ID                       XXXXXX                               FCSDL
 * 20160511           #2437                     実績取込機能改修                        Sun
 * --------------------------------------------------------------------------------------------
 */

// 共通クラスの読込み
namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmJissekiTorikomi extends ClsComDb
{
    public $ClsComFnc;
    /**
     * コントロールマスタが存在しませんcheck
     * @return {parent} result
     */
    public function fncHKEIRICTL()
    {
        $sql = $this->fncHKEIRICTL_sql();
        return parent::select($sql);
    }

    /**
     *コメント削除
     * @param {String} $ki 期
     * @return {parent} result
     */
    public function fncTableDelete($ki)
    {
        $strSql = $this->fncTableDeleteSql($ki);

        return parent::Do_Execute($strSql);
    }

    /**
     *サービス実績削除
     * @param {String} $ym 年月
     * @return {parent} result
     */
    public function fncTableDeleteService($ym)
    {
        $strSql = $this->fncTableDeleteSqlService($ym);

        return parent::Do_Execute($strSql);
    }

    /**
     *保険実績削除
     * @param {String} $ym 年月
     * @return {parent} result
     */
    public function fncTableDeleteHoken($ym)
    {
        $strSql = $this->fncTableDeleteSqlHoken($ym);

        return parent::Do_Execute($strSql);
    }

    /**
     *その他削除
     * @param {String} $ym 年月
     * @return {parent} result
     */
    public function fncTableDeleteOther($postData = NULL)
    {
        $strSql = $this->fncTableDeleteSqlOther($postData);

        return parent::Do_Execute($strSql);
    }

    /**
     * コメント登録
     * * @param {String} $KI 期 $ym 年月 $BUSYO 部署名 $comment コメント
     * @return {parent} result
     */
    public function ExcuteFncGetSqlInsert($KI, $ym, $BUSYO, $comment)
    {

        $strSql = $this->fncGetSqlInsert($KI, $ym, $BUSYO, $comment);

        return parent::Do_Execute($strSql);
    }

    /**
     * サービス実績登録
     * @param {array} $data
     * 年月：NENGETU
     * 拠点CD:BUSYO_CD
     * 拠点名称:BUSYO_NM
     * 入庫区分:NYUKO_KB
     * 入庫区分名称:NYUKO_KB_NM
     * 有償_区分:YUSYO_KB
     * 有償_無償:YUSYO_MUSYO
     * 台数:DAISU
     * 売上_工賃:URIAGE_KOUTIN
     * 売上_部品:URIAGE_BUHIN
     * 売上_外注:URIAGE_GAICHU
     * 原価_工賃:GENKA_KOUTIN
     * 原価_部品:GENKA_BUHIN
     * 原価_外注:GENKA_GAICU
     * 値引_工賃:NEBIKI_KOUTIN
     * 値引_部品:NEBIKI_BUHIN
     * 値引_外注:NEBIKI_GAICU
     * 売上_構成比:TOTAL_URIAGE
     * 売上_合計:TOTAL_GENKA
     * 原価_合計:TOTAL_ARARI
     * 粗利_合計:TOTAL_NEBIKI
     * 値引_合計:TOTAL_RIEKI_RT
     * 粗利益率_合計:TOTAL_RIEKI_RT
     * 利益率_工賃:KOUTIN_RIEKI_RT
     * 利益率_部品:BUHIN_RIEKI_RT
     * 利益率_外注:GAICHU_RIEKI_RT
     * 値引率:DAIATARI_URIAGE
     * 台当り_売上:DAIATARI_URIAGE
     * 台当り_原価:DAIARAI_GENKA
     * 台当り_粗利:DAIATARI_ARARI
     * 台当り_値引:DAIATARI_NEBIKI
     * 順番:SEQ
     * @return {parent} result
     */
    public function ExcuteFncGetSqlInsertService($data = NULL)
    {

        $strSql = $this->fncGetSqlInsertService($data);

        return parent::Do_Execute($strSql);
    }

    /**
     * 保険実績登録
     * @param {array} $data
     * 年月：NENGETU
     * 部署コード:BUSYO_CD
     * 部署名:BUSYO_NM
     * 新規:SINKI
     * 総契約数:TOTAL_KEIYAKU
     * 保険料総額:TOTAL_HOKENRYO
     * 満期件数:MANKI
     * 継続件数:KEIZOKU
     * 早期更改件数:SOUKI_KOUKAI
     * 車両付保険台数:SYARYOTUKI
     * 長期分割件数:CHOUKI_BUNKATU
     * 早期更改率:SOUKI_KOUKAI_RT
     * 長期率:CHOUKI_RT
     * @return {parent} result
     */
    public function ExcuteFncGetSqlInsertHoken($data = NULL)
    {

        $strSql = $this->fncGetSqlInsertHoken($data);
        //$this ->log($strSql);
        return parent::Do_Execute($strSql);
    }

    /**
     *  その他登録
     * @param {array} $data
     * 年月：NENGETU
     * 部署コード:BUSYO_CD
     * 行数:LINE_NO
     * 值:TOUGETU
     * @return {parent} result
     */
    public function ExcuteFncGetSqlInsertOther($data = NULL)
    {

        $strSql = $this->fncGetSqlInsertOther($data);

        return parent::Do_Execute($strSql);
    }

    //20160511 Sun Add Start
    /**
     * 台数登録查询
     * @param {String} $ENENGETU 年月 $EBUSYO_CD 部署コード  $LINE_NO1 行数
     * @return {parent} result
     */
    public function FncSelectTougetu($ENENGETU, $EBUSYO_CD, $LINE_NO1)
    {
        $strsql = $this->fncSelectTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO1);
        return parent::select($strsql);
    }

    /**
     * 台数登録INSERT
     * @param {String} $ENENGETU 年月 $EBUSYO_CD 部署コード  $SLINE_NO 行数 $KOKYAKUSU 台数
     * @return {parent} result
     */
    public function ExcuteFncINSTougetuSQL($ENENGETU, $EBUSYO_CD, $SLINE_NO, $KOKYAKUSU)
    {

        $strSql = $this->fncINSTougetuSQL($ENENGETU, $EBUSYO_CD, $SLINE_NO, $KOKYAKUSU);

        return parent::Do_Execute($strSql);
    }

    /**
     * 台数登録UPDATE
     * @param {String} $ENENGETU 年月 $EBUSYO_CD 部署コード  $SLINE_NO 行数 $KOKYAKUSU 台数
     * @return {parent} result
     */
    public function ExcuteFncUPDTougetuSQL($ENENGETU, $EBUSYO_CD, $SLINE_NO, $KOKYAKUSU)
    {

        $strSql = $this->fncUPDTougetuSQL($ENENGETU, $EBUSYO_CD, $SLINE_NO, $KOKYAKUSU);

        return parent::Do_Execute($strSql);
    }

    //20160511 Sun Add End

    /**
     *保険実績削除
     * @param {String} $ym 年月
     * @return {string} delete文
     */
    function fncTableDeleteSqlHoken($ym = NULL)
    {
        $strSQL = "";

        $strSQL .= "DELETE FROM HSIM_HOKENJISSEKI" . "\r\n";
        $strSQL .= "WHERE NENGETU= '@NENGETU' " . "\r\n";
        $strSQL = str_replace("@NENGETU", $ym, $strSQL);

        return $strSQL;

    }

    /**
     *サービス実績削除
     * @param {String} $ym 年月
     * @return {string} delete文
     */
    function fncTableDeleteSqlService($ym = NULL)
    {
        $strSQL = "";

        $strSQL .= "DELETE FROM HSIM_SERVICEJISSEKI" . "\r\n";
        $strSQL .= "WHERE NENGETU= '@NENGETU' " . "\r\n";
        $strSQL = str_replace("@NENGETU", $ym, $strSQL);

        return $strSQL;

    }

    /**
     *コメント削除
     * @param {String} $ki 期
     * @return {string} delete文
     */
    function fncTableDeleteSql($ki = NULL)
    {
        $strSQL = "";

        $strSQL .= "DELETE FROM HSIM_COMMENT" . "\r\n";
        $strSQL .= "WHERE KI= '@KI' " . "\r\n";
        $strSQL = str_replace("@KI", $ki, $strSQL);

        return $strSQL;

    }

    /**
     *その他削除
     * @param {String} $ym 年月
     * @return {string} delete文
     */
    function fncTableDeleteSqlOther($ym = NULL)
    {
        $strSQL = "";

        $strSQL .= "DELETE FROM HSIM_SONOTAJISSEKI" . "\r\n";
        $strSQL .= "WHERE NENGETU>= '@NENGETUFROM' " . "\r\n";
        $strSQL .= "AND  NENGETU<= '@NENGETUTO' " . "\r\n";
        $strSQL = str_replace("@NENGETUFROM", substr($ym, 0, 4) . '10', $strSQL);
        $strSQL = str_replace("@NENGETUTO", (substr($ym, 0, 4) + 1) . '09', $strSQL);
        return $strSQL;

    }

    /**
     * 保険実績登録
     * @param {array} $data
     * 年月：NENGETU
     * 部署コード:BUSYO_CD
     * 部署名:BUSYO_NM
     * 新規:SINKI
     * 総契約数:TOTAL_KEIYAKU
     * 保険料総額:TOTAL_HOKENRYO
     * 満期件数:MANKI
     * 継続件数:KEIZOKU
     * 早期更改件数:SOUKI_KOUKAI
     * 車両付保険台数:SYARYOTUKI
     * 長期分割件数:CHOUKI_BUNKATU
     * 早期更改率:SOUKI_KOUKAI_RT
     * 長期率:CHOUKI_RT
     * @return {string} instert文
     */
    function fncGetSqlInsertHoken($data)
    {
        $this->ClsComFnc = new ClsComFnc();
        $strSQL = "";
        $strSQL .= "INSERT INTO HSIM_HOKENJISSEKI (";
        $strSQL .= " NENGETU";
        $strSQL .= ", BUSYO_CD";
        $strSQL .= ", BUSYO_NM";
        $strSQL .= ", SINKI";
        $strSQL .= ", TOTAL_KEIYAKU";
        $strSQL .= ", TOTAL_HOKENRYO";
        $strSQL .= ", MANKI";
        $strSQL .= ", KEIZOKU";
        $strSQL .= ", SOUKI_KOUKAI";
        $strSQL .= ", SYARYOTUKI";
        $strSQL .= ", CHOUKI_BUNKATU";
        $strSQL .= ", SOUKI_KOUKAI_RT";
        $strSQL .= ", CHOUKI_RT";
        $strSQL .= ") VALUES (";
        $strSQL .= "  @NENGETU";
        $strSQL .= ", LPAD(@BUSYO_CD,3,0)";
        $strSQL .= ", @BUSYO_NM";
        $strSQL .= ", @SINKI";
        $strSQL .= ", @TOTAL_KEIYAKU";
        $strSQL .= ", @TOTAL_HOKENRYO";
        $strSQL .= ", @MANKI";
        $strSQL .= ", @KEIZOKU";
        $strSQL .= ", @SOUKI_KOUKAI";
        $strSQL .= ", @SYARYOTUKI";
        $strSQL .= ", @CHOUKI_BUNKATU";
        $strSQL .= ", @SOUKI_KOUKAI_RT";
        $strSQL .= ", @CHOUKI_RT";
        $strSQL .= ")";
        $strSQL = str_replace("@NENGETU", $this->ClsComFnc->FncSqlNv($data[0]), $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $this->ClsComFnc->FncSqlNv($data[1]), $strSQL);
        $strSQL = str_replace("@BUSYO_NM", $this->ClsComFnc->FncSqlNv($data[2]), $strSQL);
        $strSQL = str_replace("@SINKI", $this->ClsComFnc->FncSqlNz($data[3]), $strSQL);
        $strSQL = str_replace("@TOTAL_KEIYAKU", $this->ClsComFnc->FncSqlNz($data[4]), $strSQL);
        $strSQL = str_replace("@TOTAL_HOKENRYO", $this->ClsComFnc->FncSqlNz($data[5]), $strSQL);
        $strSQL = str_replace("@MANKI", $this->ClsComFnc->FncSqlNz($data[6]), $strSQL);
        $strSQL = str_replace("@KEIZOKU", $this->ClsComFnc->FncSqlNz($data[7]), $strSQL);

        if (!is_numeric($data[11])) {
            $data[11] = 0;
        }
        $strSQL = str_replace("@SOUKI_KOUKAI_RT", $this->ClsComFnc->FncSqlNz($data[11]), $strSQL);


        $strSQL = str_replace("@SOUKI_KOUKAI", $this->ClsComFnc->FncSqlNz($data[8]), $strSQL);
        $strSQL = str_replace("@SYARYOTUKI", $this->ClsComFnc->FncSqlNz($data[9]), $strSQL);
        $strSQL = str_replace("@CHOUKI_BUNKATU", $this->ClsComFnc->FncSqlNz($data[10]), $strSQL);

        if (!is_numeric($data[12])) {
            $data[12] = 0;
        }
        $strSQL = str_replace("@CHOUKI_RT", $this->ClsComFnc->FncSqlNz($data[12]), $strSQL);
        //$this->log($strSQL);
        return $strSQL;

    }

    /**
     * サービス実績登録
     * @param {array} $data
     * 年月：NENGETU
     * 拠点CD:BUSYO_CD
     * 拠点名称:BUSYO_NM
     * 入庫区分:NYUKO_KB
     * 入庫区分名称:NYUKO_KB_NM
     * 有償_区分:YUSYO_KB
     * 有償_無償:YUSYO_MUSYO
     * 台数:DAISU
     * 売上_工賃:URIAGE_KOUTIN
     * 売上_部品:URIAGE_BUHIN
     * 売上_外注:URIAGE_GAICHU
     * 原価_工賃:GENKA_KOUTIN
     * 原価_部品:GENKA_BUHIN
     * 原価_外注:GENKA_GAICU
     * 値引_工賃:NEBIKI_KOUTIN
     * 値引_部品:NEBIKI_BUHIN
     * 値引_外注:NEBIKI_GAICU
     * 売上_構成比:TOTAL_URIAGE
     * 売上_合計:TOTAL_GENKA
     * 原価_合計:TOTAL_ARARI
     * 粗利_合計:TOTAL_NEBIKI
     * 値引_合計:TOTAL_RIEKI_RT
     * 粗利益率_合計:TOTAL_RIEKI_RT
     * 利益率_工賃:KOUTIN_RIEKI_RT
     * 利益率_部品:BUHIN_RIEKI_RT
     * 利益率_外注:GAICHU_RIEKI_RT
     * 値引率:DAIATARI_URIAGE
     * 台当り_売上:DAIATARI_URIAGE
     * 台当り_原価:DAIARAI_GENKA
     * 台当り_粗利:DAIATARI_ARARI
     * 台当り_値引:DAIATARI_NEBIKI
     * 順番:SEQ
     * @return {string} instert文
     */
    function fncGetSqlInsertService($data)
    {
        $this->ClsComFnc = new ClsComFnc();
        $strSQL = "";
        $strSQL .= "INSERT INTO HSIM_SERVICEJISSEKI (";
        $strSQL .= " NENGETU";
        $strSQL .= ", BUSYO_CD";
        $strSQL .= ", BUSYO_NM";
        $strSQL .= ", NYUKO_KB";
        $strSQL .= ", NYUKO_KB_NM";
        $strSQL .= ", YUSYO_KB";
        $strSQL .= ", YUSYO_MUSYO";
        $strSQL .= ", DAISU";
        $strSQL .= ", URIAGE_KOUTIN";
        $strSQL .= ", URIAGE_BUHIN";
        $strSQL .= ", URIAGE_GAICHU";
        $strSQL .= ", GENKA_KOUTIN";
        $strSQL .= ", GENKA_BUHIN";
        $strSQL .= ", GENKA_GAICU";
        $strSQL .= ", NEBIKI_KOUTIN";
        $strSQL .= ", NEBIKI_BUHIN";
        $strSQL .= ", NEBIKI_GAICU";
        $strSQL .= ", URIAGE_KOUSEIHI";
        $strSQL .= ", TOTAL_URIAGE";
        $strSQL .= ", TOTAL_GENKA";
        $strSQL .= ", TOTAL_ARARI";
        $strSQL .= ", TOTAL_NEBIKI";
        $strSQL .= ", TOTAL_RIEKI_RT";
        $strSQL .= ", KOUTIN_RIEKI_RT";
        $strSQL .= ", BUHIN_RIEKI_RT";
        $strSQL .= ", GAICHU_RIEKI_RT";
        $strSQL .= ", NEBIKI_RT";
        $strSQL .= ",  DAIATARI_URIAGE";
        $strSQL .= ",  DAIARAI_GENKA";
        $strSQL .= ",  DAIATARI_ARARI";
        $strSQL .= ", DAIATARI_NEBIKI";
        $strSQL .= ",  SEQ";
        $strSQL .= ") VALUES (";
        $strSQL .= "  @NENGETU";
        $strSQL .= ", LPAD(@BUSYO_CD,3,0)";
        $strSQL .= ", @BUSYO_NM";
        $strSQL .= ", @NYUKO_KB";
        $strSQL .= ", @NYUKO_KB_NM";
        $strSQL .= ", @YUSYO_KB";
        $strSQL .= ", @YUSYO_MUSYO";
        $strSQL .= ", @DAISU";
        $strSQL .= ", @URIAGE_KOUTIN";
        $strSQL .= ", @URIAGE_BUHIN";
        $strSQL .= ", @URIAGE_GAICHU";
        $strSQL .= ", @GENKA_KOUTIN";
        $strSQL .= ", @GENKA_BUHIN";
        $strSQL .= ", @GENKA_GAICU";
        $strSQL .= ", @NEBIKI_KOUTIN";
        $strSQL .= ", @NEBIKI_BUHIN";
        $strSQL .= ", @NEBIKI_GAICU";
        $strSQL .= ", @URIAGE_KOUSEIHI";
        $strSQL .= ", @TOTAL_URIAGE";
        $strSQL .= ", @TOTAL_GENKA";
        $strSQL .= ", @TOTAL_ARARI";
        $strSQL .= ", @TOTAL_NEBIKI";
        $strSQL .= ", @TOTAL_RIEKI_RT";
        $strSQL .= ", @KOUTIN_RIEKI_RT";
        $strSQL .= ", @BUHIN_RIEKI_RT";
        $strSQL .= ", @GAICHU_RIEKI_RT";
        $strSQL .= ", @NEBIKI_RT";
        $strSQL .= ", @DAIATARI_URIAGE";
        $strSQL .= ", @DAIARAI_GENKA";
        $strSQL .= ", @DAIATARI_ARARI";
        $strSQL .= ", @DAIATARI_NEBIKI";
        $strSQL .= ", @SEQ";
        $strSQL .= ")";
        $strSQL = str_replace("@NENGETU", $this->ClsComFnc->FncSqlNv($data[0]), $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $this->ClsComFnc->FncSqlNv($data[1]), $strSQL);
        $strSQL = str_replace("@BUSYO_NM", $this->ClsComFnc->FncSqlNv($data[2]), $strSQL);
        $strSQL = str_replace("@NYUKO_KB_NM", $this->ClsComFnc->FncSqlNv($data[4]), $strSQL);
        $strSQL = str_replace("@NYUKO_KB", $this->ClsComFnc->FncSqlNv($data[3]), $strSQL);
        $strSQL = str_replace("@YUSYO_KB", $this->ClsComFnc->FncSqlNv($data[5]), $strSQL);
        $strSQL = str_replace("@YUSYO_MUSYO", $this->ClsComFnc->FncSqlNv($data[6]), $strSQL);

        $strSQL = str_replace("@DAISU", $this->ClsComFnc->FncSqlNz($data[7]), $strSQL);
        $strSQL = str_replace("@URIAGE_KOUTIN", $this->ClsComFnc->FncSqlNz($data[8]), $strSQL);
        $strSQL = str_replace("@URIAGE_BUHIN", $this->ClsComFnc->FncSqlNz($data[9]), $strSQL);
        $strSQL = str_replace("@URIAGE_GAICHU", $this->ClsComFnc->FncSqlNz($data[10]), $strSQL);
        $strSQL = str_replace("@GENKA_KOUTIN", $this->ClsComFnc->FncSqlNz($data[11]), $strSQL);
        $strSQL = str_replace("@GENKA_BUHIN", $this->ClsComFnc->FncSqlNz($data[12]), $strSQL);
        $strSQL = str_replace("@GENKA_GAICU", $this->ClsComFnc->FncSqlNz($data[13]), $strSQL);
        $strSQL = str_replace("@NEBIKI_KOUTIN", $this->ClsComFnc->FncSqlNz($data[14]), $strSQL);
        $strSQL = str_replace("@NEBIKI_BUHIN", $this->ClsComFnc->FncSqlNz($data[15]), $strSQL);
        $strSQL = str_replace("@NEBIKI_GAICU", $this->ClsComFnc->FncSqlNz($data[16]), $strSQL);
        $strSQL = str_replace("@URIAGE_KOUSEIHI", $this->ClsComFnc->FncSqlNz($data[17]), $strSQL);
        $strSQL = str_replace("@TOTAL_URIAGE", $this->ClsComFnc->FncSqlNz($data[18]), $strSQL);
        $strSQL = str_replace("@TOTAL_GENKA", $this->ClsComFnc->FncSqlNz($data[19]), $strSQL);
        $strSQL = str_replace("@TOTAL_ARARI", $this->ClsComFnc->FncSqlNz($data[20]), $strSQL);
        $strSQL = str_replace("@TOTAL_NEBIKI", $this->ClsComFnc->FncSqlNz($data[21]), $strSQL);
        $strSQL = str_replace("@TOTAL_RIEKI_RT", $this->ClsComFnc->FncSqlNz($data[22]), $strSQL);
        $strSQL = str_replace("@KOUTIN_RIEKI_RT", $this->ClsComFnc->FncSqlNz($data[23]), $strSQL);
        $strSQL = str_replace("@BUHIN_RIEKI_RT", $this->ClsComFnc->FncSqlNz($data[24]), $strSQL);
        $strSQL = str_replace("@GAICHU_RIEKI_RT", $this->ClsComFnc->FncSqlNz($data[25]), $strSQL);
        $strSQL = str_replace("@NEBIKI_RT", $this->ClsComFnc->FncSqlNz($data[26]), $strSQL);
        $strSQL = str_replace("@DAIATARI_URIAGE", $this->ClsComFnc->FncSqlNz($data[27]), $strSQL);
        $strSQL = str_replace("@DAIARAI_GENKA", $this->ClsComFnc->FncSqlNz($data[28]), $strSQL);
        $strSQL = str_replace("@DAIATARI_ARARI", $this->ClsComFnc->FncSqlNz($data[29]), $strSQL);
        $strSQL = str_replace("@DAIATARI_NEBIKI", $this->ClsComFnc->FncSqlNz($data[30]), $strSQL);
        $strSQL = str_replace("@SEQ", $this->ClsComFnc->FncSqlNz($data[31]), $strSQL);
        return $strSQL;

    }

    /**
     * コメント登録
     * @param $KI期 $ym年月 $BUSYO部署名 $comment コメント
     * @return {srting} insert文
     */
    function fncGetSqlInsert($KI, $ym, $BUSYO, $comment)
    {
        $this->ClsComFnc = new ClsComFnc();
        $strSQL = "";
        $strSQL .= "INSERT INTO HSIM_COMMENT (";
        $strSQL .= " KI";
        $strSQL .= ", NENGETU";
        $strSQL .= ", BUSYO_CD";
        $strSQL .= ", COMMENT_STR";
        $strSQL .= ") VALUES (";
        $strSQL .= "  @KI";
        $strSQL .= ", @NENGETU";
        $strSQL .= ", LPAD(@BUSYO_CD,3,0)";
        $strSQL .= ", @COMMENT_STR";
        $strSQL .= ")";

        $strSQL = str_replace("@KI", $this->ClsComFnc->FncSqlNv($KI), $strSQL);
        $strSQL = str_replace("@NENGETU", $this->ClsComFnc->FncSqlNv($ym), $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $this->ClsComFnc->FncSqlNv($BUSYO), $strSQL);
        $strSQL = str_replace("@COMMENT_STR", $this->ClsComFnc->FncSqlNv($comment), $strSQL);

        return $strSQL;

    }

    /**
     *  その他登録
     * @param {array} $data
     * 年月：NENGETU
     * 部署コード:BUSYO_CD
     * 行数:LINE_NO
     * 值:TOUGETU
     * @return {string} instert文
     */
    function fncGetSqlInsertOther($data)
    {
        $this->ClsComFnc = new ClsComFnc();
        $strSQL = "";
        $strSQL .= "INSERT INTO HSIM_SONOTAJISSEKI (";
        $strSQL .= " NENGETU";
        $strSQL .= ", BUSYO_CD";
        $strSQL .= ", LINE_NO";
        $strSQL .= ", TOUGETU";
        $strSQL .= ") VALUES (";
        $strSQL .= "  @NENGETU";
        $strSQL .= ", LPAD(@BUSYO_CD,3,0)";
        $strSQL .= ", @LINE_NO";
        $strSQL .= ", @TOUGETU";
        $strSQL .= ")";
        $strSQL = str_replace("@NENGETU", $this->ClsComFnc->FncSqlNv($data['NENGETU']), $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $this->ClsComFnc->FncSqlNv($data['BUSYO_CD']), $strSQL);
        $strSQL = str_replace("@LINE_NO", $this->ClsComFnc->FncSqlNz($data['LINE_NO']), $strSQL);
        $strSQL = str_replace("@TOUGETU", $this->ClsComFnc->FncSqlNz($data['TOUGETU']), $strSQL);
        return $strSQL;

    }

    //20160511 Sun Add Start

    /**
     * 台数登録查询
     * @param {String} $ENENGETU 年月 $EBUSYO_CD 部署コード  $LINE_NO1 行数
     * @return {string} select文
     */
    function fncSelectTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO1)
    {
        $strSQL = "";

        $strSQL .= "SELECT NENGETU" . "\r\n";
        $strSQL .= ",      BUSYO_CD" . "\r\n";
        $strSQL .= ",      LINE_NO" . "\r\n";
        $strSQL .= "FROM   HSIM_SONOTAJISSEKI" . "\r\n";
        $strSQL .= "WHERE  NENGETU = '@NENGETU'" . "\r\n";
        $strSQL .= "AND      BUSYO_CD = '@BUSYO_CD'" . "\r\n";
        $strSQL .= "AND      LINE_NO = @LINE_NO1" . "\r\n";
        $strSQL = str_replace("@NENGETU", $ENENGETU, $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $EBUSYO_CD, $strSQL);
        $strSQL = str_replace("@LINE_NO1", $LINE_NO1, $strSQL);
        return $strSQL;
    }

    /**
     * 台数登録UPDATE
     * @param {String} $ENENGETU 年月 $EBUSYO_CD 部署コード  $SLINE_NO 行数 $KOKYAKUSU 台数
     * @return {srting} update文
     */
    function fncUPDTougetuSQL($ENENGETU, $EBUSYO_CD, $LINE_NO, $KOKYAKUSU)
    {
        $strSQL = "";

        $strSQL .= "UPDATE HSIM_SONOTAJISSEKI SET " . "\r\n";
        $strSQL .= "       TOUGETU = @TOUGETU" . "\r\n";
        $strSQL .= "WHERE      NENGETU = @NENGETU" . "\r\n";
        $strSQL .= "AND BUSYO_CD = @BUSYO_CD" . "\r\n";
        $strSQL .= "AND LINE_NO = @LINE_NO" . "\r\n";
        $strSQL = str_replace("@NENGETU", $ENENGETU, $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $EBUSYO_CD, $strSQL);
        $strSQL = str_replace("@LINE_NO", $LINE_NO, $strSQL);
        $strSQL = str_replace("@TOUGETU", $KOKYAKUSU, $strSQL);
        return $strSQL;
    }

    /**
     * 台数登録INSERT
     * @param {String} $ENENGETU 年月 $EBUSYO_CD 部署コード  $SLINE_NO 行数 $KOKYAKUSU 台数
     * @return {srting} insert文
     */
    function fncINSTougetuSQL($ENENGETU, $EBUSYO_CD, $SLINE_NO, $KOKYAKUSU)
    {
        $this->ClsComFnc = new ClsComFnc();
        $strSQL = "";
        $strSQL .= "INSERT INTO HSIM_SONOTAJISSEKI (";
        $strSQL .= " NENGETU";
        $strSQL .= ", BUSYO_CD";
        $strSQL .= ", LINE_NO";
        $strSQL .= ", TOUGETU";
        $strSQL .= ") VALUES (";
        $strSQL .= "  @NENGETU";
        $strSQL .= ", LPAD(@BUSYO_CD,3,0)";
        $strSQL .= ", @LINE_NO";
        $strSQL .= ", @TOUGETU";
        $strSQL .= ")";
        $strSQL = str_replace("@NENGETU", $this->ClsComFnc->FncSqlNv($ENENGETU), $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $this->ClsComFnc->FncSqlNv($EBUSYO_CD), $strSQL);
        $strSQL = str_replace("@LINE_NO", $this->ClsComFnc->FncSqlNz($SLINE_NO), $strSQL);
        $strSQL = str_replace("@TOUGETU", $this->ClsComFnc->FncSqlNz($KOKYAKUSU), $strSQL);
        return $strSQL;
    }

    //20160511 Sun Add End

    //コントロールマスタが存在しませんcheck
    public function fncHKEIRICTL_sql()
    {
        $sqlstr = "";
        $sqlstr .= "SELECT ID \r";
        $sqlstr .= ",       (SUBSTR(SYR_YMD,1,4)  || SUBSTR(SYR_YMD,5,2)) TOUGETU\r";
        $sqlstr .= ",       KISYU_YMD KISYU \r";
        $sqlstr .= ",     KI \r";
        $sqlstr .= "FROM  HKEIRICTL \r";
        $sqlstr .= "WHERE ID='01'";
        return $sqlstr;
    }

}
