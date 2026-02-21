<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：FrmSyoreiSikyu
// * 関数名	：FrmSyoreiSikyu
// * 処理説明	：共通クラスの読込み
//*************************************
class FrmSyoreiSikyu extends ClsComDb
{
    //人事コントロールマスタ取得SQL
    public function fncJinjiCtlMstSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT ID " . "\r\n";
        $strSQL .= "      , SYORI_YM " . "\r\n";
        $strSQL .= " FROM   JKCONTROLMST  " . "\r\n";
        $strSQL .= " WHERE  ID = '01' ";

        return parent::select($strSQL);
    }

    //係数種類取得SQL
    public function fncKeisuSyuruiSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT   MEISYO " . "\r\n";
        $strSQL .= "        , CODE " . "\r\n";
        $strSQL .= "        , HYOJI_JUN " . "\r\n";
        $strSQL .= "        , ATAI_2 " . "\r\n";
        $strSQL .= " FROM     JKSYOREIKINMST  " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = '10000' " . "\r\n";
        $strSQL .= " ORDER BY HYOJI_JUN  ";

        return parent::select($strSQL);
    }

    //係数取得SQL
    public function fncTencyouKeisuSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT   MEISYO " . "\r\n";
        $strSQL .= "        , CODE " . "\r\n";
        $strSQL .= "        , HYOJI_JUN " . "\r\n";
        $strSQL .= "        , ATAI_1 " . "\r\n";
        $strSQL .= "        , ATAI_2 " . "\r\n";
        $strSQL .= " FROM     JKSYOREIKINMST  " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = '20000' " . "\r\n";
        $strSQL .= " AND      CODE < '11' " . "\r\n";
        $strSQL .= " ORDER BY HYOJI_JUN  ";

        return parent::select($strSQL);
    }

    //10001値1取得SQL
    public function fnc10001Atai1SQL()
    {
        $strSQL = "";
        $strSQL .= "  SELECT   ATAI_1 " . "\r\n";
        $strSQL .= " FROM     JKSYOREIKINMST  " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = '10001' " . "\r\n";
        $strSQL .= " GROUP BY ATAI_1  ";

        return parent::select($strSQL);
    }

    //12000値1取得SQL
    public function fnc12000Atai1SQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT   ATAI_1 " . "\r\n";
        $strSQL .= "  FROM     JKSYOREIKINMST  " . "\r\n";
        $strSQL .= "  WHERE    SYUBETU_CD = '12000' " . "\r\n";
        $strSQL .= "  AND      CODE = '1' ";

        return parent::select($strSQL);
    }

    //JOGEN値1取得SQL
    public function fncJOGENCode1Atai1SQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT   ATAI_1 " . "\r\n";
        $strSQL .= " FROM     JKSYOREIKINMST  " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = 'JOGEN' " . "\r\n";
        $strSQL .= " AND      CODE = '1' ";

        return parent::select($strSQL);
    }

    //JOGEN値1取得SQL
    public function fncJOGENCode2Atai1SQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT   ATAI_1 " . "\r\n";
        $strSQL .= " FROM     JKSYOREIKINMST  " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = 'JOGEN' " . "\r\n";
        $strSQL .= " AND      CODE = '2' ";

        return parent::select($strSQL);
    }

    //業績奨励手当支給計算書データ取得SQL
    public function fncGyousekiSyoureiTeateSQL($dateTimePicker1, $strBusyoCD = "")
    {
        $strSQL = "";
        $strSQL .= " SELECT   gs.BUSYO_CD " . "\r\n";
        $strSQL .= "        , gs.SYAIN_NO " . "\r\n";
        $strSQL .= "        , gs.SYAIN_NM " . "\r\n";
        $strSQL .= "        , gs.GENKAI_RIEKI1 " . "\r\n";
        $strSQL .= "        , gs.GENKAI_RIEKI2 " . "\r\n";
        $strSQL .= "        , gs.GENKAI_RIEKI " . "\r\n";
        $strSQL .= "        , (CASE WHEN gk.KEISU_KOMOKU = '01' THEN (SELECT ATAI_2 FROM JKSYOREIKINMST WHERE SYUBETU_CD = '10001' AND CODE = gk.JISSEKI) ELSE gk.JISSEKI || '' END) AS KEISU_1 " . "\r\n";
        $strSQL .= "        , gk.KEISU AS KEISU_2 " . "\r\n";
        //-------------------------------
        $strSQL .= "        , gk.JISSEKI_OFF  " . "\r\n";
        //-------------------------------
        $strSQL .= "        , gs.KEISU_TOTAL " . "\r\n";
        $strSQL .= "        , gs.SANSYUTU_KINGAKU " . "\r\n";
        $strSQL .= "        , gs.ZEN_SOUSIKYU " . "\r\n";
        $strSQL .= "        , gs.SHIHARAI_SYOUREIKIN " . "\r\n";
        $strSQL .= "        , gs.ZANGYO_TEATE " . "\r\n";
        $strSQL .= "        , gs.SYOREI_TEATE " . "\r\n";
        $strSQL .= "        , (SELECT HYOJI_JUN FROM JKSYOREIKINMST WHERE SYUBETU_CD = '10000' AND CODE = gk.KEISU_KOMOKU) AS HYOJI_JUN " . "\r\n";
        $strSQL .= " FROM     JKGYOSEKISYOREI gs, JKGYOSEKISYOREIKEISU gk  " . "\r\n";
        $strSQL .= " WHERE    gk.SIKYU_YM = gs.SIKYU_YM " . "\r\n";
        $strSQL .= " AND      gk.SYAIN_NO = gs.SYAIN_NO " . "\r\n";
        $strSQL .= " AND      gk.SIKYU_YM = '" . $dateTimePicker1 . "' " . "\r\n";
        if ($strBusyoCD <> "") {
            $strSQL .= " AND      gs.BUSYO_CD = '" . $strBusyoCD . "' " . "\r\n";
        }
        $strSQL .= " ORDER BY gs.BUSYO_CD, gk.SYAIN_NO, HYOJI_JUN ";

        return parent::select($strSQL);
    }

    //限界利益掛け率取得SQL
    public function fncGenkaiRiekiSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT   ATAI_1 " . "\r\n";
        $strSQL .= " FROM     JKSYOREIKINMST  " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = '22000' " . "\r\n";
        $strSQL .= " AND      CODE = '1' ";

        return parent::select($strSQL);
    }

    //店長奨励手当支給計算書データ取得SQL
    public function fncTencyouSyoureiTeateSQL($dateTimePicker1)
    {
        $strSQL = "";
        $strSQL .= " SELECT   ts.BUSYO_CD " . "\r\n";
        $strSQL .= "        , CASE WHEN instr(bu.BUSYO_NM, '店', 1,1)>0 THEN SUBSTR(bu.BUSYO_NM, 1,instr(bu.BUSYO_NM, '店', 1,1))" . "\r\n";
        $strSQL .= "               WHEN instr(bu.BUSYO_NM, '直', 1,1)>0 THEN SUBSTR(bu.BUSYO_NM, 1,instr(bu.BUSYO_NM, '直', 1,1)-1)" . "\r\n";
        $strSQL .= "          ELSE bu.BUSYO_NM END  BUSYO_NM " . "\r\n";
        //--------------------------------
        $strSQL .= "        , ty.SYAIN_NO " . "\r\n";
        $strSQL .= "        , round(ts.GENKAI_RIEKI / 1000, 0) AS GENKAI_RIEKI " . "\r\n";
        $strSQL .= "        , ts.GENKAI_RIEKI_CALC " . "\r\n";
        $strSQL .= "        , ts.JININ " . "\r\n";
        $strSQL .= "        , round((SELECT KEIJO_RIEKI_HON FROM JKTENCHOSYOREIKEISU WHERE SIKYU_YM = ts.SIKYU_YM AND BUSYO_CD = ts.BUSYO_CD AND KEISU_KOMOKU = '11') / 1000, 0) AS KEIJO_RIEKI_HON " . "\r\n";
        $strSQL .= "        , round((SELECT KEIJO_RIEKI_ZEN FROM JKTENCHOSYOREIKEISU WHERE SIKYU_YM = ts.SIKYU_YM AND BUSYO_CD = ts.BUSYO_CD AND KEISU_KOMOKU = '11') / 1000, 0) AS KEIJO_RIEKI_ZEN " . "\r\n";
        $strSQL .= "        , (SELECT KEISU FROM JKTENCHOSYOREIKEISU WHERE SIKYU_YM = ts.SIKYU_YM AND BUSYO_CD = ts.BUSYO_CD AND KEISU_KOMOKU = '11') AS KEISU_ZEN " . "\r\n";
        $strSQL .= "        , tk.JISSEKI AS KEISU_1 " . "\r\n";
        $strSQL .= "        , tk.JISSEKI_1 AS KEISU_2 " . "\r\n";
        $strSQL .= "        , tk.KEISU " . "\r\n";
        $strSQL .= "        , ts.KEISU_TOTAL " . "\r\n";
        $strSQL .= "        , ty.SANSYUTU_KINGAKU " . "\r\n";
        $strSQL .= "        , ty.SYOREI_TEATE " . "\r\n";
        $strSQL .= "        , (SELECT HYOJI_JUN FROM JKSYOREIKINMST WHERE SYUBETU_CD = '20000' AND CODE = tk.KEISU_KOMOKU) AS HYOJI_JUN " . "\r\n";
        $strSQL .= " FROM     JKTENCHOSYOREI ts " . "\r\n";
        //--------------------------------
        $strSQL .= "          INNER JOIN(SELECT SIKYU_YM " . "\r\n";
        //--------------------------------
        $strSQL .= "                         , BUSYO_CD " . "\r\n";
        $strSQL .= "                         , KEISU_KOMOKU " . "\r\n";
        $strSQL .= "                         , KEISU, JISSEKI " . "\r\n";
        $strSQL .= "                         , JISSEKI_1 " . "\r\n";
        $strSQL .= "                    FROM   JKTENCHOSYOREIKEISU " . "\r\n";
        $strSQL .= "                   ) tk " . "\r\n";
        $strSQL .= "                   ON  ts.SIKYU_YM = tk.SIKYU_YM " . "\r\n";
        $strSQL .= "                   AND ts.BUSYO_CD = tk.BUSYO_CD " . "\r\n";
        //--------------------------------
        $strSQL .= "          INNER JOIN(SELECT SIKYU_YM " . "\r\n";
        //--------------------------------
        $strSQL .= "                         , BUSYO_CD " . "\r\n";
        $strSQL .= "                         , SYAIN_NO " . "\r\n";
        $strSQL .= "                         , SANSYUTU_KINGAKU " . "\r\n";
        $strSQL .= "                         , SYOREI_TEATE " . "\r\n";
        $strSQL .= "                    FROM JKTENCHOSYOREISYAIN " . "\r\n";
        $strSQL .= "                   ) ty " . "\r\n";
        $strSQL .= "                   ON  ts.SIKYU_YM = ty.SIKYU_YM " . "\r\n";
        $strSQL .= "                   AND ts.BUSYO_CD = ty.BUSYO_CD " . "\r\n";
        //--------------------------------
        $strSQL .= "          INNER JOIN JKBUMON bu ON ts.BUSYO_CD = bu.BUSYO_CD " . "\r\n";
        //--------------------------------
        $strSQL .= " WHERE    tk.SIKYU_YM = '" . $dateTimePicker1 . "'\r\n";
        $strSQL .= " AND      tk.KEISU_KOMOKU < '11'" . "\r\n";
        $strSQL .= " ORDER BY ts.BUSYO_CD, ty.SYAIN_NO, HYOJI_JUN ";

        return parent::select($strSQL);
    }

    //業績奨励手当支給計算書部署一覧データ取得SQL
    public function fncGyousekiSyoureiTeateBusyoSQL($dateTimePicker1)
    {
        $strSQL = "";
        $strSQL .= " SELECT DISTINCT " . "\r\n";
        $strSQL .= "  gs.BUSYO_CD " . "\r\n";
        $strSQL .= " FROM   JKGYOSEKISYOREI gs " . "\r\n";
        $strSQL .= " WHERE  gs.SIKYU_YM = '" . $dateTimePicker1 . "'\r\n";
        $strSQL .= " ORDER BY BUSYO_CD";

        return parent::select($strSQL);
    }

}
