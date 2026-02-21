<?php
namespace App\Model\Component;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：ClsComDoRefresh
// * 処理説明：共通関数
//*************************************
class ClsComDoRefresh
{
    protected $PPRM_conn_orl = array();
    protected $PPRM_Pra_info = "";
    protected $PPRM_Exe_stid = "";
    protected $xml = "";
    protected $PPRMLogPath = "";
    protected $PPRM_Result = array();

    //*************************************
    // * 公開メソッド
    //*************************************
    public function DoRefresh($ArrSql)
    {
        register_shutdown_function(
            array(
                $this,
                "finally"
            )
        );

        $result = FALSE;
        $resultdata = "";

        try {
            $this->Fnc_PPRM_Connect();
            if (!$this->PPRM_Result['conn_sta']) {
                throw new \Exception($this->PPRM_Result['conn_orl']);
            }
            foreach ($ArrSql as $Sql) {
                $this->PPRM_Pra_info = oci_parse($this->PPRM_conn_orl, $Sql);
                if (!$this->PPRM_Pra_info) {
                    $e = oci_error($this->PPRM_conn_orl);
                    throw new \Exception($e['message']);
                }
                $this->PPRM_Exe_stid = oci_execute($this->PPRM_Pra_info);
                if (!$this->PPRM_Exe_stid) {
                    $e = oci_error($this->PPRM_Pra_info);
                    throw new \Exception($e['message']);
                }
            }

            $result = TRUE;
        } catch (\Exception $e) {
            $objSw = fopen($this->PPRMLogPath, "a+");
            fwrite($objSw, $e->getMessage() . "\r\n");
            fclose($objSw);

            $result = FALSE;
            $resultdata = $e->getMessage();
        }

        return array(
            "result" => $result,
            "data" => $resultdata
        );
    }

    public function Fnc_PPRM_Connect()
    {
        // パス取得
        $strPath = dirname(__FILE__);
        $filename = $strPath . '/HMDB.xml';

        // 値取得
        $this->xml = simplexml_load_file($filename);
        // XMLの取得
        $result = (array) $this->xml;
        $PPRMUserId = $result['ppruserid'];
        $PPRMPassword = $result['pprpassword'];
        $PPRMServer = $result['pprserver'];
        $PPRMCharacter = 'utf8';
        $PPRMSnapShotPath = $result['SnapShotPath'];
        $this->PPRMLogPath = dirname(dirname($strPath)) . "/" . $PPRMSnapShotPath . "SNAPSHOTREFRESH.log";
        try {
            $this->PPRM_conn_orl = oci_connect($PPRMUserId, $PPRMPassword, $PPRMServer, $PPRMCharacter);
            if (!$this->PPRM_conn_orl) {
                $e = oci_error();
                throw new \Exception(($e['message']));
            }
            $this->PPRM_Result['conn_sta'] = TRUE;
            $this->PPRM_Result['conn_orl'] = $this->PPRM_conn_orl;
        } catch (\Exception $e) {
            $this->PPRM_Result['conn_sta'] = FALSE;
            $this->PPRM_Result['conn_orl'] = $e->getMessage();
        }
    }

    /*************************************
     * 処理名	：関数終了時処理
     * 関数名	：finally
     * 引数		：無し
     * 戻り値		：無し
     * 処理説明	：register_shutdown_functionで呼び出された関数の終了後に実行する処理
     *************************************/
    function finally()
    {
        if (isset($this->PPRM_Pra_info) && isset($this->PPRM_Pra_info['Pra_sta'])) {
            if ($this->PPRM_Pra_info['Pra_sta'] != FALSE) {
                oci_free_statement($this->PPRM_Pra_info['Pra_info']);
            }
        }

        if (isset($this->PPRM_conn_orl)) {
            if ($this->PPRM_conn_orl != FALSE) {
                oci_close($this->PPRM_conn_orl);
            }

        }
        if (isset($this->PPRM_Pra_info)) {
            unset($this->PPRM_Pra_info);
        }
        if (isset($this->PPRM_conn_orl)) {
            unset($this->PPRM_conn_orl);
        }
        if (isset($this->PPRMLogPath)) {
            unset($this->PPRMLogPath);
        }
        if (isset($this->PPRM_Exe_stid)) {
            unset($this->PPRM_Exe_stid);
        }
        if (isset($this->PPRM_Result)) {
            unset($this->PPRM_Result);
        }
        if (isset($this->xml)) {
            unset($this->xml);
        }
    }

}
