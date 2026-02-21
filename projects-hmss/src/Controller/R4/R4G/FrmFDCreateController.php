<?php
namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmFDCreate;
use Cake\Core\Exception\Exception;

//*******************************************
// * sample controller
//*******************************************
class FrmFDCreateController extends AppController
{
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $blnErr = FALSE;
    public $strFDDataNm = "";
    public $strFDHedNm = "";
    public $strTouroku;
    public $objSw;
    public $objSwHed;
    public $blnTran;
    public $lngOutCnt = 0;
    public $intState = 0;
    public $DtFDCreate = array();
    public $FrmFDCreate;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsLogControl');
        $this->loadComponent('ClsComFnc');
    }

    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定
        $layout = 'FrmFDCreate_layout';
        $this->render('/R4/R4G/FrmFDCreate/index', $layout);
    }

    public function fncFrmFDCreate()
    {
        $result = "";
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            try {

                // 呼出クラスのインスタンス作成
                $postData = $_POST['request'];

                if (isset($postData) == true) {
                    $this->FrmFDCreate = new FrmFDCreate();

                    $result = $this->FrmFDCreate->fncFrmFDCreate($postData);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                    $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
                    $page = $tmpJqgridShow['page'];
                    $totalPage = $tmpJqgridShow['totalPage'];
                    $tmpCount = $tmpJqgridShow['count'];
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                    $result = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);

                    unset($_POST['request']);

                    $_POST['request'] = null;
                }
            } catch (\Exception $ex) {
                $result['result'] = FALSE;
                $result['data'] = $ex->getMessage();
                $this->FrmFDCreate->Do_rollback();
            }
            $this->fncReturn($result);

        }

    }

    // *******************************
    // '処 理 名：CSV出力ﾊﾟｽを取得する
    // '関 数 名：fncGetPath
    // '引    数：無し
    // '戻 り 値：CSV出力ﾊﾟｽ
    // '処理説明：CSV出力ﾊﾟｽを取得する
    // '******************************
    public function fncGetPath()
    {
        $strPathName = 'FDCreate';
        $strPath = dirname(dirname(dirname(dirname(__FILE__))));
        $returnStrPathName = $strPath . "/" . $this->ClsComFnc->FncGetPath($strPathName);


        return $returnStrPathName;
    }

    public function fncFileExistsPath($getPath)
    {
        $strPathName = $getPath;
        $flag = $this->ClsComFnc->FncFileExists($strPathName);
        return $flag;
    }

    //  *******************************
    //  '処 理 名：出力する
    //  '関 数 名：fncOutput1
    //  '引    数：無し
    //  '戻 り 値：true:正常　false:異常
    //  '処理説明：振替データをCSV出力する
    //  '******************************
    public function fncOutput1($strFDDataPath, $strFDHedPath, $FDChumnno)
    {
        $conn = "";
        $result = "";
        $strOut = "";
        $lngKensu = 0;
        $returnValue = array();
        $this->intState = 9;
        $this->blnErr = true;
        $this->lngOutCnt = 0;
        $this->blnTran = false;
        $this->strFDDataNm = $strFDDataPath;
        $this->strFDHedNm = $strFDHedPath;

        try {
            //finally
            register_shutdown_function(
                array(
                    $this,
                    "fncOutput1_finally"
                )
            );
            //インスタンス作成
            $this->objSw = fopen($this->strFDDataNm, "w");
            $this->objSwHed = fopen($this->strFDHedNm, "w");

            $this->FrmFDCreate = new FrmFDCreate();

            //FD作成データを抽出する
            $result = $this->FrmFDCreate->Do_conn();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $this->FrmFDCreate->Do_transaction();
            //行分繰り返す
            foreach ($FDChumnno as $key => $value) {
                // 作成ﾌﾗｸﾞが立っているもののみ作成
                $tmpObjDs = $this->FrmFDCreate->fncCsvSelect($value);
                $result = $tmpObjDs;
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //FD作成データを抽出する
                $objDs = $tmpObjDs['data'];
                //登録されている
                if (count((array) $objDs) > 0) {
                    //初期化
                    $k = 0;
                    $strOut = "";
                    $tmpArr = array();
                    foreach ((array) $objDs[0] as $keya => $valuea) {
                        array_push($tmpArr, $keya);
                    }
                    for ($j = 0; $j <= count($tmpArr) - 4; $j++) {
                        $strOut .= $objDs[0][$tmpArr[$j]];
                        $strOut .= "/";
                    }
                    $tt = count($tmpArr) - 3;
                    $strOut .= $objDs[0][$tmpArr[$tt]];
                    //$strOut .= "\n";
                    $strOut .= "\r\n";

                    //ファイル出力
                    $strOut = mb_convert_encoding($strOut, "SJIS-WIN", "UTF-8");
                    fwrite($this->objSw, $strOut);
                } else {
                    $this->intState = 1;
                    return FALSE;
                }

                //印刷用データセットに明細部分を挿入
                $this->DtFDCreate[$lngKensu]['RENBAN'] = $lngKensu + 1;
                $this->DtFDCreate[$lngKensu]['GYOUMU_SYUBETU'] = $objDs[0]['GYOUMU_SYUBETU'];
                $this->DtFDCreate[$lngKensu]['SRY_BAN'] = $this->ClsComFnc->FncNv($objDs[0]['SRY_BAN_MOJI']) . $this->ClsComFnc->FncNv($objDs[0]['SRY_BAN_BUNRUI']) . $this->ClsComFnc->FncNv($objDs[0]['SRY_BAN_KANA']) . $this->ClsComFnc->FncNv($objDs[0]['SRY_BAN_SITEI']);
                $this->DtFDCreate[$lngKensu]['SYADAI_NO'] = $objDs[0]['PRINT_SYADAI_NO'];
                $this->DtFDCreate[$lngKensu]['SHIYOU_NM'] = $objDs[0]['SHIYOU_NM'];
                $this->DtFDCreate[$lngKensu]['SYOYU_NM'] = $objDs[0]['SYOYU_NM'];
                //使用の本拠の位置が使用者住所と同じ場合は、"使用者住所に同じ"と出力するように変更
                if ($this->ClsComFnc->FncNv($objDs[0]['HONKYO_ADDR_SIYO']) == '1') {
                    $this->DtFDCreate[$lngKensu]['HONKYO_ADDR_NM'] = '使用者住所に同じ';
                } else {
                    $this->DtFDCreate[$lngKensu]['HONKYO_ADDR_NM'] = $this->ClsComFnc->FncNv($objDs[0]['HONKYO_ADDR_NM']);
                }
                //帳票に使用者住所を追加
                $this->DtFDCreate[$lngKensu]['SINSEI_SIYO_ADDR'] = $this->ClsComFnc->FncNv($objDs[0]['SINSEI_SIYO_ADDR']);
                // 此处写的与vb代码不同
                $this->DtFDCreate[$lngKensu]['CHUMON_NO'] = $value;
                //件数カウント
                $lngKensu += 1;
                $this->lngOutCnt += 1;
                $objDs = null;
            }
            //更新にチェックが1件も入っていない場合
            if ($lngKensu == 0) {
                $this->intState = 1;
                $returnArr = array(
                    "TF" => FALSE,
                    "msg" => "W9999",
                    "msgContent" => "更新対象を選択してください"
                );
                return $returnArr;
            }

            //EOFコードを出力
            if ($this->objSw != FALSE) {
                //20151218 ADD START
                //fwrite($this -> objSw, "\x1a");
                //fwrite($this -> objSw, "\r\n");
                fwrite($this->objSw, mb_convert_encoding("\x1a", "SJIS-WIN", "UTF-8"));
                fwrite($this->objSw, mb_convert_encoding("\r\n", "SJIS-WIN", "UTF-8"));
                //20151218 ADD END
                fclose($this->objSw);
            }

            //インスタンス作成
            //ﾌｧｲﾙ出力
            //20151218 UPDATE START
            ////20151217 UPDATE START
            ////fwrite($this -> objSwHed, "軽自動車検査協会\n");
            ////fwrite($this -> objSwHed, $this -> strTouroku . "\n");
            ////fwrite($this -> objSwHed, "株式会社（GD）（DZM）\n");
            ////fwrite($this -> objSwHed, "（GD）県（GD）市中区幟町１３－１４\n");
            ////fwrite($this -> objSwHed, $lngKensu . "\n");

            //fwrite($this -> objSwHed, "軽自動車検査協会\r\n");
            //fwrite($this -> objSwHed, $this -> strTouroku . "\r\n");
            //fwrite($this -> objSwHed, "株式会社（GD）（DZM）\r\n");
            //fwrite($this -> objSwHed, "（GD）県（GD）市中区幟町１３－１４\r\n");
            //fwrite($this -> objSwHed, $lngKensu . "\r\n");
            ////20151217 UPDATE END

            $wstr = mb_convert_encoding("軽自動車検査協会\r\n", "SJIS-WIN", "UTF-8");
            fwrite($this->objSwHed, $wstr);

            $wstr = $this->strTouroku . "\r\n";
            $wstr = mb_convert_encoding($wstr, "SJIS-WIN", "UTF-8");
            fwrite($this->objSwHed, $wstr);

            $wstr = mb_convert_encoding("株式会社（GD）（DZM）\r\n", "SJIS-WIN", "UTF-8");
            fwrite($this->objSwHed, $wstr);

            $wstr = mb_convert_encoding("（GD）県（GD）市中区幟町１３－１４\r\n", "SJIS-WIN", "UTF-8");
            fwrite($this->objSwHed, $wstr);

            $wstr = mb_convert_encoding($lngKensu . "\r\n", "SJIS-WIN", "UTF-8");
            fwrite($this->objSwHed, $wstr);

            //EOFコードを出力
            //fwrite($this -> objSwHed, "\x1a");
            //fwrite($this -> objSwHed, "\r\n");
            fwrite($this->objSwHed, mb_convert_encoding("\x1a", "SJIS-WIN", "UTF-8"));
            fwrite($this->objSwHed, mb_convert_encoding("\r\n", "SJIS-WIN", "UTF-8"));
            //20151218 UPDATE END

            //'ストリーム閉じる
            if ($this->objSwHed == true) {
                fclose($this->objSwHed);
            }

            //印刷用データｾｯﾄにヘッダーを挿入
            for ($j = 0; $j < $lngKensu; $j++) {
                $this->DtFDCreate[$j]['TOU_Y_DT'] = $this->strTouroku . '';
                $this->DtFDCreate[$j]['JUKEN_MEI'] = "株式会社（GD）（DZM）";
                $this->DtFDCreate[$j]['JUKEN_ADDRESS'] = "（GD）県（GD）市中区幟町１３－１４";
                $this->DtFDCreate[$j]['KENSU'] = $lngKensu . "件";
            }

            $this->blnErr = false;
            $this->intState = 1;
            //トランザクション処理開始
            //'トランザクションﾌﾗｸﾞ
            $this->blnTran = True;
            //FD作成済ﾌﾗｸﾞを更新する
            foreach ($FDChumnno as $key => $value) {
                //UPDATE文実行
                $resultQueryUpdSql = $this->FrmFDCreate->fncUpdCreateFlg($value);
                $returnResult = $resultQueryUpdSql['result'];
                if ($returnResult != "true") {
                    return FALSE;
                }
            }
            $this->FrmFDCreate->Do_commit();

            //コミット
            //トランザクションﾌﾗｸﾞ
            $this->blnTran = False;
            //---帳票印刷処理---
            $reportKeys = array(
                'INDEX',
                'TYPE',
                'SYADAI_NO',
                'SHIYOU_NM',
                'SYOYU_NM',
                'SINSEI_SIYO_ADDR',
                'HONKYO_ADDR_NM'
            );
            $reportHeaderKeys = array(
                "TOU_Y_DT",
                "JUKEN_NM",
                "JUKEN_ADDRESS",
                "KENSU"
            );

            //'プレビュー表示
            $path_rpxTopdf = dirname(__DIR__);
            include_once $path_rpxTopdf . '/Component/tcpdf/rpx_to_pdf.php';

            $rpx_file_names = array();
            $tmp_data = array();
            $tmp = array();
            $data = array(
                "TOU_Y_DT" => "",
                "JUKEN_MEI" => "",
                "JUKEN_ADDRESS" => "",
                "KENSU" => "",
                "RENBAN" => "",
                "SYADAI_NO" => "",
                "SHIYOU_NM" => "",
                "SYOYU_NM" => "",
                "HONKYO_ADDR_NM" => "",
                "SINSEI_SIYO_ADDR" => ""
            );
            array_push($tmp_data, $this->DtFDCreate);
            $tmp["data"] = $tmp_data;
            $tmp["mode"] = "3";
            $datas["rpfFDSinseiList"] = $tmp;
            $rpx_file_names["rpfFDSinseiList"] = $data;
            $obj = new \rpx_to_pdf($rpx_file_names, $datas);
            $pdfPath = $obj->to_pdf();

            //スプレッドを表示 & 正常終了
            $tArr = array(
                "TF" => "TRUE",
                "report_path" => $pdfPath
            );

            return $tArr;
        } catch (\Exception $e) {
            //エラー時出力ファイル削除
            $flag = $this->ClsComFnc->FncFileExists($this->strFDDataNm);

            if ($flag == true) {
                if ($this->objSw == TRUE) {
                    fclose($this->objSw);
                }
                $this->blnErr = FALSE;
                $this->lngOutCnt = 0;
                $this->intState = 9;
            }

            $flag = $this->ClsComFnc->FncFileExists($this->strFDHedNm);
            if ($flag == true) {
                if ($this->objSwHed == TRUE) {
                    fclose($this->objSwHed);
                }
                $this->blnErr = FALSE;
                $this->lngOutCnt = 0;
                $this->intState = 9;
            }

            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $this->FrmFDCreate->Do_rollback();
        }
        $result = $this->FrmFDCreate->Do_close();
    }

    //  *******************************
    //  '処 理 名：CSV出力
    //  '関 数 名：fncCmdCsvOutClick
    //  '引    数：無し
    //  '戻 り 値：true:正常　false:異常
    //  '処理説明：振替データをCSV出力する
    //  '******************************
    public function fncCmdCsvOutClick()
    {
        try {
            //finally
            register_shutdown_function(
                array(
                    $this,
                    "fncCmdCsvOut_Finally"
                )
            );
            if (isset($_POST['data']['FDChumnno']) == false) {
                return;
            } else {
                if ($_POST['data']['FDChumnno'] == "") {
                    return;
                }
            }
            $FDChumnno = $_POST['data']['FDChumnno'];
            $strFDDataPath = "";
            $strFDHedPath = "";
            $this->strTouroku = $_POST['data']["strTouroku"];
            //CSV出力ﾊﾟｽを取得する
            $getPath = $this->fncGetPath();

            //出力先ﾁｪｯｸ
            if ($getPath == "" || $getPath == null) {
                $returnArr = array(
                    "TF" => FALSE,
                    "msg" => "W0015"
                );
                $this->fncReturn($returnArr);
                return;
            } else {
                $returnTf = $this->fncFileExistsPath($getPath);

                if ($returnTf != 1) {
                    $returnArr = array(
                        "TF" => FALSE,
                        "msg" => "W0015"
                    );
                    $this->fncReturn($returnArr);
                    return;
                } else {
                    //出力先を表示する
                    $strFDDataPath = $getPath . "KOCRDATA.txt";
                    $strFDHedPath = $getPath . "KCOMMON.txt";

                    if (!is_writable($getPath)) {
                        $returnArr = array(
                            "TF" => FALSE,
                            "msg" => "E9999",
                            "msgContent" => 'Access to the path "' . $strFDDataPath . '" is denied'
                        );
                        $this->fncReturn($returnArr);
                        return;
                    } else {
                        if (file_exists($strFDDataPath)) {
                            if (!is_writable($strFDDataPath)) {
                                $returnArr = array(
                                    "TF" => FALSE,
                                    "msg" => "E9999",
                                    "msgContent" => 'Access to the path "' . $strFDDataPath . '" is denied'
                                );
                                $this->fncReturn($returnArr);
                                return;
                            }
                        }

                        if (file_exists($strFDHedPath)) {
                            if (!is_writable($strFDHedPath)) {
                                $returnArr = array(
                                    "TF" => FALSE,
                                    "msg" => "E9999",
                                    "msgContent" => 'Access to the path "' . $strFDHedPath . '" is denied'
                                );
                                $this->fncReturn($returnArr);
                                return;
                            }
                        }
                    }


                    //   if (clsComFnc.GetByteCount(strFDDataPath) > 100)
                    //   {
                    //   clsComFnc.FncMsgBox("W9999", "ファイル名が指定できる桁数をオーバーしています。半角英数字で70文字、全角文字で35文字が目安です。")
                    //   $returnArr = array(
                    //   "TF" => FALSE,
                    //   "msg" => "W9999"
                    //   "msgContent"=>"ファイル名が指定できる桁数をオーバーしています。半角英数字で70文字、全角文字で35文字が目安です。"
                    //   );
                    //   $this -> set('result', $returnArr);
                    //   $this -> render(' fncfrmfdcreate_check');
                    //   return;
                    //   };
                    //   if (clsComFnc.GetByteCount(strFDHedPath) > 100)
                    //   {
                    //   $returnArr = array(
                    //   "TF" => FALSE,
                    //   "msg" => "W9999"
                    //   "msgContent"=>"ファイル名が指定できる桁数をオーバーしています。半角英数字で70文字、全角文字で35文字が目安です。"
                    //   );
                    //   $this -> set('result', $returnArr);
                    //   $this -> render(' fncfrmfdcreate_check');
                    //   return;
                    //   };
                }
            }

            //出力処理
            $returnFlg = $this->fncOutput1($strFDDataPath, $strFDHedPath, $FDChumnno);

            if ($returnFlg == FALSE) {
                $returnArr = array(
                    "TF" => FALSE,
                    "msg" => ""
                );
                $this->fncReturn($returnFlg);
                return;
            } else {
                //正常終了のﾒｯｾｰｼﾞ
                if ($returnFlg["TF"] == "TRUE") {
                    $returnArr = array(
                        "TF" => TRUE,
                        "msg" => "I0011",
                        "report_path" => $returnFlg["report_path"]
                    );
                    $this->fncReturn($returnArr);
                    return;
                } else {
                    //error
                    $this->fncReturn($returnFlg);
                    return;
                }

            }
        } catch (\Exception $e) {
            $returnArr = array(
                "TF" => TRUE,
                "msg" => "E9999",
                "msgContent" => $e
            );

            $this->fncReturn($returnArr);
            return;
        }

    }

    public function fncCmdCsvOut_Finally()
    {

        $strJyoken = $this->ClsComFnc->initializeArray(20);
        if ($this->intState != 0) {
            $cnt = 0;
            $intRecCnt = 1;
            if (count($this->DtFDCreate) == 0) {
                $this->ClsLogControl->fncLogEntry("frmFDCreate", $this->intState, $this->lngOutCnt);
            } else {
                for ($i = 0; $i < count($this->DtFDCreate); $i++) {
                    if ($i == 0) {
                        $strJyoken[$cnt] = $this->strFDDataNm;
                        $cnt += 1;
                        $strJyoken[$cnt] = $this->strFDHedNm;
                        $cnt += 1;
                    }
                    $strJyoken[$cnt] = $this->DtFDCreate[$i]["CHUMON_NO"];
                    $cnt += 1;
                    if ($cnt > 19 || $i == count($this->DtFDCreate) - 1) {
                        $this->ClsLogControl->fncLogEntry("frmFDCreate", $this->intState, $this->lngOutCnt, $strJyoken[0], $strJyoken[1], $strJyoken[2], $strJyoken[3], $strJyoken[4], $strJyoken[5], $strJyoken[6], $strJyoken[7], $strJyoken[8], $strJyoken[9], $strJyoken[10], $strJyoken[11], $strJyoken[12], $strJyoken[13], $strJyoken[14], $strJyoken[15], $strJyoken[16], $strJyoken[17], $strJyoken[18], $strJyoken[19], $intRecCnt);
                        $strJyoken = $this->ClsComFnc->initializeArray(20);
                        $cnt = 0;
                        $intRecCnt += 1;
                    }

                }
            }

        }

    }

    public function fncOutput1_finally()
    {
        if ($this->blnErr) {

            $flag = $this->ClsComFnc->FncFileExists($this->strFDDataNm);

            if ($flag == true) {
                if ($this->objSw == TRUE) {
                    fclose($this->objSw);
                }
                $this->lngOutCnt = 0;
            }

            $flag = $this->ClsComFnc->FncFileExists($this->strFDHedNm);
            if ($flag == true) {
                if ($this->objSwHed == TRUE) {
                    fclose($this->objSwHed);
                }

                $this->lngOutCnt = 0;
                $this->blnErr = FALSE;
            }
        }
        // if ($this->blnTran) {
        // 	//ロールバック
        // 	// clsComDB.Sub_Rollback()
        // }
        // if ($this->objSw == TRUE) {
        // 	//fclose($this -> objSw);

        // }
        // if ($this->objSwHed == true) {
        // 	//fclose($this -> objSwHed);
        // }
    }

}
