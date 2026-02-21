<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmSyoyoSikyuCalc;

class FrmSyoyoSikyuCalcController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;

    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncJKSYS');
        $this->loadComponent('ClsLogControl');
    }

    public function index()
    {
        $this->render('index', 'FrmSyoyoSikyuCalc_layout');
    }

    public function frmSyoyoSikyuCalcLoad()
    {
        $res = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $FrmSyoyoSikyuCalc = new FrmSyoyoSikyuCalc();

            //定期昇給月を取得
            $result = $FrmSyoyoSikyuCalc->fncGetTeikiSyokyuMonth();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $_strTeikiSyokyuMonth = '';
            if ($result['row'] > 0) {
                $_strTeikiSyokyuMonth = $this->ClsComFncJKSYS->FncNv($result['data'][0]['KAKI_BONUS_MONTH']);
            }
            //評価取込履歴データの取得を行う
            $result = $FrmSyoyoSikyuCalc->fncHyoukaTrkRireki($_strTeikiSyokyuMonth);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] == 0) {
                throw new \Exception('評価データが取り込まれていません。先に評価データ取込を行って下さい！');
            }

            $cmbYM = $result['data'][0]['JISSHI_YM'];
            $res['data']['TrkRireki'] = $result['data'];

            //評価取込履歴データ年月の取得を行う
            $result = $FrmSyoyoSikyuCalc->fncHyoukaTrkRirekiKikan($cmbYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $res['data']['rekiKikan'] = $result['data'];
            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    public function fncHyoukaTrkRirekiKikan()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $cmbYM = $_POST['data']['cmbYM'];
            $FrmSyoyoSikyuCalc = new FrmSyoyoSikyuCalc();
            $result = $FrmSyoyoSikyuCalc->fncHyoukaTrkRirekiKikan($cmbYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncInputChk($cmbYM, $tmpPath)
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            //フォルダーが存在するかどうかのﾁｪｯｸ
            if ($this->ClsComFncJKSYS->FncGetPath("JksysPathFrom") == "") {
                throw new \Exception('W0001');
            }
            if (($this->ClsComFncJKSYS->FncFileExists($tmpPath)) == False) {
                throw new \Exception("W0015");
            }
            if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
            }

            //存在チェック
            $FrmSyoyoSikyuCalc = new FrmSyoyoSikyuCalc();
            $result = $FrmSyoyoSikyuCalc->fncHyoukaRireki($cmbYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] == 0) {
                throw new \Exception("W9999");
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    public function fncCsvOutput()
    {
        $intState = 0;
        $lngOutCnt = 0;

        $myfile = null;

        $strYM = "";
        $strKikanS = "";
        $strKikanE = "";
        $filePath = "";

        try {
            $strYM = $_POST['data']['cmbYM'];
            $strKikanS = $_POST['data']['_hyuokaTaisyouKikanSD'];
            $strKikanE = $_POST['data']['_hyuokaTaisyouKikanED'];

            //出力先 必須チェック, フォルダ存在チェック
            $tmpPath1 = dirname(dirname(dirname(__FILE__)));
            $tmpPath2 = $this->ClsComFncJKSYS->FncGetPath("JksysPathFrom");
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;
            $result = $this->fncInputChk($strYM, $tmpPath);
            if (!$result['result']) {
                throw new \Exception($result['error']);
            }

            $intState = 9;

            //CSV出力
            $FrmSyoyoSikyuCalc = new FrmSyoyoSikyuCalc();
            $result = $FrmSyoyoSikyuCalc->fncCsvOutputData($strKikanS, $strKikanE, $strYM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['row'] == 0) {
                $intState = 1;
                throw new \Exception('I0001');
            }

            //出力先
            $filePath = $tmpPath . "賞与支給計算書.csv";
            if (file_exists($filePath) && !is_writable($filePath)) {
                throw new \Exception('ファイルのパーミッションはエラーが発生しました。');
            } elseif (!file_exists($filePath)) {
                $dir = @opendir(dirname($filePath));
                if ($dir === false) {
                    //如果目录打开失败，直接返回目录不可修改、不可写、不可读
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                if (@readdir($dir) == false) {
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                @closedir($dir);
            }

            $myfile = @fopen($filePath, "w");
            if (!$myfile) {
                throw new \Exception('ファイルのパーミッションはエラーが発生しました。');
            }
            $txt_title = mb_convert_encoding("社員番号,社員名,職種コード,職種名,資格コード,資格名,役職コード,役職名,雇用区分コード,雇用区分名,基本給,最終評価－評価\r\n", 'SJIS', 'UTF-8');
            fwrite($myfile, $txt_title);
            foreach ((array) $result["data"] as $value) {
                $txt = "";
                $txt .= mb_convert_encoding(implode(",", $value), 'SJIS', 'UTF-8');
                $txt .= "\r\n";
                @fwrite($myfile, $txt);
            }
            @fclose($myfile);

            $lngOutCnt = $result["row"];
            $intState = 1;

            $result['result'] = TRUE;
            $result['data'] = "files/JKSYS/" . $filePath;
        } catch (\Exception $e) {
            //エラー時出力ファイル削除
            if ($filePath != "" && $this->ClsComFncJKSYS->FncFileExists($filePath)) {
                if ($myfile != null) {
                    @fclose($myfile);
                }
                @unlink($filePath);
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        if ($myfile != null) {
            unset($myfile);
        }

        try {
            if ($intState <> 0) {
                $res = $this->ClsLogControl->fncLogEntryJksys("FrmSyoyoSikyuClac_Csv", $intState, $lngOutCnt, $strYM, $strKikanS, $strKikanE, $filePath);
                if (!$res['result']) {
                    throw new \Exception($res['Msg']);
                }
            }
        } catch (\Exception $e1) {
            $result['result'] = FALSE;
            $result['error'] = $e1->getMessage();
        }

        $this->fncReturn($result);
    }

}
