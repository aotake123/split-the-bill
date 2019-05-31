<?php

//共通関数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('割り勘詳細ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//==============================
// 画面処理
//==============================
//画面表示用データ取得
//==============================
//請求/支払いフラグ（0が請求,1が支払い）GETパラメータの有無で判別
$isClaim = (!empty($_GET['isc_id'])) ? 1 : 0;
//GETパラメータ取得
$s_id = (!empty($_GET['s_id'])) ? $_GET['s_id'] : '';
//DBから割り勘固有データを取得
$dbBillData = (!empty($s_id)) ? getSplitbill($_SESSION['user_id'], $s_id) : null;
debug('割り勘データ：'.print_r($dbBillData,true));

//パラメータ改ざんチェック
//==============================
//GETパラメータはあるが、改ざんされている（URLをいじくった）場合、正しい対局データが取れないのでマイページへ遷移させる
if(!empty($s_id) && empty($dbBillData)){
	debug('GETパラメータのIDが違います。');
	header("Location:mypage.php"); //マイページへ
}

//割り勘の項目（実際に表示する日本語抜粋）を取得
$dbItemName = getItemName($s_id);
	//debug('割り勘の項目データ：'.print_r($dbItemName,true));
//ユーザーの個人データを取得
$dbFormData = getUser($_SESSION['user_id']);
//ユーザーの所属するグループの番号データを取得
$group_name = $dbFormData['group_name'];
//ユーザーの所属するグループの「人数」を取得
$dbMemberCount = getMemberCount($_SESSION['user_id'],$group_name);
//ユーザーの所属するグループメンバー全員の情報を取得（ユーザーが先頭）
$dbMemberData = getMemberdata($_SESSION['user_id'],$group_name);
	//debug('グループメンバー全員のデータ：'.print_r($dbMemberData,true));
?>

<?php
$siteTitle = '割り勘詳細画面 | 割り勘シェアハウス';
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
               <div class="form_title_subject line_blue"><h2><?php echo $dbBillData['g_year']; ?>/<?php echo $dbBillData['g_month']; ?>/<?php echo $dbBillData['g_date']; ?> 「<?php echo $dbBillData['title']; ?>」詳細情報</h2></div>
           </div>
           <div class="form_main">
           <div class="form_main_wrap">

            <div class="area-msg">
                <?php if(!empty($_POST['common'])) echo $err_msg['common']; ?>
            </div>
            
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">割り勘タイトル</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
                		<span class="detail_contents"><?php echo $dbBillData['title']; ?></span>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
           
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">割り勘項目名</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
                		<p class="detail_contents"><?php echo $dbItemName[0]['data']; ?></p>
             		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
           
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">割り勘前の支出総額</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
               			<div class="p_w_r_left_option1">
                		<p class="detail_contents"><?php echo $dbBillData['totalCost']; ?>円</p>
 
                		</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>			


			<?php
                foreach($dbMemberData as $key => $val):
            ?>

			<?php
                if($key === 0 && $isClaim === 1){
				}else{
            ?>


			 <div class="prof_whole">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">

						<?php
						if($key === 0){
							echo 'あなたが負担する金額'; 
						}else if($key === 1){
							if(empty($_GET['isc_id'])){
								echo '割り勘を請求する<br />相手と金額'; 
						   }else if(!empty($_GET['isc_id'])){
								echo '支払う金を渡す<br />相手と金額'; 
						   }
						}
						?>

						<!-- タイトル無し -->
						</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">																																					
            		<div class="img_wrap">
             			<div class="img_upload_left">
            				<!--  profile写真 -->
            				<div><img src="<?php echo $val['pic']; ?>" alt="profile" class="img_prev"></div>
							<div><?php echo $val['nickname']; ?></div>
						</div>
           				<div class="img_upload_check">
						   <!-- JSで入力補助機能を付けた際に使うチェックボックス -->
            				<!-- <input type="checkbox" name="" value="" class="pay_checkbox"> -->
            			</div>
            			<div class="img_upload_right pay_separate_option pay_detail pay_detail2">
						<?php $dbBillView = getBillView($val['id'],$s_id); ?>
						<?php //debug('割り勘データ：'.print_r($dbBillView,true)); ?>
            			<?php if(!empty($dbBillView[0]['splitBill'])){ echo $dbBillView[0]['splitBill']; }else{ echo 0;} ?>円
            			</div>

            			<div class="img_upload_right pay_separate_option">
          				</div>
            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
				</div>

			<?php 
				}
				//debug('情報配列のキー取得確認：'.print_r($key,true));
			?>


			<?php 
				endforeach;
			?>			
			
			
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">レシート画像</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
            			<div class="img_upload_left">
							<img src="<?php if(!empty($dbBillData['receipt'])){ echo $dbBillData['receipt']; }else{ echo 'images/noimage.jpeg';}  ?>" alt="profile" class="img_prev_detail"></a>
						</div>
            			<div class="img_upload_right">
            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>

            <div class="prof_whole">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">概要/コメント</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left textarea_option">
					<?php echo $dbBillData['comment']; ?>	
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
            
            </div>
           </div>
           
           	<div class="prof_whole">
				<!-- <p class="form_last_comment"><a href="payEditSeparate.php?s_id=<?php echo $s_id; ?>">割り勘の内容を編集する</a></p> -->
			</div>

        </form>
		
		<!--
		<form class="form" action="" method="post">
           <div class="form_title_wrap">
               <div class="form_title_subject line_blue"><h2>割り勘に対するコメント</h2></div>
           </div>
           <div class="form_main">
           <div class="form_main_wrap">

            <div class="area-msg">
                //<?php if(!empty($_POST['common'])) echo $err_msg['common']; ?>
			</div>
			
			<div class="comment_form">
				<div class="form_input form_input_option1">
					<input type="text" name="email" value="//<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
				</div>
				<div class="comment_button">
					<input type="submit" value="送信" id="comment_submit">
				</div>
			</div>
			
			
            <div class="area-msg">
                //<?php if(!empty($_POST['email'])) echo $err_msg['email']; ?>
            </div>
			
			<div class="comment_wrap">
				<div class="comment_left">
					<img src="" alt="" class="img_prev">
				</div>
				<div class="comment_right">
					<div class="comment_right_top">
						<div class="comment_name">大木</div>
						<div class="comment_time">2019/04/12 18:16</div>
					</div>
					<div class="comment_right_bottom">
						<p class="comment_main_contents">割り勘で浮いたお金で、カントリーマアムを買おう！</p>
					</div>
				</div>
			</div>
 
            
            </div>
           </div>
           			           
		</form>
		-->


     </div>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>