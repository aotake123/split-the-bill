<?php

//共通関数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('プロフィール編集ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//==============================
// 画面処理
//==============================

//DBからユーザーデータを回収(そもそもの書き変える対象データの為）
$dbFormData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報：'.print_r($dbFormData,true));

//新規登録か編集か判別用フラグ
$edit_flg = (empty($dbFormData)) ? false : true;
//DBから所属グループデータを取得
$dbGroupData = getGroup();
debug('dbGroupData情報：'.print_r($dbGroupData,true));


//POST通信の有無を確認
if(!empty($_POST)){
	debug('POST通信があります。');
	debug('POST情報：'.print_r($_POST,true));

	//POSTされた値を変数に代入
	$nickname = $_POST['nickname'];
	$group_name = $_POST['group_name'];
	$email = $_POST['email'];

	//画像アップロードし、パスを文字列で格納
	$picture = (!empty($_FILES['pic']['name'])) ? uploadImg($_FILES['pic'],'pic') : NULL;
	debug('FILES情報：'.print_r($_FILES,true));
	//画像をPOSTしてない（登録していない）が、DBには既に登録されている場合、DBのパスを入れて画像を表示する
	$picture = ( empty($picture) && !empty($dbFormData['pic']) ) ? $dbFormData['pic'] : $picture;

	//DBの情報と入力情報が異なる場合にバリデーションを行う
	if($dbFormData['nickname'] !== $nickname){
		debug('picture情報：'.print_r($picture,true));

		//ニックネームの最大文字数チェック
		validMaxLen2($nickname,'nickname');
		debug('picture情報：'.print_r($picture,true));
	}
	if($dbFormData['group_name'] !== $group_name){
				//所属グループ（未選択は認めない、新規項目による画面遷移有り）
				//デモ段階の為未実装。一般公開やサービス段階で再考
				//validSelect($group_name,'group_name');
				debug('err_msg情報：'.print_r($err_msg,true));
	}
	if($dbFormData['email'] !== $email){
				//Emailの最大文字数チェック
				validMaxLen($email,'email');
				//emailの形式チェック
				validEmail($email,'email');
				validRequired($email,'email');
				debug('err_msg情報：'.print_r($err_msg,true));

		if(empty($err_msg['email'])){
			//Emailの重複チェックする
			validEmailDup($email,'email');
		}
	}

	if(empty($err_msg)){
			//バリデーションOKです。
			//例外処理
			try{
				//DB接続関数
				$dbh = dbConnect();
				//if($edit_flg = 0){
					$sql = 'UPDATE users SET nickname = :nickname, group_name = :group_name, pic = :pic, email = :email
									WHERE id = :u_id';
					$data = array(':nickname' => $nickname, ':group_name' => $group_name, ':pic' => $picture, ':email' => $email, ':u_id' => $_SESSION['user_id']);
				//クエリ実行関数
					$stmt = queryPost($dbh,$sql,$data);
					//クエリ成功の場合
					if($stmt){
						$_SESSION['msg_success'] = SUC02;
						debug('マイページへ遷移します。');
						header('Location:mypage.php'); //マイページへ
					}
			} catch(Exception $e){
				error_log('エラー発生：' . $e->getMessage());
				$err_msg['common'] = MSG07;
			}
	}
}

?>

<?php
$siteTitle = 'プロフィール編集画面 | 割り勘シェアハウス';
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
               <div class="form_title_subject"><h2>プロフィール編集</h2></div>
           </div>
           <div class="form_main">
           <div class="form_main_wrap">

            <div class="area-msg form_wide_option">
				<?php if(!empty($err_msg['common'])) echo '・' .$err_msg['common'] .'<br/>'; ?>
				<?php if(!empty($err_msg['nickname'])) echo '・' .$err_msg['nickname'] .'<br/>'; ?>
                <?php if(!empty($err_msg['group_name'])) echo '・' .$err_msg['group_name'] .'<br/>'; ?>
                <?php if(!empty($err_msg['email'])) echo '・' .$err_msg['email'] .'<br/>'; ?>
            </div>
            
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">ニックネーム</div>
            			<div class="p_w_l_attention_on">必須</div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
                <input type="text" name="nickname" value="<?php echo getFormData('nickname'); ?>">
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
            
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">プロフィール画像</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
            			<div class="img_upload_left">
							<img src="<?php if(!empty(getFormData('pic'))){ echo getFormData('pic'); }else{ echo 'images/noimage.jpeg';}  ?>" alt="profile" class="img_prev"></a>
						</div>
            			<div class="img_upload_right">
							<div class="img_upload_btn">
         						<!-- アップロードしたい画像を選択-->
								  <input type="hidden" name="MAX_FILE_SIZE" size="3145728">
								  <input type="file" name="pic" value="<?php echo getFormData('pic'); ?>">
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
            			<div class="p_w_l_form">所属グループ名</div>
            			<div class="p_w_l_attention_on">必須</div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">

								<label class="<?php if(!empty($err_msg['group_name'])) echo 'err'; ?>">
							 <select name="group_name">
                                <option value="0" <?php if(empty(getFormData('group_name'))) echo 'selected="selected"'; ?>>▶︎選択してください</option>
                                <?php
                                foreach($dbGroupData as $key => $val){
                                ?>
                                <option value="<?php echo $val['id']?>" <?php if(getFormData('group_name')) echo 'selected="selected"'; ?>><?php echo $val['data'] ?></option>
                                <?php echo $val['data']; ?>
                                <?php
                                }
                                ?>
                            </select>
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($err_msg['group_name'])) echo $err_msg['group_name']; ?>
												</div>
												
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
			
			<div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">Email</div>
            			<div class="p_w_l_attention_on">必須</div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
                <input type="text" name="email" value="<?php echo getFormData('email'); ?>">
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
			
			<div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">パスワード</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
						<div>
							<a href="passEdit.php">
								<div class="form_passRemind">
									パスワードを変更する
								</div>
							</a>
						</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
           
           	<div class="prof_whole">
				<p class="form_last_comment"><a href="cancel.php">割り勘シェアハウスを退会する</a></p>
			</div>
            
            </div>
           </div>
           

           
            <div class="form_submit form_wide_option_submit">
                <input type="submit" value="基本情報を更新する">
            </div>
           
        </form>
            
     </div>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>