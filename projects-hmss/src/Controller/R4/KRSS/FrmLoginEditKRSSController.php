<?php
/**
 * 説明：
 *
 *
 * @author li
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20160527			  #2529						依頼							  Yinhuaiyu
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmLoginEditKRSS;
//*******************************************
// * sample controller
//*******************************************
class FrmLoginEditKRSSController extends AppController
{
    public $autoLayout = TRUE;
    public $FrmLoginEditKRSS;
    var $blnTranFlg = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'FrmLoginEditKRSS_layout');
    }

    //データリストの値を設定
    public function fncLoadDeal()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            }

            $this->FrmLoginEditKRSS = new FrmLoginEditKRSS();
            $result = $this->FrmLoginEditKRSS->fncHKEIRICTL();

            //コントロールマスタ存在ﾁｪｯｸ
            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else
                if (count((array) $result['data']) == 0) {
                    //コントロールマスタが存在していない場合
                    throw new \Exception("コントロールマスタが存在しません！");
                }

            //コンボボックスに当月年月を設定
            $strTougetu = $this->ClsComFnc->FncNv($result['data'][0]["TOUGETU"]);

            $result = $this->FrmLoginEditKRSS->getComboxListTable();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $arrSTYLEID = $result['data'];
            $result = $this->FrmLoginEditKRSS->getPatternID($postData["UserID"], $strTougetu, $postData['cboSysKB']);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $arrUserInfo = $result['data'];
            // パターンＩＤコンボボックスの項目に設定する
            $result = $this->FrmLoginEditKRSS->SetPatternCombox($arrUserInfo[0]["STYLE_ID"], $postData['cboSysKB']);
            $pattern = $result['data'];
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            ;

            $result['strTougetu'] = $strTougetu;
            $result['arrSTYLEID'] = array();
            $result['arrSTYLEID'] = $arrSTYLEID;
            $result['arrUserInfo'] = array();
            $result['arrUserInfo'] = $arrUserInfo;
            $result['arrPattern'] = array();
            $result['arrPattern'] = $pattern;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function setPatternCombox()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );
        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            }

            $this->FrmLoginEditKRSS = new FrmLoginEditKRSS();
            // パターンＩＤコンボボックスの項目に設定する
            $result = $this->FrmLoginEditKRSS->SetPatternCombox($postData["UcComboBox1"], $postData['cboSysKB']);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncDeleteUpdataMst()
    {
        $postData = "";
        $result = array(
            "result" => FALSE,
            "data" => "ErrorInfo"
        );

        register_shutdown_function(
            array(
                $this,
                "finally"
            )
        );

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            }
            ;

            if (!$postData == "") {
                $this->FrmLoginEditKRSS = new FrmLoginEditKRSS();
                $result = $this->FrmLoginEditKRSS->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //ﾄﾗﾝｻﾞｸｼｮﾝ開始
                $this->FrmLoginEditKRSS->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->blnTranFlg = TRUE;

                //ログインﾏｽﾀを削除する
                $result = $this->FrmLoginEditKRSS->fncDelMst($postData['USER_ID'], $postData['SYS_KB']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $UPDCLTNM = $this->request->clientIp();
                    //ログインﾏｽﾀに追加するためのSQLを発行
                    $result = $this->FrmLoginEditKRSS->fncUpdMst($postData['USER_ID'], $postData['PASSWORD'], $postData['REC_CRE_DT'], $postData['STYLE_ID'], $postData['PATTERN_ID'], $UPDCLTNM, $postData['SYS_KB']);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                    $result['result'] = TRUE;
                    $result['data'] = "";
                }
                ;
                //コミット
                $this->FrmLoginEditKRSS->Do_commit();

                //ﾄﾗﾝｻﾞｸｼｮﾝ終了
                $this->blnTranFlg = FALSE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }
    public function finally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmLoginEditKRSS->Do_rollback();
        }

        //DB接続解除
        $this->FrmLoginEditKRSS->Do_close();
    }
}
