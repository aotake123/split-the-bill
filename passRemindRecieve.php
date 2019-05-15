<?php

//共通変数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('パスワード認証キー発行ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
//require('auth.php');

//==============================
//パスワード再発行認証キー
//==============================


//変数定義
$email = "";
//POST通信の値を取得する
$email = $_POST['email'];
//バリデーション
if(!empty($_POST)){
    validRequired($email,'email');
    
    //バリデーションOK
    //if(empty($err_msg)){
        
        //例外処理
        //try{
            //DB接続関数
            //$dbh = dbConnect();
            //$sql = '';
            //$//data = array();
            //クエリー実行関数
            //queryPost($dbh,$sql,$data);
            
            //メール送信関数
            //sendMail
            
            
        //} catch(Exception $e){
         //   error_log('エラー発生：' . $e->getMessage());
        //}
        
    //}
    
}


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

      <div class="main_1colum_wide">
        <form class="form" action="" method="post">
           <div class="form_title_wrap">
               <div class="form_title_subject"><h2>パスワード再発行</h2></div>
           </div>

          <p class="form_introduction">ご指定のメールアドレス宛に送らせて頂いた<br />
           「パスワード再発行認証」メール内にある「認証キー」をご入力ください。</p>

           
           <div class="form_main">
           <div class="form_main_wrap">
           
            <div class="area-msg">
                <?php if(!empty($_POST['common'])) echo $err_msg['common']; ?>
            </div>

            <label>
            <span class="form_subtitle form_wide_option">認証キー</span>
            <div class="form_input form_wide_option">
                <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
            </div>
            </label>
            <div class="area-msg">
                <?php if(!empty($_POST['email'])) echo $err_msg['email']; ?>
            </div>
            
            </div>
           </div>
           
            <div class="form_submit form_wide_option_submit">
                <input type="submit" value="パスワードを再発行する">
            </div>
           
        </form>
            
     </div>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>