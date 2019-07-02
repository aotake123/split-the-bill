<?php

//共通変数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('割り勘一覧画面');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//==============================
// 割り勘一覧画面処理
//==============================
//現在いるページのGETパラメータを取得(デフォルトは1ページ目)
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;
//自分のデータのみを抜粋する為のIDデータを取得
$u_id = $_SESSION['user_id'];
// パラメータに不正な値が入っているかチェック
if(!is_int((int)$currentPageNum)){
    error_log('エラー発生:指定ページに不正な値が入りました');
    header("Location:mypage.php"); //トップページへ
  }

//DBから所属グループデータを取得(グループ名表示用)
$dbGroupData = getMyGroup($_SESSION['user_id']);
//今何月かを示す関数
$m_id = date('n');
$y_id = date('Y');

//ユーザーの個人データを取得
$dbFormData = getUser($_SESSION['user_id']);
    debug('ユーザーの個人データを取得：'.print_r($dbFormData,true));
//ユーザーの所属するグループの番号データを取得
$group_name = $dbFormData['group_name'];
//ユーザーの所属するグループメンバー全員の情報を取得（ユーザーが先頭）
$dbMemberData = getMemberdata($_SESSION['user_id'],$group_name);
    //debug('グループメンバーデータの情報：'.print_r($dbMemberData,true));

//ページネーション関連
//==============================
//表示件数
$listSpan = 20;
//現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan); //1ページ目なら(1−1)*20 = 0、2ページ目なら(2−1)*20 = 20
//グループ内の全ての割り勘データを取得
$allBillData = getAllBills($currentMinNum,$group_name,$m_id,$listSpan);
    debug('グループ内の全ての割り勘データを取得：'.print_r($allBillData,true));

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
                <div class="form_title_subject line_blue"><h2>TERRACE厚木の<?php echo $m_id; ?>月の割り勘一覧</h2></div>
            </div>
            <div class="form_main">
            <div class="form_main_wrap">

            <div class="reco_wrap reco_option">
            <table class="record">
                <thead><tr>
                <th class="reco_personal">メンバー名</th>
                <th class="reco_money_month">収支総額</th>
                <th class="reco_count">申請した件数</th>
                <!-- <th class="reco_list">詳細</th> -->
                </tr>

                <?php
               foreach($dbMemberData as $key => $val):
               ?>

                <?php
                //会計の売掛け金を集計して取得
                $paySum1 = getSumTotalCost($val['id'],$val['group_name'],$val['isClaim']= 0,$y_id,$m_id);
                $paySum2 = getSumUserCost($val['id'],$val['group_name'],$val['isClaim']= 0,$y_id,$m_id);
                //会計の買い掛け金を集計して取得
                $catchSum1 = getSumTotalCost($val['id'],$val['group_name'],$val['isClaim']= 1,$y_id,$m_id);
                $catchSum2 = getSumUserCost($val['id'],$val['group_name'],$val['isClaim']= 1,$y_id,$m_id);
                //数字単体のデータ
                $paySum_range = $paySum1[0]['SUM(totalCost)']+$paySum2[0]['SUM(splitBill)'];
                $catchSum_range = $catchSum1[0]['SUM(totalCost)']+$catchSum2[0]['SUM(splitBill)'];
                $totalSum_range = -$paySum1[0]['SUM(totalCost)']-$paySum2[0]['SUM(splitBill)']+$catchSum1[0]['SUM(totalCost)']+$catchSum2[0]['SUM(splitBill)'];
                ?>

                <tr>
                <td class="reco_item"><!-- メンバー名 --><?php echo $val['nickname']; ?></td>
                <td class="reco_item"><?php echo $totalSum_range; ?>円</td>
                <td class="reco_item">
                <?php $MyBillCount = getMyBillCount($val['id'],$group_name); ?>
                <?php echo $MyBillCount; ?>件
                </td>
                <!-- <td class="reco_item"><a href="payList.php?u_id=<?php echo $val['id']; ?>">一覧を見る</a></td> -->
                </tr>

                <?php
                endforeach;
                ?>

                </thead>
            </table>           
        </div>


        <div class="search-title">
            <div class="search-left">
                <span class="total-num"><?php echo sanitize($allBillData['total']); ?></span>件の割り勘履歴が見つかりました
            </div>
            <div class="search-right">
                <span class="num"><?php echo $currentMinNum+1; ?></span> - <span class="num"><?php echo $currentMinNum+$listSpan; ?></span>件 / <span class="num"><?php echo sanitize($allBillData['total']); ?></span>件中
            </div>
        </div>



        <div class="reco_wrap">
            <table class="record">
                <thead><tr>
                <th class="reco_date">日時</th>
                <th class="reco_title">割り勘タイトル</th>
                <th class="reco_total">総額</th>
                <th class="reco_request">割り勘の申請者</th>
                <!-- <th class="reco_billcount">割り勘の<br />関係者数</th> -->
                <th class="reco_detail">詳細</th>
                </tr>

                <?php
               foreach($allBillData['data'] as $key => $val):
                //debug('$allBillDataを取得：'.print_r($allBillData,true));
               ?>

                <tr>
                <td class="reco_item"><?php echo $val['g_year']; ?>/<?php echo $val['g_month']; ?>/<?php echo $val['g_date']; ?></td>
                <td class="reco_item"><?php echo $val['title']; ?></td>
                <td class="reco_item"><?php echo $val['totalCost']; ?>円</td>
                <td class="reco_item">
                <?php $request = getUser($val['users'],$group_name);
                //debug('ユーザーの個人データを取得：'.print_r($request,true)); ?>
                <?php echo $request['nickname']; ?>
                (<?php 
                    if($val['isClaim'] == 1){
                        echo 'された側';
                    }else{ 
                        echo 'した側';
                    } ?>)
                </td>
                <!-- <td class="reco_item">
                <?php $splitBillCount = splitBillCount($val['id']);
                    debug('割り勘の関係者人数データを取得：'.print_r($splitBillCount,true));
                ?>
                <?php echo $splitBillCountUp; ?>人
                </td> -->
                <td class="reco_item"><a href="payDetail.php?s_id=<?php echo $val['id']; ?>">詳細を見る</a></td>
                </tr>

                <?php
                endforeach;
                ?>


                </thead>
            </table>
            <?php pagination($currentPageNum, $allBillData['total_page'], $group_name); ?>       
        </div>


            
            </div>
            </div>

                                    
        </form>


        </div>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>