<?php

//共通関数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('割り勘申請ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//==============================
// 割り勘申請画面処理
//==============================
// 画面表示用データ取得
//==============================

//請求/支払いフラグ（0が請求,1が支払い）GETパラメータの有無で判別
$isClaim = (!empty($_GET['isc_id'])) ? 1 : 0;
//各割り勘詳細画面向けリンクを表示する為のGETデータを格納
$s_id = (!empty($_GET['s_id'])) ? $_GET['s_id'] : '';
//DBから割り勘固有データを取得
$dbBillFormData = (!empty($s_id)) ? getSplitbill($_SESSION['user_id'], $s_id) : null;
//新規登録画面か編集画面かの判別用フラグ
$edit_flg = (empty($dbBillFormData)) ? false :true;

//パラメータ改ざんチェック
//==============================
//GETパラメータはあるが、改ざんされている（URLを手入力でいじくった）場合、
//正しい割り勘データが取れないのでマイページへ遷移させる
if(!empty($s_id) && empty($dbBillFormData)){
	debug('GETパラメータの対局IDが違います。');
	header("Location:mypage.php"); //マイページへ
}

//割り勘の項目データを取得
$dbItemData = getItem();
//ユーザーの個人データを取得
$dbFormData = getUser($_SESSION['user_id']);
//ユーザーの所属するグループの番号データを取得
$group_name = $dbFormData['group_name'];
//ユーザーの所属するグループの「人数」を取得
$dbMemberCount = getMemberCount($_SESSION['user_id'],$group_name);
//ユーザーの所属するグループメンバー全員の情報を取得（ユーザーが先頭）
$dbMemberData = getMemberdata($_SESSION['user_id'],$group_name);

//debug('割り勘ID：'.$s_id); //DB登録後に付けられるデータ
debug('割り勘項目データ：'.print_r($dbItemData,true)); //OK
//debug('ユーザーの所属するグループの人数：'.print_r($dbMemberCount,true)); //OK
//debug('ユーザーの所属するグループの情報：'.print_r($dbMemberData,true)); //OK



//POST通信の有無を確認
if(!empty($_POST)){
	debug('POST送信が有ります');
	debug('POST送信の中身：'.print_r($_POST,true));
	//debug('FILE情報：'.print_r($_FILES,true));

	//POSTされた値を変数に代入
	$title = $_POST['title'];
	$item = $_POST['item'];
	$totalCost = (int)$_POST['totalCost'];
	debug('$totalCostの中身：'.print_r($totalCost,true));

	$comment = $_POST['comment'];
	$users = $_SESSION['user_id'];
	$g_year = $_POST['g_year'];
	$g_month = $_POST['g_month'];
    $g_month = sprintf('%02d',$g_month);
	$g_date = $_POST['g_date'];
	$g_month = sprintf('%02d',$g_month);

	//中間テーブル照合用のIDを作成
	$id2 = makerandkey();	//割り勘テーブル側に代入
	$payment = $id2;	//中間テーブル側に代入
	//割り勘のバリデーション計算用の変数を作成
	$countCost = 0;

	//グループの人数分変数を定義→値を代入
	for($i=0; $i<$dbMemberCount; ++$i){
		$userCost[$i] = $_POST["userCost$i"];
		$userIsClaim[$i] = $_POST["userIsClaim$i"];
		//debug('$_POST["userCost$i"]の中身：'.print_r($_POST["userCost$i"],true));
		//debug('$_POST["userIsClaim$i"]の中身：'.print_r($_POST["userIsClaim$i"],true));
		//$userIsClaim[$i]がNULLでエラー判定になるのを回避する処理
		if($userIsClaim[$i] === NULL){
			$userIsClaim[$i] = 0;
		}
		if($userCost[$i] === NULL){
			$userCost[$i] = 0;
		}
		global $countCost;
		$countCost += $userCost[$i];

	}


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
			validMaxLen3($title,'title');
		//割り勘項目名
			//未選択
			validSelect($item,'item');
		//割り勘総額
			//合計金額が違う
			validCost($totalCost,$countCost,'totalCost');
			//半角数字チェック
			validMath($totalCost,'totalCost');
			//最大文字数超過
			validMaxLen($totalCost,'totalCost');
		//コメント文
			//最大文字数超過
			validMaxLen($comment,'comment');
		//割り勘個別入力欄
		for($i=0; $i<$dbMemberCount; ++$i){
			$userCost[$i] = $_POST["userCost$i"];
			//半角数字か(入力値がある場合のみ)
			if(!empty($userCost[$i])){
				validMath($userCost[$i],"userCost$i");
			}
		}


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
						comment = :comment, users = :users, group_name = :group_name, id2 = :id2,
						g_year = :g_year, g_month = :g_month, g_date = :g_date';
						$data = array(':title' => $title, ':item'=> $item, ':totalCost' => $totalCost,
						':isClaim' => $isClaim, ':receipt' => $receipt, ':comment' => $comment, 
						':users' => $_SESSION['user_id'], ':group_name' => $group_name, ':id2' => $payment,
						':g_year' => $g_year, ':g_month' => $g_month, ':g_date' => $g_date);
					}else{
						debug('DB新規登録です。');
						$sql = 'INSERT INTO payment (title,item,totalCost,isClaim,receipt,comment,users,group_name,id2,createDate,g_year,g_month,g_date) 
						values (:title,:item,:totalCost,:isClaim,:receipt,:comment,:users,:group_name,:id2,:createDate,:g_year,:g_month,:g_date)';
						$data = array(':title' => $title, ':item' => $item, ':totalCost' => $totalCost,
						':isClaim' => $isClaim, ':receipt' => $receipt, ':comment' => $comment, 
						':users' => $_SESSION['user_id'], ':group_name' => $group_name, ':id2' => $payment, ':createDate' => date('Y-m-d H:i:s'),
						':g_year' => $g_year, ':g_month' => $g_month, ':g_date' => $g_date);
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
				<?php
				if(empty($_GET['isc_id'])) {
				?>
               <div class="form_title_subject line_blue"><h2>自分が支払った会計の割り勘を、メンバーに依頼する</h2></div>
				<?php
				}else{
				?>
					<div class="form_title_subject line_pink"><h2>メンバーが支払ったものに対して、自分の割り勘分を支払う</h2></div>
				<?php
				}
				?>
           </div>
           <div class="form_main">
           <div class="form_main_wrap">

            <div class="err_msg">
                <?php if(!empty($_POST['common'])) echo '・' .$err_msg['common'] .'<br/>'; ?>
				<?php if(!empty($err_msg['title'])) echo '・' .$err_msg['title'] .'<br/>'; ?>
				<?php if(!empty($err_msg['item'])) echo '・' .$err_msg['item'] .'<br/>'; ?>
				<?php if(!empty($err_msg['totalCost'])) echo '・' .$err_msg['totalCost'] .'<br/>'; ?>
            </div>
            
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">割り勘タイトル</div>
            			<div class="p_w_l_attention_on">必須</div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
                <input type="text" name="title" value="<?php echo getBillFormData('title'); ?>">
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>

            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">割り勘をした日</div>
            			<div class="p_w_l_attention_on">必須</div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
						<div>
							 <select name="g_year">
                                <option value="2019" <?php if(empty(getBillFormData('g_year'))) echo 'selected="selected"'; ?>>2019</option>
                                <option value="2020" <?php if(getBillFormData('g_year') == 2020) echo 'selected="selected"'; ?>>2020</option>
                                <option value="2021" <?php if(getBillFormData('g_year') == 2021) echo 'selected="selected"'; ?>>2021</option>
                                <option value="2022" <?php if(getBillFormData('g_year') == 2022) echo 'selected="selected"'; ?>>2022</option>
                                <option value="2023" <?php if(getBillFormData('g_year') == 2023) echo 'selected="selected"'; ?>>2023</option>
                                <option value="2024" <?php if(getBillFormData('g_year') == 2024) echo 'selected="selected"'; ?>>2024</option>
                            </select>
						</div>
						<div>年</div>
						<div>
							<select name="g_month">
									<option value="1" <?php if(getBillFormData('g_month') == 1) { echo 'selected="selected"'; }else if(date('n') == 1){ echo 'selected="selected"'; } ?>>1</option>
									<option value="2" <?php if(getBillFormData('g_month') == 2) { echo 'selected="selected"'; }else if(date('n') == 2){ echo 'selected="selected"'; } ?>>2</option>
									<option value="3" <?php if(getBillFormData('g_month') == 3) { echo 'selected="selected"'; }else if(date('n') == 3){ echo 'selected="selected"'; } ?>>3</option>
									<option value="4" <?php if(getBillFormData('g_month') == 4) { echo 'selected="selected"'; }else if(date('n') == 4){ echo 'selected="selected"'; } ?>>4</option>
									<option value="5" <?php if(getBillFormData('g_month') == 5) { echo 'selected="selected"'; }else if(date('n') == 5){ echo 'selected="selected"'; } ?>>5</option>
									<option value="6" <?php if(getBillFormData('g_month') == 6) { echo 'selected="selected"'; }else if(date('n') == 6){ echo 'selected="selected"'; } ?>>6</option>
									<option value="7" <?php if(getBillFormData('g_month') == 7) { echo 'selected="selected"'; }else if(date('n') == 7){ echo 'selected="selected"'; } ?>>7</option>
									<option value="8" <?php if(getBillFormData('g_month') == 8) { echo 'selected="selected"'; }else if(date('n') == 8){ echo 'selected="selected"'; } ?>>8</option>
									<option value="9" <?php if(getBillFormData('g_month') == 9) { echo 'selected="selected"'; }else if(date('n') == 9){ echo 'selected="selected"'; } ?>>9</option>
									<option value="10" <?php if(getBillFormData('g_month') == 10) { echo 'selected="selected"'; }else if(date('n') == 10){ echo 'selected="selected"'; } ?>>10</option>
									<option value="11" <?php if(getBillFormData('g_month') == 11) { echo 'selected="selected"'; }else if(date('n') == 11){ echo 'selected="selected"'; } ?>>11</option>
									<option value="12" <?php if(getBillFormData('g_month') == 12) { echo 'selected="selected"'; }else if(date('n') == 12){ echo 'selected="selected"'; } ?>>12</option>
                            </select>
						</div>
						<div>月</div>
						<div>
                           <select name="g_date">
                                <?php
                                for($i = 1; $i < 32; $i++){
                                ?>
                                    <option value="<?php echo $i; ?>" <?php if(getBillFormData('g_date') == $i ) { echo 'selected="selected"'; }else if(date('j') == $i){ echo 'selected="selected"'; } ?>><?php echo $i; ?></option>;
                                <?php
                                }
                                ?>
                            </select>
						</div>
						<div>日</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>

           
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">割り勘項目名</div>
            			<div class="p_w_l_attention_on">必須</div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">

							<label class="<?php if(!empty($err_msg['item'])) echo 'err'; ?>">
							 <select name="item">
                                <option value="0" <?php if(empty(getBillFormData('item'))) echo 'selected="selected"'; ?>>▶︎選択してください</option>
                                <?php
                                foreach($dbItemData as $key => $val){
                                ?>
                                <option value="<?php echo $val['id']?>" <?php if(getBillFormData('item') == $val['id']) echo 'selected="selected"' ?>><?php echo $val['data'] ?></option>
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
            			<div class="p_w_l_attention_on">必須</div>
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
            				<div><img src="<?php if(!empty($val['pic'])){ echo $val['pic']; }else{ echo 'images/noimage.jpeg';}  ?>" alt="profile" class="img_prev"></div>
							<div><?php echo $val['nickname']; ?></div>
						</div>
           				<div class="img_upload_check">
						   <!-- JSで入力補助機能を付けた際に使うチェックボックス -->
            				<!-- <input type="checkbox" name="" value="" class="pay_checkbox"> -->
            			</div>
            			<div class="img_upload_right pay_separate_option">
              			<div class="p_w_r_left_option1">
               				<input type="text" name="userCost<?php echo $key; ?>" value="<?php if(!empty($_POST["userCost$key"])) echo $_POST["userCost$key"]; ?>">
							<input type="hidden" name="userIsClaim<?php echo $key; ?>"
							 value="<?php
							 if($key === 0){
								if(empty($_GET['isc_id'])) { echo 1; }else{ echo 0; }; 
							}else{
								if(empty($_GET['isc_id'])) { echo 1; }else{ echo 0; }; 
							}
							 ?>">

                		</div>
                		<div class="p_w_r_left_option2">円
						</div>
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
            			<div class="p_w_l_attention_self">任意</div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
            			<div class="img_upload_left">
            				<img src="<?php if(!empty(getBillFormData('receipt'))){ echo getBillFormData('receipt');}else{ echo "images/noimage2.jpg"; } ?>" alt="profile" class="img_prev">
						</div>
            			<div class="img_upload_right">
							<div class="img_upload_btn">
         						<!-- アップロードしたい画像を選択 -->
          						<input type="hidden" name="MAX_FILE_SIZE" size="3145728">
								<input type="file" name="receipt" value="<?php echo getBillFormData('receipt'); ?>">
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
						<textarea name="comment" rows="6" cols="36" class="pay_comment"><?php echo getBillFormData('comment');?></textarea>
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