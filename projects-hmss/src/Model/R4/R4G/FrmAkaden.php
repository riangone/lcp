<?php
// 共通クラスの読込み
namespace App\Model\R4\R4G;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmAkaden extends ClsComDb
{
    public function fncFrmAkaden($postData)
    {
        $strSql = $this->get_sql($postData);
        return $this->m_run_sql($strSql);
    }

    //*************************************
    // * SQL文
    //*************************************
    public function get_sql($postData)
    {
        /*
         * 生成sql文
         * 参数：数据数?
         */

        $strsql = "";
        $strsql .= "SELECT KASO.CMN_NO";
        $strsql .= ",      KASO.EDA_NO	";
        $strsql .= ",      KASO.KASOUNO	";
        $strsql .= ",      KASO.SYADAIKATA	";
        $strsql .= ",      KASO.CAR_NO		";
        $strsql .= ",      KASO.HANBAISYASYU	";
        $strsql .= ",      KASO.TOIAWASENM		";
        $strsql .= ",      KASO.SYASYU_NM		";
        $strsql .= ",      KASO.MEMO			";
        $strsql .= ",      KASO.FUZOKUHINKBN	";
        $strsql .= ",      KASO.GYOUSYA_CD		";
        $strsql .= ",      KASO.GYOUSYA_NM		";
        $strsql .= ",      KASO.MEDALCD			";
        $strsql .= ",      KASO.BUHINNM			";
        $strsql .= ",      KASO.BIKOU			";
        $strsql .= ",      KASO.SUURYOU				";
        $strsql .= ",      KASO.TEIKA				";
        $strsql .= ",      KASO.BUHIN_SYANAI_GEN	";
        $strsql .= ",      KASO.BUHIN_SYANAI_ZITU	";
        $strsql .= ",      KASO.GAICYU_GEN			";
        $strsql .= ",      KASO.GAICYU_ZITU			";
        $strsql .= ",      KASO.ZEIRITU				";
        $strsql .= ",      KASO.KAZEIKBN			";
        $strsql .= ",      KASO.DELKBN				";
        $strsql .= ", to_char(KASO.UPD_DATE,'yyyy/mm/dd HH:MI:SS')";
        //$strsql .= ",      KASO.UPD_DATE			";
        $strsql .= ", to_char(KASO.CREATE_DATE,'yyyy/mm/dd HH:MI:SS')";
        //$strsql .= ",      KASO.CREATE_DATE				";
        $strsql .= ",      (OKY.INP_SIM1 || OKY.INP_SIM2) SIYOSYA	";
        $strsql .= ",      BUS.KYOTN_NM			";
        $strsql .= ",      (SYA.SYAIN_KNJ_SEI || '  ' || SYA.SYAIN_KNJ_MEI) SYAIN	";
        $strsql .= ",      CMN.KYOTN_CD			";
        $strsql .= " FROM   HKASOUMEISAI KASO	";
        $strsql .= " LEFT JOIN M41E10 CMN		";
        $strsql .= " ON        KASO.CMN_NO = CMN.CMN_NO	";
        $strsql .= " LEFT JOIN M27M01 BUS					";
        $strsql .= " ON        BUS.KYOTN_CD = CMN.KYOTN_CD	";
        $strsql .= " AND       BUS.HANSH_CD = '3634'	";
        $strsql .= " AND       BUS.ES_KB = 'E'	";
        $strsql .= " LEFT JOIN M29MA4 SYA	";
        $strsql .= " ON        SYA.SYAIN_NO = CMN.HNB_TAN_EMP_NO	";
        $strsql .= " LEFT JOIN M41C01 OKY			";
        $strsql .= " ON        OKY.DLRCSRNO = CMN.SIY_CUS_NO		";
        $strsql .= " WHERE     1=1	";
        if (isset($postData['SIYFGN']) == true && $postData['SIYFGN'] != "" && $postData['SIYFGN'] != null) {
            $strsql .= " AND      CMN.SIY_FGN = '" . $postData['SIYFGN'] . "'		";
        }
        if (isset($postData['EMPNO']) == true && $postData['EMPNO'] != "" && $postData['EMPNO'] != null) {
            $strsql .= " AND      CMN.HNB_TAN_EMP_NO = '" . $postData['EMPNO'] . "'		";
        }
        if (isset($postData['CMN_NO']) == true && $postData['CMN_NO'] != "" && $postData['CMN_NO'] != null) {
            $strsql .= " AND      KASO.CMN_NO = '" . $postData['CMN_NO'] . "'		";
        }
        $strsql .= " ORDER BY NVL(KASO.GYOUSYA_CD,0) , KASO.FUZOKUHINKBN , KASO.KASOUNO , KASO.EDA_NO ";

        return $strsql;
    }

    public function fncSelect_sql()
    {
        $strsql = "SELECT TANTO_SEI, BUSYO_NM FROM HPRINTTANTO ";
        return $strsql;
    }

    public function fncDelete_sql($where)
    {
        $strsql = "";
        $strsql .= "DELETE FROM HKASOUMEISAI  ";
        $strsql .= $where;
        return $strsql;
    }

    //*************************************
    // * 公開メソッド
    //*************************************
    public function m_run_sql($strSql)
    {
        /*
         * ?行sql文
         * ?明：返回数?。
         * 数?形式：{result:true,data:[key:value]}
         */
        return parent::select($strSql);
    }

    public function fncHPRINTTANT()
    {
        return parent::select($this->fncSelect_sql());
    }

    public function fncDeleteKasou($where)
    {
        return parent::Do_Execute($this->fncDelete_sql($where));
    }

    public function fncFrmAkaden_part($postData)
    {
        $strSql = $this->get_sql($postData);
        return $this->m_run_sql($strSql);
    }

}
