<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmHyokaNewDataUpd extends ClsComDb
{
    protected $R4_conn_orl = array();
    protected $R4_Pra_info = "";
    protected $R4_Exe_stid = "";
    protected $xml = "";
    protected $R4LogPath = "";
    protected $R4_Result = array();

    public function fncUpdData()
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
            $this->Fnc_R4_Connect();
            if (!$this->R4_Result['conn_sta']) {
                throw new \Exception($this->R4_Result['conn_orl']);
            }
            //ファンクション
            $Sql = "begin :ret_msg := JINJITOR4_CREATEVIEW(); end;";
            $this->R4_Pra_info = oci_parse($this->R4_conn_orl, $Sql);
            if (!$this->R4_Pra_info) {
                $e = oci_error($this->R4_conn_orl);
                throw new \Exception($e['message']);
            }
            oci_bind_by_name($this->R4_Pra_info, ':ret_msg', $ret_msg, 40);

            $this->R4_Exe_stid = oci_execute($this->R4_Pra_info);
            if (!$this->R4_Exe_stid) {
                $e = oci_error($this->R4_Pra_info);
                throw new \Exception($e['message']);
            }
            $resultdata = $ret_msg;

            $result = TRUE;
        } catch (\Exception $e) {
            $result = FALSE;
            $resultdata = $e->getMessage();
        }

        return array(
            "result" => $result,
            "data" => $resultdata
        );
    }

    public function Fnc_R4_Connect()
    {
        // パス取得
        $strPath = dirname(dirname(__FILE__));
        $objReader = $strPath . '/Component/HMDB.xml';

        // 値取得
        $this->xml = simplexml_load_file($objReader);
        // XMLから値取得
        $result = (array) $this->xml;
        $strUserID = $result['userid'];
        $strPassword = $result['password'];
        $strServer = $result['server'];
        $R4Character = 'utf8';
        try {
            $this->R4_conn_orl = oci_connect($strUserID, $strPassword, $strServer, $R4Character);
            if (!$this->R4_conn_orl) {
                $e = oci_error();
                throw new \Exception(($e['message']));
            }
            $this->R4_Result['conn_sta'] = TRUE;
            $this->R4_Result['conn_orl'] = $this->R4_conn_orl;
        } catch (\Exception $e) {
            $this->R4_Result['conn_sta'] = FALSE;
            $this->R4_Result['conn_orl'] = $e->getMessage();
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
        if (isset($this->R4_Pra_info)) {
            if ($this->R4_Pra_info['Pra_sta'] != FALSE) {
                oci_free_statement($this->R4_Pra_info['Pra_info']);
            }
        }

        if (isset($this->R4_conn_orl)) {
            if ($this->R4_conn_orl != FALSE) {
                oci_close($this->R4_conn_orl);
            }
        }
        if (isset($this->R4_Pra_info)) {
            unset($this->R4_Pra_info);
        }
        if (isset($this->R4_conn_orl)) {
            unset($this->R4_conn_orl);
        }
        if (isset($this->R4LogPath)) {
            unset($this->R4LogPath);
        }
        if (isset($this->R4_Exe_stid)) {
            unset($this->R4_Exe_stid);
        }
        if (isset($this->R4_Result)) {
            unset($this->R4_Result);
        }
        if (isset($this->xml)) {
            unset($this->xml);
        }
    }

}
