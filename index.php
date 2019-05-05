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
$pass_re = '';

//POST通信の値を受け取る
if(!empty($_POST)){
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_re = $_POST['pass_re'];

    //未入力チェック
    validRequired($email,'email');
    validRequired($pass,'pass');
    //validRequired($pass_re,'pass_re');
    //Emailの最大文字数チェック
    validMaxLen($email,'email');
    //Email形式チェック
    validEmail($email,'email');
    //Email重複チェック（セッション関数出来次第）
    //validEmailDup();

    //パスワードのバリデーションへ移行
    if(!empty($err_msg)){
        //パスワードの最大最小文字数確認
        validMaxLen($pass,'pass');
        validMinLen($pass,'pass');
        //同値チェック
        //validSame($pass,$pass_re,'pass');
        //半角文字かチェック
        validHalf($pass,'pass');

        if(!empty($err_msg)){
            try{
            //DB接続準備
            $dbh = dbConnect();
            $sql = 'INSERT INTO (email,pass,create_date) VALUE (:email,:pass,:create_date)';
            $data = array(':email' => $email, ':pass' => password_hash($pass,PASSWORD_DEFAULT),
                          ':create_date' => date('Y-m-s H:m:s'));

            //クエリ実行
            $stmt = queryPost($dbh,$sql,$data);

            //クエリ成功の場合
                if($stmt){
                    //ログイン有効期限（デフォルトを1時間とする）
                    $sesLimit = 60*60;
                    //最終ログイン日時を現在日時に
                    $_SESSION['login_date'] = time();
                    $_SESSION['login_limit'] = sesLimit;
                    //ユーザーIDを格納
                    $_SESSION['user_id'] = $dbh->LastInsertId();

                    debug('セッション変数の中身：'.print_r($_SESSION,true));
                    header('Location:index.php'); //マイページへ
                }

            } catch(Exception $e){
                error_log('クエリ失敗'. $e->getMessage());
            }
        }
    }
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

    <main>
      <section>
        <div class="main_visual">
          <div class="m_v_left">
            <p>割り勘を簡単に<br />
              サクッと済ませて、<br />
              楽しい共同生活をしよう</p>
          </div>
          <div class="m_v_right">
            <img src="" alt="">
          </div>
        </div>
      </section>

      <section>
        <form class="form" action="" method="post">
            <h2>新規登録はこちら</h2>
            <label>Email</label>
            <input type="text" name="email" value=""> 
            <label>パスワード</label>
            <input type="text" name="pass" value="">
            <input type="submit" value="今すぐ登録">
        </form>
      </section>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>