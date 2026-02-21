<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
// 履歴：
// * -----------------------------------------------------------------------------------------------------------------------------------
// * 日付                Feature/Bug                  内容                             担当
// * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
// * 20151019 		  #2167,#2168,#2169				BUG									YIN
// * 20231115 		  	20231108_R4K_営業スタッフ個別入力に新アイテム追加_依頼廃車				依頼									YIN
// * ----------------------------------------------------------------------------------------------------------------------------------
//*************************************

class FrmKaverRankSyukei extends ClsComDb
{


    function frmSampleLoadDateSql()
    {

        $strSQL = "SELECT ID" . "\r\n";

        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";

        $strSQL .= "   FROM  HKEIRICTL" . "\r\n";

        $strSQL .= "  WHERE  ID = '01'" . "\r\n";

        return $strSQL;
    }

    function fncExistsJinkenhiSql($postData)
    {

        $strSQL = "SELECT KEIJO_DT, SYAIN_NO" . "\r\n";

        $strSQL .= "   FROM  HSTAFFJINKEN" . "\r\n";

        $strSQL .= "WHERE  KEIJO_DT = '@TUKI'" . "\r\n";

        $strSQL = str_replace("@TUKI", $postData, $strSQL);

        return $strSQL;
    }

    function fncExistsJibaisekiSql($postData)
    {

        $strSQL = "SELECT KEIJO_DT, SYAIN_NO" . "\r\n";

        $strSQL .= "   FROM  HSTAFFJIBAI" . "\r\n";

        $strSQL .= "WHERE  KEIJO_DT = '@TUKI'" . "\r\n";

        $strSQL = str_replace("@TUKI", $postData, $strSQL);

        return $strSQL;
    }

    function fncExistsNinhoSql($postData)
    {

        $strSQL = "SELECT KEIJO_DT, SYAIN_NO" . "\r\n";

        $strSQL .= "   FROM  HSTAFFNINHO" . "\r\n";

        $strSQL .= "WHERE  KEIJO_DT = '@TUKI'" . "\r\n";

        $strSQL = str_replace("@TUKI", $postData, $strSQL);

        return $strSQL;
    }

    function fncExistsNensuSql($postData)
    {

        $strSQL = "SELECT KEIJO_DT, SYAIN_NO" . "\r\n";

        $strSQL .= "   FROM  HSTAFFNENSU" . "\r\n";

        $strSQL .= "WHERE  KEIJO_DT = '@TUKI'" . "\r\n";

        $strSQL = str_replace("@TUKI", $postData, $strSQL);

        return $strSQL;
    }

    function fncDeleteSalseSql($postData)
    {
        $strSQL = " DELETE FROM HSLSSTAFF" . "\r\n";

        $strSQL .= " WHERE  KEIJO_DT = '@KEIJOBI'" . "\r\n";

        $strSQL = str_replace("@KEIJOBI", $postData, $strSQL);

        return $strSQL;
    }

    function fncInsKaverRankTotalSql($postData = '')
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HSLSSTAFF" . "\r\n";
        $strSQL .= "(     KEIJO_DT" . "\r\n";
        $strSQL .= ",     BUSYO_CD" . "\r\n";
        $strSQL .= ",     SYAIN_NO" . "\r\n";
        $strSQL .= ",     SRY_RIEKI" . "\r\n";
        $strSQL .= ",     JIBAISEKI" . "\r\n";
        $strSQL .= ",     NINIHOKEN" . "\r\n";
        $strSQL .= ",     GDMZ_LEASE" . "\r\n";
        $strSQL .= ",     CHUKO_SYOKAI" . "\r\n";
        $strSQL .= ",     KICKBACK" . "\r\n";
        $strSQL .= ",     RIEKI_PLUS_KO" . "\r\n";
        $strSQL .= ",     RIEKI_PLUS_BU" . "\r\n";
        $strSQL .= ",     HIYOU_MINUS_KO" . "\r\n";
        $strSQL .= ",     HIYOU_MINUS_BU" . "\r\n";
        $strSQL .= ",     KO_NOUSYA" . "\r\n";
        $strSQL .= ",     KO_SERVICE" . "\r\n";
        $strSQL .= ",     KO_UNSOUHI" . "\r\n";
        $strSQL .= ",     KO_ZAPPI" . "\r\n";
        $strSQL .= ",     KO_KOUKOKU" . "\r\n";
        $strSQL .= ",     BU_NOUSYA" . "\r\n";
        $strSQL .= ",     BU_SERVICE" . "\r\n";
        $strSQL .= ",     BU_UNSOUHI" . "\r\n";
        $strSQL .= ",     BU_ZAPPI" . "\r\n";
        $strSQL .= ",     BU_KOUKOKU" . "\r\n";
        $strSQL .= ",     BU_DEMO" . "\r\n";
        $strSQL .= ",     FWR_KINRI" . "\r\n";
        $strSQL .= ",     PENALTY" . "\r\n";
        $strSQL .= ",     SETTAIHI" . "\r\n";
        $strSQL .= ",     RIEKI_MINUS_KO" . "\r\n";
        $strSQL .= ",     RIEKI_MINUS_BU" . "\r\n";
        $strSQL .= ",     HIYOU_PLUS_KO" . "\r\n";
        $strSQL .= ",     HIYOU_PLUS_BU" . "\r\n";
        $strSQL .= ",     SOU_JINKEN" . "\r\n";
        $strSQL .= ",     SOU_JINKEN_BU" . "\r\n";
        $strSQL .= ",     FUKURIKOSEI" . "\r\n";
        $strSQL .= ",     FUKURIKOSEI_KO" . "\r\n";
        $strSQL .= ",     SYOCHO_FUTAN" . "\r\n";
        $strSQL .= ",     SYOCHO_FUTAN_KO" . "\r\n";
        $strSQL .= ",     HONSYA_FUTAN" . "\r\n";
        $strSQL .= ",     CHUZAI_FUTAN" . "\r\n";
        $strSQL .= ",     CHUZAI_FUTAN_KO" . "\r\n";
        $strSQL .= ",     SYOHI" . "\r\n";
        $strSQL .= "--,     SYOHI_HITORI" . "\r\n";
        $strSQL .= ",     KOTEIHICHOSEI" . "\r\n";
        $strSQL .= ",     YACHIN" . "\r\n";
        $strSQL .= ",     YACHIN_KO" . "\r\n";
        $strSQL .= ",     YACHIN_JININ" . "\r\n";
        $strSQL .= ",     SIN_DAISU" . "\r\n";
        $strSQL .= ",     CHU_DAISU" . "\r\n";
        $strSQL .= ",     SOU_DAISU" . "\r\n";
        $strSQL .= ",     BU_SIN_DAISU" . "\r\n";
        $strSQL .= ",     BU_DAISU" . "\r\n";
        $strSQL .= ",     SOUJININ" . "\r\n";
        $strSQL .= ",     KANRIJININ" . "\r\n";
        $strSQL .= ",     SYOREIKIN" . "\r\n";
        $strSQL .= ",     KNR_DAISU" . "\r\n";
        $strSQL .= ",     KEI_NEN_SU" . "\r\n";
        $strSQL .= ",     DISP_KB" . "\r\n";
        $strSQL .= ",     DAI_HYOUJI" . "\r\n";
        $strSQL .= "--,     EIGYO_STAFF_FLG" . "\r\n";
        $strSQL .= ",     UPD_DATE" . "\r\n";
        $strSQL .= ",     CREATE_DATE" . "\r\n";
        $strSQL .= ",     UPD_SYA_CD" . "\r\n";
        $strSQL .= ",     UPD_PRG_ID" . "\r\n";
        $strSQL .= ",     UPD_CLT_NM" . "\r\n";
        // 20231115 YIN INS S
        $strSQL .= ",     IRAI_HAISYA" . "\r\n";
        // 20231115 YIN INS E

        $strSQL .= ")" . "\r\n";
        $strSQL .= "SELECT JIN.KEIJO_DT" . "\r\n";
        $strSQL .= ",      JIN.BUSYO_CD" . "\r\n";
        $strSQL .= ",      JIN.SYAIN_NO" . "\r\n";
        $strSQL .= ",      NVL(KO_GENR.GENRI,0)" . "\r\n";
        $strSQL .= ",      NVL(JBI.TESURYO_GK,0)" . "\r\n";
        $strSQL .= ",      NVL(NIN.NINPO_GK,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_KOUMOKU.H_LEASE,0)" . "\r\n";
        $strSQL .= ",      NVL(CHU_SYOKAI.GAKU,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_KEI.KICKBACK,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_KOUMOKU.RIEKI_PLUS,0)" . "\r\n";
        $strSQL .= ",      NVL(BU_KOUMOKU.RIEKI_PLUS,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_KOUMOKU.HIYOU_MINUS,0)	--INS" . "\r\n";
        $strSQL .= ",      NVL(BU_KOUMOKU.HIYOU_MINUS,0)	--INS" . "\r\n";
        $strSQL .= ",      NVL(KO_KEI.KO_NOUSYA,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_KEI.KO_SERVICE,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_KEI.KO_UNSO,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_KEI.KO_ZAPPI,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_KEI.KO_KOKOKU,0)" . "\r\n";
        $strSQL .= ",      NVL(BU_KEI.BU_NOUSYA,0)" . "\r\n";
        $strSQL .= ",      NVL(BU_KEI.BU_SERVICE,0)" . "\r\n";
        $strSQL .= ",      NVL(BU_KEI.BU_UNSOU,0)" . "\r\n";
        $strSQL .= ",      NVL(BU_KEI.BU_ZAPPI,0)" . "\r\n";
        $strSQL .= ",      NVL(BU_KEI.BU_KOKOKU,0)" . "\r\n";
        $strSQL .= ",      NVL(BU_KEI.BU_DEMO,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_KEI.FUWATARI,0)" . "\r\n";
        //2007/05/10 UPD部署別実績集計でペナルティ作成時、社員№がL_SYAIN_NOではなくTEKIYO3に入るようになっており、ペナルティが反映されていなかったので変更
        $strSQL .= ",      NVL(PNA_TBL.PENALTY,0)	--UPD" . "\r\n";
        $strSQL .= ",      NULL   --,      KO_KEI.SETTAI UPD" . "\r\n";
        $strSQL .= ",      NVL(KO_KOUMOKU.RIEKI_MINUS,0)" . "\r\n";
        $strSQL .= ",      NVL(BU_KOUMOKU.RIEKI_MINUS,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_KOUMOKU.HIYOU_PLUS,0)	--INS" . "\r\n";
        $strSQL .= ",      NVL(BU_KOUMOKU.HIYOU_PLUS,0)	--INS" . "\r\n";
        $strSQL .= ",      NVL(JIN.JINKENHI_GK,0) - (CASE WHEN JIN.SYAIN_NO = '05902' AND JIN.KEIJO_DT >= '200702' AND JIN.KEIJO_DT <= '200707' THEN 170000 ELSE 0 END)" . "\r\n";
        $strSQL .= ",      NVL(BU_JIN.KEI,0)" . "\r\n";
        $strSQL .= ",      NVL(BU_KEI.FUKURI,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_KEI.FUKURI,0)" . "\r\n";
        $strSQL .= ",      NVL(BU_KOUMOKU.SYOCHO_FUTAN,0)	--UPD" . "\r\n";
        $strSQL .= ",      NVL(KO_KOUMOKU.SYOCHO_FUTAN,0)	--INS" . "\r\n";
        $strSQL .= ",      NVL(BU_KOUMOKU.HONSYA_FUTAN,0)" . "\r\n";
        $strSQL .= ",      NVL(BU_KOUMOKU.CHUZAI_FUTAN,0)	--UPD" . "\r\n";
        $strSQL .= ",      NVL(KO_KOUMOKU.CHUZAI_FUTAN,0)	--INS" . "\r\n";
        $strSQL .= ",      NVL(BU_SYOHI.SYOHI,0)" . "\r\n";
        $strSQL .= "--,      DECODE(NVL(SOU_JININ.NINZU,0),0,0,ROUND(NVL(BU_SYOHI.SYOHI,0) / NVL(SOU_JININ.NINZU,0),0))" . "\r\n";
        $strSQL .= ",      NVL(KO_KOUMOKU.KOTEI_CHOSEI,0)" . "\r\n";
        $strSQL .= ",      NVL(YACHIN.YACHIN_GAKU,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_KOUMOKU.YACHIN,0)	--INS" . "\r\n";
        $strSQL .= ",      NVL(BU_KOUMOKU.YACHIN_JININ,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_GENR.SINSYA_DAI,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_GENR.CHUKO_DAI,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_GENR.SINSYA_DAI,0) + NVL(KO_GENR.CHUKO_DAI,0)" . "\r\n";
        $strSQL .= ",      NVL(BU_GENR.BU_SIN_DAI,0)" . "\r\n";
        $strSQL .= ",      NVL(BU_GENR.BU_DAI,0)" . "\r\n";
        $strSQL .= ",      NVL(SOU_JININ.NINZU,0)" . "\r\n";
        $strSQL .= ",      NVL(BU_KOUMOKU.KANRI_JININ,0) --2006/11/22 INS" . "\r\n";
        $strSQL .= ",      NVL(BU_KOUMOKU.SYOREIKIN,0)" . "\r\n";
        $strSQL .= ",      NVL(KO_KOUMOKU.KANRI_DAISU,0)" . "\r\n";
        $strSQL .= ",      NVL(NSU.KEIKEN_NEN_SU,0)" . "\r\n";
        $strSQL .= "--,      NVL(SLSKBN.SLSCNT,0)" . "\r\n";
        $strSQL .= ",      HAI.DISP_KB" . "\r\n";
        $strSQL .= ",      HAI.DAI_HYOUJI" . "\r\n";
        $strSQL .= ",      SYSDATE" . "\r\n";
        $strSQL .= ",      SYSDATE" . "\r\n";
        $strSQL .= ",      '@UPDUSER'" . "\r\n";
        $strSQL .= ",      '@UPDAPP'" . "\r\n";
        $strSQL .= ",      '@UPDCLT'" . "\r\n";
        // 20231115 YIN INS S
        $strSQL .= ",     NVL(KO_KOUMOKU.IRAI_HAISYA,0)" . "\r\n";
        // 20231115 YIN INS E

        $strSQL .= "FROM   HSTAFFJINKEN JIN" . "\r\n";

        //2007/05/10 UPD Start　配属先マスタ導入のため
        $strSQL .= "INNER JOIN (SELECT SYAIN_NO FROM HSYAINMST WHERE SLSSUTAFF_KB IN ('1','3')) SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = JIN.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN HHAIZOKU HAI" . "\r\n";
        $strSQL .= "ON     HAI.SYAIN_NO = SYA.SYAIN_NO" . "\r\n";
        $strSQL .= "AND    HAI.START_DATE <= '@ENDDATE'" . "\r\n";
        $strSQL .= "AND    NVL(HAI.END_DATE,'99999999') >= '@ENDDATE'" . "\r\n";

        $strSQL .= "/*LEFT JOIN (SELECT J.BUSYO_CD" . "\r\n";
        $strSQL .= "           ,      SUM(CASE WHEN S.SLSSUTAFF_KB = '2' THEN 1 ELSE 0 END) SLSCNT" . "\r\n";
        $strSQL .= "           FROM   HSTAFFJINKEN J" . "\r\n";
        $strSQL .= "           ,      HSYAINMST S" . "\r\n";
        $strSQL .= "           WHERE  J.SYAIN_NO = S.SYAIN_NO" . "\r\n";
        $strSQL .= "           GROUP BY J.BUSYO_CD) SLSKBN" . "\r\n";
        $strSQL .= "ON     SLSKBN.BUSYO_CD = JIN.BUSYO_CD*/" . "\r\n";

        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       (SELECT KAR.BUSYO_CD" . "\r\n";
        $strSQL .= "		,      SUM(KAR.KEIJO_GK) KEI" . "\r\n";
        $strSQL .= "		FROM   HFURIKAE KAR" . "\r\n";
        $strSQL .= "		WHERE  ((KAR.KAMOK_CD IN ('43231','43241','43251')" . "\r\n";
        $strSQL .= "		      AND (NVL(TRIM(KAR.HIMOK_CD),'00') = '00' OR  SUBSTR(KAR.HIMOK_CD,1,1) = '0'))" . "\r\n";
        $strSQL .= "		OR     KAR.KAMOK_CD = '43220' )" . "\r\n";
        $strSQL .= "		AND    KAR.HASEI_MOTO_KB = 'JH'" . "\r\n";
        $strSQL .= "		AND    KAR.TAISK_KB = '1'" . "\r\n";
        $strSQL .= "		AND    KAR.KEIJO_DT = '@ENDDATE'" . "\r\n";
        $strSQL .= "		GROUP BY BUSYO_CD" . "\r\n";
        $strSQL .= "       ) BU_JIN" . "\r\n";
        $strSQL .= "ON     BU_JIN.BUSYO_CD = JIN.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HSTAFFJIBAI JBI" . "\r\n";
        $strSQL .= "ON     JBI.SYAIN_NO = JIN.SYAIN_NO" . "\r\n";
        $strSQL .= "AND    JBI.KEIJO_DT = JIN.KEIJO_DT" . "\r\n";
        $strSQL .= "LEFT JOIN HSTAFFNINHO NIN" . "\r\n";
        $strSQL .= "ON     NIN.SYAIN_NO = JIN.SYAIN_NO" . "\r\n";
        $strSQL .= "AND    NIN.KEIJO_DT = JIN.KEIJO_DT" . "\r\n";
        $strSQL .= "LEFT JOIN HSTAFFNENSU NSU" . "\r\n";
        $strSQL .= "ON     NSU.SYAIN_NO = JIN.SYAIN_NO" . "\r\n";
        $strSQL .= "AND    NSU.KEIJO_DT = JIN.KEIJO_DT" . "\r\n";
        $strSQL .= "LEFT JOIN  " . "\r\n";
        $strSQL .= "        (" . "\r\n";
        $strSQL .= "		SELECT  V.SYAINNO" . "\r\n";
        $strSQL .= "		,       SUM(NVL(V.KINGAKU,0)) GAKU" . "\r\n";
        $strSQL .= "		FROM	(" . "\r\n";
        $strSQL .= "				SELECT MOT_SYAIN_NO SYAINNO" . "\r\n";
        $strSQL .= "				,      SUM(NVL(KEIJO_GK,0) * -1) KINGAKU" . "\r\n";
        $strSQL .= "				FROM   HSTAFFCHUSYOKAI" . "\r\n";
        $strSQL .= "                WHERE  KEIJO_DT = '@TUKI'" . "\r\n";
        $strSQL .= "				GROUP BY MOT_SYAIN_NO" . "\r\n";
        $strSQL .= "				UNION ALL" . "\r\n";
        $strSQL .= "				SELECT SAKI_SYAIN_NO SYAINNO" . "\r\n";
        $strSQL .= "				,      SUM(NVL(KEIJO_GK,0))" . "\r\n";
        $strSQL .= "				FROM   HSTAFFCHUSYOKAI" . "\r\n";
        $strSQL .= "                WHERE  KEIJO_DT = '@TUKI'" . "\r\n";
        $strSQL .= "				GROUP BY SAKI_SYAIN_NO" . "\r\n";
        $strSQL .= "		        ) V" . "\r\n";
        $strSQL .= "		GROUP BY V.SYAINNO" . "\r\n";
        $strSQL .= "	    ) CHU_SYOKAI" . "\r\n";
        $strSQL .= "ON      CHU_SYOKAI.SYAINNO = JIN.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN		" . "\r\n";
        $strSQL .= "		(" . "\r\n";
        $strSQL .= "		SELECT SYAINNO" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN HANBETU_2 = '19' THEN NVL(R_KEIJYO,0) - NVL(L_KEIJYO,0) ELSE 0 END) KICKBACK" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN HANBETU_1 = '12' THEN NVL(L_KEIJYO,0) - NVL(R_KEIJYO,0) ELSE 0 END) KO_NOUSYA" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN HANBETU_1 = '13' THEN NVL(L_KEIJYO,0) - NVL(R_KEIJYO,0) ELSE 0 END) KO_SERVICE" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN HANBETU_1 = '14' THEN NVL(L_KEIJYO,0) - NVL(R_KEIJYO,0) ELSE 0 END) KO_UNSO" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN HANBETU_1 = '16' THEN NVL(L_KEIJYO,0) - NVL(R_KEIJYO,0) ELSE 0 END) KO_ZAPPI" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN HANBETU_1 = '15' THEN NVL(L_KEIJYO,0) - NVL(R_KEIJYO,0) ELSE 0 END) KO_KOKOKU" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN KAMOK_CD = '85113' THEN NVL(L_KEIJYO,0) - NVL(R_KEIJYO,0) ELSE 0 END) FUWATARI" . "\r\n";
        //2007/05/10 DEL ペナルティはL_SYAIN_NOに社員が入らない(TEKIYO３に入る)
        $strSQL .= "		,      SUM(CASE WHEN KAMOK_CD = '43441' THEN NVL(L_KEIJYO,0) - NVL(R_KEIJYO,0) ELSE 0 END) SETTAI" . "\r\n";
        $strSQL .= "        ,      SUM(CASE WHEN KAMOK_CD = '43251' THEN NVL(L_KEIJYO,0) - NVL(R_KEIJYO,0) ELSE 0 END) FUKURI" . "\r\n";
        $strSQL .= "		--,      SUM(CASE WHEN HANBETU_2 IN ('33','34') THEN NVL(L_KEIJYO,0) - NVL(R_KEIJYO,0) ELSE 0 END) SYOHI" . "\r\n";
        $strSQL .= "		FROM    (" . "\r\n";
        $strSQL .= "				SELECT L_SYAIN_NO SYAINNO" . "\r\n";
        $strSQL .= "		        ,      KRI.L_KAMOK_CD KAMOK_CD" . "\r\n";
        $strSQL .= "				,      SUBSTR(KRI.L_KAMOK_CD,3,2) HANBETU_1" . "\r\n";
        $strSQL .= "				,      SUBSTR(KRI.L_KAMOK_CD,2,2) HANBETU_2" . "\r\n";
        $strSQL .= "				,      SUM(NVL(KRI.KEIJO_GK,0)) L_KEIJYO" . "\r\n";
        $strSQL .= "				,      0 R_KEIJYO" . "\r\n";
        $strSQL .= "				FROM   HKAIKEI KRI" . "\r\n";
        $strSQL .= "				--2006/10/05追加Start" . "\r\n";
        $strSQL .= "				LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "				ON     BUS.MANEGER_CD = KRI.L_SYAIN_NO" . "\r\n";
        $strSQL .= "				AND    BUS.BUSYO_CD = KRI.L_BUSYO_CD" . "\r\n";
        $strSQL .= "				------" . "\r\n";
        $strSQL .= "		       	WHERE  KRI.L_KAMOK_CD IN ('43121','43131','43161'" . "\r\n";
        $strSQL .= "		                                  ,'43251','43122'" . "\r\n";
        $strSQL .= "		                                  ,'43151','43162'" . "\r\n";
        $strSQL .= "		                                  ,'43142','43141','43132'" . "\r\n";
        //2007/05/10 UPD Start ペナルティ除外
        $strSQL .= "		                                  ,'43152','41951','41941','85113')" . "\r\n";
        $strSQL .= "		        AND    KRI.L_SYAIN_NO IS NOT NULL" . "\r\n";
        $strSQL .= "				--2006/10/05追加" . "\r\n";
        $strSQL .= "				AND    BUS.BUSYO_CD IS NULL" . "\r\n";
        $strSQL .= "				-----" . "\r\n";
        $strSQL .= "                AND    KRI.KEIJO_DT BETWEEN '@STARTDATE' AND '@ENDDATE'" . "\r\n";
        $strSQL .= "				GROUP BY KRI.L_SYAIN_NO" . "\r\n";
        $strSQL .= "				,        KRI.L_KAMOK_CD" . "\r\n";
        $strSQL .= "				UNION ALL" . "\r\n";
        $strSQL .= "				SELECT R_SYAIN_NO SYAINNO" . "\r\n";
        $strSQL .= "				,      KAS.R_KAMOK_CD" . "\r\n";
        $strSQL .= "				,      SUBSTR(KAS.R_KAMOK_CD,3,2) HANBETU" . "\r\n";
        $strSQL .= "				,      SUBSTR(KAS.R_KAMOK_CD,2,2) HANBETU_2" . "\r\n";
        $strSQL .= "				,      0 L_KEIJYO" . "\r\n";
        $strSQL .= "				,      SUM(NVL(KAS.KEIJO_GK,0)) R_KEIJYO" . "\r\n";
        $strSQL .= "				FROM   HKAIKEI KAS" . "\r\n";
        $strSQL .= "				--2006/10/05追加" . "\r\n";
        $strSQL .= "				LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "				ON     BUS.MANEGER_CD = KAS.R_SYAIN_NO" . "\r\n";
        $strSQL .= "				AND    BUS.BUSYO_CD = KAS.R_BUSYO_CD" . "\r\n";
        $strSQL .= "				------" . "\r\n";
        $strSQL .= "				WHERE  KAS.R_KAMOK_CD IN ('43121','41941','43122'" . "\r\n";
        $strSQL .= "		                                  ,'43131','43162','43251','41951'" . "\r\n";
        $strSQL .= "		                                  ,'43161','43151','43132'" . "\r\n";
        $strSQL .= "		                                  ,'85113','43141','43142','43152')" . "\r\n";
        $strSQL .= "		        AND    KAS.R_SYAIN_NO IS NOT NULL" . "\r\n";
        $strSQL .= "				--2006/10/05追加" . "\r\n";
        $strSQL .= "				AND    BUS.BUSYO_CD IS NULL" . "\r\n";
        $strSQL .= "				-----" . "\r\n";
        $strSQL .= "                AND    KAS.KEIJO_DT BETWEEN '@STARTDATE' AND '@ENDDATE'" . "\r\n";
        $strSQL .= "				GROUP BY KAS.R_SYAIN_NO" . "\r\n";
        $strSQL .= "				,        KAS.R_KAMOK_CD" . "\r\n";
        $strSQL .= "		       ) V" . "\r\n";
        $strSQL .= "        GROUP BY SYAINNO" . "\r\n";
        $strSQL .= "		) KO_KEI" . "\r\n";
        $strSQL .= "ON      KO_KEI.SYAINNO = JIN.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN		" . "\r\n";
        $strSQL .= "		(" . "\r\n";
        $strSQL .= "		SELECT SYAINNO" . "\r\n";
        $strSQL .= "		,      SUM(NVL(L_KEIJYO,0)) PENALTY" . "\r\n";
        $strSQL .= "		FROM    (" . "\r\n";
        $strSQL .= "				SELECT KRI.TEKIYO3 SYAINNO" . "\r\n";
        $strSQL .= "		        ,      KRI.L_KAMOK_CD KAMOK_CD" . "\r\n";
        $strSQL .= "				,      SUM(NVL(KRI.KEIJO_GK,0)) L_KEIJYO" . "\r\n";
        $strSQL .= "				FROM   HKAIKEI KRI" . "\r\n";
        $strSQL .= "				LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "				ON     BUS.MANEGER_CD = KRI.L_SYAIN_NO" . "\r\n";
        $strSQL .= "				AND    BUS.BUSYO_CD = KRI.L_BUSYO_CD" . "\r\n";
        $strSQL .= "		       	WHERE  KRI.L_KAMOK_CD = '85114'" . "\r\n";
        $strSQL .= "		        AND    KRI.TEKIYO3 IS NOT NULL" . "\r\n";
        $strSQL .= "				AND    BUS.BUSYO_CD IS NULL" . "\r\n";
        $strSQL .= "                AND    KRI.KEIJO_DT BETWEEN '@STARTDATE' AND '@ENDDATE'" . "\r\n";
        $strSQL .= "				GROUP BY KRI.TEKIYO3" . "\r\n";
        $strSQL .= "				,        KRI.L_KAMOK_CD" . "\r\n";
        $strSQL .= "			    ) V" . "\r\n";
        $strSQL .= "        GROUP BY SYAINNO" . "\r\n";
        $strSQL .= "		) PNA_TBL" . "\r\n";
        $strSQL .= "ON      PNA_TBL.SYAINNO = JIN.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "        (" . "\r\n";
        $strSQL .= "		SELECT BUSYOCD" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN HANBETU_1 = '12' THEN NVL(ZANDAKA,0) ELSE 0 END) BU_NOUSYA" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN HANBETU_1 = '13' THEN NVL(ZANDAKA,0) ELSE 0 END) BU_SERVICE" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN HANBETU_1 = '14' THEN NVL(ZANDAKA,0) ELSE 0 END) BU_UNSOU" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN HANBETU_1 = '16' THEN NVL(ZANDAKA,0) ELSE 0 END) BU_ZAPPI" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN HANBETU_1 = '15' THEN NVL(ZANDAKA,0) ELSE 0 END) BU_KOKOKU" . "\r\n";
        $strSQL .= "        ,      SUM(CASE WHEN HANBETU_1 = '17' THEN NVL(ZANDAKA,0) ELSE 0 END) BU_DEMO" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN KAMOK_CD = '43251' THEN NVL(ZANDAKA,0) ELSE 0 END) FUKURI	--INS" . "\r\n";
        $strSQL .= "        FROM" . "\r\n";
        $strSQL .= "		        (" . "\r\n";
        $strSQL .= "				SELECT KRI.BUSYO_CD BUSYOCD" . "\r\n";
        $strSQL .= "		        ,      KRI.KAMOKU_CD KAMOK_CD" . "\r\n";
        $strSQL .= "				,      SUBSTR(KRI.KAMOKU_CD,3,2) HANBETU_1" . "\r\n";
        $strSQL .= "				,      SUBSTR(KRI.KAMOKU_CD,2,2) HANBETU_2" . "\r\n";
        $strSQL .= "				,      SUM(NVL(KRI.TOU_ZAN,0)) ZANDAKA" . "\r\n";
        $strSQL .= "				FROM   HKANRIZ KRI" . "\r\n";
        $strSQL .= "              	WHERE  KRI.KAMOKU_CD IN ('43121','43131','43161','43122','43151'" . "\r\n";
        $strSQL .= "		                                  ,'43162','43171','43142','43141','43132'" . "\r\n";
        $strSQL .= "		                                  ,'43152','43251')" . "\r\n";
        $strSQL .= "                AND    KRI.KEIJO_DT = '@TUKI'" . "\r\n";
        $strSQL .= "		        GROUP BY KRI.BUSYO_CD" . "\r\n";
        $strSQL .= "				,        KRI.KAMOKU_CD" . "\r\n";
        $strSQL .= "				" . "\r\n";
        $strSQL .= "			    ) V" . "\r\n";
        $strSQL .= "        GROUP BY BUSYOCD" . "\r\n";
        $strSQL .= "		) BU_KEI" . "\r\n";
        $strSQL .= "ON     BU_KEI.BUSYOCD = JIN.BUSYO_CD" . "\r\n";
        $strSQL .= "---諸費用等" . "\r\n";
        $strSQL .= "LEFT JOIN		" . "\r\n";
        $strSQL .= "		(" . "\r\n";
        $strSQL .= "		SELECT BUSYOCD" . "\r\n";
        $strSQL .= "		,      SUM(NVL(ZANDAKA,0)) SYOHI" . "\r\n";
        $strSQL .= "		FROM    (" . "\r\n";
        $strSQL .= "			    SELECT KRI.BUSYO_CD BUSYOCD" . "\r\n";
        $strSQL .= "		        ,      KRI.KAMOKU_CD KAMOK_CD" . "\r\n";
        $strSQL .= "				,      SUBSTR(KRI.KAMOKU_CD,3,2) HANBETU_1" . "\r\n";
        $strSQL .= "				,      SUBSTR(KRI.KAMOKU_CD,2,2) HANBETU_2" . "\r\n";
        $strSQL .= "				,      SUM(NVL(KRI.TOU_ZAN,0)) ZANDAKA" . "\r\n";
        $strSQL .= "				FROM   HKANRIZ KRI" . "\r\n";
        $strSQL .= "				/* 2006/11/07 UPD Start" . "\r\n";
        $strSQL .= "		       	WHERE  KRI.KAMOKU_CD IN ('43461','43471','43481','43421','43431','43351'" . "\r\n";
        $strSQL .= "		                                  ,'43341','43411','43441','43361','43331'" . "\r\n";
        $strSQL .= "		                                  ,'43491','43472')" . "\r\n";
        $strSQL .= "                */" . "\r\n";
        $strSQL .= "                WHERE  KRI.KAMOKU_CD IN ('43461','43471','43481','43421','43431','43351'" . "\r\n";
        $strSQL .= "		                                  ,'43341','43411','43441','43331','43451'" . "\r\n";
        $strSQL .= "		                                  ,'43491','43472','43311','43321','43361')" . "\r\n";
        $strSQL .= "                AND    (NVL(TRIM(KRI.HIMOKU_CD),'00') = '00' OR  SUBSTR(KRI.HIMOKU_CD,1,1) = '0')" . "\r\n";
        $strSQL .= "		        AND    KRI.KEIJO_DT = '@TUKI'" . "\r\n";
        $strSQL .= "			    GROUP BY KRI.BUSYO_CD" . "\r\n";
        $strSQL .= "				,        KRI.KAMOKU_CD" . "\r\n";
        $strSQL .= "			    ) V" . "\r\n";
        $strSQL .= "        GROUP BY BUSYOCD" . "\r\n";
        $strSQL .= "		) BU_SYOHI" . "\r\n";
        $strSQL .= "ON      BU_SYOHI.BUSYOCD = JIN.BUSYO_CD" . "\r\n";
        $strSQL .= "----------" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "        (" . "\r\n";
        $strSQL .= "		SELECT SKM.SYAIN_NO" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SKM.ITEM_CD = '1' THEN NVL(SKM.KEIJYO_GK,0) ELSE 0 END) H_LEASE" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SKM.ITEM_CD = '2' THEN NVL(SKM.KEIJYO_GK,0) ELSE 0 END) RIEKI_PLUS" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SKM.ITEM_CD = '3' THEN NVL(SKM.KEIJYO_GK,0) ELSE 0 END) RIEKI_MINUS" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SKM.ITEM_CD = '4' THEN NVL(SKM.KEIJYO_GK,0) ELSE 0 END) KOTEI_CHOSEI" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SKM.ITEM_CD = '5' THEN NVL(SKM.KEIJYO_GK,0) ELSE 0 END) KANRI_DAISU" . "\r\n";
        $strSQL .= "        --2006/10/18 INS START" . "\r\n";
        $strSQL .= "        ,      SUM(CASE WHEN SKM.ITEM_CD = '6' THEN NVL(SKM.KEIJYO_GK,0) ELSE 0 END) SYOCHO_FUTAN" . "\r\n";
        $strSQL .= "        ,      SUM(CASE WHEN SKM.ITEM_CD = '7' THEN NVL(SKM.KEIJYO_GK,0) ELSE 0 END) CHUZAI_FUTAN" . "\r\n";
        $strSQL .= "        ,      SUM(CASE WHEN SKM.ITEM_CD = '8' THEN NVL(SKM.KEIJYO_GK,0) ELSE 0 END) HIYOU_PLUS" . "\r\n";
        $strSQL .= "        ,      SUM(CASE WHEN SKM.ITEM_CD = '9' THEN NVL(SKM.KEIJYO_GK,0) ELSE 0 END) HIYOU_MINUS" . "\r\n";
        $strSQL .= "        ,      SUM(CASE WHEN SKM.ITEM_CD = '10' THEN NVL(SKM.KEIJYO_GK,0) ELSE 0 END) YACHIN" . "\r\n";
        $strSQL .= "        --2006/10/18 INS END" . "\r\n";
        // 20231115 YIN INS S
        $strSQL .= "        ,      SUM(CASE WHEN SKM.ITEM_CD = '11' THEN NVL(SKM.KEIJYO_GK,0) ELSE 0 END) IRAI_HAISYA" . "\r\n";
        // 20231115 YIN INS E
        $strSQL .= "		FROM   HSTAFFKOUMOKU SKM" . "\r\n";
        $strSQL .= "		WHERE  SKM.SYAIN_NO > '00000'" . "\r\n";
        $strSQL .= "		AND    SKM.KEIJO_DT = '@TUKI'" . "\r\n";
        $strSQL .= "		GROUP BY SKM.SYAIN_NO" . "\r\n";
        $strSQL .= "        ) KO_KOUMOKU" . "\r\n";
        $strSQL .= "ON      JIN.SYAIN_NO = KO_KOUMOKU.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       (" . "\r\n";
        $strSQL .= "        " . "\r\n";
        $strSQL .= "			SELECT SKM_B.BUSYO_CD" . "\r\n";
        $strSQL .= "			,      SUM(CASE WHEN SKM_B.ITEM_CD = '2' THEN NVL(SKM_B.KEIJYO_GK,0) ELSE 0 END) RIEKI_PLUS" . "\r\n";
        $strSQL .= "			,      SUM(CASE WHEN SKM_B.ITEM_CD = '3' THEN NVL(SKM_B.KEIJYO_GK,0) ELSE 0 END) RIEKI_MINUS" . "\r\n";
        $strSQL .= "			,      SUM(CASE WHEN SKM_B.ITEM_CD = '5' THEN NVL(SKM_B.KEIJYO_GK,0) ELSE 0 END) SYOCHO_FUTAN" . "\r\n";
        $strSQL .= "			,      SUM(CASE WHEN SKM_B.ITEM_CD = '4' THEN NVL(SKM_B.KEIJYO_GK,0) ELSE 0 END) HONSYA_FUTAN" . "\r\n";
        $strSQL .= "			,      SUM(CASE WHEN SKM_B.ITEM_CD = '6' THEN NVL(SKM_B.KEIJYO_GK,0) ELSE 0 END) CHUZAI_FUTAN" . "\r\n";
        $strSQL .= "			,      SUM(CASE WHEN SKM_B.ITEM_CD = '1' THEN NVL(SKM_B.KEIJYO_GK,0) ELSE 0 END) SYOREIKIN" . "\r\n";
        $strSQL .= "	        ,      SUM(CASE WHEN SKM_B.ITEM_CD = '7' THEN NVL(SKM_B.KEIJYO_GK,0) ELSE 0 END) YACHIN_JININ" . "\r\n";
        $strSQL .= "            ,      SUM(CASE WHEN SKM_B.ITEM_CD = '8' THEN NVL(SKM_B.KEIJYO_GK,0) ELSE 0 END) HIYOU_PLUS	--2006/10/18 INS" . "\r\n";
        $strSQL .= "            ,      SUM(CASE WHEN SKM_B.ITEM_CD = '9' THEN NVL(SKM_B.KEIJYO_GK,0) ELSE 0 END) HIYOU_MINUS   -- 2006/10/18 INS" . "\r\n";
        $strSQL .= "			,      SUM(CASE WHEN SKM_B.ITEM_CD = '10' THEN NVL(SKM_B.KEIJYO_GK,0) ELSE 0 END) KANRI_JININ	--2006/11/22 INS" . "\r\n";
        // 20231115 YIN INS S
        $strSQL .= "			,      SUM(CASE WHEN SKM_B.ITEM_CD = '11' THEN NVL(SKM_B.KEIJYO_GK,0) ELSE 0 END) IRAI_HAISYA" . "\r\n";
        // 20231115 YIN INS E
        $strSQL .= "			FROM   HSTAFFKOUMOKU SKM_B" . "\r\n";
        $strSQL .= "			WHERE  SKM_B.SYAIN_NO = '00000'" . "\r\n";
        $strSQL .= "			AND    SKM_B.KEIJO_DT = '@TUKI'" . "\r\n";
        $strSQL .= "			GROUP BY SKM_B.BUSYO_CD" . "\r\n";
        $strSQL .= "        ) BU_KOUMOKU" . "\r\n";
        $strSQL .= "ON      JIN.BUSYO_CD = BU_KOUMOKU.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "        (" . "\r\n";
        $strSQL .= "		SELECT GRI.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "		,      SUM(NVL(GRI.TOUGETU_GENRI,0)) GENRI" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN GRI.DATA_KB IN ('1','3') THEN NVL(GRI.DAISU,0) ELSE 0 END) SINSYA_DAI" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN GRI.DATA_KB = '2' THEN NVL(GRI.DAISU,0) ELSE 0 END) CHUKO_DAI" . "\r\n";
        $strSQL .= "		FROM   HGENRI_VW GRI" . "\r\n";
        $strSQL .= "		INNER JOIN HGENRI GEN" . "\r\n";
        $strSQL .= "		ON   GEN.UC_NO = GRI.UC_NO" . "\r\n";
        $strSQL .= "		AND  GEN.NENGETU = '@TUKI'" . "\r\n";
        $strSQL .= "     WHERE GRI.ATUKAI_BUSYO <> '211'" . "\r\n";
        $strSQL .= "		AND   GRI.NENGETU = '@TUKI'" . "\r\n";
        $strSQL .= "		GROUP BY GRI.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "        ) KO_GENR" . "\r\n";
        $strSQL .= "ON      KO_GENR.ATUKAI_SYAIN = JIN.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       (" . "\r\n";
        $strSQL .= "       SELECT GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "       ,      SUM(CASE WHEN GRI.DATA_KB IN ('1','3') THEN NVL(GRI.DAISU,0) ELSE 0 END) BU_SIN_DAI " . "\r\n";
        $strSQL .= "       ,      SUM(NVL(GRI.DAISU,0)) BU_DAI" . "\r\n";
        $strSQL .= "       FROM   HGENRI_VW GRI" . "\r\n";
        $strSQL .= "       --販売区分をとるために限界利益ﾃﾞｰﾀと結合　2006/10/18 INS" . "\r\n";
        $strSQL .= "       INNER JOIN HGENRI GEN" . "\r\n";
        $strSQL .= "       ON     GEN.UC_NO = GRI.UC_NO" . "\r\n";
        $strSQL .= "       AND    GEN.NENGETU = GRI.NENGETU" . "\r\n";
        $strSQL .= "       ----------------------------------------- 2006/10/18 INS" . "\r\n";
        $strSQL .= "       WHERE  GRI.DATA_KB IN ('1','2','3')" . "\r\n";
        $strSQL .= "       AND    GRI.ATUKAI_BUSYO <> '211'" . "\r\n";
        $strSQL .= "       AND    GRI.NENGETU = '@TUKI'" . "\r\n";
        $strSQL .= "       GROUP BY GRI.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "       ) BU_GENR" . "\r\n";
        $strSQL .= "ON     BU_GENR.ATUKAI_BUSYO = JIN.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       (" . "\r\n";
        $strSQL .= "		SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "        ,      SUM(NVL(V.GAKU,0)) YACHIN_GAKU" . "\r\n";
        $strSQL .= "        FROM   (" . "\r\n";
        $strSQL .= "				SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "		        ,      SUM(NVL(FRI.KEIJO_GK,0)) GAKU" . "\r\n";
        $strSQL .= "				FROM   HFURIKAE FRI" . "\r\n";
        $strSQL .= "				WHERE  FRI.KEIJO_DT BETWEEN '@STARTDATE' AND '@ENDDATE'" . "\r\n";
        $strSQL .= "				AND    FRI.TAISK_KB = '1'" . "\r\n";
        $strSQL .= "				AND    FRI.KAMOK_CD = '43361'" . "\r\n";
        $strSQL .= "				AND    FRI.HIMOK_CD = '11'" . "\r\n";
        $strSQL .= "		        GROUP BY FRI.BUSYO_CD" . "\r\n";
        $strSQL .= "		        UNION ALL" . "\r\n";
        $strSQL .= "				SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "		        ,      SUM(NVL(FRI.KEIJO_GK,0)) * -1 GAKU" . "\r\n";
        $strSQL .= "				FROM   HFURIKAE FRI" . "\r\n";
        $strSQL .= "				WHERE  FRI.KEIJO_DT BETWEEN '@STARTDATE' AND '@ENDDATE'" . "\r\n";
        $strSQL .= "				AND    FRI.TAISK_KB = '2'" . "\r\n";
        $strSQL .= "				AND    FRI.KAMOK_CD = '43361'" . "\r\n";
        $strSQL .= "				AND    FRI.HIMOK_CD = '11'" . "\r\n";
        $strSQL .= "		        GROUP BY FRI.BUSYO_CD" . "\r\n";
        $strSQL .= "                ) V" . "\r\n";
        $strSQL .= "       GROUP BY BUSYO_CD" . "\r\n";
        $strSQL .= "       ) YACHIN" . "\r\n";
        $strSQL .= "ON     YACHIN.BUSYO_CD = JIN.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       (" . "\r\n";
        $strSQL .= "        SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "        ,      SUM(NVL(GAKU,0)) NINZU" . "\r\n";
        $strSQL .= "        FROM   (" . "\r\n";
        $strSQL .= "				SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "		        ,      (NVL(FRI.KEIJO_GK,0)) GAKU" . "\r\n";
        $strSQL .= "				FROM   HFURIKAE FRI" . "\r\n";
        $strSQL .= "				WHERE  FRI.KEIJO_DT BETWEEN '@STARTDATE' AND '@ENDDATE'" . "\r\n";
        $strSQL .= "				AND    FRI.TAISK_KB = '1'" . "\r\n";
        $strSQL .= "				AND    FRI.KAMOK_CD = '00800'" . "\r\n";
        $strSQL .= "				UNION ALL" . "\r\n";
        $strSQL .= "		        SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "		        ,      (NVL(FRI.KEIJO_GK,0)) * -1" . "\r\n";
        $strSQL .= "				FROM   HFURIKAE FRI" . "\r\n";
        $strSQL .= "				WHERE  FRI.KEIJO_DT BETWEEN '@STARTDATE' AND '@ENDDATE'" . "\r\n";
        $strSQL .= "				AND    FRI.TAISK_KB = '2'" . "\r\n";
        $strSQL .= "				AND    FRI.KAMOK_CD = '00800'" . "\r\n";
        $strSQL .= "                ) V" . "\r\n";
        $strSQL .= "        GROUP BY BUSYO_CD" . "\r\n";
        $strSQL .= "       ) SOU_JININ" . "\r\n";
        $strSQL .= "ON     SOU_JININ.BUSYO_CD = JIN.BUSYO_CD" . "\r\n";
        $strSQL .= "WHERE JIN.KEIJO_DT = '@TUKI'" . "\r\n";
        $strSQL .= "AND   JIN.BUSYO_CD <> '211'" . "\r\n";

        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "KaverRankSyukei";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];

        $ym = $postData;
        $y = substr($ym, 0, 4);
        $m = substr($ym, 4, 2);
        // $m1 = (int) $m;
        // $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $d = date("t", strtotime($y . '-' . $m));
        $ymd = $y . $m . $d;

        $strSQL = str_replace("@TUKI", $postData, $strSQL);
        $strSQL = str_replace("@STARTDATE", $postData . '01', $strSQL);

        $strSQL = str_replace("@ENDDATE", $ymd, $strSQL);

        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);

        return $strSQL;

    }

    function fncUpdJinkenSagakuWariSql($postData)
    {
        //取込んだ人件費ﾃﾞｰﾀと部署別実績集計ﾃﾞｰﾀの人件費の差額を割り振る
        $strSQL = "";

        $strSQL .= "UPDATE HSLSSTAFF SLS" . "\r\n";
        $strSQL .= "SET SOU_JINKEN = SOU_JINKEN + " . "\r\n";
        $strSQL .= "                   DECODE(SLS.YACHIN_JININ,0,0,(SELECT ROUND(SAGAKU_TBL.SAGAKU)" . "\r\n";
        $strSQL .= "                    FROM   (" . "\r\n";
        $strSQL .= "                            SELECT STAFF.BUSYO_CD" . "\r\n";
        $strSQL .= "                            ,      NVL(KANR.KANR_JINKEN,0) + (CASE WHEN STAFF.BUSYO_CD = '161' AND STAFF.KEIJO_DT >= '200702' AND STAFF.KEIJO_DT <= '200707' THEN 170000 ELSE 0 END) - NVL(STAFF.SOU_JINKEN,0) SAGAKU" . "\r\n";
        $strSQL .= "                            FROM  (SELECT KEIJO_DT, BUSYO_CD, MAX(SOU_JINKEN_BU) SOU_JINKEN FROM HSLSSTAFF WHERE KEIJO_DT = '@TUKI' GROUP BY KEIJO_DT, BUSYO_CD) STAFF" . "\r\n";
        $strSQL .= "                            ,     (SELECT KAR.BUSYO_CD" . "\r\n";
        $strSQL .= "                                   ,      SUM(NVL(KAR.TOU_ZAN,0)) KANR_JINKEN" . "\r\n";
        $strSQL .= "                                   FROM   HKANRIZ KAR" . "\r\n";
        $strSQL .= "                                   WHERE  ((KAR.KAMOKU_CD IN ('42219','42419','43211','43222','43223'," . "\r\n";
        $strSQL .= "                                                            '43224','43225','43226','43227','43228'," . "\r\n";
        $strSQL .= "                                                            '43229','43231','43232','43240','43241'," . "\r\n";
        $strSQL .= "                                                            '43242','43243','43251')" . "\r\n";
        $strSQL .= "                                          AND (NVL(TRIM(KAR.HIMOKU_CD),'00') = '00' OR  SUBSTR(KAR.HIMOKU_CD,1,1) = '0'))" . "\r\n";
        $strSQL .= "                                   OR     KAR.KAMOKU_CD = '43220')" . "\r\n";
        $strSQL .= "                                   AND    KAR.KEIJO_DT = '@TUKI'" . "\r\n";
        $strSQL .= "                                   GROUP BY BUSYO_CD" . "\r\n";
        $strSQL .= "						           ) KANR" . "\r\n";
        $strSQL .= "				            WHERE STAFF.BUSYO_CD = KANR.BUSYO_CD" . "\r\n";
        $strSQL .= "				            ) SAGAKU_TBL" . "\r\n";
        $strSQL .= "                    WHERE SLS.BUSYO_CD = SAGAKU_TBL.BUSYO_CD)" . "\r\n";
        $strSQL .= "                  / SLS.YACHIN_JININ)" . "\r\n";
        $strSQL .= "WHERE SLS.KEIJO_DT = '@TUKI'" . "\r\n";

        $strSQL = str_replace("@TUKI", $postData, $strSQL);

        return $strSQL;
    }

    function fncUpdBolboZeroSetSql($postData)
    {
        //部署ｺｰﾄﾞがボルボの場合、部の広告費、デモ費は0にする
        $strSQL = "";
        $strSQL .= "UPDATE HSLSSTAFF A" . "\r\n";
        $strSQL .= "SET    BU_KOUKOKU = (CASE WHEN BUSYO_CD LIKE '27%' OR BUSYO_CD LIKE '29%' THEN 0 ELSE BU_KOUKOKU END)" . "\r\n";
        $strSQL .= ",      BU_DEMO = (CASE WHEN BUSYO_CD LIKE '27%' OR BUSYO_CD LIKE '29%' THEN 0 ELSE BU_DEMO END)" . "\r\n";
        $strSQL .= "WHERE KEIJO_DT = '@TUKI'" . "\r\n";

        $strSQL = str_replace("@TUKI", $postData, $strSQL);

        return $strSQL;
    }

    function fncUpdBubetuMinusKobetuSql($postData)
    {
        //部別の項目に個別の値も含まれているため、部別から個別を引く
        $strSQL = "";

        $strSQL .= "UPDATE HSLSSTAFF A" . "\r\n";
        $strSQL .= "SET    BU_NOUSYA = NVL(BU_NOUSYA,0) - (SELECT NVL(SUM(NVL(B.KO_NOUSYA,0)),0) FROM HSLSSTAFF B WHERE A.BUSYO_CD = B.BUSYO_CD AND A.KEIJO_DT = B.KEIJO_DT GROUP BY B.BUSYO_CD)" . "\r\n";
        $strSQL .= ",      BU_SERVICE = NVL(BU_SERVICE,0) - (SELECT NVL(SUM(NVL(B.KO_SERVICE,0)),0) FROM HSLSSTAFF B WHERE A.BUSYO_CD = B.BUSYO_CD AND A.KEIJO_DT = B.KEIJO_DT GROUP BY B.BUSYO_CD)" . "\r\n";
        $strSQL .= ",      BU_UNSOUHI = NVL(BU_UNSOUHI,0) - (SELECT NVL(SUM(NVL(B.KO_UNSOUHI,0)),0) FROM HSLSSTAFF B WHERE A.BUSYO_CD = B.BUSYO_CD AND A.KEIJO_DT = B.KEIJO_DT GROUP BY B.BUSYO_CD)" . "\r\n";
        $strSQL .= ",      BU_ZAPPI = NVL(BU_ZAPPI,0) - (SELECT NVL(SUM(NVL(B.KO_ZAPPI,0)),0) FROM HSLSSTAFF B WHERE A.BUSYO_CD = B.BUSYO_CD AND A.KEIJO_DT = B.KEIJO_DT GROUP BY B.BUSYO_CD)" . "\r\n";
        $strSQL .= ",      BU_KOUKOKU = NVL(BU_KOUKOKU,0) - (SELECT NVL(SUM(NVL(B.KO_KOUKOKU,0)),0) FROM HSLSSTAFF B WHERE A.BUSYO_CD = B.BUSYO_CD AND A.KEIJO_DT = B.KEIJO_DT GROUP BY B.BUSYO_CD)" . "\r\n";
        $strSQL .= ",      SYOHI_HITORI =  DECODE(NVL(YACHIN_JININ,0),0,0,ROUND((NVL(SYOHI,0)) / NVL(YACHIN_JININ,0),0))" . "\r\n";
        $strSQL .= "WHERE  KEIJO_DT = '@TUKI'" . "\r\n";
        $strSQL = str_replace("@TUKI", $postData, $strSQL);

        return $strSQL;
    }

    function fncAtamaWariIppanSql($postData)
    {
        //頭割りするものは、マネージャには割り振らない
        $strSQL = "";
        $strSQL .= "UPDATE HSLSSTAFF A" . "\r\n";
        $strSQL .= "SET    ATM_WARI_RIEKI_PLUS = DECODE(NVL(YACHIN_JININ,0) - NVL(KANRIJININ,0),0,0,ROUND((NVL(RIEKI_PLUS_BU,0)" . "\r\n";
        $strSQL .= "                                                                   - (SELECT SUM(NVL(RIEKI_PLUS_KO,0)) FROM HSLSSTAFF B" . "\r\n";
        $strSQL .= "                                                                   WHERE A.KEIJO_DT = B.KEIJO_DT AND A.BUSYO_CD = B.BUSYO_CD GROUP BY B.BUSYO_CD)" . "\r\n";
        $strSQL .= "                                                                  )" . "\r\n";
        $strSQL .= "                                                                 / (NVL(YACHIN_JININ,0) - NVL(KANRIJININ,0)),0))	" . "\r\n";
        $strSQL .= ",      ATM_WARI_HIYOU_MINUS = DECODE(NVL(YACHIN_JININ,0) - NVL(KANRIJININ,0),0,0,ROUND((NVL(HIYOU_MINUS_BU,0)" . "\r\n";
        $strSQL .= "                                                                   - (SELECT SUM(NVL(HIYOU_MINUS_KO,0)) FROM HSLSSTAFF B" . "\r\n";
        $strSQL .= "                                                                      WHERE A.KEIJO_DT = B.KEIJO_DT AND A.BUSYO_CD = B.BUSYO_CD GROUP BY B.BUSYO_CD)" . "\r\n";
        $strSQL .= "                                                                  )" . "\r\n";
        $strSQL .= "                                                                 / (NVL(YACHIN_JININ,0) - NVL(KANRIJININ,0)),0))	" . "\r\n";
        $strSQL .= ",      ATM_WARI_RIEKI_MINUS =  DECODE(NVL(YACHIN_JININ,0) - NVL(KANRIJININ,0),0,0,ROUND((NVL(RIEKI_MINUS_BU,0)" . "\r\n";
        $strSQL .= "                                                                    - (SELECT SUM(NVL(RIEKI_MINUS_KO,0)) FROM HSLSSTAFF B" . "\r\n";
        $strSQL .= "                                                                       WHERE A.KEIJO_DT = B.KEIJO_DT AND A.BUSYO_CD = B.BUSYO_CD GROUP BY B.BUSYO_CD)" . "\r\n";
        $strSQL .= "                                                                    )" . "\r\n";
        $strSQL .= "                                                                   / (NVL(YACHIN_JININ,0) - NVL(KANRIJININ,0)),0))	" . "\r\n";
        $strSQL .= ",      ATM_WARI_HIYOU_PLUS = DECODE(NVL(YACHIN_JININ,0) - NVL(KANRIJININ,0),0,0,ROUND((NVL(HIYOU_PLUS_BU,0)" . "\r\n";
        $strSQL .= "                                                                   - (SELECT SUM(NVL(HIYOU_PLUS_KO,0)) FROM HSLSSTAFF B" . "\r\n";
        $strSQL .= "                                                                      WHERE A.KEIJO_DT = B.KEIJO_DT AND A.BUSYO_CD = B.BUSYO_CD GROUP BY B.BUSYO_CD)" . "\r\n";
        $strSQL .= "                                                                  )" . "\r\n";
        $strSQL .= "                                                                 / (NVL(YACHIN_JININ,0) - NVL(KANRIJININ,0)),0))	" . "\r\n";
        $strSQL .= ",      ATM_WARI_HNB = (CASE WHEN (NVL(YACHIN_JININ,0) - NVL(KANRIJININ,0)) < 1 THEN 0" . "\r\n";
        $strSQL .= "                              ELSE ROUND((NVL(BU_KOUKOKU,0) + NVL(BU_DEMO,0)) / (NVL(YACHIN_JININ,0) - NVL(KANRIJININ,0)),0)	" . "\r\n";
        $strSQL .= "                              END)" . "\r\n";
        $strSQL .= "WHERE  NOT EXISTS (SELECT *" . "\r\n";
        $strSQL .= "                   FROM   HBUSYO B" . "\r\n";
        $strSQL .= "                   WHERE  A.BUSYO_CD = B.BUSYO_CD" . "\r\n";
        $strSQL .= "                   AND    A.SYAIN_NO = B.MANEGER_CD)" . "\r\n";
        $strSQL .= "AND   KEIJO_DT = '@TUKI'" . "\r\n";

        $strSQL = str_replace("@TUKI", $postData, $strSQL);

        return $strSQL;
    }

    function fncAtamaWariManegerSql($postData)
    {
        //頭割りの項目にマネージャの場合はゼロをセットする
        $strSQL = "";
        $strSQL .= "UPDATE HSLSSTAFF A" . "\r\n";
        $strSQL .= "SET    ATM_WARI_RIEKI_PLUS = 0" . "\r\n";
        $strSQL .= ",      ATM_WARI_HIYOU_MINUS = 0" . "\r\n";
        $strSQL .= ",      ATM_WARI_RIEKI_MINUS =  0" . "\r\n";
        $strSQL .= ",      ATM_WARI_HIYOU_PLUS = 0" . "\r\n";
        $strSQL .= ",      ATM_WARI_HNB = 0" . "\r\n";
        $strSQL .= "WHERE  EXISTS (SELECT *" . "\r\n";
        $strSQL .= "                   FROM   HBUSYO B" . "\r\n";
        $strSQL .= "                   WHERE  A.BUSYO_CD = B.BUSYO_CD" . "\r\n";
        $strSQL .= "                   AND    A.SYAIN_NO = B.MANEGER_CD)" . "\r\n";
        $strSQL .= "AND   KEIJO_DT = '@TUKI'" . "\r\n";

        $strSQL = str_replace("@TUKI", $postData, $strSQL);

        return $strSQL;
    }

    function fncUpdRankTotalFirstSql($postData)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HSLSSTAFF A" . "\r\n";
        $strSQL .= "SET    DAI_SYOUREI = DECODE(NVL(BU_SIN_DAISU,0),0,0,ROUND(NVL(SYOREIKIN,0) / NVL(BU_SIN_DAISU,0) * NVL(SIN_DAISU,0),0))" . "\r\n";
        $strSQL .= ",      DAI_WARI_HNB = DECODE(NVL(BU_DAISU,0),0,0,ROUND(((NVL(BU_NOUSYA,0) + NVL(BU_SERVICE,0) + NVL(BU_UNSOUHI,0) + NVL(BU_ZAPPI,0)) / NVL(BU_DAISU,0) * NVL(SOU_DAISU,0)),0))" . "\r\n";
        //20151019 yin DEL S
        //$strSQL .= "                    + NVL(HIYOU_PLUS_KO,0) + NVL(ATM_WARI_HIYOU_PLUS,0) - NVL(HIYOU_MINUS_KO,0) - NVL(ATM_WARI_HIYOU_MINUS,0)" . "\r\n";
        //$strSQL .= "                    + DECODE(NVL(SYOCHO_FUTAN_KO,0),0,NVL(SYOCHO_FUTAN,0),NVL(SYOCHO_FUTAN_KO,0)) + NVL(HONSYA_FUTAN,0) --DEL STart + DECODE(NVL(CHUZAI_FUTAN_KO,0),0,NVL(CHUZAI_FUTAN,0),NVL(CHUZAI_FUTAN_KO,0))	--UPD" . "\r\n";
        //20151019 yin DEL E
        $strSQL .= ",      KOTEIHIKEI = NVL(SOU_JINKEN,0) + NVL(SYOHI_HITORI,0) + NVL(KOTEIHICHOSEI,0) /*+ NVL(FUKURIKOSEI_KO,0)*/" . "\r\n";
        $strSQL .= "                    + NVL(HIYOU_PLUS_KO,0) + NVL(ATM_WARI_HIYOU_PLUS,0) - NVL(HIYOU_MINUS_KO,0) - NVL(ATM_WARI_HIYOU_MINUS,0)" . "\r\n";
        $strSQL .= "                    + DECODE(NVL(SYOCHO_FUTAN_KO,0),0,NVL(SYOCHO_FUTAN,0),NVL(SYOCHO_FUTAN_KO,0)) + NVL(HONSYA_FUTAN,0) --DEL STart + DECODE(NVL(CHUZAI_FUTAN_KO,0),0,NVL(CHUZAI_FUTAN,0),NVL(CHUZAI_FUTAN_KO,0))	--UPD" . "\r\n";
        $strSQL .= ",      YACHIN_HITORI = (CASE WHEN NVL(YACHIN_KO,0) <> 0" . "\r\n";
        $strSQL .= "                             THEN NVL(YACHIN_KO,0)" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN BUSYO_CD = '231' " . "\r\n";
        $strSQL .= "                                        THEN DECODE(NVL(YACHIN_JININ,0),0,0,ROUND((NVL(YACHIN,0) " . "\r\n";
        $strSQL .= "                                                                                   - (SELECT NVL(SUM(YACHIN_KO),0) FROM HSLSSTAFF B" . "\r\n";
        $strSQL .= "                                                                                      WHERE A.KEIJO_DT = B.KEIJO_DT AND B.BUSYO_CD = '231'" . "\r\n";
        $strSQL .= "                                                                                      GROUP BY B.BUSYO_CD)) / NVL(YACHIN_JININ,0),0))" . "\r\n";
        $strSQL .= "                                        ELSE DECODE(NVL(YACHIN_JININ,0),0,0,ROUND((NVL(YACHIN,0) + DECODE(NVL(CHUZAI_FUTAN_KO,0),0,NVL(CHUZAI_FUTAN,0),NVL(CHUZAI_FUTAN_KO,0))) / NVL(YACHIN_JININ,0),0))" . "\r\n";
        $strSQL .= "                                   END)" . "\r\n";
        $strSQL .= "                        END)" . "\r\n";
        $strSQL .= "WHERE  A.KEIJO_DT = '@TUKI'" . "\r\n";

        $strSQL = str_replace("@TUKI", $postData, $strSQL);

        return $strSQL;
    }

    function fncUpdRankTotalSecoundSql($postData)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HSLSSTAFF A" . "\r\n";
        $strSQL .= "SET    SOU_GENRI = (NVL(SRY_RIEKI,0) + NVL(JIBAISEKI,0) + NVL(NINIHOKEN,0) + NVL(GDMZ_LEASE,0) " . "\r\n";
        $strSQL .= "                     + NVL(CHUKO_SYOKAI,0) + NVL(KICKBACK,0) + NVL(DAI_SYOUREI,0) + NVL(RIEKI_PLUS_KO,0)" . "\r\n";
        $strSQL .= "                     + NVL(ATM_WARI_RIEKI_PLUS,0)" . "\r\n";
        // 20231115 YIN INS S
        $strSQL .= "                     + NVL(IRAI_HAISYA,0)" . "\r\n";
        // 20231115 YIN INS E
        $strSQL .= "                     )" . "\r\n";
        $strSQL .= "                     - (NVL(KO_NOUSYA,0) + NVL(KO_SERVICE,0) + NVL(KO_UNSOUHI,0) + NVL(KO_ZAPPI,0) " . "\r\n";
        $strSQL .= "                       + NVL(KO_KOUKOKU,0) + NVL(DAI_WARI_HNB,0) + NVL(ATM_WARI_HNB,0) + NVL(FWR_KINRI,0)" . "\r\n";
        $strSQL .= "                       + NVL(PENALTY,0) + NVL(RIEKI_MINUS_KO,0) + NVL(ATM_WARI_RIEKI_MINUS,0))" . "\r\n";
        $strSQL .= ",      KOTEIHI_YACHIN = ROUND(NVL(KOTEIHIKEI,0) + NVL(YACHIN_HITORI,0),0)" . "\r\n";
        $strSQL .= "WHERE  KEIJO_DT = '@TUKI'" . "\r\n";

        $strSQL = str_replace("@TUKI", $postData, $strSQL);

        return $strSQL;
    }

    public function fncExistsNensu($postData)
    {
        $strSql = $this->fncExistsNensuSql($postData);

        return parent::select($strSql);
    }

    public function frmSampleLoadDate()
    {
        $strSql = $this->frmSampleLoadDateSql();

        return parent::select($strSql);
    }

    public function fncExistsNinho($postData)
    {
        $strSql = $this->fncExistsNinhoSql($postData);

        return parent::select($strSql);
    }

    public function fncExistsJibaiseki($postData)
    {
        $strSql = $this->fncExistsJibaisekiSql($postData);

        return parent::select($strSql);
    }

    public function fncExistsJinkenhi($postData)
    {
        $strSql = $this->fncExistsJinkenhiSql($postData);

        return parent::select($strSql);
    }

    public function fncDeleteSalse($postData)
    {
        $strSql = $this->fncDeleteSalseSql($postData);

        return parent::Do_Execute($strSql);
    }

    public function fncInsKaverRankTotal($postData)
    {
        $strSql = $this->fncInsKaverRankTotalSql($postData);

        return parent::Do_Execute($strSql);
    }

    public function fncUpdJinkenSagakuWari($postData)
    {
        $strSql = $this->fncUpdJinkenSagakuWariSql($postData);

        return parent::Do_Execute($strSql);
    }

    public function fncUpdBolboZeroSet($postData)
    {
        $strSql = $this->fncUpdBolboZeroSetSql($postData);

        return parent::Do_Execute($strSql);
    }

    public function fncUpdBubetuMinusKobetu($postData)
    {
        $strSql = $this->fncUpdBubetuMinusKobetuSql($postData);

        return parent::Do_Execute($strSql);
    }

    public function fncAtamaWariIppan($postData)
    {
        $strSql = $this->fncAtamaWariIppanSql($postData);

        return parent::Do_Execute($strSql);
    }

    public function fncAtamaWariManeger($postData)
    {
        $strSql = $this->fncAtamaWariManegerSql($postData);

        return parent::Do_Execute($strSql);
    }

    public function fncUpdRankTotalFirst($postData)
    {
        $strSql = $this->fncUpdRankTotalFirstSql($postData);

        return parent::Do_Execute($strSql);
    }

    public function fncUpdRankTotalSecound($postData)
    {
        $strSql = $this->fncUpdRankTotalSecoundSql($postData);

        return parent::Do_Execute($strSql);
    }

}