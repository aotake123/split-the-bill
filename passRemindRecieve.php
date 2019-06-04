<?php

//共通変数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('パスワード認証キー発行ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証は、出来ない段階で訪れるページの為、行わない

//SESSIONに認証キーがあるか確認、無ければリダイレクト
if(empty($_SESSION['auth_key'])){
    header("Location:passRemindSend.php");  //認証キー発行ページへ戻る
}

//==============================
//パスワード再発行認証キー
//==============================
//POST通信が行なわれた時
if(!empty($_POST)){
debug('POST情報が有ります。');
debug('POST情報：'.print_r($_POST,true));

//POST通信の値を取得する
$auth_key = $_POST['auth'];

//未入力チェック
validRequired($auth_key,'auth');
    //バリデーション開始
    if(empty($err_msg)){
    
        //固定長チェック
        validRequired($auth_key,'auth');
        //半角チェック
        validHalf($auth_key,'auth');

        if(empty($err_msg)) {
            debug('バリデーションOK');

            //セッションキー称号。エラーはタイムアウトとキーの相違
            if($auth_key !== $_SESSION['auth_key']){
                $err_msg['common'] = MSG12;
            }
            if(time() > $_SESSION['auth_key_limit']){
                $err_msg['common'] = MSG13;
            }

            //うまくいったらもう一度パスワード発行
            //開発中は表示
            if(empty($err_msg)){
                debug('認証OK');
                $pass = makeRandKey();  //パスワード生成
                debug('新規パスワード：'.$pass);    //開発中のみの表示

                //Email探して作ったパスワード登録
                //例外処理
                try{
                    $dbh = dbConnect();
                    $sql = 'UPDATE users SET pass = :pass WHERE email = :email AND isDelete = 0';
                    $data = array(':email' => $_SESSION['auth_email'], ':pass' => password_hash($pass,PASSWORD_DEFAULT));
                    //クエリー実行関数
                    $stmt = queryPost($dbh,$sql,$data);
                    //クエリ成功したらメール送信関数
                    if($stmt){
                        $from = 'tasukuoki3@gmail.com';
                        $to = $_SESSION['auth_email'];
                        $subject = '【パスワード再発行完了】| 割り勘シェアハウス';
                        $comment = <<<EOT
本メールアドレス宛にパスワード再発行を致しました。
下記のURLにて再発行パスワードをご入力頂き、ログインください。

ログインページ：http://ikizama-design.com/splitbill/login.php
再発行パスワード：{$pass}
※ログイン後、パスワードの変更をお願いします

///////////////////////////////////////////
割り勘シェアハウス 管理事務局
E-mail　tasukuoki3@gmail.com
///////////////////////////////////////////
EOT;
                        sendmail($from,$to,$subject,$comment);
                        //session消して画面を遷移
                        session_unset();    //IDが無くなると下記のメッセージが表示されなくなるので消さない
                        $_SESSION['msg_success'] = SUC03;

                        header("Location:login.php");
                        }else{
                            debug('クエリに失敗しました。');
                            $err_msg['common'] = MSG07;
                        }
                
                    } catch(Exception $e){
                    error_log('エラー発生：' . $e->getMessage());
                    $err_msg['common'] = MSG07;
                    }
            }
        }
    }
}


?>

<?php
$siteTitle = 'パスワード認証 | 割り勘シェアハウス';
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
                <input type="password" name="auth" value="<?php if(!empty($_POST['auth'])) echo $_POST['auth']; ?>">
            </div>
            </label>
            <div class="area-msg">
                <?php if(!empty($_POST['auth'])) echo $err_msg['auth']; ?>
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