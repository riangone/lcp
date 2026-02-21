<?php
// 共通クラスの読込み
namespace App\Model\R4\R4G;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmFDHokanSelect extends ClsComDb
{

    function fncSearchTorokuyotei($postData)
    {

        $strSQL = "";
        $strSQL .= "SELECT";
        $strSQL .= "             DECODE(NVL(KEI.FD_CRE_FLG,0),1,'True','False') FD_CRE ";
        $strSQL .= " ,           DECODE(NVL(KEI.INP_FLG,0),1,'True','False') INP_FLG ";
        $strSQL .= " ,           KEI.KATASIKI_RUIBETU KATASIKI ";
        $strSQL .= " ,           KEI.SYADAI_NO CARNO ";
        $strSQL .= " ,           KEI.SINSEI_SIYO_NM SHI_USER_NM ";
        $strSQL .= " ,           KEI.SINSEI_SIYO_ADDR SHI_ADDRESS ";
        $strSQL .= " ,           KEI.SINSEI_SYOYU_NM SYO_USER_NM  ";
        $strSQL .= " ,           SINSEI_SYOYU_ADDR SYO_ADDRESS ";
        $strSQL .= " ,           KEI.CHUMN_NO ";
        $strSQL .= " ,           KEI.TOU_Y_DT ";
        $strSQL .= " ,           KEI.SYOYU_NM_SIYO ";
        $strSQL .= " ,           KEI.SYOYU_ADDR_SIYO ";
        $strSQL .= " FROM 		 HKEIJIREPORT KEI";
        $strSQL .= " WHERE       KEI.TOU_Y_DT > '";
        $strSQL .= date("Ymd", strtotime($postData['KAISHI'] . " -1    day"));
        $strSQL .= "'  AND      KEI.TOU_Y_DT < '";
        $strSQL .= date("Ymd", strtotime($postData['SYURYO'] . " +1    day"));
        $strSQL .= "'";

        if (isset($postData['Misakusei']) == true && $postData['Misakusei'] == "true") {
            $strSQL .= " AND   NVL(KEI.FD_CRE_FLG,'0') = '0'  ";
        }
        $strSQL .= "  ORDER BY  KEI.TOU_Y_DT, KEI.CHUMN_NO  ";

        return $strSQL;
    }

    public function fncFrmFDHokanSelect($postData)
    {
        $strSql = $this->fncSearchTorokuyotei($postData);
        return $this->m_run_sql($strSql);
    }

    public function m_run_sql($strSql)
    {
        /*
         * 运行sql文
         * 说明：返回数组。
         * 数组形式：{result:true,data:[key:value]}
         */

        return parent::select($strSql);

    }

}