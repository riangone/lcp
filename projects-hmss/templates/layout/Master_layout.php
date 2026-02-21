<!-- 最新:jquery.js、jquery-ui.js、jquery-ui.css、jquery.min.js -->
<!DOCTYPE html>
<html style="overflow: auto; height: 100%; border: none; padding: 0px; margin: 0px;">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- 20181011 YIN INS S -->
    <meta http-equiv="x-ua-compatible" content="IE=11">
    <meta http-equiv="x-ua-compatible" content="IE=EmulateIE11">
    <!-- 20181011 YIN INS E -->
    <!-- 20181030 YIN INS S -->
    <!-- 禁止浏览器阅读缓存 -->
    <!--20250818 UPD START-->
    <meta http-equiv="Pragma" content="no-store">
    <meta http-equiv="Cache-Control" content="no-store">
    <meta http-equiv="Expires" content="0">
    <!--20250818 UPD END-->
    <!-- 20181030 YIN INS E -->

    <title><?php echo $title_for_layout; ?></title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <?php
    /**
     * @var Cake\View\View $this
     */
    // 可変情報表示
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');

    //css start
    
    echo $this->Html->css('jquery/jquery-ui') . "\n";
    echo $this->Html->css('jquery/ui.jqgrid') . "\n";
    //20240605 zhangxiaolei ins s
    echo $this->Html->css('jquery/themes/default/style') . "\n";
    //20240605 zhangxiaolei ins e
    echo $this->Html->css('Master/Master') . "\n";

    //css end
    ?>

    <?php
    //js start
    echo $this->Html->script(array('jquery/jquery'));
    echo $this->Html->script(array('jquery/jquery-ui'));
    echo $this->Html->script(array('jquery/jquery.layout'));
    echo $this->Html->script(array('jquery/jquery.jqGrid'));
    echo $this->Html->script(array('jquery/i18n/grid.locale-ja'));

    echo $this->Html->script(array('jquery/jquery-ui/i18n/jquery.ui.datepicker-ja'));
    //2013/11/21 zhenghuiyun update start
    echo $this->Html->script(array('jquery/jquery.numeric'));
    //2013/11/21 zhenghuiyun update end
    
    echo $this->Html->script(array('jquery/jquery.jstree'));
    echo $this->Html->script(array('jquery/jquery.blockUI'));

    echo $this->Html->script(array('base/namespace'));
    echo $this->Html->script(array('base/panel'));
    //2014/08/28 zhenghuiyun insert start
    echo $this->Html->script(array('base/panel_dialog'));
    //2014/08/28 zhenghuiyun insert end
    echo $this->Html->script(array('common/jqgrid'));

    //20131030 luchao add start
    echo $this->Html->script(array('common/ecl'));
    //20131030 luchao add end
    
    echo $this->Html->script(array('common/clsComFnc'));
    echo $this->Html->script(array('common/ajax'));
    echo $this->Html->script(array('common/MessageBox'));
    echo $this->Html->script(array('common/clsCreateCsv'));
    echo $this->Html->script(array('common/file'));

    echo $this->Html->script(array('Master/Master'));
    //20131105 zxl add start
    echo $this->Html->script(array('jquery/shortcut'));
    //20131105 zxl add end
    //js end
    //20140514 zxl add start
    // echo $this -> Html -> script(array('jquery/ui-tabs-paging'));
    //20140514 zxl add end
    //20140801 zxl add start
    echo $this->Html->script(array('jquery/jquery.contextmenu'));
    //20140801 zxl add end
    ?>
</head>

<body class="ui-layout-container">
    <?php
    // echo $this->Js->writeBuffer();
    ?>
</body>

</html>
