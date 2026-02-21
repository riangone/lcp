<?php
// 共通クラスの読込み
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmSyoreikinSyoriMente extends ClsComDb
{
    public $ClsComFncJKSYS;
    //奨励金処理マスタ_取得SQL
    public function fncSyoreiKinSyoriSQL($strSyubetuCd, $pcnGyoHanbaiRoute, $strCode1 = '', $strCode2 = '', $cmbFlg = false)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        switch ($strSyubetuCd) {
            case "10000":
            case "20000":
                //係数種類
                $strSQL .= " SELECT   SRM.CODE " . "\r\n";
                $strSQL .= "         ,SRM.MEISYO " . "\r\n";
                $strSQL .= "         ,SRM.ATAI_1 " . "\r\n";
                $strSQL .= "         ,DECODE(SRM.ATAI_1,'1','有','無') ATAI_1_NM " . "\r\n";
                $strSQL .= "         ,SRM.ATAI_2 " . "\r\n";
                $strSQL .= "         ,SRM.HYOJI_JUN " . "\r\n";
                $strSQL .= " FROM     JKSYOREIKINMST SRM" . "\r\n";
                $strSQL .= " WHERE    " . "\r\n";
                break;
            case "11000":
                //業績奨励_支給対象
                $strSQL .= " SELECT   SUBSTR(SRM.CODE,1,3) SYOKUSYU" . "\r\n";
                $strSQL .= "         ,CDM.MEISYOU SYOKUSYU_NM" . "\r\n";
                $strSQL .= "         ,SUBSTR(SRM.CODE,4,3) BUSYO" . "\r\n";
                $strSQL .= "         ,BSM.BUSYO_NM " . "\r\n";
                $strSQL .= "         ,SRM.ATAI_1 ROUTE" . "\r\n";
                $strSQL .= "         ,HBR.MEISYO ROUTE_NM" . "\r\n";
                $strSQL .= " FROM    (SELECT CODE " . "\r\n";
                $strSQL .= "                ,MEISYO" . "\r\n";
                $strSQL .= "          FROM   JKSYOREIKINMST" . "\r\n";
                $strSQL .= "          WHERE  SYUBETU_CD = @HANBAIROUTE) HBR" . "\r\n";
                $strSQL .= "         ,JKCODEMST CDM" . "\r\n";
                $strSQL .= "         ,JKBUMON BSM" . "\r\n";
                $strSQL .= "         ,JKSYOREIKINMST SRM" . "\r\n";
                $strSQL .= " WHERE  TRIM(SRM.ATAI_1) =  HBR.CODE" . "\r\n";
                $strSQL .= "   AND  SUBSTR(SRM.CODE,4,3) =  BSM.BUSYO_CD" . "\r\n";
                $strSQL .= "   AND  SUBSTR(SRM.CODE,1,3) =  CDM.CODE" . "\r\n";
                $strSQL .= "   AND  'SYOKUSYU' = CDM.ID" . "\r\n";
                $strSQL .= "   AND  " . "\r\n";
                break;
            case "21000":
                //店長奨励_支給対象
                $strSQL .= " SELECT   SUBSTR(SRM.CODE,1,3) BUSYO" . "\r\n";
                $strSQL .= "         ,BSM.BUSYO_NM " . "\r\n";
                $strSQL .= "         ,SUBSTR(SRM.CODE,4,3) SYOKUSYU" . "\r\n";
                $strSQL .= "         ,CDM.MEISYOU SYOKUSYU_NM" . "\r\n";
                $strSQL .= "         ,SRM.ATAI_1 ROUTE" . "\r\n";
                $strSQL .= "         ,HBR.MEISYO ROUTE_NM" . "\r\n";
                $strSQL .= " FROM    (SELECT CODE " . "\r\n";
                $strSQL .= "                ,MEISYO" . "\r\n";
                $strSQL .= "          FROM   JKSYOREIKINMST" . "\r\n";
                $strSQL .= "          WHERE  SYUBETU_CD = @HANBAIROUTE) HBR" . "\r\n";
                $strSQL .= "         ,JKCODEMST CDM" . "\r\n";
                $strSQL .= "         ,JKBUMON BSM" . "\r\n";
                $strSQL .= "         ,JKSYOREIKINMST SRM" . "\r\n";
                $strSQL .= " WHERE  TRIM(SRM.ATAI_1) =  HBR.CODE" . "\r\n";
                $strSQL .= "   AND  SUBSTR(SRM.CODE,1,3) =  BSM.BUSYO_CD" . "\r\n";
                $strSQL .= "   AND  SUBSTR(SRM.CODE,4,3) =  CDM.CODE" . "\r\n";
                $strSQL .= "   AND  'SYOKUSYU' = CDM.ID" . "\r\n";
                $strSQL .= "   AND  " . "\r\n";
                break;
            case "12000":
            case "22000":
                //掛け率
                $strSQL .= " SELECT   SRM.ATAI_1 KAKERITU " . "\r\n";
                $strSQL .= " FROM     JKSYOREIKINMST SRM" . "\r\n";
                $strSQL .= " WHERE    " . "\r\n";
                break;
            case "JOGEN":
                //支給上限
                if ($strCode2 != "") {
                    $strSQL .= " SELECT   SUBSTR(SRM.CODE,2,2) KOYOU" . "\r\n";
                    $strSQL .= "         ,KBM.KUBUN_NM KOYOU_NM" . "\r\n";
                    $strSQL .= "         ,SUBSTR(SRM.CODE,4,3) SYOKUSYU" . "\r\n";
                    $strSQL .= "         ,CDM.MEISYOU SYOKUSYU_NM" . "\r\n";
                    $strSQL .= "         ,SRM.ATAI_1 JOGEN" . "\r\n";
                    $strSQL .= " FROM     JKCODEMST CDM" . "\r\n";
                    $strSQL .= "         ,JKKUBUNMST KBM" . "\r\n";
                    $strSQL .= "         ,JKSYOREIKINMST SRM" . "\r\n";
                } else {
                    $strSQL .= " SELECT   SRM.ATAI_1 JOGEN" . "\r\n";
                    $strSQL .= " FROM     JKSYOREIKINMST SRM" . "\r\n";
                }
                $strSQL .= " WHERE    " . "\r\n";
                break;
            case "21100":
                //限界/経常利益取得部署
                $strSQL .= " SELECT   SRM.CODE BUSYO" . "\r\n";
                $strSQL .= "         ,BSM.BUSYO_NM BUSYO_NM" . "\r\n";
                $strSQL .= "         ,SRM.ATAI_1 RIEKI" . "\r\n";
                $strSQL .= "         ,RKM.BUSYO_NM RIEKI_NM" . "\r\n";
                $strSQL .= "         ,SRM.ATAI_2 GENKAI" . "\r\n";
                $strSQL .= "         ,GKM.BUSYO_NM GENKAI_NM" . "\r\n";
                $strSQL .= " FROM     JKSYOREIKINMST SRM" . "\r\n";
                $strSQL .= "          LEFT JOIN JKBUMON BSM" . "\r\n";
                $strSQL .= "          ON SRM.CODE = BSM.BUSYO_CD" . "\r\n";
                $strSQL .= "          LEFT JOIN JKBUMON RKM" . "\r\n";
                $strSQL .= "          ON SRM.ATAI_1 = RKM.BUSYO_CD" . "\r\n";
                $strSQL .= "          LEFT JOIN JKBUMON GKM" . "\r\n";
                $strSQL .= "          ON SRM.ATAI_2 = GKM.BUSYO_CD" . "\r\n";
                $strSQL .= " WHERE    " . "\r\n";
                break;
            default:
                //係数項目/販売ルート
                $strSQL .= " SELECT   SRM.CODE " . "\r\n";
                $strSQL .= "         ,SRM.MEISYO " . "\r\n";
                $strSQL .= "         ,SRM.ATAI_1 " . "\r\n";
                $strSQL .= "         ,SRM.ATAI_2 " . "\r\n";
                $strSQL .= "         ,SRM.HYOJI_JUN " . "\r\n";
                $strSQL .= " FROM     JKSYOREIKINMST SRM" . "\r\n";
                $strSQL .= " WHERE    " . "\r\n";

                break;
        }
        $strSQL .= "        SRM.SYUBETU_CD = @SYUBETUCD" . "\r\n";
        if ($strCode1 != "") {
            $strSQL .= "   AND  SRM.CODE = @CODE1" . "\r\n";
        }
        if ($strCode2 != "") {
            $strSQL .= "   AND  SUBSTR(SRM.CODE,2,2) =  KBM.KUBUN_CD" . "\r\n";
            $strSQL .= "   AND  'KOYOU' = KBM.KUBUN_ID" . "\r\n";
            $strSQL .= "   AND  SUBSTR(SRM.CODE,4,3) =  CDM.CODE" . "\r\n";
            $strSQL .= "   AND  'SYOKUSYU' = CDM.ID" . "\r\n";
            $strSQL .= "   AND  SUBSTR(SRM.CODE,1,1) = @CODE2" . "\r\n";
            $strSQL .= "   AND  SRM.CODE <> @CODE2" . "\r\n";
        }
        $strSQL .= " ORDER BY SRM.HYOJI_JUN, SRM.CODE" . "\r\n";
        //----- パラメータ　-----
        $strSQL = str_replace("@SYUBETUCD", $this->ClsComFncJKSYS->FncSqlNv($strSyubetuCd), $strSQL);
        $strSQL = str_replace("@CODE1", $this->ClsComFncJKSYS->FncSqlNv($strCode1), $strSQL);
        $strSQL = str_replace("@CODE2", $this->ClsComFncJKSYS->FncSqlNv($strCode2), $strSQL);
        $strSQL = str_replace("@HANBAIROUTE", $this->ClsComFncJKSYS->FncSqlNv($pcnGyoHanbaiRoute), $strSQL);

        return parent::select($strSQL);
    }

    //業績奨励_係数種類_更新SQL
    //<param name="strCode">コード</param>
    //<param name="strTani">支給計算書表示単位</param>
    //<param name="strHyojijun">表示順</param>
    public function fncUpdGyoKeisuSyuruiSQL($strCode, $strTani, $strHyojijun)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " UPDATE   JKSYOREIKINMST " . "\r\n";
        $strSQL .= " SET      ATAI_2 = @TANI " . "\r\n";
        $strSQL .= "         ,HYOJI_JUN = @HYOJIJUN " . "\r\n";
        $strSQL .= "         ,UPD_DATE = SYSDATE " . "\r\n";
        $strSQL .= "         ,UPD_SYA_CD = '" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSQL .= "         ,UPD_PRG_ID = 'FrmSyoreikinSyoriMen' " . "\r\n";
        $strSQL .= "         ,UPD_CLT_NM = '" . $this->GS_LOGINUSER['strClientNM'] . "' " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = '10000' " . "\r\n";
        $strSQL .= " AND      CODE = @CODE " . "\r\n";

        $strSQL = str_replace("@TANI", $this->ClsComFncJKSYS->FncSqlNv($strTani), $strSQL);
        $strSQL = str_replace("@HYOJIJUN", $this->ClsComFncJKSYS->FncSqlNv($strHyojijun), $strSQL);
        $strSQL = str_replace("@CODE", $this->ClsComFncJKSYS->FncSqlNv($strCode), $strSQL);

        return parent::update($strSQL);
    }

    //奨励金処理マスタ_削除SQL
    public function fncDelSyoreiKinSyoriSQL($strSyubetuCd, $strCode = '')
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " DELETE   JKSYOREIKINMST " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = @SYUBETUCD " . "\r\n";
        if ($strCode != "") {
            $strSQL .= "   AND  SUBSTR(CODE,1,1) = @CODE" . "\r\n";
            $strSQL .= "   AND  CODE <> @CODE" . "\r\n";
        }
        //----- パラメータ　-----
        $strSQL = str_replace("@SYUBETUCD", $this->ClsComFncJKSYS->FncSqlNv($strSyubetuCd), $strSQL);
        $strSQL = str_replace("@CODE", $this->ClsComFncJKSYS->FncSqlNv($strCode), $strSQL);

        return parent::delete($strSQL);
    }

    //業績奨励_係数項目_更新SQL
    // <param name="strSyubetuCd">種別コード</param>
    // <param name="strCode">コード</param>
    // <param name="strMeisyo">名称</param>
    // <param name="strRitsu">掛け率</param>
    // <param name="strRoute">表示販売ルート名</param>
    // <param name="strHyojijun">表示順</param>
    public function fncInsGyokeisuKomokuSQL($strSyubetuCd, $strCode, $strMeisyo, $strRitsu, $strRoute, $strHyojijun)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " INSERT INTO JKSYOREIKINMST " . "\r\n";
        $strSQL .= "     (SYUBETU_CD " . "\r\n";
        $strSQL .= "     ,CODE " . "\r\n";
        $strSQL .= "     ,MEISYO " . "\r\n";
        $strSQL .= "     ,ATAI_1 " . "\r\n";
        $strSQL .= "     ,ATAI_2 " . "\r\n";
        $strSQL .= "     ,HYOJI_JUN " . "\r\n";
        $strSQL .= "     ,CREATE_DATE " . "\r\n";
        $strSQL .= "     ,CRE_SYA_CD " . "\r\n";
        $strSQL .= "     ,CRE_PRG_ID " . "\r\n";
        $strSQL .= "     ,UPD_CLT_NM) " . "\r\n";
        $strSQL .= "VALUES (@SYUBETUCD " . "\r\n";
        $strSQL .= "       ,@CODE " . "\r\n";
        $strSQL .= "       ,@MEISYO " . "\r\n";
        $strSQL .= "       ,@RITSU " . "\r\n";
        $strSQL .= "       ,@ROUTE " . "\r\n";
        $strSQL .= "       ,@HYOJIJUN " . "\r\n";
        $strSQL .= "       ,SYSDATE " . "\r\n";
        $strSQL .= "       ,'" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSQL .= "       ,'FrmSyoreikinSyoriMen' " . "\r\n";
        $strSQL .= "       ,'" . $this->GS_LOGINUSER['strClientNM'] . "') " . "\r\n";
        //----- パラメータ　-----
        $strSQL = str_replace("@SYUBETUCD", $this->ClsComFncJKSYS->FncSqlNv($strSyubetuCd), $strSQL);
        $strSQL = str_replace("@CODE", $this->ClsComFncJKSYS->FncSqlNv($strCode), $strSQL);
        $strSQL = str_replace("@MEISYO", $this->ClsComFncJKSYS->FncSqlNv($strMeisyo), $strSQL);
        $strSQL = str_replace("@RITSU", $this->ClsComFncJKSYS->FncSqlNv($strRitsu), $strSQL);
        $strSQL = str_replace("@ROUTE", $this->ClsComFncJKSYS->FncSqlNv($strRoute), $strSQL);
        $strSQL = str_replace("@HYOJIJUN", $this->ClsComFncJKSYS->FncSqlNv($strHyojijun), $strSQL);

        return parent::insert($strSQL);
    }

    //業績/店長奨励_対象販売ルート_更新SQL
    // <param name="strSyubetuCd">種別コード</param>
    // <param name="strCode">販売ルート</param>
    // <param name="strMeisyo">販売ルート名</param>
    public function fncInsTaisyoRouteSQL($strSyubetuCd, $strCode, $strMeisyo)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " INSERT INTO JKSYOREIKINMST " . "\r\n";
        $strSQL .= "     (SYUBETU_CD " . "\r\n";
        $strSQL .= "     ,CODE " . "\r\n";
        $strSQL .= "     ,MEISYO " . "\r\n";
        $strSQL .= "     ,CREATE_DATE " . "\r\n";
        $strSQL .= "     ,CRE_SYA_CD " . "\r\n";
        $strSQL .= "     ,CRE_PRG_ID " . "\r\n";
        $strSQL .= "     ,UPD_CLT_NM) " . "\r\n";
        $strSQL .= "VALUES (@SYUBETUCD " . "\r\n";
        $strSQL .= "       ,@CODE " . "\r\n";
        $strSQL .= "       ,@MEISYO " . "\r\n";
        $strSQL .= "       ,SYSDATE " . "\r\n";
        $strSQL .= "       ,'" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSQL .= "       ,'FrmSyoreikinSyoriMen' " . "\r\n";
        $strSQL .= "       ,'" . $this->GS_LOGINUSER['strClientNM'] . "') " . "\r\n";
        //----- パラメータ　-----
        $strSQL = str_replace("@SYUBETUCD", $this->ClsComFncJKSYS->FncSqlNv($strSyubetuCd), $strSQL);
        $strSQL = str_replace("@CODE", $this->ClsComFncJKSYS->FncSqlNv($strCode), $strSQL);
        $strSQL = str_replace("@MEISYO", $this->ClsComFncJKSYS->FncSqlNv($strMeisyo), $strSQL);

        return parent::insert($strSQL);
    }

    //業績奨励_支給対象_更新SQL
    // <param name="strSyubetuCd">種別コード</param>
    // <param name="strSyokusyu">職種コード</param>
    // <param name="strBusyo">部署コード</param>
    // <param name="strRoute">販売ルート</param>
    public function fncInsGyoTaisyoSQL($strSyubetuCd, $strSyokusyu, $strBusyo, $strRoute)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " INSERT INTO JKSYOREIKINMST " . "\r\n";
        $strSQL .= "     (SYUBETU_CD " . "\r\n";
        $strSQL .= "     ,CODE " . "\r\n";
        $strSQL .= "     ,MEISYO " . "\r\n";
        $strSQL .= "     ,ATAI_1 " . "\r\n";
        $strSQL .= "     ,CREATE_DATE " . "\r\n";
        $strSQL .= "     ,CRE_SYA_CD " . "\r\n";
        $strSQL .= "     ,CRE_PRG_ID " . "\r\n";
        $strSQL .= "     ,UPD_CLT_NM) " . "\r\n";
        $strSQL .= "VALUES (@SYUBETUCD " . "\r\n";
        $strSQL .= "       ,@CODE " . "\r\n";
        $strSQL .= "       ,'業績奨励手当支給対象コード(職種&部署)' " . "\r\n";
        $strSQL .= "       ,@ROUTE " . "\r\n";
        $strSQL .= "       ,SYSDATE " . "\r\n";
        $strSQL .= "       ,'" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSQL .= "       ,'FrmSyoreikinSyoriMen' " . "\r\n";
        $strSQL .= "       ,'" . $this->GS_LOGINUSER['strClientNM'] . "') " . "\r\n";

        $strSQL = str_replace("@SYUBETUCD", $this->ClsComFncJKSYS->FncSqlNv($strSyubetuCd), $strSQL);
        $strSQL = str_replace("@CODE", $this->ClsComFncJKSYS->FncSqlNv($strSyokusyu . $strBusyo), $strSQL);
        $strSQL = str_replace("@ROUTE", $this->ClsComFncJKSYS->FncSqlNv($strRoute), $strSQL);

        return parent::insert($strSQL);
    }

    //業績奨励_支給上限_正社員_更新SQL
    // <param name="strCode"></param>
    // <param name="strAtai">支給上限額</param>
    public function fncUpdJogenSQL($strCode, $strAtai)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " UPDATE   JKSYOREIKINMST " . "\r\n";
        $strSQL .= " SET      ATAI_1 = @ATAI " . "\r\n";
        $strSQL .= "         ,UPD_DATE = SYSDATE " . "\r\n";
        $strSQL .= "         ,UPD_SYA_CD = '" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSQL .= "         ,UPD_PRG_ID = 'FrmSyoreikinSyoriMen' " . "\r\n";
        $strSQL .= "         ,UPD_CLT_NM = '" . $this->GS_LOGINUSER['strClientNM'] . "' " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = 'JOGEN' " . "\r\n";
        $strSQL .= " AND      CODE = @CODE " . "\r\n";

        $strSQL = str_replace("@CODE", $this->ClsComFncJKSYS->FncSqlNv($strCode), $strSQL);
        $strSQL = str_replace("@ATAI", $this->ClsComFncJKSYS->FncSqlNv(str_replace(",", "", $strAtai)), $strSQL);

        return parent::update($strSQL);
    }

    //業績奨励_支給上限_正社員以外_更新SQL
    // <param name="strSyubetuCd">種別コード</param>
    // <param name="strKoyou">雇用区分コード</param>
    // <param name="strSyokusyu">職種コード</param>
    // <param name="strAtai">支給上限額</param>
    public function fncInsGyoJogenSQL($strSyubetuCd, $strKoyou, $strSyokusyu, $strAtai)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " INSERT INTO JKSYOREIKINMST " . "\r\n";
        $strSQL .= "     (SYUBETU_CD " . "\r\n";
        $strSQL .= "     ,CODE " . "\r\n";
        $strSQL .= "     ,MEISYO " . "\r\n";
        $strSQL .= "     ,ATAI_1 " . "\r\n";
        $strSQL .= "     ,CREATE_DATE " . "\r\n";
        $strSQL .= "     ,CRE_SYA_CD " . "\r\n";
        $strSQL .= "     ,CRE_PRG_ID " . "\r\n";
        $strSQL .= "     ,UPD_CLT_NM) " . "\r\n";
        $strSQL .= "VALUES (@SYUBETUCD " . "\r\n";
        $strSQL .= "       ,@CODE " . "\r\n";
        $strSQL .= "       ,'営業業績_契約社員'  " . "\r\n";
        $strSQL .= "       ,@ATAI " . "\r\n";
        $strSQL .= "       ,SYSDATE " . "\r\n";
        $strSQL .= "       ,'" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSQL .= "       ,'FrmSyoreikinSyoriMen' " . "\r\n";
        $strSQL .= "       ,'" . $this->GS_LOGINUSER['strClientNM'] . "') " . "\r\n";
        //----- パラメータ　-----
        $strSQL = str_replace("@SYUBETUCD", $this->ClsComFncJKSYS->FncSqlNv($strSyubetuCd), $strSQL);
        $strSQL = str_replace("@CODE", $this->ClsComFncJKSYS->FncSqlNv("1" . $strKoyou . $strSyokusyu), $strSQL);
        $strSQL = str_replace("@ATAI", $this->ClsComFncJKSYS->FncSqlNv($strAtai), $strSQL);

        return parent::insert($strSQL);
    }

    //業績奨励_掛け率_更新SQL
    // <param name="strSyubetuCd">種別コード</param>
    // <param name="strAtai">算出奨励金掛け率</param>
    public function fncUpdKakerituSQL($strSyubetuCd, $strAtai)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " UPDATE   JKSYOREIKINMST " . "\r\n";
        $strSQL .= " SET      ATAI_1 = @ATAI " . "\r\n";
        $strSQL .= "         ,UPD_DATE = SYSDATE " . "\r\n";
        $strSQL .= "         ,UPD_SYA_CD = '" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSQL .= "         ,UPD_PRG_ID = 'FrmSyoreikinSyoriMen' " . "\r\n";
        $strSQL .= "         ,UPD_CLT_NM = '" . $this->GS_LOGINUSER['strClientNM'] . "' " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = @SYUBETUCD " . "\r\n";
        $strSQL .= " AND      CODE = '1' " . "\r\n";

        $strSQL = str_replace("@SYUBETUCD", $this->ClsComFncJKSYS->FncSqlNv($strSyubetuCd), $strSQL);
        $strSQL = str_replace("@ATAI", $this->ClsComFncJKSYS->FncSqlNv($strAtai), $strSQL);

        return parent::update($strSQL);
    }

    //店長奨励_係数種類_更新SQL
    // <param name="strCode">コード</param>
    // <param name="strUmu">人員割有無</param>
    // <param name="strTani">支給計算書表示単位</param>
    // <param name="strHyojijun">表示順</param>
    public function fncUpdTenKeisuSyuruiSQL($strCode, $strUmu, $strTani, $strHyojijun)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " UPDATE   JKSYOREIKINMST " . "\r\n";
        $strSQL .= " SET      ATAI_1 = @UMU " . "\r\n";
        $strSQL .= "         ,ATAI_2 = @TANI " . "\r\n";
        $strSQL .= "         ,HYOJI_JUN = @HYOJIJUN " . "\r\n";
        $strSQL .= "         ,UPD_DATE = SYSDATE " . "\r\n";
        $strSQL .= "         ,UPD_SYA_CD = '" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSQL .= "         ,UPD_PRG_ID = 'FrmSyoreikinSyoriMen' " . "\r\n";
        $strSQL .= "         ,UPD_CLT_NM = '" . $this->GS_LOGINUSER['strClientNM'] . "' " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = '20000' " . "\r\n";
        $strSQL .= " AND      CODE = @CODE " . "\r\n";

        $strSQL = str_replace("@UMU", $this->ClsComFncJKSYS->FncSqlNv($strUmu), $strSQL);
        $strSQL = str_replace("@TANI", $this->ClsComFncJKSYS->FncSqlNv($strTani), $strSQL);
        $strSQL = str_replace("@HYOJIJUN", $this->ClsComFncJKSYS->FncSqlNv($strHyojijun), $strSQL);
        $strSQL = str_replace("@CODE", $this->ClsComFncJKSYS->FncSqlNv($strCode), $strSQL);

        return parent::update($strSQL);
    }

    //店長奨励_係数項目_更新SQL
    // <param name="strSyubetuCd">種別コード</param>
    // <param name="strCode">コード</param>
    // <param name="strMeisyo">名称</param>
    // <param name="strHyojijun">表示順</param>
    public function fncInsTenkeisuKomokuSQL($strSyubetuCd, $strCode, $strMeisyo, $strHyojijun)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " INSERT INTO JKSYOREIKINMST " . "\r\n";
        $strSQL .= "     (SYUBETU_CD " . "\r\n";
        $strSQL .= "     ,CODE " . "\r\n";
        $strSQL .= "     ,MEISYO " . "\r\n";
        $strSQL .= "     ,HYOJI_JUN " . "\r\n";
        $strSQL .= "     ,CREATE_DATE " . "\r\n";
        $strSQL .= "     ,CRE_SYA_CD " . "\r\n";
        $strSQL .= "     ,CRE_PRG_ID " . "\r\n";
        $strSQL .= "     ,UPD_CLT_NM) " . "\r\n";
        $strSQL .= "VALUES (@SYUBETUCD " . "\r\n";
        $strSQL .= "       ,@CODE " . "\r\n";
        $strSQL .= "       ,@MEISYO " . "\r\n";
        $strSQL .= "       ,@HYOJIJUN " . "\r\n";
        $strSQL .= "       ,SYSDATE " . "\r\n";
        $strSQL .= "       ,'" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSQL .= "       ,'FrmSyoreikinSyoriMen' " . "\r\n";
        $strSQL .= "       ,'" . $this->GS_LOGINUSER['strClientNM'] . "') " . "\r\n";

        $strSQL = str_replace("@SYUBETUCD", $this->ClsComFncJKSYS->FncSqlNv($strSyubetuCd), $strSQL);
        $strSQL = str_replace("@CODE", $this->ClsComFncJKSYS->FncSqlNv($strCode), $strSQL);
        $strSQL = str_replace("@MEISYO", $this->ClsComFncJKSYS->FncSqlNv($strMeisyo), $strSQL);
        $strSQL = str_replace("@HYOJIJUN", $this->ClsComFncJKSYS->FncSqlNv($strHyojijun), $strSQL);

        return parent::insert($strSQL);
    }

    //店長奨励_支給対象_更新SQL
    // <param name="strSyubetuCd">種別コード</param>
    // <param name="strBusyo">部署コード</param>
    // <param name="strSyokusyu">職種コード</param>
    // <param name="strRoute">販売ルート</param>
    public function fncInsTenTaisyoSQL($strSyubetuCd, $strBusyo, $strSyokusyu, $strRoute)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " INSERT INTO JKSYOREIKINMST " . "\r\n";
        $strSQL .= "     (SYUBETU_CD " . "\r\n";
        $strSQL .= "     ,CODE " . "\r\n";
        $strSQL .= "     ,MEISYO " . "\r\n";
        $strSQL .= "     ,ATAI_1 " . "\r\n";
        $strSQL .= "     ,CREATE_DATE " . "\r\n";
        $strSQL .= "     ,CRE_SYA_CD " . "\r\n";
        $strSQL .= "     ,CRE_PRG_ID " . "\r\n";
        $strSQL .= "     ,UPD_CLT_NM) " . "\r\n";
        $strSQL .= "VALUES (@SYUBETUCD " . "\r\n";
        $strSQL .= "       ,@CODE " . "\r\n";
        $strSQL .= "       ,'店長奨励手当支給対象コード(部署&職種)' " . "\r\n";
        $strSQL .= "       ,@ROUTE " . "\r\n";
        $strSQL .= "       ,SYSDATE " . "\r\n";
        $strSQL .= "       ,'" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSQL .= "       ,'FrmSyoreikinSyoriMen' " . "\r\n";
        $strSQL .= "       ,'" . $this->GS_LOGINUSER['strClientNM'] . "') " . "\r\n";

        $strSQL = str_replace("@SYUBETUCD", $this->ClsComFncJKSYS->FncSqlNv($strSyubetuCd), $strSQL);
        $strSQL = str_replace("@CODE", $this->ClsComFncJKSYS->FncSqlNv($strBusyo . $strSyokusyu), $strSQL);
        $strSQL = str_replace("@ROUTE", $this->ClsComFncJKSYS->FncSqlNv($strRoute), $strSQL);

        return parent::insert($strSQL);
    }

    //店長奨励_限界/経常利益取得部署_更新SQL
    // <param name="strSyubetuCd">種別コード</param>
    // <param name="strBusyo">部署コード</param>
    // <param name="strRieki">経常利益取得コード</param>
    // <param name="strGenkai">総限界取得コード</param>
    public function fncInsTenSyutokuSQL($strSyubetuCd, $strBusyo, $strRieki, $strGenkai)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " INSERT INTO JKSYOREIKINMST " . "\r\n";
        $strSQL .= "     (SYUBETU_CD " . "\r\n";
        $strSQL .= "     ,CODE " . "\r\n";
        $strSQL .= "     ,MEISYO " . "\r\n";
        $strSQL .= "     ,ATAI_1 " . "\r\n";
        $strSQL .= "     ,ATAI_2 " . "\r\n";
        $strSQL .= "     ,CREATE_DATE " . "\r\n";
        $strSQL .= "     ,CRE_SYA_CD " . "\r\n";
        $strSQL .= "     ,CRE_PRG_ID " . "\r\n";
        $strSQL .= "     ,UPD_CLT_NM) " . "\r\n";
        $strSQL .= "VALUES (@SYUBETUCD " . "\r\n";
        $strSQL .= "       ,@CODE " . "\r\n";
        $strSQL .= "       ,'店長奨励手当店舗・販売部署コード' " . "\r\n";
        $strSQL .= "       ,@RIEKI " . "\r\n";
        $strSQL .= "       ,@GENKAI " . "\r\n";
        $strSQL .= "       ,SYSDATE " . "\r\n";
        $strSQL .= "       ,'" . $this->GS_LOGINUSER["strUserID"] . "' " . "\r\n";
        $strSQL .= "       ,'FrmSyoreikinSyoriMen' " . "\r\n";
        $strSQL .= "       ,'" . $this->GS_LOGINUSER["strClientNM"] . "') " . "\r\n";

        $strSQL = str_replace("@SYUBETUCD", $this->ClsComFncJKSYS->FncSqlNv($strSyubetuCd), $strSQL);
        $strSQL = str_replace("@CODE", $this->ClsComFncJKSYS->FncSqlNv($strBusyo), $strSQL);
        $strSQL = str_replace("@RIEKI", $this->ClsComFncJKSYS->FncSqlNv($strRieki), $strSQL);
        $strSQL = str_replace("@GENKAI", $this->ClsComFncJKSYS->FncSqlNv($strGenkai), $strSQL);

        return parent::insert($strSQL);
    }

    //職種名_取得SQL
    public function fncSelSyokusyuSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT   CODE " . "\r\n";
        $strSQL .= "         ,MEISYOU " . "\r\n";
        $strSQL .= " FROM     JKCODEMST " . "\r\n";
        $strSQL .= " WHERE    ID = 'SYOKUSYU' " . "\r\n";

        return parent::select($strSQL);
    }

    //部署名_取得SQL
    public function fncSelBusyoSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT   BUSYO_CD " . "\r\n";
        $strSQL .= "         ,BUSYO_NM " . "\r\n";
        $strSQL .= " FROM     JKBUMON " . "\r\n";

        return parent::select($strSQL);
    }

    //販売ルート名_取得SQL
    public function fncSelRouteSQL($pcnGyoHanbaiRoute)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " SELECT   CODE " . "\r\n";
        $strSQL .= "         ,MEISYO " . "\r\n";
        $strSQL .= " FROM     JKSYOREIKINMST " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = @HANBAIROUTE " . "\r\n";

        $strSQL = str_replace("@HANBAIROUTE", $this->ClsComFncJKSYS->FncSqlNv($pcnGyoHanbaiRoute), $strSQL);

        return parent::select($strSQL);
    }

    //雇用区分名_取得SQL
    public function fncSelKoyouSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT   KUBUN_CD " . "\r\n";
        $strSQL .= "         ,KUBUN_NM " . "\r\n";
        $strSQL .= " FROM     JKKUBUNMST " . "\r\n";
        $strSQL .= " WHERE    KUBUN_ID = 'KOYOU' " . "\r\n";

        return parent::select($strSQL);
    }

}
