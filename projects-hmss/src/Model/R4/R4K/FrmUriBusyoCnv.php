<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmUriBusyoCnv extends ClsComDb
{
    //********************************************************************
    //処理概要：SpreadリストSQL
    //引　　数：
    //戻 り 値：SQL
    //********************************************************************
    public function fncListSel_sql($post_mark, $post_txtSYAINNO, $post_txtSYAINKN)
    {
        $strsql = "";
        $strsql .= " SELECT " . "\r\n";
        $strsql .= "     URI.CMN_NO " . "\r\n";
        $strsql .= "     ,URI.UC_NO " . "\r\n";
        $strsql .= "     ,SYAIN.SYAIN_NO " . "\r\n";
        $strsql .= "     ,SYAIN.SYAIN_NM " . "\r\n";
        $strsql .= "     ,URI.URI_BUSYO_CD " . "\r\n";
        $strsql .= "     ,BU.BUSYO_NM " . "\r\n";
        $strsql .= "     ,HAIZOKU.BUSYO_CD" . "\r\n";
        $strsql .= "     ,HB.BUSYO_NM " . "\r\n";
        $strsql .= " FROM HSCURI URI " . "\r\n";
        $strsql .= "     ,HSYAINMST SYAIN " . "\r\n";
        $strsql .= "     ,HBUSYO BU " . "\r\n";
        $strsql .= "     ,HBUSYO HB " . "\r\n";
        $strsql .= "     ,HURIBUSYOCNV HAIZOKU " . "\r\n";
        $strsql .= " WHERE(Uri.URI_TANNO = SYAIN.SYAIN_NO) " . "\r\n";
        $strsql .= "     AND URI.URI_BUSYO_CD=BU.BUSYO_CD " . "\r\n";
        $strsql .= "     AND HAIZOKU.BUSYO_CD=HB.BUSYO_CD " . "\r\n";
        $strsql .= "     AND HAIZOKU.CMN_NO=URI.CMN_NO " . "\r\n";

        //画面初期化以外
        if ($post_mark != 0) {

            if (($post_txtSYAINNO == "" || $post_txtSYAINNO == null) == FALSE) {
                $strsql .= "     AND SYAIN.SYAIN_NO = '" . $post_txtSYAINNO . "' " . "\r\n";
            }
            if (($post_txtSYAINKN == "" || $post_txtSYAINKN == null) == FALSE) {
                //20240520 lujunxia PHP8 upd s
                //$strsql .= "     AND (SYAIN.SYAIN_KN  LIKE '" . mb_convert_kana($post_txtSYAINKN, "Ckh") . "%' " . "\r\n";
                $strsql .= "     AND (SYAIN.SYAIN_KN  LIKE '" . mb_convert_kana(mb_convert_kana($post_txtSYAINKN, "kh"), "C") . "%' " . "\r\n";
                //20240520 lujunxia PHP8 upd e
                $strsql .= "     OR SYAIN.SYAIN_KN  LIKE '" . mb_convert_kana($post_txtSYAINKN, "C") . "%' " . "\r\n";
                $strsql .= "     OR SYAIN.SYAIN_KN  LIKE '" . $post_txtSYAINKN . "%' )" . "\r\n";
            }
        }
        $strsql .= " ORDER BY HAIZOKU.UPD_DATE DESC " . "\r\n";
        return $strsql;
    }

    public function fncListSel($post_mark, $post_txtSYAINNO, $post_txtSYAINKN)
    {
        return parent::select($this->fncListSel_sql($post_mark, $post_txtSYAINNO, $post_txtSYAINKN));
    }

    //********************************************************************
    //処理概要：データ削除SQL
    //引　　数：なし
    //戻 り 値：SQL
    //********************************************************************
    public function fncDeletData_sql($post_CMNNO)
    {
        $strsql = "";
        $strsql .= " DELETE FROM HURIBUSYOCNV " . "\r\n";
        $strsql .= " WHERE CMN_NO = '" . $post_CMNNO . "'" . "\r\n";
        return $strsql;
    }

    public function fncDeletData($post_CMNNO)
    {
        return parent::delete($this->fncDeletData_sql($post_CMNNO));
    }

}