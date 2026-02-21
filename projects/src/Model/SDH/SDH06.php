<?php
/**
 * 説明：
 *
 *
 * @author jinmingai
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150526           ---                       新規                           FCSDL
 * --------------------------------------------------------------------------------------------
 */

// 共通クラスの読込み
namespace App\Model\SDH;

use App\Model\Component\ClsComDb;

class SDH06 extends ClsComDb
{
    public function m_select_TANT_HENKO_LIST_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    {
        $str_sql = '';
        $str_sql .= 'SELECT ';
        $str_sql .= '  M41C04.B1_KATANNM, ';
        $str_sql .= '  M41C01.B1_KATACHGDAY, ';
        $str_sql .= '  M41C04.B2_KATANNM, ';
        $str_sql .= '  M41C01.B2_KATACHGDAY, ';
        $str_sql .= '  M41C04.B3_KATANNM, ';
        $str_sql .= '  M41C01.B3_KATACHGDAY, ';
        $str_sql .= "  M29MA4.SYAIN_KNJ_SEI || ' ' || M29MA4.SYAIN_KNJ_MEI AS TANTOSYA_NM ";
        $str_sql .= 'FROM ';
        $str_sql .= '  M41C04, ';
        $str_sql .= '  M29MA4, ';
        $str_sql .= '  M41C01 ';
        $str_sql .= 'WHERE ';
        $str_sql .= "  M41C04.DLRCSRNO = '" . $DLRCSRNO . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  M41C04.VIN_WMIVDS = '" . $VIN_WMIVDS . "' ";
        $str_sql .= 'AND ';
        $str_sql .= "  M41C04.VIN_VIS = '" . $VIN_VIS . "' ";
        //20171120 Upd Start
//        $str_sql .= "AND ";
//        $str_sql .= "  M29MA4.HANSH_CD = M41C04.HAN_HANCD ";
//        $str_sql .= "AND ";
//        $str_sql .= "  M29MA4.SYAIN_NO = M41C04.HAN_BUSMANCD ";
        $str_sql .= 'AND ';
        $str_sql .= '  M29MA4.HANSH_CD(+) = M41C04.HAN_HANCD ';
        $str_sql .= 'AND ';
        $str_sql .= '  M29MA4.SYAIN_NO(+) = M41C04.HAN_BUSMANCD ';
        //20171120 Upd End

        $str_sql .= 'AND ';
        $str_sql .= '  M41C01.DLRCSRNO   =M41C04.DLRCSRNO ';

        return $str_sql;
    }

    public function m_select_TANT_HENKO_LIST($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS)
    {
        $str_sql = $this->m_select_TANT_HENKO_LIST_sql($DLRCSRNO, $VIN_WMIVDS, $VIN_VIS);

        return parent::select($str_sql);
    }
}