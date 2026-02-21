<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;

class FrmSnapShotBatController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComDoRefresh');
    }

    public function index()
    {
        $this->render('index', 'FrmSnapShotBat_layout');
    }

    public function fncActionClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            //'バッチﾌｧｲﾙ起動
            $RefreshSql = array();

            $RefreshSql[0] = "BEGIN dbms_snapshot.refresh('M27A01','cf'); END;";
            $RefreshSql[1] = "BEGIN dbms_snapshot.refresh('M27A02','cf'); END;";
            $RefreshSql[2] = "BEGIN dbms_snapshot.refresh('M27A04','cf'); END;";
            $RefreshSql[3] = "BEGIN dbms_snapshot.refresh('M27AM1','cf'); END;";
            $RefreshSql[4] = "BEGIN dbms_snapshot.refresh('M27M01','cf'); END;";
            $RefreshSql[5] = "BEGIN dbms_snapshot.refresh('M27M08','cf'); END;";
            $RefreshSql[6] = "BEGIN dbms_snapshot.refresh('M28M68','cf'); END;";
            $RefreshSql[7] = "BEGIN dbms_snapshot.refresh('M28M71','cf'); END;";
            $RefreshSql[8] = "BEGIN dbms_snapshot.refresh('M28T13','cf'); END;";
            $RefreshSql[9] = "BEGIN dbms_snapshot.refresh('M28T14','cf'); END;";
            $RefreshSql[10] = "BEGIN dbms_snapshot.refresh('M29F01','cf'); END;";
            $RefreshSql[11] = "BEGIN dbms_snapshot.refresh('M29MA4','cf'); END;";
            $RefreshSql[12] = "BEGIN dbms_snapshot.refresh('M41B02','cf'); END;";
            $RefreshSql[13] = "BEGIN dbms_snapshot.refresh('M41C01','cf'); END;";
            $RefreshSql[14] = "BEGIN dbms_snapshot.refresh('M41C02','cf'); END;";
            $RefreshSql[15] = "BEGIN dbms_snapshot.refresh('M41E10','cf'); END;";
            $RefreshSql[16] = "BEGIN dbms_snapshot.refresh('M41E11','cf'); END;";
            $RefreshSql[17] = "BEGIN dbms_snapshot.refresh('M41E12','cf'); END;";
            $RefreshSql[18] = "BEGIN dbms_snapshot.refresh('M41E13','cf'); END;";
            $RefreshSql[19] = "BEGIN dbms_snapshot.refresh('M41E15','cf'); END;";
            $RefreshSql[20] = "BEGIN dbms_snapshot.refresh('M41E30','cf'); END;";
            $RefreshSql[21] = "BEGIN dbms_snapshot.refresh('M41E31','cf'); END;";
            $RefreshSql[22] = "BEGIN dbms_snapshot.refresh('M41E68','cf'); END;";
            $RefreshSql[23] = "BEGIN dbms_snapshot.refresh('HBILLSITOD','cf'); END;";
            $RefreshSql[24] = "BEGIN dbms_snapshot.refresh('HRIKUJI','cf'); END;";
            $RefreshSql[25] = "BEGIN dbms_snapshot.refresh('HKASOUMEISAI','cf'); END;";
            $RefreshSql[26] = "BEGIN dbms_snapshot.refresh('M_DATARECEP','cf'); END;";
            $RefreshSql[27] = "BEGIN UPDATE KEIRI_RECEP SET    BEF_CSVPUT_DT = (SELECT T_BEF_GET_DT FROM M_DATARECEP WHERE TABLE_ID = '3') WHERE  TABLE_ID = '1'  AND    BEF_CSVPUT_DT IS NULL;END;";
            $RefreshSql[28] = "BEGIN dbms_snapshot.refresh('M27M18','cf'); END;";
            $RefreshSql[29] = "BEGIN dbms_snapshot.refresh('M27U01','cf'); END;";
            $RefreshSql[30] = "BEGIN dbms_snapshot.refresh('M27U02','cf'); END;";
            $RefreshSql[31] = "BEGIN dbms_snapshot.refresh('M29FZ6','cf'); END;";
            $RefreshSql[32] = "BEGIN dbms_snapshot.refresh('M29FZ7','cf'); END;";

            $result = $this->ClsComDoRefresh->DoRefresh($RefreshSql);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $result['result'] = TRUE;
            $result['data'] = "";
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }
}
