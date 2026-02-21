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
 * 日付                Feature/Bug               内容                                                  担当
 * YYYYMMDD           #ID                       XXXXXX                                             FCSDL
 * 20160511           #2436                     NEW                                                   YinHuaiyu
 * 20160716           経理Gテスト-5         データがありませんエラー対応                HM
 * 20160716           経理Gテスト-6         オートザム事業Gr追加対応                    HM
 * --------------------------------------------------------------------------------------------
 */

namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;

class FrmKeijouRiekiTree extends ClsComDb
{
    //コントロールマスタ存在ﾁｪｯｸ
    public function frmGetYearMonthSQL()
    {
        $strSQL = "";
        $strSQL = "SELECT ";
        $strSQL .= "  ID, ";
        $strSQL .= "  (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU, ";
        $strSQL .= "  KISYU_YMD KISYU, ";
        $strSQL .= "  KI ";
        $strSQL .= "FROM ";
        $strSQL .= "  HKEIRICTL ";
        $strSQL .= "WHERE ";
        $strSQL .= "  ID = '01' ";

        return $strSQL;
    }

    public function fncPrintSelectSQL($cboYM, $KiMon)
    {
        $strSQL = "";
        $strSQL .= "SELECT ";
        $strSQL .= " a.KEIJO_DT ";
        //			$strSQL .= ",a.BUSYO_CD ";
        $strSQL .= ",BM.BUSYO_CD ";
        $strSQL .= ",a.TOU_ZAN ";
        $strSQL .= ",b.TKI_ZAN ";
        $strSQL .= "FROM ";

        $strSQL .= " HBUSYO BM, ";

        //			$strSQL .= "(SELECT KEIJO_DT,BUSYO_CD,LINE_NO,TOU_ZAN FROM HSIMRUISEKIKANR ";
        $strSQL .= "(SELECT KEIJO_DT,BUSYO_CD,LINE_NO,TOU_ZAN FROM HSIMRUISEKIKANR_KRSS ";
        //20160716 Upd Start
//			$strSQL .= "WHERE KEIJO_DT= @TOUGETU AND LINE_NO IN ('138')) a, ";
//20160716 Upd End
//			$strSQL .= "WHERE KEIJO_DT= @TOUGETU AND LINE_NO IN ('141')) a, ";
        $strSQL .= "WHERE KEIJO_DT= @TOUGETU AND LINE_NO IN ('144')) a, ";
        //			$strSQL .= " (SELECT BUSYO_CD,LINE_NO,SUM(TOU_ZAN) TKI_ZAN FROM HSIMRUISEKIKANR ";
        $strSQL .= " (SELECT BUSYO_CD,LINE_NO,SUM(TOU_ZAN) TKI_ZAN FROM HSIMRUISEKIKANR_KRSS ";
        //20160716 Upd Start
//			$strSQL .= "WHERE KEIJO_DT>= @KIMONTH AND KEIJO_DT<= @TOUGETU AND LINE_NO IN ('111') ";
//			$strSQL .= "WHERE KEIJO_DT>= @KIMONTH AND KEIJO_DT<= @TOUGETU AND LINE_NO IN ('112') ";
//20160716 Upd End
        $strSQL .= "WHERE KEIJO_DT>= @KIMONTH AND KEIJO_DT<= @TOUGETU AND LINE_NO IN ('114') ";
        $strSQL .= " GROUP BY BUSYO_CD,LINE_NO ";
        $strSQL .= ") b";
        $strSQL .= "  WHERE ";
        //			$strSQL .= "  a.BUSYO_CD = b.BUSYO_CD ";
        $strSQL .= "  a.BUSYO_CD(+) = BM.BUSYO_CD AND ";
        $strSQL .= "  b.BUSYO_CD(+) = BM.BUSYO_CD  ";
        $strSQL .= "  ORDER BY ";
        //			$strSQL .= "  a.KEIJO_DT,a.BUSYO_CD ";
        $strSQL .= "  a.KEIJO_DT,BM.BUSYO_CD ";

        $strSQL = str_replace("@TOUGETU", $cboYM, $strSQL);
        $strSQL = str_replace("@KIMONTH", $KiMon, $strSQL);
        //$this->log($strSQL);
        return $strSQL;
    }

    public function frmGetYearMonth()
    {
        $strsql = $this->frmGetYearMonthSQL();
        return parent::select($strsql);
    }

    public function fncPrintSelect($cboYM, $KiMon)
    {
        $strsql = $this->fncPrintSelectSQL($cboYM, $KiMon);
        return parent::select($strsql);
    }

}
