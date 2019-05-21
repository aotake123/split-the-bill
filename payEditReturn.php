<?php

//共通関数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('割り勘申請ページ（される側の人用）');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//==============================
// 割り勘申請（される側）画面処理
//==============================
//画面表示用データ取得
//==============================
//DBから割り勘項目データを取得
//ユーザーの所属するグループの「人数」「ユーザー番号」を取得


//POST通信の有無を確認
if(!empty($_POST)){
	debug('POST送信が有ります');
	debug('POST送信の中身：'.print_r($_POST,true));
	debug('FILE情報：'.print_r($_FILE,true));

	//POSTされた値を変数に代入
	$title = $_POST['title'];
	$item = $_POST['item'];
	$totalCost = $_POST['totalCost'];
	//グループの人数分変数を定義→値を代入
	for($i=1; $i > 4; ++$i){
		$userCost[$i] = $_POST[$i]['userCost'];
	}
	//請求/支払いフラグ（0が請求）
	$isClaim = 0;

	$receipt = $_POST['receipt'];
	$comment = $_POST['comment'];
	$users = $_SESSION['user_id'];
	//画像アップロード
	$receipt = uploadImg($_FILES['receipt'],'receipt');

	//未入力チェック
	validRequired($title,'title');
	validRequired($item,'item');
	validRequired($totalCost,'totalCost');
	validRequired($receipt.'receipt');
	validRequired($comment,'comment');

	if(empty($err_msg)){
		debug('バリデーション開始。');
		//バリデーション開始
		//割り勘タイトル
			//文字数制限
		validMaxLen($title,'title');
		//割り勘項目名
			//未選択
		//割り勘総額
			//30000円以上の精算は割り勘できません。
			//金額上限、ゼロ禁止
			//合計金額が違う
		//各々の割り勘金額画面
			//清算式(ボタンを押さないと出てこないようにする)
		$splitbill = $totalCost / 4;	//余り算を使う
			//金額上限、ゼロ禁止
		//コメント文
		validMaxLen($comment, 'comment');

		if(empty($err_msg)){
			debug('バリデーションOKです。');
			//例外処理
			try{
				//DB接続関数
				$dbh = dbConnect();
				$sql1 = 'INSERT INTO payment (title,item,totalCost,isClaim,receipt,comment,users) 
				values (:title,:item,:totalCost,:isClaim,:receipt,:comment,:users)';
				$sql2 =	"UPDATE payment userCost1, userCost2, userCost3, userCost4";
				$data1 = array(':title' => $title, ':item'=> $item, ':totalCost' => $totalCost, 
				':isClaim' => $isClaim, ':receipt' => $receipt, ':comment' => $comment, ':users' => $users);
				$data2 = array(":userCost1" => $userCost1, ":userCost2" => $userCost2, 
				":userCost3" => $userCost3, ":userCost4" => $userCost4);

				debug('SQL：'.$sql);
				debug('流し込みデータ：'.print_r($data,true));
				//クエリ実行関数
				$stmt1 = queryPost($dbh,$sql,$data);
				$stmt2 = queryPost($dbh,$sql,$data);
				//クエリ成功
				if($stmt1 && $stmt2){
					$_SESSION['msg_success'] = SUC04;
					debug('マイページへ遷移します。');
					header("Location:mypage.php");
				}

			} catch(Exception $e){
				error_log('エラー発生：' . $e->getMessage());
				$err_msg['common'] = MSG07;
			}
		}
	}
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

?>




<?php
$siteTitle = '割り勘申請画面（される側） | 割り勘シェアハウス';
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
               <div class="form_title_subject line_pink"><h2>メンバーが買ったものを割り勘で自分が支払う</h2></div>
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
                <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
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
                <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">新規項目追加はこちら
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
           
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">自分の支払う合計金額</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
               			<div class="p_w_r_left_option1">
                <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
                		</div>
                		<div class="p_w_r_left_option2">円
						</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
           
			
            <div class="prof_whole">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">支払う相手と金額</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
            			<div class="img_upload_left">
            				<img src="" alt="ログイン者の写真" class="img_prev">
						</div>
            			<div class="img_upload_right equal_btn">
							<div class="img_upload_btn">
         						選択した全員に対し<br />
         						均等に支払いをする
          						<input type="hidden" name="MAX_FILE_SIZE" size="3145728">
          					</div>
            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
			
			 <div class="prof_whole">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form"><!-- タイトル無し --></div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
             			<div class="img_upload_left">
            				<!--  profile写真 -->
            				<img src="" alt="profile" class="img_prev">
						</div>
           				<div class="img_upload_check">
            				<input type="checkbox" name="" value="" class="pay_checkbox">
            			</div>
            			<div class="img_upload_right pay_separate_option">
              			<div class="p_w_r_left_option1">
               				<input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
                		</div>
                		<div class="p_w_r_left_option2">円
						</div>
          				</div>
            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>

			 <div class="prof_whole">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form"><!-- タイトル無し --></div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
             			<div class="img_upload_left">
            				<!--  profile写真 -->
            				<img src="" alt="profile" class="img_prev">
						</div>
           				<div class="img_upload_check">
            				<input type="checkbox" name="" value="" class="pay_checkbox">
            			</div>
            			<div class="img_upload_right pay_separate_option">
              			<div class="p_w_r_left_option1">
               				<input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
                		</div>
                		<div class="p_w_r_left_option2">円
						</div>
          				</div>
            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>

			 <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form"><!-- タイトル無し --></div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
             			<div class="img_upload_left">
            				<!--  profile写真 -->
            				<img src="" alt="profile" class="img_prev">
						</div>
           				<div class="img_upload_check">
            				<input type="checkbox" name="" value="" class="pay_checkbox">
            			</div>
            			<div class="img_upload_right pay_separate_option">
              			<div class="p_w_r_left_option1">
               				<input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
                		</div>
                		<div class="p_w_r_left_option2">円
						</div>
          				</div>
            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			
			
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">レシート画像</div>
            			<div class="p_w_l_attention_self">任意</div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
            			<div class="img_upload_left">
            				<img src="" alt="profile" class="img_prev">
						</div>
            			<div class="img_upload_right">
							<div class="img_upload_btn">
         						アップロードしたい画像を選択
          						<input type="hidden" name="MAX_FILE_SIZE" size="3145728">
          					</div>
           					<p class="img_comment">※イメージ画像・写真を設定できます</p>
            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>

            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">概要/コメント</div>
            			<div class="p_w_l_attention_self">任意</div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
						<textarea name="comment" rows="6" cols="36" class="pay_comment"></textarea>	
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
            
            </div>
           </div>
           

           
            <div class="form_submit form_wide_option_submit">
                <input type="submit" value="メンバーに支払いを申請する">
            </div>
           
        </form>
            
     </div>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>