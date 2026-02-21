<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE090ExhibitionEntry extends ClsComDb
{
    //*************************************
    // * SQL文
    //*************************************

    //基準日を取得する
    function baseFlagGetSQL()
    {
        $strSql = "";
        $strSql = $strSql . " SELECT START_DATE     ";
        $strSql = $strSql . " ,      END_DATE       ";
        $strSql = $strSql . " FROM   HDTIVENTDATA   ";
        $strSql = $strSql . " WHERE  BASE_FLG = '1' ";

        return $strSql;
    }

    //展示会データを取得する
    function calendarDayRenderSQL($STDT, $EDDT)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT START_DATE ";
        $strSql = $strSql . " ,      END_DATE   ";
        $strSql = $strSql . " ,      IVENT_NM   ";
        $strSql = $strSql . " ,      nvl(BASE_FLG,0) BASE_FLG  ";
        $strSql = $strSql . " FROM   HDTIVENTDATA ";
        $strSql = $strSql . " WHERE  (START_DATE >= @STDT ";
        $strSql = $strSql . " AND  START_DATE <= @EDDT) ";
        $strSql = $strSql . " OR  (END_DATE >= @STDT ";
        $strSql = $strSql . " AND  END_DATE <= @EDDT) ";
        $strSql = $strSql . " OR  (START_DATE <= @STDT ";
        $strSql = $strSql . " AND  END_DATE >= @EDDT) ";
        $strSql = str_replace("@STDT", $STDT, $strSql);
        $strSql = str_replace("@EDDT", $EDDT, $strSql);

        return $strSql;
    }

    //カレンダー内の日付セルクリック時,展示会データを取得する
    function calendarSelectionChangedSQL($STDT)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT TO_CHAR(TO_DATE(START_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') START_DATE ";
        $strSql = $strSql . " ,      TO_CHAR(TO_DATE(END_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') END_DATE   ";
        $strSql = $strSql . " ,      IVENT_NM   ";
        $strSql = $strSql . " ,      nvl(BASE_FLG,0) BASE_FLG  ";
        $strSql = $strSql . " ,      TO_CHAR(CREATE_DATE, 'YYYY/MM/DD hh24:mi:ss')　as CREATE_DATE   ";
        $strSql = $strSql . " FROM   HDTIVENTDATA ";
        $strSql = $strSql . " WHERE  START_DATE = '@STDT' ";

        $strSql = str_replace("@STDT", $STDT, $strSql);

        return $strSql;
    }

    //基準日ﾌﾗｸﾞに"1"が入っているものをNULLに更新する
    function baseFlagNullSql()
    {
        $strSql = "";
        $strSql = $strSql . " UPDATE HDTIVENTDATA ";
        $strSql = $strSql . " SET    BASE_FLG = NULL ";
        $strSql = $strSql . " WHERE  BASE_FLG = '1' ";

        return $strSql;
    }

    //展示会データを更新する
    function updateSql($postdata)
    {
        $strSql = "";
        $strSql = $strSql . " UPDATE   HDTIVENTDATA ";
        $strSql = $strSql . " SET  END_DATE = '@END_DATE' ";
        $strSql = $strSql . " ,    IVENT_NM = '@IVENT_NM' ";
        $strSql = $strSql . " ,    BASE_FLG = '@BASE_FLG' ";
        $strSql = $strSql . " ,    UPD_DATE = SYSDATE  ";

        //画面項目No13(作成日)がNULLの場合、SYSDATE　　以外は画面項目No13(作成日)
        if (!array_key_exists("CREATE_DATE", $postdata) || $postdata['CREATE_DATE'] == null || $postdata['CREATE_DATE'] == "") {
            $strSql = $strSql . ",    CREATE_DATE = SYSDATE  ";
        } else {
            $strSql = $strSql . " ,    CREATE_DATE = TO_DATE('@CREATE_DATE', 'YYYY/MM/DD hh24:mi:ss') ";
        }

        $strSql = $strSql . " ,    UPD_SYA_CD = '@UPD_SYA_CD' ";
        $strSql = $strSql . " ,    UPD_PRG_ID = '@UPD_PRG_ID' ";
        $strSql = $strSql . " ,    UPD_CLT_NM = '@UPD_CLT_NM' ";
        $strSql = $strSql . " WHERE  START_DATE = '@STDT' ";

        $strSql = str_replace("@END_DATE", $postdata['END_DATE'], $strSql);
        $strSql = str_replace("@IVENT_NM", $postdata['IVENT_NM'], $strSql);
        $strSql = str_replace("@BASE_FLG", $postdata['BASE_FLG'], $strSql);
        $strSql = str_replace("@CREATE_DATE", $postdata['CREATE_DATE'], $strSql);
        $strSql = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSql);
        $strSql = str_replace("@UPD_PRG_ID", $postdata['UPD_PRG_ID'], $strSql);
        $strSql = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSql);
        $strSql = str_replace("@STDT", $postdata['STDT'], $strSql);

        return $strSql;
    }

    //データ存在チェックSQL
    function existCheckSql($STDT)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT  START_DATE    " . "\r\n";
        $strSql = $strSql . " FROM    HDTIVENTDATA  " . "\r\n";
        $strSql = $strSql . " WHERE  START_DATE = '@STDT' " . "\r\n";

        $strSql = str_replace("@STDT", $STDT, $strSql);

        return $strSql;
    }

    //展示会データに追加する
    function insertSql($postdata)
    {
        $strSql = "";
        $strSql = $strSql . " INSERT INTO       " . "\r\n";
        $strSql = $strSql . " HDTIVENTDATA      " . "\r\n";
        $strSql = $strSql . " ( START_DATE,     " . "\r\n";
        $strSql = $strSql . "   END_DATE,       " . "\r\n";
        $strSql = $strSql . "   IVENT_NM,       " . "\r\n";
        $strSql = $strSql . "   BASE_FLG,       " . "\r\n";
        $strSql = $strSql . "   UPD_DATE,       " . "\r\n";
        $strSql = $strSql . "   CREATE_DATE,    " . "\r\n";
        $strSql = $strSql . "   UPD_SYA_CD,     " . "\r\n";
        $strSql = $strSql . "   UPD_PRG_ID,     " . "\r\n";
        $strSql = $strSql . "   UPD_CLT_NM      " . "\r\n";
        $strSql = $strSql . "   ) VALUES (      " . "\r\n";
        $strSql = $strSql . "   '@START_DATE',  " . "\r\n";
        $strSql = $strSql . "   '@END_DATE',   " . "\r\n";
        $strSql = $strSql . "   '@IVENT_NM',    " . "\r\n";
        $strSql = $strSql . "   @BASE_FLG,    " . "\r\n";
        $strSql = $strSql . "    SYSDATE,       " . "\r\n";

        //画面項目No13(作成日)がNULLの場合、SYSDATE　　以外は画面項目No13(作成日) ----insertの場合、CREATEDATEは必ずNULLですよね。。。
        if (!array_key_exists("CREATE_DATE", $postdata) || $postdata['CREATE_DATE'] == null || $postdata['CREATE_DATE'] == "") {
            $strSql = $strSql . "    SYSDATE,  " . "\r\n";
        } else {
            $strSql = $strSql . "     TO_DATE('@CREATE_DATE', 'YYYY/MM/DD hh24:mi:ss') ," . "\r\n";
            $strSql = str_replace("@CREATE_DATE", $postdata['CREATE_DATE'], $strSql);
        }

        $strSql = $strSql . "   '@UPD_SYA_CD',  " . "\r\n";
        $strSql = $strSql . "   '@UPD_PRG_ID',  " . "\r\n";
        $strSql = $strSql . "   '@UPD_CLT_NM' ) " . "\r\n";

        $strSql = str_replace("@START_DATE", $postdata['STDT'], $strSql);
        $strSql = str_replace("@END_DATE", $postdata['END_DATE'], $strSql);
        $strSql = str_replace("@IVENT_NM", $postdata['IVENT_NM'], $strSql);
        $strSql = str_replace("@BASE_FLG", $postdata['BASE_FLG'], $strSql);
        $strSql = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSql);
        $strSql = str_replace("@UPD_PRG_ID", $postdata['UPD_PRG_ID'], $strSql);
        $strSql = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSql);

        return $strSql;
    }

    //展示会データの削除処理する
    function deleteSql($STDT)
    {
        $strSql = "";
        $strSql = $strSql . " DELETE FROM HDTIVENTDATA " . "\r\n";
        $strSql = $strSql . " WHERE  START_DATE = '@STDT' " . "\r\n";

        $strSql = str_replace("@STDT", $STDT, $strSql);

        return $strSql;
    }

    //展示会データを取得する
    function bindDetailViewSql($STDT)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT TO_CHAR(TO_DATE(START_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') START_DATE ";
        $strSql = $strSql . " ,      TO_CHAR(TO_DATE(END_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') END_DATE   ";
        $strSql = $strSql . " ,      IVENT_NM   ";
        $strSql = $strSql . " ,      nvl(BASE_FLG,0) BASE_FLG  ";
        $strSql = $strSql . " ,      TO_CHAR(CREATE_DATE, 'YYYY/MM/DD hh24:mi:ss')　as CREATE_DATE   ";
        $strSql = $strSql . " FROM   HDTIVENTDATA ";
        $strSql = $strSql . " WHERE  START_DATE = '@STDT' ";

        $strSql = str_replace("@STDT", $STDT, $strSql);

        return $strSql;
    }

    //基準日を取得する
    public function baseFlagGet()
    {
        return parent::select($this->baseFlagGetSQL());
    }

    //展示会データを取得する
    public function calendarDayRender($STDT, $EDDT)
    {
        return parent::select($this->calendarDayRenderSQL($STDT, $EDDT));
    }

    //カレンダー内の日付セルクリック時,展示会データを取得する
    public function calendarSelectionChanged($STDT)
    {
        return parent::select($this->calendarSelectionChangedSQL($STDT));
    }

    //基準日ﾌﾗｸﾞに"1"が入っているものをNULLに更新する
    public function baseFlagNull()
    {
        return parent::update($this->baseFlagNullSql());
    }

    //展示会データを更新する
    public function update($postdata)
    {
        return parent::update($this->updateSql($postdata));
    }

    //展示会データに追加する
    public function insert($postdata)
    {
        return parent::insert($this->insertSql($postdata));
    }

    //データ存在チェックSQL
    public function existCheck($STDT)
    {
        return parent::select($this->existCheckSql($STDT));
    }

    //展示会データの削除処理する
    public function delete($STDT)
    {
        return parent::delete($this->deleteSql($STDT));
    }

    //展示会データを取得する
    public function bindDetailView($STDT)
    {
        return parent::select($this->bindDetailViewSql($STDT));
    }

}
