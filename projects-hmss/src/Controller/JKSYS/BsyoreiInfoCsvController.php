<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\BsyoreiInfoCsv;

//*******************************************
// * sample controller
//*******************************************
class BsyoreiInfoCsvController extends AppController
{
    public $prvArgNM;
    //奨励金区分
    public $intSyourei_Kbn;
    //対象年月
    public $strTaisyou_YM;
    //奨励金処理マスタ
    public $strPass;
    public $bsyoreiInfoCsv;
    public $ClsLogControl;
    public $ClsComFncJKSYS;
    public function __construct() {
    }
    //CSV出力
    public function Fnc_CSVOut()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        $lngOutCnt = 0;
        $csvPath = '';
        try {
            $this->bsyoreiInfoCsv = new BsyoreiInfoCsv();
            //ログ管理のため
            $intState = 9;
            //対象データ取得
            if ($this->intSyourei_Kbn == 1) {
                //奨励金区分が業績の場合
                //データ取得(業績奨励手当支給データ)
                $table_name = "JKGYOSEKISYOREI";
                $result = $this->bsyoreiInfoCsv->fncGetGYOSEKISYOUREI($this->strTaisyou_YM);
            } else {
                $table_name = "JKTENCHOSYOREISYAIN";
                //奨励金区分が店長の場合
                $result = $this->bsyoreiInfoCsv->fncGetTENCHOSYOUREI($this->strTaisyou_YM);
            }
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //データが存在する場合
            if (count((array) $result['data']) > 0) {
                //ログ管理のため
                $lngOutCnt = count((array) $result['data']);

                $result = $this->outputCSV($result['data'], $this->intSyourei_Kbn, $this->strPass);
                if (!$result['result']) {
                    throw new \Exception($result['error']);
                }
                if (isset($result['csvPath'])) {
                    $csvPath = $result['csvPath'];
                }
            } else {
                throw new \Exception($table_name . 'データが存在しないです');
            }
            //正常終了
            $intState = 1;

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        //ログ管理テーブルに登録
        try {
            if (isset($intState)) {
                $res = $this->ClsLogControl->fncLogEntryJksys("BsyoreiInfoCsv", $intState, $lngOutCnt, $this->strTaisyou_YM, $csvPath);
                if (!$res['result']) {
                    throw new \Exception($res['Msg']);
                }
            }
        } catch (\Exception $e1) {
            $result['logResult'] = FALSE;
            $result['logError'] = $e1->getMessage();
        }
        if (!$result['result']) {
            //============ ｴﾗｰ処理 ============
            $result['result'] = FALSE;
            $res = $this->ClsLogControl->procWriteOutLog($this->prvArgNM, 0);
            if (!$res['result']) {
                $result['error'] = $res['data'];
                return $result;
            }
            $res = $this->ClsLogControl->procWriteOutLog($this->prvArgNM, 9, $result['error']);
            if (!$res['result']) {
                $result['error'] = $res['data'];
                return $result;
            }
            $res = $this->ClsLogControl->procWriteOutLog($this->prvArgNM, 3);
            if (!$res['result']) {
                $result['error'] = $res['data'];
                return $result;
            }
            $result['error'] = "CSV出力エラーが発生しました。ログを確認してください。";
        }

        return $result;
    }

    public function outputCSV($dt, $kbn, $strPass)
    {
        $result = array(
            'result' => false,
            'error' => ''
        );

        try {
            //ファイル名取得
            if ($kbn == "1") {
                //奨励金区分が業績の場合
                $csvPath = $strPass . '業績奨励手当.csv';
            } elseif ($kbn == "2") {
                //奨励金区分が店長の場合
                $csvPath = $strPass . '店長奨励手当.csv';
            }
            $result['csvPath'] = $csvPath;
            if (!file_exists($strPass)) {
                $dir = @opendir($strPass);
                if ($dir === false) {
                    //如果目录打开失败，直接返回目录不可修改、不可写、不可读
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                if (@readdir($dir) == false) {
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                @closedir($dir);
            }
            if (file_exists($csvPath)) {
                if (!is_writable($csvPath)) {
                    throw new \Exception('ファイルのパーミッションはエラーが発生しました。');
                }
                @unlink($csvPath);
            }
            //ファイル出力
            $myfile = fopen($csvPath, "w");
            foreach ($dt as $value) {
                $txt = "";
                $txt .= implode(",", $value);
                $txt = mb_convert_encoding($txt, 'SJIS', 'UTF-8');
                $txt .= ",";
                $txt .= "\r\n";

                fwrite($myfile, $txt);
            }
            fclose($myfile);

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            //エラー時出力ファイル削除
            if (file_exists($csvPath)) {
                @unlink($csvPath);
            }
        }
        return $result;
    }

}
