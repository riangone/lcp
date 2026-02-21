<?php
// 共通クラスの読込み
namespace App\Model\CkChkzaiko;

use App\Model\Component\ClsComDb;
//App::uses('Do_SQL_MySql', 'Model/Component');
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class CkChkzaiko extends ClsComDb
{
    public function get_sql($postData, $sortStr, $start, $limit, $cmn_no_data = [])
    {
        //20140218 yushuangji edit start
        $sql = "";
        $sql = "";

        $sql .= "SELECT   ";
        //整理No
        $sql .= "		m41e10.CKO_CAR_SER_NO as CKO_CAR_SER_NO,   ";
        //UCNO
        $sql .= "		m41e10.UC_NO as UC_NO,   ";
        //注文書No
        $sql .= "		m41e11.CMN_NO as CMN_NO,  ";
        //2014-03-03 修正 START 中古車Noの項目追加
        //中古車No
        $sql .= "		m41b02.CHUKOSYA_NO as CHUKOSYA_NO,   ";
        //2014-03-03 修正 END 中古車Noの項目追加
        //車種
        $sql .= "		m41e11.VCLNM as VCLNM,   ";
        //登録No陸自名
        $sql .= "		decode(m28m66.SCD_NM,null, m41e11.TOU_NO_RKJ_CD,m28m66.SCD_NM) as TOU_NO_RKJ_NM,  ";
        //登録No-種別
        $sql .= "		m41e11.VCLRGTNO_SYU,   ";
        //登録No-カナ
        $sql .= "		m41e11.TOU_NO_KNA,   ";
        //登録No-連番
        $sql .= "		m41e11.TOU_NO_RBN,   ";
        //下買取先
        $sql .= "		m41e11.USR_NM,   ";
        //印刷日時
        $sql .= "		to_char(ck_chkzaiko.OUT_PUT_DTM,'yyyy-mm-dd'),  ";
        //下取車シーケンスNo
        $sql .= "		m41e11.TRA_CAR_SEQ_NO as TRA_CAR_SEQ_NO,   ";
        //銘柄コード
        $sql .= "		m41e11.BRD_CD,   ";
        //MEIGARA_MEI
        $sql .= "		M28M71.MEIGARA_MEI,    ";
        //年製
        $sql .= "		m41e11.SYD_TOU_YM,   ";
        //認可型式
        $sql .= "		m41e11.NINKATA_CD,   ";
        //車体No
        $sql .= "		m41e11.CAR_NO,   ";
        //査定価格
        $sql .= "		m41e11.SATEI_GK,   ";
        //登録日 sat_dt
        //2014-02-25 修正 START 登録日を REC_CRE_DT から TOU_DT に修正
        //$sql .= "		to_char(m41e11.REC_CRE_DT,'yyyy-mm-dd') as REC_CRE_DT , ";
        $sql .= "		m41e10.TOU_DT , ";
        //2014-02-25 修正 END 登録日を REC_CRE_DT から TOU_DT に修正
        //部署 kyotn_nm
        $sql .= "		M27M01.KYOTN_RKN,   ";
        //扱者姓
        $sql .= "		M29MA4.SYAIN_KNJ_SEI,  ";
        //扱者名
        $sql .= "		M29MA4.SYAIN_KNJ_MEI,  ";
        //型式指定番号
        $sql .= "		m41e11.SITEI_NO,   ";
        //類別区分
        $sql .= "		m41e11.RUIBETU_NO,   ";
        //受入価格
        $sql .= "		m41e11.TRA_GK,   ";
        //部署コード
        $sql .= "		m41e10.HNB_KTN_CD,   ";
        //扱者コード
        $sql .= "		m41e10.HNB_TAN_EMP_NO,  ";
        //車両状態コード SYR_JT_CD
        //リサイクル券No
        $sql .= "		m41e11.RCYL_KEN_NO,  ";
        //リサイクル預託金合計額
        $sql .= "		m41e11.YOTAK_GK,   ";
        //リサイクル料金合計額
        $sql .= "		m41e11.RCYL_GK,   ";
        //シュレッダーダスト料金
        $sql .= "		m41e11.ASR_RYOKIN,   ";
        //エアバッグ類料金
        $sql .= "		m41e11.AIRBUG_RYOKIN,   ";
        //フロン類料金
        $sql .= "		m41e11.FULON_RYOKIN,   ";
        //情報管理料金
        $sql .= "		m41e11.JOHO_KNR_RYOKIN,   ";
        //資金管理料金
        $sql .= "		m41e11.SHIKIN_KNR_RYOKIN,   ";
        //エアバック類装備有無
        $sql .= "		m41e11.AIRBUG_EQU_UM,   ";
        //フロン類装備有無
        $sql .= "		m41e11.FULON_EQU_UM,   ";
        //シュレッダーダスト預託有無
        $sql .= "		m41e11.ASR_YOTAK_UM,   ";
        //エアバック類預託有無
        $sql .= "		m41e11.AIRBUG_YOTAK_UM,   ";
        //フロン類預託有無
        $sql .= "		m41e11.FULON_YOTAK_UM,   ";
        //情報管理預託有無
        $sql .= "		m41e11.JOHO_KNR_YOTAK_UM,   ";
        //抹消登録手続き代行費用
        $sql .= "		m41e11.MSY_TOU_TTK_DAIKO_HYO,   ";
        //抹消登録預かり法定費用
        $sql .= "		m41e11.MSY_TOU_AZK_HTE_HYO,   ";
        //使用済自動車処理費用
        $sql .= "		m41e11.SIY_SMI_CAR_SYR_HYO,  ";
        //印刷済みフラグ
        $sql .= "		ck_chkzaiko.OUT_PUT_FLG,  ";
        //印刷ユーザID
        $sql .= "		ck_chkzaiko.OUT_PUT_ID,   ";
        //shz_rt.
        $sql .= "		m41e11.SHZ_RT,   ";
        //shz_kb
        $sql .= "			m41e11.SHZ_KB ";
        //$sql .= "		M27M01.KYOTN_RKN,  ";
        $sql .= "FROM  ";
        $sql .= "		m41e10  ";
        $sql .= "		 LEFT JOIN ck_chkzaiko on m41e10.CMN_NO = ck_chkzaiko.CMN_NO  ";
        $sql .= "		 LEFT JOIN M27M01 on m41e10.HNB_KTN_CD = M27M01.KYOTN_CD and M27M01.HANSH_CD='3634'  ";
        $sql .= "		 LEFT JOIN M29MA4 on m41e10.HNB_TAN_EMP_NO = M29MA4.SYAIN_NO and M27M01.HANSH_CD='3634',  ";
        $sql .= "		m41e11  ";
        $sql .= "		 LEFT JOIN m28m66 on m41e11.TOU_NO_RKJ_CD = m28m66.SCD_VAL and m28m66.SCD_SYSID = 'V'  ";
        $sql .= "		 LEFT JOIN M28M71 on m41e11.BRD_CD = M28M71.MEIGARA_CODE  ";
        //2014-03-03 修正 START 中古車Noを追加する対応
        $sql .= "		 LEFT JOIN M41B02 on m41e11.CMN_NO = m41b02.SEIRI_NO and m41e11.TRA_CAR_SEQ_NO = m41b02.SEIRI_SEQ ";
        //2014-03-03 修正 END 中古車Noを追加する対応
        $sql .= "WHERE  ";
        $sql .= "		 m41e10.CMN_NO = m41e11.CMN_NO  ";
        //----------
        if (!empty($cmn_no_data)) {
            $sql .= "AND		 m41e10.CMN_NO in ('" . implode("','", $cmn_no_data) . "')  ";
        } else {
            $where = $this->createWhere($postData["checkTF"], $postData["preDate"], $postData["nextDate"]);
            $where = str_replace("WHERE", " ", $where);
            $sql .= $where;

            //sort
            if (trim($sortStr) != "") {
                $sql .= " ORDER BY " . $sortStr;
            } else {
                //2014-02-28 修正 START 登録日を REC_CRE_DT から TOU_DTへ修正
                $sql .= " ORDER BY m41e10.TOU_DT,  m41e10.HNB_KTN_CD ";
                //2014-02-28 修正 END 登録日を REC_CRE_DT から TOU_DTへ修正
            }
        }
        //---
        $cell = "*";
        if (trim($start) != "") {
            $start = " WHERE RNM >" . $start;
        }
        if (!empty($cmn_no_data)) {
            $limit = "   ORDER BY CASE CMN_NO   ";
            foreach ($cmn_no_data as $key => $value) {
                $limit .= "       WHEN '" . $value . "' THEN " . $key + 1;
            }
            $limit .= "   END";
        } else {
            if (trim($limit) != "") {
                $limit = " WHERE ROWNUM<=" . $limit;
            }
        }
        $sql = "SELECT " . $cell . " FROM (SELECT TBL." . $cell . ",ROWNUM RNM FROM ( " . $sql . ") TBL " . $limit . ") " . $start;
        return $sql;
        //20140218 yushuangji edit end
    }

    public function get_cnt($postData, $sortStr)
    {
        //20140218 yushuangji edit start
        $sql = "";
        $sql .= "SELECT   ";
        $sql .= "		count(*) as cnt   ";
        $sql .= "FROM  ";
        $sql .= "		m41e10  ";
        $sql .= "		LEFT JOIN ck_chkzaiko on m41e10.CMN_NO = ck_chkzaiko.CMN_NO  ";
        $sql .= "		LEFT JOIN M27M01 on m41e10.HNB_KTN_CD = M27M01.KYOTN_CD and

M27M01.HANSH_CD='3634'  ";
        $sql .= "		LEFT JOIN M29MA4 on m41e10.HNB_TAN_EMP_NO = M29MA4.SYAIN_NO and

M27M01.HANSH_CD='3634',  ";
        $sql .= "		m41e11  ";
        $sql .= "		LEFT JOIN m28m66 on m41e11.TOU_NO_RKJ_CD = m28m66.SCD_VAL and

m28m66.SCD_SYSID = 'V'  ";
        $sql .= "		LEFT JOIN M28M71 on m41e11.BRD_CD = M28M71.MEIGARA_CODE  ";
        $sql .= "WHERE  ";
        $sql .= "		 m41e10.CMN_NO = m41e11.CMN_NO  ";
        $where = $this->createWhere($postData["checkTF"], $postData["preDate"], $postData["nextDate"]);
        $where = str_replace("WHERE", " ", $where);
        $sql .= $where;
        //sort
        if ($sortStr) {
            $sql .= " ORDER BY " . $sortStr;
        }
        return $sql;
        //20140218 yushuangji edit end
    }

    public function createWhere($checkTF, $preDate, $nextDate)
    {
        $whereA = "";
        $where2 = "";
        //check tf
        if (isset($checkTF) && $checkTF == "true") {
            //2014-02-25 修正 START 印刷済の条件を追加
            $where2 .= " CK_CHKZAIKO.OUT_PUT_FLG IS NOT NULL ";
            //2014-02-25 修正 END 印刷済の条件を追加
        } else {
            //ysj
            $where2 .= " CK_CHKZAIKO.OUT_PUT_FLG IS NULL ";
        }

        if (trim($where2) != "") {
            if (trim($whereA) != "") {
                $whereA .= " AND " . $where2;
            } else {
                $whereA .= " and " . $where2;
            }
        }

        //print date
        $where3 = "";
        if (isset($checkTF) && $checkTF == "true") {
            $where3 .= "  CK_CHKZAIKO.OUT_PUT_FLG=1 ";
            if (isset($preDate) && trim($preDate) != "") {
                $where3 .= " AND to_char(ck_chkzaiko.OUT_PUT_DTM,'yyyy-mm-dd') >= '" . $preDate . "'  ";
            }
            if (isset($nextDate) && trim($nextDate) != "") {
                if (trim($where3) != "") {
                    $where3 .= " AND " . " to_char(ck_chkzaiko.OUT_PUT_DTM,'yyyy-mm-dd') <= '" . $nextDate . "'  ";
                } else {
                    $where3 .= " AND to_char(ck_chkzaiko.OUT_PUT_DTM,'yyyy-mm-dd') <= '" . $nextDate . "'  ";
                }
            }
        }
        //rec_cre_dt
        else {
            //2014-02-25 修正 START 登録日を REC_CRE_DT から TOU_DT に修正
            if (isset($preDate) && trim($preDate) != "") {
                $where3 .= " m41e10.TOU_DT >= '" . str_replace("-", "", $preDate) . "'  ";
            }
            if (isset($nextDate) && trim($nextDate) != "") {
                if (trim($where3) != "") {
                    $where3 .= " AND " . " m41e10.TOU_DT <= '" . str_replace("-", "", $nextDate) . "'  ";
                } else {
                    $where3 .= " m41e10.TOU_DT <= '" . str_replace("-", "", $nextDate) . "'  ";
                }
            }
            //2014-02-25 修正 END 登録日を REC_CRE_DT から TOU_DT に修正
        }
        if (trim($where3) != "") {
            if (trim($whereA) != "") {
                $whereA .= " AND " . $where3;
            } else {
                $whereA .= " AND " . $where3;
            }
        }
        if (trim($whereA) != "") {
            $whereA = " WHERE " . $whereA;
        }
        return $whereA;
    }

    public function fncCkChkzaikoSelect($sortStr, $start, $limit, $postData, $cmn_no_data = [])
    {
        //20140218 yushuangji edit start
        if ($sortStr == "" && $start == "" && $limit == "" && empty($cmn_no_data)) {
            $strSql = $this->get_cnt($postData, $sortStr);
            $getCntFlg = TRUE;

            return $this->m_run_sql($strSql, $getCntFlg);
        } else {
            $strSql = $this->get_sql($postData, $sortStr, $start, $limit, $cmn_no_data);
            $getCntFlg = FALSE;
            return $this->m_run_sql($strSql, $getCntFlg);
        }
        //20140218 yushuangji edit end

    }

    public function UpdateSQL($userid, $postData, $time)
    {
        $i = 0;
        $sql = "";
        $tmpRoleCD = "CMN_NO";
        $tmpWhere = array();

        foreach ($postData as $i => $value) {
            $tmpWhere[$i] = "CMN_NO=:" . $tmpRoleCD . $i;
        }

        $tmpWhere = implode(" OR ", $tmpWhere);

        $sql = "UPDATE ck_chkzaiko";
        $sql .= " SET OUT_PUT_ID ='" . $userid . "',";
        $sql .= " OUT_PUT_DTM ='" . $time . "',";
        $sql .= " OUT_PUT_FLG ='1' ";

        if ($tmpWhere != "") {
            $tmpWhere = "WHERE " . $tmpWhere;
        }

        $sql .= $tmpWhere;
        return $sql;
    }

    public function m_run_sql($strSql, $flg = FALSE)
    {
        /*
         * 运行sql文
         * 说明：返回数组。
         * 数组形式：{result:true,data:[key:value]}
         */
        if ($flg) {
            return parent::select($strSql);
        } else {
            return parent::select($strSql);
            //print_r($tt);

        }

        //print_r($tt['data']);
        //return oci_fetch_all($tt);
    }

    public function fncUpdatePrintInfo($userid, $postData, $time)
    {
        return parent::update($this->UpdateSQL($userid, $postData, $time));
    }

    public function insertCK($sqlKeys, $sqlVals)
    {
        $sqlStr = "INSERT INTO CK_CHKZAIKO ";
        $sqlStr .= "(";
        $sqlStr .= $sqlKeys;
        $sqlStr .= ")";
        $sqlStr .= "values";
        $sqlStr .= "(";
        $sqlStr .= $sqlVals;
        $sqlStr .= ")";
        return parent::Do_Execute($sqlStr);
    }

}
