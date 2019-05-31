<?php

//共通変数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('マイページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//==============================
// マイページ画面処理
//==============================
// 画面表示用データ取得
//==============================
//自分のデータのみを抜粋する為のIDデータを取得
$u_id = $_SESSION['user_id'];
//DBから所属グループデータを取得(グループ名表示用)
$dbGroupData = getMyGroup($_SESSION['user_id']);
//今何月かを示す関数
$m_id = date('n');

//各割り勘をリンク表示目的で判別する為のGETデータを格納
$s_id = (!empty($_GET['s_id'])) ? $_GET['s_id'] : '';
//ユーザーの個人データを取得
$dbFormData = getUser($_SESSION['user_id']);
//ユーザーの所属するグループの番号データを取得
$group_name = $dbFormData['group_name'];
//ユーザーの所属するグループメンバー全員の情報を取得（ユーザーが先頭）
$dbMemberData = getMemberdata($_SESSION['user_id'],$group_name);
//debug('グループメンバーデータの情報：'.print_r($dbMemberData,true));

//最新の割り勘表示機能
//==============================
//表示件数
$listSpan = 5;
//自分用の割り勘データを取得
$myBillData = getMyNewBills($_SESSION['user_id'],$group_name);

//最新の割り勘表示機能(追加搭載予定)
//==============================
//自分の最新コメントのDB回収配列
//自分の最新コメントのgetパラメータ

?>

<?php
require('head.php');
?>
   
    <!-- header -->

<?php
require('header.php');
?>

    <!-- main -->

    <main id="main">

        <div class="main_1colum_wide2">
        <form class="form" action="" method="post">
            <div class="form_title_wrap">
                <div class="form_title_subject line_blue"><h2>今月（<?php echo $m_id; ?>月）の <?php echo $dbGroupData[0]['data']; ?> の割り勘状況</h2></div>
            </div>
            <div class="form_main">
            <div class="form_main_wrap">

            <div class="area-msg">
                <?php if(!empty($_POST['common'])) echo $err_msg['common']; ?>
            </div>

            <?php
            foreach($dbMemberData as $key => $val):
            ?>

            <?php
            //会計の売掛け金を集計して取得
            $paySum1 = getSumTotalCost($val['id'],$val['group_name'],$val['isClaim']= 0);
            $paySum2 = getSumUserCost($val['id'],$val['group_name'],$val['isClaim']= 0);
            //会計の買い掛け金を集計して取得
            $catchSum1 = getSumTotalCost($val['id'],$val['group_name'],$val['isClaim']= 1);
            $catchSum2 = getSumUserCost($val['id'],$val['group_name'],$val['isClaim']= 1);
            //数字単体のデータ
            $paySum_range = $paySum1[0]['SUM(totalCost)']+$paySum2[0]['SUM(splitBill)'];
            $catchSum_range = $catchSum1[0]['SUM(totalCost)']+$catchSum2[0]['SUM(splitBill)'];
            $totalSum_range = -$paySum1[0]['SUM(totalCost)']-$paySum2[0]['SUM(splitBill)']+$catchSum1[0]['SUM(totalCost)']+$catchSum2[0]['SUM(splitBill)'];
            $paySum_graph = ($paySum_range/15000)*200;
            $catchSum_graph = ($catchSum_range/15000)*200;
            $totalSum_graph = ($totalSum_range/15000)*200;
            //15000円を超える会計が発生した場合、グラフ横幅を最大幅で固定
            if($paySum_range > 15000){ $paySum_graph = 200;}
            if($catchSum_range > 15000){ $catchSum_graph = 200;}
            if($totalSum_range > 15000){ $totalSum_graph = 200;}
            //totalが0円を下回る会計が発生した場合、値を反転させる
            if($totalSum_range < 0){
                $totalSum_graph = -($totalSum_range/15000)*200;
            }
            

            ?>

            <div class="prof_whole">
                <div class="prof_whole_left_graph">
                    <div class="prof_whole_wrap">
                        <!-- メンバー名 --><?php echo $val['nickname']; ?>
                    </div>
                </div>
                <div class="prof_whole_right">
                    <div class="img_wrap">
                        <div class="img_upload_left">
                            <img src="<?php echo $val['pic']; ?>" alt="profile" class="img_prev">
                        </div>

                        <div class="graph">
                            <div class="graph1">
                                <div class="graph_left">
                                    <p class="graph_title"> 割勘した金額
                                    </p>
                                    <p class="graph_money"><!-- 総額ここから --><?php print_r($paySum1[0]['SUM(totalCost)']+$paySum2[0]['SUM(splitBill)']); ?>円</p><!-- ここまで -->

                                </div>
                                <div class="graph_right">
                                    <div class="graph1_main" style="width: <?php echo $paySum_graph ?>px;"></div>
                                </div>
                            </div>
                            <div class="graph2">
                                <div class="graph_left">
                                    <p class="graph_title"> 割勘された金額</p>
                                    <p class="graph_money"><!-- 総額ここから --><?php print_r($catchSum1[0]['SUM(totalCost)']+$catchSum2[0]['SUM(splitBill)']); ?>円</p><!-- ここまで -->
                                </div>
                                <div class="graph_right">
                                    <div class="graph2_main" style="width: <?php echo $catchSum_graph; ?>px;"></div>
                                </div>
                            </div>
                            <div class="graph3">
                                <div class="graph_left">
                                    <p class="graph_title">収支の合計</p>
                                    <p class="graph_money"><!-- 総額ここから --><strong><?php print_r(-$paySum1[0]['SUM(totalCost)']-$paySum2[0]['SUM(splitBill)']+$catchSum1[0]['SUM(totalCost)']+$catchSum2[0]['SUM(splitBill)']); ?></strong>円</p><!-- ここまで -->
                                </div>
                                <div class="graph_right">
                                    <div class="graph3_main" style="width: <?php echo $totalSum_graph ?>px; <?php if($totalSum_range < 0){ echo 'background: #FF0000;';} ?>"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="p_w_r_right"></div>
                </div>



            </div>


            <?php
            endforeach;
            ?>
            
            
            </div>
            </div>
            
            <div class="prof_whole">
                <p class="form_last_comment"><a href="payList.php">月毎の割り勘状況を確認する</a></p>
            </div>

        </form>
        
        <form class="form" action="" method="post">
            <div class="form_title_wrap">
                <div class="form_title_subject line_blue"><h2>最新のあなたが入力/編集した割り勘</h2></div>
            </div>
            <div class="form_main">
            <div class="form_main_wrap">

            <div class="reco_wrap">
            <table class="record">
                <thead><tr>
                <th class="reco_date">日時</th>
                <th class="reco_title">割り勘タイトル</th>
                <th class="reco_total">総額</th>
                <th class="reco_main">割り勘を</th>
                <!-- <th class="reco_sub">あなた以外の関係者</th> -->
                <th class="reco_detail">詳細</th>
                </tr>

                <?php
                foreach($myBillData as $key => $val):
                ?>            

                <tr>
                <td class="reco_item"><?php echo $val['g_year']; ?>/<?php echo $val['g_month']; ?>/<?php echo $val['g_date']; ?></td>
                <td class="reco_item"><?php echo $val['title']; ?></td>
                <td class="reco_item"><?php echo $val['totalCost']; ?>円</td>
                <td class="reco_item">
                <?php 
                    if($val['isClaim'] == 1){
                        echo 'された側';
                    }else{ 
                        echo 'した側';
                    } ?></td>
                <!--  <td class="reco_item"></td> -->
                <td class="reco_item"><a href="payDetail.php?s_id=<?php echo $val['id']; ?>">詳細を見る</a></td>
                </tr>

                <?php
                endforeach;
                ?> 

                </thead>
            </table>           
        </div>

             
            </div>
            </div>

            <div class="prof_whole">
                <p class="form_last_comment"><a href="payList.php">全ての割り勘を一覧で確認する</a></p>
            </div>
                                    
        </form>
        
        <!-- コメント機能実装まで凍結
        <form class="form" action="" method="post">
            <div class="form_title_wrap">
                <div class="form_title_subject line_blue"><h2>最新のコメント</h2></div>
            </div>
            <div class="form_main">
            <div class="form_main_wrap">

            <div class="reco_wrap">
            <table class="record">
                <thead><tr>
                <th class="reco_date">日時</th>
                <th class="reco_title">割り勘タイトル</th>
                <th class="reco_total">総額</th>
                <th class="reco_main">記入者</th>
                <th class="reco_sub">内容</th>
                <th class="reco_detail">詳細</th>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あなた</td>
                <td class="reco_item">これ○円の間違いでは</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">つなお</td>
                <td class="reco_item">タンパク質が欲しい</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あやね</td>
                <td class="reco_item">今日はいい風が吹いている</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">大木</td>
                <td class="reco_item">肉が好き</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>


                </thead>
            </table>           
        </div>

 

            
            </div>
            </div>

            <div class="prof_whole">
                <p class="form_last_comment"><a href="payEdit.php">全てのコメントを一覧で確認する</a></p>
            </div>
                                    
        </form>
        -->


        </div>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>