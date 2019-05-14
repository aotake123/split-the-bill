<?php

//共通変数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('パスワード認証キー発行ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証は、ログインができない人が使う為行わない

//変数定義
$email = "";

//POST通信の値を取得する
$email = $_POST['email'];
//メール送信


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
           
           <p class="form_introduction">パスワードを再設定する際は、メールアドレスを記入し「認証メールを送る」を押してください。<br />
           送信されたメールに記載された認証キーを、後ほどご案内するフォームに入力すると、<br />
           新しいパスワードの入力ページへと移動します。</p>

           <div class="form_main">
           <div class="form_main_wrap">
           
            <div class="area-msg">
                <?php if(!empty($_POST['common'])) echo $err_msg['common']; ?>
            </div>

            <label>
            <span class="form_subtitle form_wide_option">Email</span>
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
                <input type="submit" value="認証メールを送る">
            </div>
           
        </form>
            
     </div>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>