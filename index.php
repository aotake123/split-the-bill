<?php

//共通変数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('ユーザー登録ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//変数定義
$email = '';
$pass = '';

//POST通信の値を受け取る
if(!empty($_POST)){
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    //未入力チェック
    validRequired($email,'email');
    validRequired($pass,'pass');
    //Emailの最大文字数チェック
    validMaxLen($email,'email');
    //Email形式チェック
    validEmail($email,'email');
    //Email重複チェック
    validEmailDup($email);

    //パスワードのバリデーションへ移行
    if(empty($err_msg)){
        //パスワードの最大最小文字数確認
        validMaxLen($pass,'pass');
        validMinLen($pass,'pass');
        //同値チェック
        //validSame($pass,$pass_re,'pass');
        //半角文字かチェック
        validHalf($pass,'pass');

        if(empty($err_msg)){
            debug('バリデーションOK');
            try{
            //DB接続準備
            $dbh = dbConnect();
            $sql = 'INSERT INTO users (email,pass,createDate) VALUE (:email,:pass,:createDate)';
            $data = array(':email' => $email, ':pass' => password_hash($pass,PASSWORD_DEFAULT),
                          ':createDate' => date('Y-m-s H:m:s'));

            //クエリ実行
            $stmt = queryPost($dbh,$sql,$data);
            debug('クエリ実行完了');

            //クエリ成功の場合
                if($stmt){
                    //ログイン有効期限（デフォルトを1時間とする）
                    $sesLimit = 60*60;
                    //最終ログイン日時を現在日時に
                    $_SESSION['login_date'] = time();
                    $_SESSION['login_limit'] = $sesLimit;
                    //ユーザーIDを格納
                    $_SESSION['user_id'] = $dbh->LastInsertId();

                    debug('セッション変数の中身：'.print_r($_SESSION,true));
                    header('Location:profEdit.php'); //マイページへ
                }

            } catch(Exception $e){
                error_log('エラーが発生しました'. $e->getMessage());
            }
        }
    }
}

?>

<?php
$siteTitle = 'トップページ | 割り勘シェアハウス';
require('head.php');
?>
   
    <!-- header -->

<?php
require('header.php');
?>
    
    <!-- main -->

    <main id="main">
     
     <div class="main_contents_wrap">

      <section class="contents_left">
        <div class="main_image">
          <div class="m_v_left">
            <p class="catch_copy">割り勘を簡単に<br />
              サクッと済ませて、<br />
              楽しい共同生活をしよう</p>
          </div>
          <div class="m_i_right">
            <img src="images/main_image.jpg"
             class="main_image_jpg" alt="main_image">
          </div>
        </div>
      </section>

      <section class="contents_right">
        <div class="contents_right_wrap">
        <form class="form" action="" method="post">
           <div class="form_title_wrap">
               <div class="form_title_subject"><h2>新規登録はこちら</h2></div>
           </div>
           <div class="form_main">
           <div class="form_main_wrap">
           
            <div class="area-msg">
                <?php if(!empty($_POST['common'])) echo $err_msg['common']; ?>
            </div>

            <label>
            <span class="form_subtitle">Email</span>
            <div class="form_input">
                <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
            </div>
            </label>
            <div class="area-msg">
                <?php if(!empty($_POST['email'])) echo $err_msg['email']; ?>
            </div>
            
            <label>
            <span class="form_subtitle">パスワード</span>
            <div class="form_input">
                <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
            </div>
            </label>
            <div class="area-msg">
                <?php if(!empty($_POST['pass'])) echo $err_msg['pass']; ?>
            </div>
            
            </div>
           </div>
           
            <div class="form_submit">            
                <input type="submit" value="今すぐ登録">
            </div>
            
        </form>
        </div>
        </section>
     
     </div>


    </main>

    <!-- footer -->

<?php
require('footer.php');
?>