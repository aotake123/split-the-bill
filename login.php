<?php

//共通変数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('ログインページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//変数定義
$email = '';
$pass = '';
$pass_save = '';

//POST通信の値を受け取る
if(!empty($_POST)){
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_save = $_POST['pass_save'];
    
    //emailの形式チェック
    validEmail($email,'email');
    //emailの最大文字数チェック
    validMaxLen($email,'email');
    
    //パスワードの半角英数字チェック
    validHalf($pass,'pass');
    //パスワードの最大文字数チェック
    validMaxLen($pass,'pass');
    //パスワードの最小文字数チェック
    validMinLen($pass,'pass');
    
    //未入力チェック
    validRequired($email,'email');
    validRequired($pass,'pass');
    
    //バリデーションOK
    if(empty($err_msg)){
        debug('バリデーションOKです');
        
         try{
            //DB接続準備
            $dbh = dbConnect();
            $sql = 'SELECT pass,id FROM users WHERE email = :email AND isDelete = 0';
            $data = array('email' => $email);
            //クエリ実行
            $stmt = queryPost($dbh,$sql,$data);
            //クエリの結果の値を取得
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
             
            debug('クエリ結果の中身：'.print_r($result,true));
             
            //パスワード照合
            if(!empty($result) && password_verify($pass,array_shift($result))){
                debug('パスワードがマッチしました。');
                
                //ログイン有効期限設定
                $sesLimit = 60*60;
                //最終ログイン日時を現在日時に
                $_SESSION['login_date'] = time();
                
                //ログイン保持にチェックがある場合
                if($pass_save){
                    debug('ログイン保持にチェックがあります。');
                    //ログイン有効期限を30日にしてセット
                    $_SESSION['login_limit'] = $sesLimit * 24 * 30;
                }else{
                    debug('ログイン保持にチェックはありません。');
                    //次回からログイン保持しないので、ログイン有効期限を1時間後にセット
                    $_SESSION['login_limit'] = $sesLimit;
                }
                //ユーザーIDを格納
                $_SESSION['user_id'] = $result['id'];
                
                debug('セッション変数の中身：'.print_r($_SESSION,true));
                debug('マイページに遷移します。');
                header("Location:mypage.php");
            }else{
                debug('パスワードがアンマッチです。');
                $err_msg['common'] = MSG09;
            }
            
        } catch(Exception $e){
            error_log('エラー発生：' . $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }     
}

?>

<?php
$siteTitle = 'ログイン | 割り勘シェアハウス';
require('head.php');
?>
   
    <!-- header -->

<?php
require('header.php');
?>

    <!-- main -->

    <main id="main">

      <div class="main_1colum">
        <form class="form" action="" method="post">
           <div class="form_title_wrap">
               <div class="form_title_subject"><h2>ログイン</h2></div>
           </div>
           <div class="form_main">
           <div class="form_main_wrap">
           
            <div class="area-msg">
                <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
            </div>

            <label>
            <span class="form_subtitle">Email</span>
            <div class="form_input">
                <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
            </div>
            </label>
            <div class="area-msg">
                <?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?>
            </div>
            
            <label>
            <span class="form_subtitle">パスワード</span>
            <div class="form_input">
                <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
            </div>
            </label>
            <div class="area-msg">
                <?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?>
            </div>
            <label>
                <span class="form_subtitle">
                <input type="checkbox" name="pass_save">次回から自動ログイン</span>
            </label>
            
            </div>
           </div>
           
            <div class="form_submit">
                <input type="submit" value="ログイン">
            </div>

            <div class="form_submit form_remind">
            	<a href="passRemindSend.php">
            	<div class="form_passRemind">
					パスワードをお忘れの方はこちら
				</div></a>
			</div>
           
        </form>
            
     </div>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>