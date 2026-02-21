<?php
//App::uses('ClsComFnc', 'Model/Component');
namespace App\Model\Component;

use Cake\Routing\Router;
use PDO;
use PDOException;

class Do_MYSQL
{

    // protected $Ora_db = '192.168.2.80/gdmzh';
    // protected $Ora_user = 'gdmz';
    // protected $Ora_pwd = 'gdmz';
    protected $Sql_db = '';
    protected $Sql_dbpt;
    private $SessionComponent;
    protected $Sql_user = '';
    protected $Sql_pwd = '';
    protected $Sql_dbn;
    protected $Sql_Character = 'utf8';
    protected $conn_sql = null;
    protected $errmsg = '';
    protected $Sql_Sring = '';
    protected $Do_conn_return = array(
        'conn_sql' => '',
        'conn_sta' => ''
    );
    protected $Pra_info = '';
    protected $Exe_stid = '';
    protected $xml = '';

    protected $Do_Execute_return = array(
        'Pra_info' => '',
        'Pra_sta' => ''
    );
    protected $Do_mode = OCI_COMMIT_ON_SUCCESS;

    public $GS_LOGINUSER = array(
        'strUserID' => "",
        'strUserNM' => "",
        'strClientNM' => "",
        'LoginTime' => ""
    );

    function __construct($Ora_db = "", $Ora_user = '', $Ora_pwd = '', $Ora_Character = '')
    {
        // パス取得
        $strPath = dirname(__FILE__);
        $filename = $strPath . "/" . 'HMDB.xml';

        // 値取得
        $this->xml = simplexml_load_file($filename);
        // XMLの取得
        $result = (array) $this->xml;

        //***************fan add start***********************
        // $sys_id = SessionComponent::read('sys_id');
        $this->SessionComponent = Router::getRequest()->getSession();
        $sys_id = $this->SessionComponent->read('sys_id');

        // $session = Router::getRequest()->getSession();
        // $sys_id = $session->read('sys_id');
        if ($sys_id == "HMHRMS") {
            $this->Sql_db = $result['hmserver'];
            $this->Sql_dbpt = $result['hmport'];
            $this->Sql_user = $result['hmuserid'];
            $this->Sql_pwd = $result['hmpassword'];
            $this->Sql_dbn = $result['hmdbname'];
        }

        //***************fan add end***********************
        $client = "";
        if (isset($_SERVER['REMOTE_HOST'])) {
            if ($_SERVER['REMOTE_HOST'] != "" && $_SERVER['REMOTE_HOST'] != null) {
                $client = $_SERVER['REMOTE_HOST'];
            }
        }
        if (!isset($_SERVER['REMOTE_HOST']) || $_SERVER['REMOTE_HOST'] == "" || $_SERVER['REMOTE_HOST'] == null) {
            if ($_SERVER['REMOTE_ADDR'] != "" && $_SERVER['REMOTE_ADDR'] != null) {
                $client = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            }
            if ($_SERVER['REMOTE_ADDR'] == "" && $_SERVER['REMOTE_ADDR'] == null) {
                $client = "UNSET";
            }
        }
        $this->GS_LOGINUSER['strClientNM'] = $client;

        unset($client);

    }

    function Do_conn()
    {
        try {
            if ($this->conn_sql == null) {
                //数据库类型
                $dbms = 'mysql';
                //数据库主机名
                $host = $this->Sql_db;
                $port = $this->Sql_dbpt;
                //使用的数据库
                $dbName = $this->Sql_dbn;
                //数据库连接用户名
                $user = $this->Sql_user;
                //对应的密码
                $pass = $this->Sql_pwd;
                $dsn = "$dbms:host=$host;port=$port;dbname=$dbName";
                $this->conn_sql = new PDO($dsn, $user, $pass);
                $this->conn_sql->query("SET names utf8");
                $this->conn_sql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            $this->Do_conn_return['conn_sql'] = $this->conn_sql;
            $this->Do_conn_return['conn_sta'] = TRUE;

            return $this->Do_conn_return;
        } catch (PDOException $e) {
            $this->errmsg = $e->getMessage();
            $this->Do_conn_return['conn_sql'] = $this->errmsg;
            $this->Do_conn_return['conn_sta'] = FALSE;
            return $this->Do_conn_return;
        }
    }

    function Do_Execute($Sql)
    {
        try {
            $this->Pra_info = $this->conn_sql->prepare($Sql);
            if (!$this->Pra_info) {
                throw new PDOException($this->conn_sql->errorInfo());
            }

            $this->Exe_stid = $this->Pra_info->execute();
            if (!$this->Exe_stid) {
                throw new PDOException($this->conn_sql->errorInfo());
            }
            $this->Do_Execute_return['Pra_info'] = $this->Pra_info;
            $this->Do_Execute_return['Pra_sta'] = TRUE;
            return $this->Do_Execute_return;
        } catch (PDOException $e) {
            $this->errmsg = $e->getMessage();
            $this->Do_Execute_return['Pra_info'] = $this->errmsg;
            $this->Do_Execute_return['Pra_sta'] = FALSE;
            return $this->Do_Execute_return;
        }
    }

    function Do_transaction()
    {
        $this->conn_sql->beginTransaction();
    }

    function Do_commit()
    {
        try {
            if ($this->Do_conn_return['conn_sql']) {
                if (!$this->Do_conn_return['conn_sql']->commit()) {
                    throw new PDOException($this->conn_sql->errorInfo());
                }
            }

            $this->Do_conn_return['conn_sql'] = $this->conn_sql;
            $this->Do_conn_return['conn_sta'] = TRUE;
            return $this->Do_conn_return;
        } catch (PDOException $e) {
            $this->errmsg = $e->getMessage();
            $this->Do_conn_return['conn_sql'] = $this->errmsg;
            $this->Do_conn_return['conn_sta'] = FALSE;
            return $this->Do_conn_return;
        }
    }

    function Do_rollback($tables)
    {
        try {
            if ($this->Do_conn_return['conn_sql']) {
                if (!$this->Do_conn_return['conn_sql']->rollBack()) {
                    throw new PDOException($this->conn_sql->errorInfo());
                }
            }
            foreach ($tables as $value) {
                $this->Do_conn_return['conn_sql']->exec("ALTER TABLE $value AUTO_INCREMENT = 1;");
            }

            $this->Do_conn_return['conn_sql'] = $this->conn_sql;
            $this->Do_conn_return['conn_sta'] = TRUE;
            return $this->Do_conn_return;
        } catch (PDOException $e) {
            $this->errmsg = $e->getMessage();
            $this->Do_conn_return['conn_sql'] = $this->errmsg;
            $this->Do_conn_return['conn_sta'] = FALSE;
            return $this->Do_conn_return;
        }
    }

    function Do_close()
    {
        try {
            if ($this->Do_conn_return['conn_sta']) {
                $this->conn_sql = null;
            }

            $this->Do_conn_return['conn_sql'] = $this->conn_sql;
            $this->Do_conn_return['conn_sta'] = TRUE;
            return $this->Do_conn_return;
        } catch (\Exception $e) {
            $this->errmsg = $e->getMessage();
            $this->Do_conn_return['conn_sql'] = $this->errmsg;
            $this->Do_conn_return['conn_sta'] = FALSE;
            return $this->Do_conn_return;
        }
    }

}
