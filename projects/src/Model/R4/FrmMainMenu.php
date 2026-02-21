<?php

// 共通クラスの読込み
// App::uses('ClsComDb', 'Model/Component');

namespace App\Model\R4;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名    ：機能名Model相当の処理
// * 関数名    ：機能名
// * 処理説明    ：機能名のデータ処理クラス
//*************************************
class FrmMainMenu extends ClsComDb
{
    //*************************************
    // * SQL文
    //*************************************
    public function selectMDatarecep()
    {
        // ログインチェック
        $strSQL = '';
        $strSQL .= 'SELECT ';
        $strSQL .= ' TABLE_ID ';

        $strSQL .= ",TO_CHAR(BEF_GET_DT,'yyyy/MM/dd HH24:mi:ss') BEF_GET_DT ";
        $strSQL .= ',BEF_GET_COUNT ';
        $strSQL .= ",TO_CHAR(T_BEF_GET_DT,'yyyy/MM/dd HH24:mi:ss') T_BEF_GET_DT ";
        $strSQL .= ',T_BEF_GET_COUNT ';
        $strSQL .= ",TO_CHAR(BEF_CSVPUT_DT,'yyyy/MM/dd HH24:mi:ss') BEF_CSVPUT_DT ";
        $strSQL .= ",TO_CHAR(T_BEF_CSVPUT_DT,'yyyy/MM/dd HH24:mi:ss') T_BEF_CSVPUT_DT ";
        $strSQL .= ",TO_CHAR(UPD_DT,'yyyy/MM/dd HH24:mi:ss') UPD_DT ";
        $strSQL .= ",TO_CHAR(CRE_DT,'yyyy/MM/dd HH24:mi:ss') CRE_DT ";

        $strSQL .= 'FROM ';
        $strSQL .= ' M_DATARECEP ';

        return $strSQL;
    }

    public function menuSql($STYLE_ID, $PATTERN_ID, $SYS_KB)
    {
        $strSql = 'SELECT  VW.KAISOU_ID1';
        $strSql = $strSql . ' , VW.KAISOU_ID2';
        $strSql = $strSql . ' , VW.KAISOU_ID3';
        $strSql = $strSql . ' , VW.KAISOU_ID4';
        $strSql = $strSql . ' , VW.KAISOU_ID5';
        $strSql = $strSql . ' , VW.KAISOU_ID6';
        $strSql = $strSql . ' , VW.KAISOU_ID7';
        $strSql = $strSql . ' , VW.KAISOU_ID8';
        $strSql = $strSql . ' , VW.KAISOU_ID9';
        $strSql = $strSql . ' , VW.KAISOU_ID10';
        $strSql = $strSql . ' , VW.KAISOU_NM';
        $strSql = $strSql . ' , VW.PRO_NO';
        $strSql = $strSql . ' , PRO.PRO_NM';
        $strSql = $strSql . ' , PRO.PRO_ID';

        $strSql = $strSql . ' FROM ( ';
        $strSql = $strSql . ' SELECT  MAX(KAI.PRO_NO) PRO_NO';
        $strSql = $strSql . ' , MAX(KAI.KAISOU_NM) KAISOU_NM';
        $strSql = $strSql . ' , KAI.KAISOU_ID1';
        $strSql = $strSql . ' , KAI.KAISOU_ID2';
        $strSql = $strSql . ' , KAI.KAISOU_ID3';
        $strSql = $strSql . ' , KAI.KAISOU_ID4';
        $strSql = $strSql . ' , KAI.KAISOU_ID5';
        $strSql = $strSql . ' , KAI.KAISOU_ID6';
        $strSql = $strSql . ' , KAI.KAISOU_ID7';
        $strSql = $strSql . ' , KAI.KAISOU_ID8';
        $strSql = $strSql . ' , KAI.KAISOU_ID9';
        $strSql = $strSql . ' , KAI.KAISOU_ID10';

        $strSql = $strSql . '  FROM HMENUKAISOUMST KAI';
        $strSql = $strSql . ',(';
        $strSql = $strSql . ' Select  KAISOU.STYLE_ID';
        $strSql = $strSql . ' , KAISOU.PRO_NO';
        $strSql = $strSql . ' , KAISOU.KAISOU_ID1';
        $strSql = $strSql . ' , KAISOU.KAISOU_ID2';
        $strSql = $strSql . ' , KAISOU.KAISOU_ID3';
        $strSql = $strSql . ' , KAISOU.KAISOU_ID4';
        $strSql = $strSql . ' , KAISOU.KAISOU_ID5';
        $strSql = $strSql . ' , KAISOU.KAISOU_ID6';
        $strSql = $strSql . ' , KAISOU.KAISOU_ID7';
        $strSql = $strSql . ' , KAISOU.KAISOU_ID8';
        $strSql = $strSql . ' , KAISOU.KAISOU_ID9';
        $strSql = $strSql . ' , KAISOU.KAISOU_ID10';

        $strSql = $strSql . '  FROM HMENUKAISOUMST KAISOU';
        $strSql = $strSql . ', HMENUKANRIPATTERN PAT';
        $strSql = $strSql . '  WHERE(KAISOU.PRO_NO = PAT.PRO_NO)';
        $strSql = $strSql . '  AND KAISOU.STYLE_ID = PAT.STYLE_ID';

        $strSql = $strSql . "  AND PAT.STYLE_ID ='$STYLE_ID'";
        $strSql = $strSql . "  AND PAT.PATTERN_ID ='$PATTERN_ID'";
        $strSql = $strSql . "  AND KAISOU.SYS_KB ='$SYS_KB'";
        $strSql = $strSql . "  AND PAT.SYS_KB ='$SYS_KB'";

        $strSql = $strSql . ') V ';

        $strSql = $strSql . '  WHERE(KAI.KAISOU_ID1 = V.KAISOU_ID1)';
        $strSql = $strSql . '  AND (KAI.KAISOU_ID2 = V.KAISOU_ID2 OR KAI.KAISOU_ID2 = 0)';
        $strSql = $strSql . '  AND (KAI.KAISOU_ID3 = V.KAISOU_ID3 OR KAI.KAISOU_ID3 = 0)';
        $strSql = $strSql . '  AND (KAI.KAISOU_ID4 = V.KAISOU_ID4 OR KAI.KAISOU_ID4 = 0)';
        $strSql = $strSql . '  AND (KAI.KAISOU_ID5 = V.KAISOU_ID5 OR KAI.KAISOU_ID5 = 0)';
        $strSql = $strSql . '  AND (KAI.KAISOU_ID6 = V.KAISOU_ID6 OR KAI.KAISOU_ID6 = 0)';
        $strSql = $strSql . '  AND (KAI.KAISOU_ID7 = V.KAISOU_ID7 OR KAI.KAISOU_ID7 = 0)';
        $strSql = $strSql . '  AND (KAI.KAISOU_ID8 = V.KAISOU_ID8 OR KAI.KAISOU_ID8 = 0)';
        $strSql = $strSql . '  AND (KAI.KAISOU_ID9 = V.KAISOU_ID9 OR KAI.KAISOU_ID9 = 0)';
        $strSql = $strSql . '  AND (KAI.KAISOU_ID10 = V.KAISOU_ID10 OR KAI.KAISOU_ID10 = 0)';
        $strSql = $strSql . '  AND KAI.STYLE_ID = V.STYLE_ID ';
        $strSql = $strSql . "  AND KAI.SYS_KB = '$SYS_KB'";

        $strSql = $strSql . '  GROUP BY KAI.KAISOU_ID1 ';
        $strSql = $strSql . ' , KAI.KAISOU_ID2';
        $strSql = $strSql . ' , KAI.KAISOU_ID3';
        $strSql = $strSql . ' , KAI.KAISOU_ID4';
        $strSql = $strSql . ' , KAI.KAISOU_ID5';
        $strSql = $strSql . ' , KAI.KAISOU_ID6';
        $strSql = $strSql . ' , KAI.KAISOU_ID7';
        $strSql = $strSql . ' , KAI.KAISOU_ID8';
        $strSql = $strSql . ' , KAI.KAISOU_ID9';
        $strSql = $strSql . ' , KAI.KAISOU_ID10';
        $strSql = $strSql . ' ) VW ';
        $strSql = $strSql . ' LEFT JOIN HPROGRAMMST PRO';
        $strSql = $strSql . ' ON VW.PRO_NO = PRO.PRO_NO';
        $strSql = $strSql . " AND PRO.SYS_KB = '$SYS_KB'";

        $strSql = $strSql . ' ORDER BY ';
        $strSql = $strSql . ' VW.KAISOU_ID1';
        $strSql = $strSql . ',VW.KAISOU_ID2';
        $strSql = $strSql . ',VW.KAISOU_ID3';
        $strSql = $strSql . ',VW.KAISOU_ID4';
        $strSql = $strSql . ',VW.KAISOU_ID5';
        $strSql = $strSql . ',VW.KAISOU_ID6';
        $strSql = $strSql . ',VW.KAISOU_ID7';
        $strSql = $strSql . ',VW.KAISOU_ID8';
        $strSql = $strSql . ',VW.KAISOU_ID9';
        $strSql = $strSql . ',VW.KAISOU_ID10';
        //getFields();
        return $strSql;
    }

    //*************************************
    // * 公開メソッド
    //*************************************
    // public function select($strsql = null)
    // {
    //     try {
    //         // ユーザーIDの存在チェック
    //         $chkResult = parent::select($this->selectMDatarecep());

    //         //if($chkResult['result'] == "true"){
    //         // 正常時処理
    //         //if(count($chkResult['data']) > 0){
    //         // データ有り
    //         //$result = array("result"=>"TRUE", "data"=>$chkResult['data']);
    //         //}else{
    //         // データ無し
    //         //throw new Exception("該当するユーザーIDは登録されていません");
    //         //}
    //         //}else{
    //         // エラー時処理
    //         //throw new Exception($chkResult['data']);
    //         //}
    //     } catch (\Exception $e) {
    //         return array(
    //             'result' => 'FALSE',
    //             'data' => $e->getmessage(),
    //         );
    //     }

    //     return $chkResult;
    // }

    public function menu($STYLE_ID, $PATTERN_ID, $SYS_KB)
    {
        return parent::select($this->menuSql($STYLE_ID, $PATTERN_ID, $SYS_KB));
    }

    public function menulistSql($user_id)
    {
        //20240206 YIN UPD S
        // $strSql = "SELECT STYLE_ID, PATTERN_ID FROM M_LOGIN WHERE SYS_KB = '0' AND USER_ID = '$user_id'";
        $strSql = "";
        $strSql .= " SELECT LOG.USER_ID AS USER_ID " . "\r\n";
        $strSql .= ",       LOG.PATTERN_ID AS PATTERN_ID " . "\r\n";
        $strSql .= ",       SYA.SYAIN_NM AS SYAIN_NM " . "\r\n";
        $strSql .= ",       HAI.BUSYO_CD AS BUSYO_CD " . "\r\n";
        $strSql .= ",       LOG.STYLE_ID AS STYLE_ID " . "\r\n";
        $strSql .= " FROM   M_LOGIN LOG " . "\r\n";
        $strSql .= " LEFT JOIN HSYAINMST SYA " . "\r\n";
        $strSql .= " ON     SYA.SYAIN_NO = LOG.USER_ID " . "\r\n";
        $strSql .= " LEFT JOIN HHAIZOKU HAI " . "\r\n";
        $strSql .= " ON     SYA.SYAIN_NO = HAI.SYAIN_NO " . "\r\n";
        $strSql .= " AND    HAI.START_DATE <= TO_CHAR(SYSDATE,'YYYYMMDD') " . "\r\n";
        $strSql .= " AND    NVL(HAI.END_DATE,'99999999') >= TO_CHAR(SYSDATE,'YYYYMMDD') " . "\r\n";
        $strSql .= " WHERE USER_ID = '@LoginID' " . "\r\n";
        $strSql .= " AND SYS_KB = '0' " . "\r\n";
        $strSql = str_replace("@LoginID", $user_id, $strSql);
        //20240206 YIN UPD E
        return $strSql;
    }

    public function getmenulist($user_id)
    {
        $res = parent::select($this->menulistSql($user_id));

        return $res;
    }

    public function menulistSql1($user_id)
    {
        $strSql = "SELECT STYLE_ID, PATTERN_ID FROM M_LOGIN WHERE SYS_KB = '11' AND USER_ID = '$user_id'";

        return $strSql;
    }

    public function getmenulist1($user_id)
    {
        $res = parent::select($this->menulistSql1($user_id));

        return $res;
    }

    //---20161128 li INS S.
    //PPRのSession 設定のSQL
    public function menulistSql9($user_id, $sys_kb)
    {
        $strSQL = '';
        $strSQL .= ' SELECT LOG.USER_ID AS USER_ID ' . "\r\n";
        $strSQL .= ',       LOG.PATTERN_ID AS PATTERN_ID ' . "\r\n";
        $strSQL .= ',       SYA.SYAIN_NM AS SYAIN_NM ' . "\r\n";
        $strSQL .= ',       HAI.BUSYO_CD AS BUSYO_CD ' . "\r\n";
        $strSQL .= ',       LOG.STYLE_ID AS STYLE_ID ' . "\r\n";
        $strSQL .= ' FROM   M_LOGIN LOG ' . "\r\n";
        $strSQL .= ' LEFT JOIN HSYAINMST SYA ' . "\r\n";
        $strSQL .= ' ON     SYA.SYAIN_NO = LOG.USER_ID ' . "\r\n";
        $strSQL .= ' LEFT JOIN HHAIZOKU HAI ' . "\r\n";
        $strSQL .= ' ON     SYA.SYAIN_NO = HAI.SYAIN_NO ' . "\r\n";
        $strSQL .= " AND    HAI.START_DATE <= TO_CHAR(SYSDATE,'YYYYMMDD') " . "\r\n";
        $strSQL .= " AND    NVL(HAI.END_DATE,'99999999') >= TO_CHAR(SYSDATE,'YYYYMMDD') " . "\r\n";
        $strSQL .= " WHERE USER_ID = '@LoginID' " . "\r\n";
        $strSQL .= " AND SYS_KB = '@SYS_KB' " . "\r\n";

        $strSQL = str_replace('@LoginID', $user_id, $strSQL);
        $strSQL = str_replace('@SYS_KB', $sys_kb, $strSQL);

        return $strSQL;
    }

    //PPRのSession 設定
    public function getmenulist9($user_id, $sys_kb)
    {
        $res = parent::select($this->menulistSql9($user_id, $sys_kb));

        return $res;
    }

    //---20161128 li INS E.
}
