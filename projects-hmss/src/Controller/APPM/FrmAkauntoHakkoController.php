<?php
/**
 * 説明：
 *
 *
 * @author YINHUAIYU
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\APPM;

use App\Controller\AppController;
use App\Model\APPM\FrmAkauntoHakko;

/**
 * アカウント発行
 * FrmAkauntoHakkoController
 */
class FrmAkauntoHakkoController extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    public $autoLayout = TRUE;
    public $FrmAkauntoHakko;
    public $result = array();
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    /**
     * デフォルトで最初に実行される機能
     */
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmAkauntoHakko_layout');
    }

    //'**********************************************************************
    //'処 理 名：お客様情報取得
    //'関 数 名：FncGetSelect_Keiyakusya
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncGetSelectKeiyakusya()
    {
        $txtCusNo = $_POST['data']['txtCusNo'];
        try {
            $this->FrmAkauntoHakko = new FrmAkauntoHakko();
            $this->result = $this->FrmAkauntoHakko->FncKyakuCheck($txtCusNo);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            if ($this->result['row'] > 0) {
                throw new \Exception("対象のお客様は既にアカウント情報を発行しています。");
            }

            $this->result = $this->FrmAkauntoHakko->FncGetSelect_Keiyakusya($txtCusNo);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            if ($this->result['row'] > 0) {
                $data = $this->result['data'];

                $jqdata = array();

                foreach ((array) $data as $value) {
                    $updno = $value['SCD_NM'] . $value['VCLRGTNO_SYU'] . mb_convert_kana($value['VCLRGTNO_KANA'], "Hc") . $value['VCLRGTNO_REN'];
                    if (($value['VIN_WMIVDS'] == " " || $value['VIN_WMIVDS'] == null) && ($value['VIN_VIS'] == " " || $value['VIN_VIS'] == null)) {
                        $carno = $value['VIN_WMIVDS'] . $value['VIN_VIS'];
                    } else {
                        $carno = $value['VIN_WMIVDS'] . "-" . $value['VIN_VIS'];
                    }

                    $carnm = $value['VCLNM'];
                    $mydata = array(
                        'UPDNO' => $updno,
                        'CARNO' => $carno,
                        'CARNM' => $carnm
                    );
                    array_push($jqdata, $mydata);
                }
                $data = $this->result['data'][0];
                if (($data['CUS_HOM_TEL_ACD'] == " " || $data['CUS_HOM_TEL_ACD'] == null) && ($data['CUS_HOM_TEL_CCD'] == " " || $data['CUS_HOM_TEL_CCD'] == null) && ($data['CUS_HOM_TEL_KNY_NO'] == " " || $data['CUS_HOM_TEL_KNY_NO'] == null)) {
                    $homtel = $data['CUS_HOM_TEL_ACD'] . $data['CUS_HOM_TEL_CCD'] . $data['CUS_HOM_TEL_KNY_NO'];
                } else {
                    $homtel = $data['CUS_HOM_TEL_ACD'] . '-' . $data['CUS_HOM_TEL_CCD'] . '-' . $data['CUS_HOM_TEL_KNY_NO'];
                }

                if (($data['MOB_TEL_ACD'] == " " || $data['MOB_TEL_ACD'] == null) && ($data['MOB_TEL_CCD'] == " " || $data['MOB_TEL_CCD'] == null) && ($data['MOB_TEL_KNY_NO'] == " " || $data['MOB_TEL_KNY_NO'] == null)) {
                    $mobtel = $data['MOB_TEL_ACD'] . $data['MOB_TEL_CCD'] . $data['MOB_TEL_KNY_NO'];
                } else {
                    $mobtel = $data['MOB_TEL_ACD'] . '-' . $data['MOB_TEL_CCD'] . '-' . $data['MOB_TEL_KNY_NO'];
                }

                $csrad = $data['CSRAD1'] . $data['CSRAD2'] . $data['CSRAD3'];

                $data['HOM_TEL'] = $homtel;
                $data['MOB_TEL'] = $mobtel;
                $data['CSRAD'] = $csrad;
                $data['JQDATA'] = $jqdata;

                $this->result['data'] = $data;
            }

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        $this->fncReturn($this->result);
    }

    //'**********************************************************************
    //'処 理 名：ID/仮PW 発行
    //'関 数 名：fncIssue
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncIssue()
    {
        $txtCusNo = $_POST['data']['txtCusNo'];
        $txtCusNm = $_POST['data']['txtCusNm'];
        try {
            $this->FrmAkauntoHakko = new FrmAkauntoHakko();

            $DB_Conn = $this->FrmAkauntoHakko->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }
            //トランザクション開始
            $this->FrmAkauntoHakko->Do_transaction();

            $this->result = $this->FrmAkauntoHakko->fncRembanSelect();
            if (!$this->result['result']) {
                if (!stristr($this->result['data'], 'ORA-00054')) {
                    throw new \Exception($this->result['data']);
                } else {
                    $this->result['data'] = "他のユーザーが更新中です";
                    throw new \Exception($this->result['data']);
                }

            }

            if ($this->result['row'] == 0) {
                $this->result = $this->FrmAkauntoHakko->fncRembanSelect2();
                if (!$this->result['result']) {
                    if (!stristr($this->result['data'], 'ORA-00054')) {
                        throw new \Exception($this->result['data']);
                    } else {
                        $this->result['data'] = "他のユーザーが更新中です";
                        throw new \Exception($this->result['data']);
                    }

                }

                $result = $this->FrmAkauntoHakko->fncRembanInsert($this->result['data'][0]['REMBAN']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

            }

            $saibanym = $this->result['data'][0]['SAIBAN_YM'];
            $remban = $this->result['data'][0]['REMBAN'];

            $this->result = $this->FrmAkauntoHakko->FncKyakuCheck($txtCusNo);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            if ($this->result['row'] > 0) {
                throw new \Exception("対象のお客様は既にアカウント情報を発行しています。");
            }

            $KokyakuId = $saibanym . sprintf("%05s", $remban);

            $this->result = $this->FrmAkauntoHakko->fncKokyakuInsert($KokyakuId, $txtCusNo);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            $idcheck = true;
            while ($idcheck) {
                $idC = $this->getRandChar(6);
                $this->result = $this->FrmAkauntoHakko->fncIdCheck($idC);
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }
                if ($this->result['row'] == 0) {
                    $idcheck = false;
                }
            }
            $pwdC = $this->getRandChar(8);

            $this->result = $this->FrmAkauntoHakko->fncIssueInsert($idC, $pwdC, $KokyakuId);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            $this->result = $this->FrmAkauntoHakko->fncRembanUpdata();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            $this->FrmAkauntoHakko->Do_commit();

            $this->PDFoutput($txtCusNm, $idC, $pwdC);

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
            $this->FrmAkauntoHakko->Do_rollback();
        }
        if (isset($this->FrmAkauntoHakko->conn_orl)) {
            $this->FrmAkauntoHakko->Do_close();
            unset($this->FrmAkauntoHakko->conn_orl);
        }
        $this->fncReturn($this->result);
    }

    //'**********************************************************************
    //'処 理 名：PDF出力
    //'関 数 名：PDFoutput
    //'引 数 　：お客様No,ログインID,仮パスワード
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function PDFoutput($txtCusNm, $idC, $pwdC)
    {
        try {

            include_once "rpx_to_pdf.php";
            include_once 'rptKyakuPrint.inc';

            $tmp = array();

            $arr = array();
            $arr['CSRNM'] = $txtCusNm . " 様";
            $arr['ROGUIN_ID'] = $idC;
            $arr['KARI_PASUWADO'] = $pwdC;
            array_push($tmp, $arr);

            $rpx_file_names = array();
            $rpx_file_names['rptKyakuPrint'] = $data_fields_rptKyakuPrint;

            $tmp_data = array();
            $tmp_data['data'] = $tmp;
            $tmp_data['mode'] = "0";

            $datas = array();
            $datas['rptKyakuPrint'] = $tmp_data;

            $obj = new \rpx_to_pdf($rpx_file_names, $datas);
            $pdfPath = $obj->to_pdf();

            $this->result = array(
                'result' => true,
                'flag' => 'true',
                'msg' => 'true',
                'reports' => $pdfPath
            );
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }

    }

    //'**********************************************************************
    //'処 理 名：仮パスワード
    //'関 数 名：getRandChar
    //'引 数 　：$length
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function getRandChar($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];
        }

        return $str;
    }

}
