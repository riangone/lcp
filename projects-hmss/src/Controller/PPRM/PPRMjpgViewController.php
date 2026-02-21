<?php
/**
 * 説明：
 *
 *
 * @author wangying
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           GSDL　　　　　　　　
 * * ------------------------------------------------------------------------------------------
 */
namespace App\Controller\PPRM;

use App\Controller\AppController;
use App\Model\PPRM\PPRMjpgView;

class PPRMjpgViewController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'PPRMjpgView_layout';
        $this->render('/PPRM/PPRMjpgView/index', $layout);
    }

    public function fncImgPath1()
    {
        $result = array();
        $postData = $_POST["data"]["request"];

        try {
            $PPRMjpgView = new PPRMjpgView();
            $result = $PPRMjpgView->fncImgPath1($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncImgPath2()
    {
        $result = array();
        $postData = $_POST["data"]["request"];

        try {
            $PPRMjpgView = new PPRMjpgView();
            $result = $PPRMjpgView->fncImgPath2($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}
