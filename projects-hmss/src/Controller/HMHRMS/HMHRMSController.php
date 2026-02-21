<?php
namespace App\Controller\HMHRMS;

use App\Controller\AppController;
use App\Model\HMHRMS\HMHRMS;
use App\Controller\HMHRMS\Component\upload\UploadHandler;

//*******************************************
// * sample controller
//*******************************************
class HMHRMSController extends AppController
{
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $HMHRMS;
    public $E0005 = '%d登録失敗。';
    public $W0031 = 'データは既に存在します';
    private $Session;
    // var $components = array(
    // 	'RequestHandler',
    // 	'ClsComFnc',
    // );
    public $tableName = array(
        //家族状況
        'family',
        //学歴
        'education',
        //社外職歴
        'othercompany',
        //表彰歴
        'praise',
        //資格・免許
        'qualication'
    );
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // $this->layout = 'HMHRMS_layout';
        // Viewファイル呼出し
        $this->render('index', 'HMHRMS_layout');
    }

    //初期Columns取得
    public function getColumns()
    {

        $this->HMHRMS = new HMHRMS();
        $result = array(
            'result' => FALSE,
            'columns' => null,
            'error' => ''
        );
        try {
            //初期Columns取得
            $result = $this->HMHRMS->getColumns();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $cnt = $result['row'];
            $column_arr = array();
            for ($i = 0; $i < $cnt; $i++) {
                $id = $result['data'][$i]['id'];
                $tmp = $result['data'][$i]['name'];
                $des = $result['data'][$i]['description'];
                $align = $result['data'][$i]['column_align'];
                $sortable = $result['data'][$i]['column_sortable'];
                foreach ($this->tableName as $value) {
                    if (($result['data'][$i]['type'] == $value)) {
                        $strsch = array();
                        $strsch['id'] = $id;
                        $strsch['name'] = $tmp;
                        $strsch['label'] = $des;
                        $strsch['field'] = $tmp;
                        $strsch['align'] = $align;
                        $strsch['sortable'] = $sortable;
                        if (!isset($column_arr[$value])) {
                            $column_arr[$value] = array();
                        }
                        $column_arr[$value][] = $strsch;
                    }
                }
            }
            $result['columns'] = $column_arr;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //社員個人情報と履歴search
    public function fncEmpSearch()
    {
        $this->HMHRMS = new HMHRMS();
        $result = array(
            'result' => FALSE,
            'data' => null,
            'rows' => null,
            'selopts' => array(),
            'error' => ''
        );

        try {
            if (!isset($_POST['data']['data'])) {
                throw new \Exception("param error");
            }

            $empId = $_POST['data']['data'];
            $this->Session = $this->request->getSession();

            // 通勤方法，学校種別option取得
            $params_opt = array(
                'commuteMethodOpt' => '通勤方法',
                'schoolTypeOpt' => '学校種別'
            );

            foreach ($params_opt as $key => $value) {
                $result_opt = $this->HMHRMS->getOptionData($value);
                if (!$result_opt['result']) {
                    throw new \Exception($result_opt['data']);
                }
                $result['selopts'][$key] = $result_opt['data'];
            }

            // 社員個人情報データ取得
            $result_emp = $this->HMHRMS->getEmpData($empId);
            if (!$result_emp['result']) {
                throw new \Exception($result_emp['data']);
            }

            if (empty($result_emp['data']) || !isset($result_emp['data'][0])) {
                throw new \Exception("社員データが見つかりませんでした。");
            }

            $this->Session->write('facePhoto', $result_emp['data'][0]['facePhoto']);

            $photoUrl = str_replace('face', '', $result_emp['data'][0]['facePhoto'] == null ? '' : $result_emp['data'][0]['facePhoto']);
            require_once dirname(__FILE__) . '/Component/upload/UploadHandler.php';
            $strPath = dirname(dirname(dirname(__FILE__)));
            $pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('hmhrmsPhotoPath');

            if ($photoUrl == null || $photoUrl == "") {
                $file = $pathUpLoad . 'error.png';
            } else {
                if (file_exists($pathUpLoad . $photoUrl)) {
                    $file = $pathUpLoad . $photoUrl;
                } else {
                    $file = $pathUpLoad . 'error.png';
                }
            }

            if (file_exists($file)) {
                $image_info = getimagesize($file);
                $image_data = fread(fopen($file, 'r'), filesize($file));
                // 输出
                $result_emp['data'][0]['facePhotobase'] = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
            } else {
                $result_emp['data'][0]['facePhotobase'] = '';
            }

            $result['rows'] = $result_emp['data'];

            // 履歴部分データ取得
            $cv_data = array();
            foreach ($this->tableName as $key => $value) {
                $result_cv = $this->HMHRMS->getRirekiData($empId, $value);
                if (!$result_cv['result']) {
                    throw new \Exception($result_cv['data']);
                }
                $cv_data[$value] = $result_cv['data'];
            }

            // 社外職歴
            if (isset($cv_data['othercompany']) && count((array) $cv_data['othercompany']) > 0) {
                foreach ((array) $cv_data['othercompany'] as $key => $value) {
                    if ('company_start' == $value['name'] || 'company_end' == $value['name']) {
                        if (false != date_create_from_format('Y/m', $value['value'])) {
                            $value['value'] = date_format(date_create_from_format('Y/m', $value['value']), 'Y/m');
                        } else {
                            $value['value'] = '';
                        }
                        $cv_data['othercompany'][$key] = $value;
                    }
                }
            }

            // 表彰歴
            if (isset($cv_data['praise']) && count($cv_data['praise']) > 0) {
                foreach ($cv_data['praise'] as $key => $value) {
                    if ('praise_date' == $value['name']) {
                        if (7 == strlen($value['value'])) {
                            if (false != date_create_from_format('Y/m', $value['value'])) {
                                $value['value'] = date_format(date_create_from_format('Y/m', $value['value']), 'Y/m');
                            } else {
                                $value['value'] = '';
                            }
                            $cv_data['praise'][$key] = $value;
                        }
                    }
                }
            }

            // 資格・免許
            if (isset($cv_data['qualication']) && count($cv_data['qualication']) > 0) {
                foreach ($cv_data['qualication'] as $key => $value) {
                    if ('get_date' == $value['name']) {
                        if (7 == strlen($value['value'])) {
                            if (false != date_create_from_format('Y/m', $value['value'])) {
                                $value['value'] = date_format(date_create_from_format('Y/m', $value['value']), 'Y/m');
                            } else {
                                $value['value'] = '';
                            }
                            $cv_data['qualication'][$key] = $value;
                        }
                    }
                }
            }

            // 社员权限取得
            $resrole = $this->HMHRMS->getRoleData($empId);
            if (!$resrole['result']) {
                throw new \Exception($resrole['data']);
            }

            if (empty($resrole['data']) || !isset($resrole['data'][0])) {
                throw new \Exception("社員権限データが見つかりませんでした。");
            }

            $result['sys_kb'] = $resrole['data'][0]['sys_kb'];
            $result['data'] = $cv_data;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncCheckFile()
    {
        $result = array(
            'result' => FALSE,
            'data' => array(),
        );

        try {
            $this->Session = $this->request->getSession();
            $login_user = $this->Session->read('login_user');

            // 社員個人情報データ取得
            $this->HMHRMS = new HMHRMS();
            $result_emp = $this->HMHRMS->getEmpData($login_user);

            if (!$result_emp['result']) {
                throw new \Exception($result_emp['data']);
            }

            if (empty($result_emp['data']) || !isset($result_emp['data'][0])) {
                throw new \Exception("社員データが見つかりませんでした。");
            }

            $facePhoto = $this->Session->read('facePhoto');

            // if (!isset($result_emp['data'][0]['facePhoto'])) {
            //     throw new \Exception("facePhoto データが見つかりませんでした。");
            // }

            if ($facePhoto !== $result_emp['data'][0]['facePhoto']) {
                throw new \Exception("他のユーザーにより更新されています。最新の情報を取得しました。");
            }

            $strPath = dirname(dirname(dirname(__FILE__)));
            $pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('hmhrmsPhotoPath');

            if (!file_exists($pathUpLoad)) {
                if (!mkdir($pathUpLoad, 0755, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。");
                }
                chmod($pathUpLoad, 0755);
            }

            if ($_FILES["file"]["error"] > 0) {
                throw new \Exception("ファイルのアップロードに失敗しました。");
            } else {
                $const = array(
                    'upload_dir' => $pathUpLoad,
                    'replace_dots_in_filenames' => '',
                    'param_name' => 'file',
                );
                $uploadedFile = new UploadHandler($const);

                if (isset($uploadedFile->response['file'][0]->error)) {
                    if ('The uploaded file exceeds the upload_max_filesize directive in php.ini' == $uploadedFile->response['file'][0]->error) {
                        throw new \Exception('アップロードされたファイルはupload_max_filesizeディレクティブを超えています。');
                    } else {
                        if (file_exists($pathUpLoad . $uploadedFile->response['file'][0]->name)) {
                            unlink($pathUpLoad . $uploadedFile->response['file'][0]->name);
                        } else {
                            throw new \Exception('アップロードが失敗しました。');
                        }
                    }
                }

                $name = $uploadedFile->response['file'][0]->name;
                $time = date('YmdHis');
                $filetype = pathinfo($name, PATHINFO_EXTENSION);
                $new_name = $login_user . '_' . $time . '.' . $filetype;
                rename($pathUpLoad . $name, $pathUpLoad . $new_name);

                if (file_exists($pathUpLoad . $name)) {
                    unlink($pathUpLoad . $name);
                }

                $database_img_src = $result_emp['data'][0]['facePhoto'] ? str_replace('face/', '', $result_emp['data'][0]['facePhoto']) : '';
                if ($database_img_src && file_exists($pathUpLoad . $database_img_src)) {
                    $old_csvpath = $pathUpLoad . $database_img_src;
                    $newpath = str_replace($login_user . '_', 'deleted_' . $login_user . '_', $old_csvpath);
                    rename($old_csvpath, $newpath);
                }

                $result['data'] = array('filename' => $new_name);
                $result['result'] = true;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncCheckFileReturn($result);
    }

    //社員個人情報 update
    public function fncEmpUpdate()
    {
        $tranStartFlg = FALSE;
        $this->HMHRMS = new HMHRMS();
        $result = array(
            'result' => FALSE,
            'data' => null,
            'rows' => null,
            'error' => ''
        );

        try {
            $time = date('YmdHis');

            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }

            $update_data = $_POST['data'];

            $rollbackTables = array(
                'employee',
                'custom_values_history'
            );

            $this->HMHRMS->Do_conn();
            //トランザクション開始
            $this->HMHRMS->Do_transaction();
            $tranStartFlg = TRUE;

            // 社員個人情報データ更新
            $result_emp = $this->HMHRMS->updateEmp($update_data, $time);
            if (!$result_emp['result']) {
                throw new \Exception($result_emp['data']);
            }

            //社員id取得
            $result_id = $this->HMHRMS->getEmpId($update_data['empId']);
            if (!$result_id['result']) {
                throw new \Exception($result_id['data']);
            }

            //employeeテーブルのcomment
            $result_cot = $this->HMHRMS->getInfo();
            if (!$result_cot['result']) {
                throw new \Exception($result_cot['data']);
            }

            //employeeテーブルのid
            if (empty($result_id['data']) || !isset($result_id['data'][0])) {
                throw new \Exception("社員IDデータが見つかりませんでした。");
            }
            $id = $result_id['data'][0]['id'];

            $history_data = array();
            if (isset($update_data['emp'])) {
                foreach ($update_data['emp'] as $key => $value) {
                    if ($key == 'facePhoto') {
                        continue;
                    }

                    $history_data['id'] = $id;
                    $history_data['update_before'] = isset($update_data['pre_emp'][$key]) ? $update_data['pre_emp'][$key] : '';
                    $history_data['update_after'] = $update_data['emp'][$key];

                    //通勤方法
                    if ($key == 'commuteMethod') {
                        $history_data['commuteMethod'] = $update_data['emp']['commuteMethod'];
                    } else {
                        $history_data['commuteMethod'] = '';
                    }

                    if (isset($result_cot['data'])) {
                        foreach ((array) $result_cot['data'] as $num => $info) {
                            //column name
                            if ($key == $info['COLUMN_NAME']) {
                                $history_data['item1'] = $info['COLUMN_COMMENT'];
                            }
                        }
                    }

                    // 履歴テーブル追加
                    $result_ins = $this->HMHRMS->insertHistory($history_data, $time);
                    if (!$result_ins['result']) {
                        throw new \Exception($result_ins['data']);
                    }
                }
            }

            // 社員個人情報データ取得
            $result_emp = $this->HMHRMS->getEmpData($update_data['empId']);
            if (!$result_emp['result']) {
                throw new \Exception($result_emp['data']);
            }

            if (empty($result_emp['data']) || !isset($result_emp['data'][0])) {
                throw new \Exception("社員データが見つかりませんでした。");
            }

            $result['rows'] = $result_emp['data'];
            $this->Session = $this->request->getSession();
            $this->Session->write('facePhoto', $result_emp['data'][0]['facePhoto']);

            //コミット
            $this->HMHRMS->Do_commit();

            if (isset($update_data['pre_emp']['facePhoto'])) {
                require_once dirname(__FILE__) . '/Component/upload/UploadHandler.php';
                $strPath = dirname(dirname(dirname(__FILE__)));
                $pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('hmhrmsPhotoPath');
                $oldp = str_replace("face/", "", $update_data['pre_emp']['facePhoto']);
                if ($oldp && file_exists($pathUpLoad . str_replace($update_data['empId'] . '_', 'deleted_' . $update_data['empId'] . '_', $oldp))) {

                    $old_path = $pathUpLoad . str_replace($update_data['empId'] . '_', 'deleted_' . $update_data['empId'] . '_', $oldp);
                    unlink($old_path);
                }
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMHRMS->Do_rollback($rollbackTables);
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

            if (isset($update_data['emp']['facePhoto']) && isset($update_data['pre_emp']['facePhoto'])) {
                require_once dirname(__FILE__) . '/Component/upload/UploadHandler.php';
                $strPath = dirname(dirname(dirname(__FILE__)));
                $pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('hmhrmsPhotoPath');

                $new_path = $pathUpLoad . str_replace("face/", "", $update_data['emp']['facePhoto']);
                if (file_exists($new_path)) {
                    unlink($new_path);
                }

                $path = $pathUpLoad . str_replace("face/", "", $update_data['pre_emp']['facePhoto']);
                $old_path = str_replace($update_data['empId'] . '_', 'deleted_' . $update_data['empId'] . '_', $path);
                if (file_exists($old_path)) {
                    $rpath = $pathUpLoad . str_replace("face/", "", $update_data['pre_emp']['facePhoto']);
                    rename($old_path, $rpath);
                }
            }
        }

        $this->HMHRMS->Do_close();
        $this->fncReturn($result);
    }

    //履歴部分データupdate
    public function funUpdate()
    {
        $tranStartFlg = FALSE;
        $this->HMHRMS = new HMHRMS();
        $result = array(
            'result' => FALSE,
            'data' => null,
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $data = $_POST['data'];
            } else {
                throw new \Exception("param error");

            }
            $time = date('YmdHis');
            $rollbackTables = array(
                'custom_values_history',
                'employee_sub_table_' . $data['employee_sub_table']['type'],
                'custom_values_' . $data['employee_sub_table']['type'],
            );
            $this->HMHRMS->Do_conn();
            //表自增
            $result_atin = $this->HMHRMS->alterIncrementHistory();
            if (false == $result_atin['result']) {
                throw new \Exception($this->E0005);
            }
            //トランザクション開始
            $this->HMHRMS->Do_transaction();
            $tranStartFlg = TRUE;
            //社員id取得
            $result_id = $this->HMHRMS->getEmpId($data['employee_sub_table']['employee_id']);
            if (!$result_id['result']) {
                throw new \Exception($result_id['data']);
            }
            //employeeテーブルのid
            $id = $result_id['data'][0]['id'];
            $data['employee_sub_table']['employee_id'] = $id;
            //履歴更新custom_values
            $res_upd = $this->updCustomValueData($data);
            if (!$res_upd['result']) {
                throw new \Exception($res_upd['data']);
            }
            //履歴テーブルのフィールド名
            $res_name = $this->HMHRMS->getDescription($data['employee_sub_table']['type']);
            if (!$res_name['result']) {
                throw new \Exception($res_name['data']);
            }

            //変更履歴 insert
            if (isset($data['custom_values_history'])) {
                foreach ($data['custom_values_history'] as $value) {
                    foreach ((array) $res_name['data'] as $value_name) {
                        if ($value_name['position'] == '0') {
                            $item1 = $value_name['description'];
                        }
                        if ($value['item2'] == $value_name['name']) {
                            $item2 = $value_name['description'];
                        }
                    }

                    $value['id'] = $id;
                    $value['item1'] = $item1 ? $item1 : '';
                    $value['item2'] = $item2 ? $item2 : '';
                    $res_his = $this->HMHRMS->insertHistory($value, $time);
                    if (!$res_his['result']) {
                        throw new \Exception($res_his['data']);
                    }
                }
            }
            //コミット
            $this->HMHRMS->Do_commit();
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMHRMS->Do_rollback($rollbackTables);
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->HMHRMS->Do_close();

        $this->fncReturn($result);
    }

    //履歴部分データinsert
    public function funInsert()
    {
        $tranStartFlg = FALSE;
        $this->HMHRMS = new HMHRMS();
        $result = array(
            'result' => FALSE,
            'estid' => null,
            'ids' => null,
            'employee_id' => null,
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $data = $_POST['data'];
            } else {
                throw new \Exception("param error");
            }
            $time = date('YmdHis');
            $rollbackTables = array(
                'custom_values_history',
                'employee_sub_table_' . $data['employee_sub_table']['type'],
                'custom_values_' . $data['employee_sub_table']['type'],
            );
            $this->HMHRMS->Do_conn();
            //表自增
            $result = $this->HMHRMS->alterIncrementSt($data['employee_sub_table']);
            if (false == $result['result']) {
                throw new \Exception($this->E0005);
            }
            $result = $this->HMHRMS->alterIncrementCv($data['employee_sub_table']);
            if (false == $result['result']) {
                throw new \Exception($this->E0005);
            }
            $result_atin = $this->HMHRMS->alterIncrementHistory();
            if (false == $result_atin['result']) {
                throw new \Exception($this->E0005);
            }
            //トランザクション開始
            $this->HMHRMS->Do_transaction();
            $tranStartFlg = TRUE;
            //社員id取得
            $result_id = $this->HMHRMS->getEmpId($data['employee_sub_table']['employee_id']);
            if (!$result_id['result']) {
                throw new \Exception($result_id['data']);
            }
            //employeeテーブルのid
            $id = $result_id['data'][0]['id'];
            $data['employee_sub_table']['employee_id'] = $id;
            //履歴部分を追加する方法
            $result_ins = $this->insHistoryData($data);
            if (!$result_ins['result']) {
                throw new \Exception($result_ins['data']);
            }
            $result['estid'] = $result_ins['estid'];
            $result['ids'] = $result_ins['ids'];
            $result['employee_id'] = $result_ins['employee_id'];

            //履歴テーブルのフィールド名
            $res_name = $this->HMHRMS->getDescription($data['employee_sub_table']['type']);
            if (!$res_name['result']) {
                throw new \Exception($res_name['data']);
            }
            //変更履歴 insert
            if (isset($data['custom_values_history'])) {
                foreach ($data['custom_values_history'] as $value) {
                    //データが空場合、追加しない
                    if ($value['update_after'] !== '') {
                        foreach ((array) $res_name['data'] as $value_name) {
                            if ($value_name['position'] == '0') {
                                $item1 = $value_name['description'];
                            }
                            if ($value['item2'] == $value_name['name']) {
                                $item2 = $value_name['description'];
                            }
                        }
                        $value['id'] = $id;
                        $value['item1'] = $item1 ? $item1 : '';
                        $value['item2'] = $item2 ? $item2 : '';
                        $res_his = $this->HMHRMS->insertHistory($value, $time);
                        if (!$res_his['result']) {
                            throw new \Exception($res_his['data']);
                        }
                    }
                }
            }
            //コミット
            $this->HMHRMS->Do_commit();
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMHRMS->Do_rollback($rollbackTables);
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->HMHRMS->Do_close();

        $this->fncReturn($result);
    }

    //履歴部分データdelete
    public function fncDelete()
    {
        $tranStartFlg = FALSE;
        $this->HMHRMS = new HMHRMS();
        $result = array(
            'result' => FALSE,
            'data' => null,
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $data = $_POST['data'];
            } else {
                throw new \Exception("param error");

            }
            $time = date('YmdHis');
            $rollbackTables = array(
                'custom_values_history',
                'employee_sub_table_' . $data['employee_sub_table']['type'],
                'custom_values_' . $data['employee_sub_table']['type'],
            );
            $this->HMHRMS->Do_conn();
            //表自增
            $result_atin = $this->HMHRMS->alterIncrementHistory();
            if (false == $result_atin['result']) {
                throw new \Exception($this->E0005);
            }
            //トランザクション開始
            $this->HMHRMS->Do_transaction();
            $tranStartFlg = TRUE;
            //データを削除方法(EmployeeEditorController)
            $res_del = $this->delupdateData($data);
            if (!$res_del['result']) {
                throw new \Exception($res_del['data']);
            }
            //社員id取得
            $result_id = $this->HMHRMS->getEmpId($data['empId']);
            if (!$result_id['result']) {
                throw new \Exception($result_id['data']);
            }
            //employeeテーブルのid
            $id = $result_id['data'][0]['id'];
            //履歴テーブルのフィールド名
            $res_name = $this->HMHRMS->getDescription($data['employee_sub_table']['type']);
            if (!$res_name['result']) {
                throw new \Exception($res_name['data']);
            }
            //変更履歴 insert
            if (isset($data['custom_values_history'])) {
                foreach ($data['custom_values_history'] as $value) {
                    //データが空場合、追加しない
                    if ($value['update_before'] !== '') {
                        foreach ((array) $res_name['data'] as $value_name) {
                            if ($value_name['position'] == '0') {
                                $item1 = $value_name['description'];
                            }
                            if ($value['item2'] == $value_name['name']) {
                                $item2 = $value_name['description'];
                            }
                        }
                        $value['id'] = $id;
                        $value['item1'] = $item1 ? $item1 : '';
                        $value['item2'] = $item2 ? $item2 : '';
                        $res_his = $this->HMHRMS->insertHistory($value, $time);
                        if (!$res_his['result']) {
                            throw new \Exception($res_his['data']);
                        }
                    }
                }
            }
            //コミット
            $this->HMHRMS->Do_commit();
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMHRMS->Do_rollback($rollbackTables);
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->HMHRMS->Do_close();

        $this->fncReturn($result);
    }

    //履歴部分を追加する方法
    public function insHistoryData($params)
    {
        $result = array(
            'result' => false,
            'error' => '',
        );

        $time = date('YmdHis');

        $employee_sub_table = null;
        $custom_values = null;
        $type = null;
        $table_name = null;
        // $sub_table = null;
        $cv_table = null;

        try {
            $employee_sub_table = $params['employee_sub_table'];
            $type = $employee_sub_table['type'];
            $employee_id = $employee_sub_table['employee_id'];

            $table_name = array(
                'cv_table' => 'custom_values_' . $type,
                'sub_table' => 'employee_sub_table_' . $type,
                'sub_table_m' => 'EmployeeSubTable' . ucfirst($type),
                'type' => $type,
            );

            // $sub_table = $table_name['sub_table'];
            $cv_table = $table_name['cv_table'];

            $custom_values = $params['custom_values'];

            $seq = 1;
            $cris = array();

            //custom fieldsはposition順に取得します。
            $res = $this->HMHRMS->gettableColumnsOrder($table_name['type']);
            if (false == $res['result']) {
                throw new \Exception($this->E0005);
            }
            if (isset($res['data'])) {
                foreach ((array) $res['data'] as $value) {
                    array_push($cris, $value['id']);
                }
            }
            $isforallarr = array();
            $res = $this->HMHRMS->getfieldsid($table_name['type']);
            if (false == $res['result']) {
                throw new \Exception($this->E0005);
            }
            $isforallData = $res['data'];

            if (count((array) $isforallData) > 0 || 'punish' == $type) {
                foreach ((array) $isforallData as $value) {
                    array_push($isforallarr, $value['id']);
                }

                if ('punish' == $type) {
                    $cvsel = $this->HMHRMS->selectForImport($employee_sub_table, $custom_values, $cris, $table_name, $isforallarr);
                    if (false == $cvsel['result']) {
                        throw new \Exception($this->E0005);
                    }
                    $cvres = $cvsel['data'];
                    $seq = $cvres[0]['seq'];
                    if ('' == $seq) {
                        $seq = 1;
                    }
                } else {
                    $cvsel = $this->HMHRMS->selectForImport($employee_sub_table, $custom_values, $cris, $table_name, $isforallarr);
                    if (false == $cvsel['result']) {
                        throw new \Exception($this->E0005);
                    }
                    $cvres = $cvsel['data'];
                    if (count((array) $cvres) > 0) {
                        throw new \Exception($this->W0031);
                    }
                }
            }

            //SubTableインポート
            $stres = $this->HMHRMS->subIns($table_name['sub_table'], $employee_sub_table, $time);
            if (false == $stres['result']) {
                throw new \Exception($this->E0005);
            }

            $customized_id = $stres['data'];
            $cvparam = array(
                'cv_table' => $cv_table,
                'custom_values' => $custom_values,
                'type' => $type,
                'customized_id' => $customized_id,
                'cris' => $cris,
                'seq' => $seq,
                'time' => $time,
            );
            //customValueインポートsql
            $result = $this->HMHRMS->customValueIns($cvparam);
            if (false == $result['result']) {
                throw new \Exception($this->E0005);
            }

            //追加データ取得sql
            $result = $this->HMHRMS->customValueSel($cv_table, $customized_id);
            if (false == $result['result']) {
                throw new \Exception($this->E0005);
            }
            $rows = $result['data'];
            if (count((array) $rows) < 1) {
                throw new \Exception($this->E0005);
            }
            $result['ids'] = $rows;
            $result['estid'] = $customized_id;
            $result['employee_id'] = $employee_id;

            $result['result'] = true;
            $result['data'] = '';
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //履歴更新custom_values
    public function updCustomValueData($params)
    {
        $result = array(
            'result' => false,
            'error' => '',
        );

        try {
            $time = date('YmdHis');
            $employee_sub_table = $params['employee_sub_table'];
            $custom_values = $params['custom_values'];
            $updcustomized_ids = $employee_sub_table['estid'];

            $type = $employee_sub_table['type'];
            $table_name = array(
                'cv_table' => 'custom_values_' . $type,
                'sub_table' => 'employee_sub_table_' . $type,
                'sub_table_m' => 'EmployeeSubTable' . ucfirst($type),
                'type' => $type,
            );

            // $employee_id = $employee_sub_table['employee_id'];
            // $sub_table = $table_name['sub_table'];
            $cv_table = $table_name['cv_table'];
            $cris = array();
            //社内歴、懲戒歴（社内）、表彰歴、評価履歴、存在チェック

            //custom fieldsはposition順に取得します。
            $res = $this->HMHRMS->gettableColumnsOrder($table_name['type']);
            if (false == $res['result']) {
                throw new \Exception($this->E0005);
            }
            if (isset($res['data'])) {
                foreach ((array) $res['data'] as $value) {
                    array_push($cris, $value['id']);
                }
            }
            $isforallarr = array();
            $res = $this->HMHRMS->getfieldsid($table_name['type']);
            if (false == $res['result']) {
                throw new \Exception($this->E0005);
            }
            $isforallData = $res['data'];
            if (count((array) $isforallData) > 0) {
                foreach ((array) $isforallData as $value) {
                    array_push($isforallarr, $value['id']);
                }
                $rescv = $this->HMHRMS->selectForImport($employee_sub_table, $custom_values, $cris, $table_name, $isforallarr, $updcustomized_ids);
                if (false == $rescv['result']) {
                    throw new \Exception($this->E0005);
                }
                $cvres = $rescv['data'];
                if (count((array) $cvres) > 0) {
                    throw new \Exception($this->W0031);
                }
            }
            //SubTable更新
            $stres = $this->HMHRMS->subUpd($table_name['sub_table'], $employee_sub_table, $time);
            if (false == $stres['result']) {
                throw new \Exception($this->E0005);
            }
            $cvparam = array(
                'cv_table' => $cv_table,
                'custom_values' => $custom_values,
                'type' => $type,
                'customized_id' => $updcustomized_ids,
                'cris' => $cris,
                'time' => $time,
            );
            //customValue更新sql
            $cvres = $this->HMHRMS->customValueUpd($cvparam);
            if (false == $cvres['result']) {
                throw new \Exception($this->E0005);
            }
            $result['result'] = true;
            $result['error'] = '';
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //データを削除方法(EmployeeEditorController)
    public function delupdateData($params)
    {
        $result = array(
            'result' => false,
            'error' => '',
        );

        try {
            // $time = date('YmdHis');
            $employee_sub_table = $params['employee_sub_table'];
            $delcustomized_ids = $employee_sub_table['estid'];
            $type = $employee_sub_table['type'];
            $delTables = array(
                'cv_table' => 'custom_values_' . $type,
                'sub_table' => 'employee_sub_table_' . $type,
            );

            $cvDel = $this->HMHRMS->cvdelete($delTables['cv_table'], $delcustomized_ids);
            if (false == $cvDel['result']) {
                throw new \Exception($this->E0005);
            }
            $stDel = $this->HMHRMS->stdelete($delTables['sub_table'], $delcustomized_ids);
            if (false == $stDel['result']) {
                throw new \Exception($this->E0005);
            }
            $result['result'] = true;
            $result['error'] = '';
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

}
