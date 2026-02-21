<?php

namespace App\Model\Component;

use Cake\Routing\Router;
use Cake\Log\Log;

//App::uses('ClsComFnc', 'Model/Component');
class Do_SQL
{
    // protected $Ora_db = '192.168.2.80/gdmzh';
    // protected $Ora_user = 'gdmz';
    // protected $Ora_pwd = 'gdmz';
    protected $Ora_db = '';
    protected $Ora_user = '';
    protected $Ora_pwd = '';
    protected $Ora_Character = 'utf8';
    protected $conn_orl = '';
    protected $errmsg = '';
    protected $Sql_Sring = '';
    protected $Do_conn_return = array(
        'conn_orl' => '',
        'conn_sta' => '',
    );
    protected $Do_Execute_return = array(
        'Pra_info' => '',
        'Pra_sta' => '',
    );
    protected $Do_mode = OCI_COMMIT_ON_SUCCESS;

    public $GS_LOGINUSER = array(
        'strUserID' => '',
        'strUserNM' => '',
        'strClientNM' => '',
        'LoginTime' => '',
    );
    protected $xml = '';
    protected $Pra_info = '';
    protected $Exe_stid = '';

    public function __construct($Ora_db = '', $Ora_user = '', $Ora_pwd = '', $Ora_Character = '')
    {
        // パス取得
        $strPath = dirname(__FILE__);
        $filename = $strPath . '/' . 'HMDB.xml';

        // 値取得
        $this->xml = simplexml_load_file($filename);
        // XMLの取得
        $result = (array) $this->xml;
        //20141028 fan del s.
        //$r4_name = SessionComponent::read("r4_name");
        //20141028 fan del e.

        //***************fan add start***********************
        $session = Router::getRequest()->getSession();
        $sys_id = $session->read('sys_id');

        // if ($sys_id == "CkChkzaiko")
        // {
        // $this -> Ora_db = $result['server'];
        // $this -> Ora_user = $result['userid'];
        // $this -> Ora_pwd = $result['password'];
        // $this -> Ora_Character = 'utf8';
        // }
        // else
        // {
        // if ($sys_id != "" && $r4_name != "")
        // {
        // if ($sys_id == 'R4K')
        // {
        // $this -> Ora_db = $result['pprserver'];
        // $this -> Ora_user = $result['ppruserid'];
        // $this -> Ora_pwd = $result['pprpassword'];
        // $this -> Ora_Character = 'utf8';
        // }
        // elseif ($sys_id == 'R4')
        // {
        // if ($r4_name == '管理会計システム')
        // {
        // $this -> Ora_db = $result['pprserver'];
        // $this -> Ora_user = $result['ppruserid'];
        // $this -> Ora_pwd = $result['pprpassword'];
        // $this -> Ora_Character = 'utf8';
        // }
        // else
        // {
        // $this -> Ora_db = $result['server'];
        // $this -> Ora_user = $result['userid'];
        // $this -> Ora_pwd = $result['password'];
        // $this -> Ora_Character = 'utf8';
        // }
        // }
        // else
        // {
        // $this -> Ora_db = $result['server'];
        // $this -> Ora_user = $result['userid'];
        // $this -> Ora_pwd = $result['password'];
        // $this -> Ora_Character = 'utf8';
        // }
        // }
        // if ($sys_id == 'SDH')
        // {
        // $this -> Ora_db = $result['server'];
        // $this -> Ora_user = $result['userid'];
        // $this -> Ora_pwd = $result['password'];
        // $this -> Ora_Character = 'utf8';
        // }
        // }
        if ('CkChkzaiko' == $sys_id) {
            $this->Ora_db = $result['server'];
            $this->Ora_user = $result['userid'];
            $this->Ora_pwd = $result['password'];
            $this->Ora_Character = 'utf8';
        } else {
            if ('' != $sys_id) {
                if ('R4K' == $sys_id) {
                    $this->Ora_db = $result['pprserver'];
                    $this->Ora_user = $result['ppruserid'];
                    $this->Ora_pwd = $result['pprpassword'];
                    $this->Ora_Character = 'utf8';
                } elseif ('R4G' == $sys_id) {
                    $this->Ora_db = $result['server'];
                    $this->Ora_user = $result['userid'];
                    $this->Ora_pwd = $result['password'];
                    $this->Ora_Character = 'utf8';
                } elseif ('KRSS' == $sys_id) {
                    $this->Ora_db = $result['pprserver'];
                    $this->Ora_user = $result['ppruserid'];
                    $this->Ora_pwd = $result['pprpassword'];
                    $this->Ora_Character = 'utf8';
                }
                //---20170710 li INS S.
                elseif ('PPRM' == $sys_id) {
                    $this->Ora_db = $result['pprserver'];
                    $this->Ora_user = $result['ppruserid'];
                    $this->Ora_pwd = $result['pprpassword'];
                    $this->Ora_Character = 'utf8';
                }
                //---20170710 li INS E.
                //---20190418 yuan INS S.
                elseif ('JKSYS' == $sys_id) {
                    $this->Ora_db = $result['kyjserver'];
                    $this->Ora_user = $result['kyjuserid'];
                    $this->Ora_pwd = $result['kyjpassword'];
                    $this->Ora_Character = 'utf8';
                }
                //---20190418 yuan INS E.
                //---20210506 lqs INS S.
//                elseif ('HMDPS' == $sys_id || 'HMTVE' == $sys_id) {
                elseif ('HMDPS' == $sys_id) {
                    $this->Ora_db = $result['pprserver'];
                    $this->Ora_user = $result['ppruserid'];
                    $this->Ora_pwd = $result['pprpassword'];
                    $this->Ora_Character = 'utf8';
                } elseif ('HMTVE' == $sys_id) {
                    $this->Ora_db = $result['server'];
                    $this->Ora_user = $result['userid'];
                    $this->Ora_pwd = $result['password'];
                    $this->Ora_Character = 'utf8';
                }
                //---20210506 lqs INS E.
                elseif ('SDH' == $sys_id) {
                    $this->Ora_db = $result['server'];
                    $this->Ora_user = $result['userid'];
                    $this->Ora_pwd = $result['password'];
                    $this->Ora_Character = 'utf8';
                }
                //---20170425 li INS S.
                elseif ('APPM' == $sys_id) {
                    $this->Ora_db = $result['server'];
                    $this->Ora_user = $result['userid'];
                    $this->Ora_pwd = $result['password'];
                    $this->Ora_Character = 'utf8';
                }
                //---20170425 li INS E.
                //---20220617 YIN INS S.
                elseif ('HMAUD' == $sys_id) {
                    $this->Ora_db = $result['server'];
                    $this->Ora_user = $result['userid'];
                    $this->Ora_pwd = $result['password'];
                    $this->Ora_Character = 'utf8';
                }
                //---20220617 YIN INS E.
                //---20230626 YIN INS S.
                elseif ($sys_id == 'HDKAIKEI') {
                    $this->Ora_db = $result['hdkserver'];
                    $this->Ora_user = $result['hdkuserid'];
                    $this->Ora_pwd = $result['hdkpassword'];
                    $this->Ora_Character = 'utf8';
                }
                //---20230626 YIN INS E.
            }
        }
        //***************fan add end***********************
        $client = '';
        if (isset($_SERVER['REMOTE_HOST'])) {
            if ('' != $_SERVER['REMOTE_HOST'] && null != $_SERVER['REMOTE_HOST']) {
                $client = $_SERVER['REMOTE_HOST'];
            }
        }
        if (!isset($_SERVER['REMOTE_HOST']) || '' == $_SERVER['REMOTE_HOST'] || null == $_SERVER['REMOTE_HOST']) {
            if ('' != $_SERVER['REMOTE_ADDR'] && null != $_SERVER['REMOTE_ADDR']) {
                $client = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            }
            if ('' == $_SERVER['REMOTE_ADDR'] && null == $_SERVER['REMOTE_ADDR']) {
                $client = 'UNSET';
            }
        }
        $this->GS_LOGINUSER['strClientNM'] = $client;

        unset($client);
    }

    public function Do_conn()
    {
        try {
            //20140401 パフォーマンス対応 st
            //DB接続をプールし処理速度を改善
            //$this -> conn_orl = oci_connect($this -> Ora_user, $this -> Ora_pwd, $this -> Ora_db, $this -> Ora_Character);
            $this->conn_orl = oci_pconnect($this->Ora_user, $this->Ora_pwd, $this->Ora_db, $this->Ora_Character);
            //20140401 パフォーマンス対応 ed

            if (!$this->conn_orl) {
                $e = oci_error();
                throw new \Exception($this->getOracleError($e['message']));
            }
            $this->Do_conn_return['conn_orl'] = $this->conn_orl;
            $this->Do_conn_return['conn_sta'] = true;

            return $this->Do_conn_return;
        } catch (\Exception $e) {
            $this->errmsg = $e->getMessage();
            $this->Do_conn_return['conn_orl'] = $this->errmsg;
            $this->Do_conn_return['conn_sta'] = false;

            return $this->Do_conn_return;
        }
    }

    public function Do_Execute($Sql)
    {
        try {
            $this->Pra_info = oci_parse($this->conn_orl, $Sql);
            if (!$this->Pra_info) {
                $e = oci_error($this->conn_orl);
                throw new \Exception($this->getOracleError($e['message']));
            }

            $this->Exe_stid = oci_execute($this->Pra_info, $this->Do_mode);
            if (!$this->Exe_stid) {
                $e = oci_error($this->Pra_info);
                throw new \Exception($this->getOracleError($e['message']));
            }
            $this->Do_Execute_return['Pra_info'] = $this->Pra_info;
            $this->Do_Execute_return['Pra_sta'] = true;

            return $this->Do_Execute_return;
        } catch (\Exception $e) {

            Log::error($this->errmsg);
            Log::error($Sql);

            $this->errmsg = $e->getMessage();
            $this->Do_Execute_return['Pra_info'] = $this->errmsg;
            $this->Do_Execute_return['Pra_sta'] = false;

            return $this->Do_Execute_return;
        }
    }

    public function Do_transaction()
    {
        $this->Do_mode = OCI_NO_AUTO_COMMIT;
    }

    public function Do_commit()
    {
        try {
            if ($this->Do_conn_return['conn_orl']) {
                if (!oci_commit($this->Do_conn_return['conn_orl'])) {
                    throw new \Exception($this->getOracleError(oci_error($this->Do_conn_return['conn_orl'])));
                }
            }

            $this->Do_mode = OCI_COMMIT_ON_SUCCESS;
            $this->Do_conn_return['conn_orl'] = $this->conn_orl;
            $this->Do_conn_return['conn_sta'] = true;

            return $this->Do_conn_return;
        } catch (\Exception $e) {
            $this->errmsg = $e->getMessage();
            $this->Do_conn_return['conn_orl'] = $this->errmsg;
            $this->Do_conn_return['conn_sta'] = false;

            return $this->Do_conn_return;
        }
    }

    public function Do_rollback()
    {
        try {
            if ($this->Do_conn_return['conn_orl']) {
                if (!oci_rollback($this->Do_conn_return['conn_orl'])) {
                    throw new \Exception($this->getOracleError(oci_error($this->Do_conn_return['conn_orl'])));
                }
            }

            $this->Do_mode = OCI_COMMIT_ON_SUCCESS;
            $this->Do_conn_return['conn_orl'] = $this->conn_orl;
            $this->Do_conn_return['conn_sta'] = true;

            return $this->Do_conn_return;
        } catch (\Exception $e) {
            $this->errmsg = $e->getMessage();
            $this->Do_conn_return['conn_orl'] = $this->errmsg;
            $this->Do_conn_return['conn_sta'] = false;

            return $this->Do_conn_return;
        }
    }

    public function Do_close()
    {
        try {
            if ($this->Do_conn_return['conn_sta']) {
                if (!oci_close($this->Do_conn_return['conn_orl'])) {
                    throw new \Exception($this->getOracleError(oci_error($this->Do_conn_return['conn_orl'])));
                }
            }

            $this->Do_mode = OCI_COMMIT_ON_SUCCESS;
            $this->Do_conn_return['conn_orl'] = $this->conn_orl;
            $this->Do_conn_return['conn_sta'] = true;

            return $this->Do_conn_return;
        } catch (\Exception $e) {
            $this->errmsg = $e->getMessage();
            $this->Do_conn_return['conn_orl'] = $this->errmsg;
            $this->Do_conn_return['conn_sta'] = false;

            return $this->Do_conn_return;
        }
    }
    /**
     * エラー内容の切り取り
     * @param mixed $message
     * @return string
     */
    function getOracleError($message)
    {
        $message = substr($message, 0, stripos($message, 'Help'));
        return $message;
    }
}
