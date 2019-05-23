<?php

//共通関数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('割り勘申請ページ（する側の人用）');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//==============================
// 割り勘申請（する側）画面処理
//==============================
// 画面表示用データ取得
//==============================
//各割り勘を表示目的で判別する為のGETデータを格納
$s_id = (!empty($_GET['s_id'])) ? $_GET['s_id'] : '';
//DBから割り勘固有データを取得
$dbBillData = (!empty($s_id)) ? getSplitbill($_SESSION['user_id'], $s_id) : null;
//割り勘の項目データを取得
$dbItemData = getItem();
//ユーザーの個人データを取得
$dbFormData = getUser($_SESSION['user_id']);
//ユーザーの所属するグループの番号データを取得
$group_name = $dbFormData['group_name'];
//ユーザーの所属するグループの「人数」を取得
$dbMemberCount = getMemberCount($_SESSION['user_id'],$group_name);
//ユーザーの所属するグループメンバーの情報を取得
$dbMemberData = getMemberdata($_SESSION['user_id'],$group_name);
//新規登録画面か編集画面かの判別用フラグ
$edit_flg = (empty($dbBillData)) ? false :true;

//debug('割り勘ID：'.$s_id); //登録後に付けられるデータの為、開発前は保留
//debug('割り勘固有のDBデータ：'.print_r($dbBillData,true)); //登録ができるようになったら解放
//debug('割り勘項目データ：'.print_r($dbItemData,true)); //OK
//debug('ユーザーの所属するグループの人数：'.print_r($dbMemberCount,true)); //OK
//debug('ユーザーの所属するグループの情報：'.print_r($dbMemberData,true)); //OK

//パラメータ改ざんチェック
//==============================
//GETパラメータはあるが、改ざんされている（URLをいじくった）場合、正しい対局データが取れないのでマイページへ遷移させる
if(!empty($s_id) && empty($dbFormData)){
	debug('GETパラメータの対局IDが違います。');
	header("Location:mypage.php"); //マイページへ
}

//POST通信の有無を確認
if(!empty($_POST)){
	debug('POST送信が有ります');
	debug('POST送信の中身：'.print_r($_POST,true));
	//debug('FILE情報：'.print_r($_FILES,true));

	//POSTされた値を変数に代入
	$title = $_POST['title'];
	$item = $_POST['item'];
	$totalCost = $_POST['totalCost'];
	$comment = $_POST['comment'];
	$users = $_SESSION['user_id'];

	//中間テーブル照合用のIDを作成
	$id2 = makerandkey();	//割り勘テーブル側に代入
	$payment = $id2;	//中間テーブル側に代入

	//グループの人数分変数を定義→値を代入
	for($i=0; $i<$dbMemberCount; ++$i){
		$userCost[$i] = $_POST["userCost$i"];
		$userIsClaim[$i] = $_POST["userIsClaim$i"];
	}

	//請求/支払いフラグ（0が請求,1が支払い）
	$isClaim = 0;

	//画像アップロード
	$receipt = ( !empty($_FILES['receipt']['name']) ) ? uploadImg($_FILES['receipt'],'receipt') : '';
	//debug('FILES情報：'.print_r($_FILES,true));
	//画像をPOSTしてない（登録していない）が、DBには既に登録されている場合、DBのパスを入れて画像を表示する
	$receipt = ( empty($receipt) && !empty($dbFormData['receipt']) ) ? $dbFormData['receipt'] : $receipt;
	//debug('receipt情報：'.print_r($receipt,true));
	

	//未入力チェック
	validRequired($title,'title');
	validRequired($item,'item');
	validRequired($totalCost,'totalCost');

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
			//金額上限、ゼロ禁止
		//コメント文
		validMaxLen($comment, 'comment');

		if(empty($err_msg)){
			debug('バリデーションOKです。');
			//例外処理
			try{
				//DB接続関数
				$dbh = dbConnect();

				//編集画面の場合はUPDATE文、新規登録画面の場合はINSERT文を作成
				if($edit_flg){
					debug('DB更新です。');
						$sql = 'UPDATE payment SET title = :title, item = :item, 
						totalCost = :totalCost, isClaim = :isClaim, receipt = :receipt,
						comment = :comment, users = :users, group_name = :group_name, id2 = :id2';
						$data = array(':title' => $title, ':item'=> $item, ':totalCost' => $totalCost,
						':isClaim' => $isClaim, ':receipt' => $receipt, ':comment' => $comment, 
						':users' => $_SESSION['user_id'], ':group_name' => $group_name, ':id2' => $payment);
					}else{
						debug('DB新規登録です。');
						$sql = 'INSERT INTO payment (title,item,totalCost,isClaim,receipt,comment,users,group_name,id2,createDate) 
						values (:title,:item,:totalCost,:isClaim,:receipt,:comment,:users,:group_name,:id2,:createDate)';
						$data = array(':title' => $title, ':item' => $item, ':totalCost' => $totalCost,
						':isClaim' => $isClaim, ':receipt' => $receipt, ':comment' => $comment, 
						':users' => $_SESSION['user_id'], ':group_name' => $group_name, ':id2' => $payment, ':createDate' => date('Y-m-d H:i:s'));
						}
					debug('SQL：'.$sql);
					debug('流し込みデータ：'.print_r($data,true));
					//クエリ実行関数
					$stmt = queryPost($dbh,$sql,$data);
					debug('クエリ実行 完了');

				foreach($dbMemberData as $key => $val):
	
				//中間テーブルに割り勘の各該当者ごとに1レコードのデータを挿入
				if($edit_flg){
					debug('DB更新です。');
						$sql = 'UPDATE usersPayment SET users = :users, payment = :payment,isClaim = :isClaim, splitBill = :splitBill';
						$data = array(':users' => $val['id'], 'payment' => $payment, ':isClaim' => $userIsClaim[$key],':splitBill' => $userCost[$key]);
					}else{
					debug('DB新規登録です。');
						$sql = 'INSERT INTO usersPayment (users,payment,isClaim,splitBill,createDate) VALUES (:users,:payment,:isClaim,:splitBill,:createDate)';
						$data = array(':users' => $val['id'], 'payment' => $payment, ':isClaim' => $userIsClaim[$key],':splitBill' => $userCost[$key],':createDate' => date('Y-m-d H:i:s'));
					}
					//debug('SQL：'.$sql);
					//debug('流し込みデータ：'.print_r($data,true));
					//クエリ実行関数
					$stmt = queryPost($dbh,$sql,$data);
					debug('クエリ実行 完了');

				endforeach;
	

				//クエリ成功
				if($stmt){
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
$siteTitle = '割り勘申請画面（する側） | 割り勘シェアハウス';
require('head.php');
?>
   
    <!-- header -->

<?php
require('header.php');
?>

    <!-- main -->

    <main id="main">

      <div class="main_1colum_wide2">
        <form class="form" action="" method="post" enctype="multipart/form-data">
           <div class="form_title_wrap">
               <div class="form_title_subject line_blue"><h2>自分で買ったものをメンバーへ割り勘申請する</h2></div>
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
                <input type="text" name="title" value="<?php echo getFormData('title'); ?>">
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

							<label class="<?php if(!empty($err_msg['item'])) echo 'err'; ?>">
							 <select name="item">
                                <option value="0" <?php if(empty(getFormData('item'))) echo 'selected="selected"'; ?>>▶︎選択してください</option>
                                <?php
                                foreach($dbItemData as $key => $val){
                                ?>
                                <option value="<?php echo $val['id']?>" <?php if(getFormData('item') == $val['id']) echo 'selected="selected"' ?>><?php echo $val['data'] ?></option>
                                <?php echo $val['data']; ?>
                                <?php
                                }
                                ?>
                            </select>
							<!-- 新規項目追加はこちら -->

            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
           
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">買い物の合計金額</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
               			<div class="p_w_r_left_option1">
                <input type="text" name="totalCost" value="<?php if(!empty($_POST['totalCost'])) echo $_POST['totalCost']; ?>">
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
            			<div class="p_w_l_form">あなたが負担する金額</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
            			<div class="img_upload_left">
            				<div><img src="<?php echo getFormData('pic'); ?>" alt="ログイン者の写真" class="img_prev"></div>
							<div><?php echo getFormData('nickname'); ?></div>
						</div>
						<div class="img_upload_check">
            				<input type="checkbox" name="" value="" class="pay_checkbox">
            			</div>

            			<div class="img_upload_right pay_separate_option">
              			<div class="p_w_r_left_option1">
               				<input type="text" name="userCost0" value="<?php if(!empty($_POST["userCost0"])) echo $_POST['userCost0']; ?>">
							<input type="hidden" name="userIsClaim0" value="0">

                		</div>
                		<div class="p_w_r_left_option2">円
						</div>

            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>

			<?php
                foreach($dbMemberData as $key => $val):
            ?>

			 <div class="prof_whole">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form"><?php if($key == 0 ){ echo '割り勘を請求する<br />相手と金額'; } ?><!-- タイトル無し --></div>
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
            				<input type="checkbox" name="" value="" class="pay_checkbox">
            			</div>
            			<div class="img_upload_right pay_separate_option">
              			<div class="p_w_r_left_option1">
               				<input type="text" name="userCost<?php echo $key+1; ?>" value="<?php if(!empty($_POST["userCost$key+1"])) echo $_POST["userCost$key+1"]; ?>">
							<input type="hidden" name="userIsClaim<?php echo $key+1; ?>" value="1">

                		</div>
                		<div class="p_w_r_left_option2">円
						</div>
          				</div>
            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>

			<?php 
				debug('情報配列のキー取得確認：'.print_r($key,true));
			?>


			<?php 
				endforeach;
			?>			
			
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
            				<img src="<?php echo getFormData('receipt'); ?>" alt="profile" class="img_prev">
						</div>
            			<div class="img_upload_right">
							<div class="img_upload_btn">
         						<!-- アップロードしたい画像を選択 -->
          						<input type="hidden" name="MAX_FILE_SIZE" size="3145728">
								<input type="file" name="receipt" value="<?php echo getFormData('receipt'); ?>">
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
						<textarea name="comment" rows="6" cols="36" class="pay_comment"><?php echo getFormData('comment');?></textarea>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
            
            </div>
           </div>
           

           
            <div class="form_submit form_wide_option_submit">
                <input type="submit" value="メンバーに割り勘を申請する">
            </div>
           
        </form>
            
     </div>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>