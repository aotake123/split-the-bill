<?php

//共通変数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('パスワード変更ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//==============================
// パスワード変更機能 画面処理
//==============================
//DBからユーザーデータを取得
$userData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報：'.print_r($userData,true));

//POSTされていた場合
if(!empty($_POST)){
    debug('POST通信があります。');
    debug('POST情報：'.print_r($_POST,true));

    //POST通信の確認
    $pass_old = $_POST['pass_old'];
    $pass_new = $_POST['pass_new'];
    $pass_new_re = $_POST['pass_new_re'];

    //バリデーション開始
    //未入力チェック
    validRequired($pass_old,'pass_old');
    validRequired($pass_new,'pass_new');
    validRequired($pass_new_re,'pass_new_re');

    if(empty($err_msg)){
        debug('未入力チェックOK。');
        
        //古いパスワードとDB登録済のパスワードを比較
        //同じであれば、古いパスワードの最大最小と半角は確認不用
        if(!password_verify($pass_old,$userData['pass'])){
            $err_msg['pass_new'] = MSG10;
        }
        //新しいパスワードのチェック
        validPass($pass_new, 'pass_new');
        //再入力した価が同じかどうかチェック
        validSame($pass_new, $pass_new_re, 'pass_new');
        //新しいパスワードと古いパスワードが同じかチェック
        if($pass_old === $pass_new){
            $err_msg['pass_new'] = MSG11;
        }

        if(empty($err_msg)){
            debug('バリデーションOK。');
            //例外処理
            try{
            //DB接続関数
                $dbh = dbConnect();
                $sql = 'UPDATE users SET pass = :pass WHERE id = :id AND isDelete = 0';
                $data = array(':id' => $_SESSION['user_id'], 
                        ':pass' => password_hash($pass_new,PASSWORD_DEFAULT));
            //クエリー実行関数
                $stmt = queryPost($dbh,$sql,$data);
            
            //照合結果を真偽値で返す
                if($stmt){
                    $_SESSION['msg_success'] = SUC01;

                    //メールを送信
                    $username = ($userData['nickname']) ? $userData['nickname'] : '名無し';
                    $from = 'tasukuoki3@gmail.com';
                    $to = $userData['email'];
                    $subject = 'パスワード変更通知 | 割り勘シェアハウス';
                    //EOTはEndOfFileの略。ABCでもなんでもいい。先頭の<<<の後の文字列と合わせること。最後のEOTの前後に空白など何も入れてはいけない
                    //EOT内の半角空白もすべてそのまま半角空白として扱われるのでインデントはしないこと
                    $commnet = <<<EOT
{$username}様
パスワードが変更されました。

///////////////////////////////////////////
割り勘シェアハウス 管理事務局
E-mail　tasukuoki3@gmail.com
///////////////////////////////////////////
EOT;
                    sendmail($from,$to,$subject,$comment);

                    header("Location:mypage.php");  //マイページへ遷移
            }
            //失敗
            } catch (Exception $e){
                error_log('エラー発生：' . $e->getMessage());
                $err_msg['common'] = MSG07;
            }
        }
    }
}
?>

<?php
$siteTitle = 'パスワードの変更 | 割り勘シェアハウス';
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
               <div class="form_title_subject"><h2>パスワード再設定</h2></div>
           </div>
           
           <div class="form_main">
           <div class="form_main_wrap">
           
            <div class="area-msg<?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?> form_wide_option">
                <?php if(!empty($_POST['common'])) echo $err_msg['common']; ?>
            </div>

            <label>
            <span class="form_subtitle form_wide_option">現在のパスワード</span>
            <div class="form_input form_wide_option">
                <input type="password" name="pass_old" value="<?php if(!empty($_POST['pass_old'])) echo $_POST['pass_old']; ?>">
            </div>
            </label>
            <div class="area-msg form_wide_option">
                <?php if(!empty($_POST['pass_old'])) echo $err_msg['pass_old']; ?>
            </div>

           <label>
            <span class="form_subtitle form_wide_option">新しいパスワード</span>
            <div class="form_input form_wide_option">
                <input type="password" name="pass_new" value="<?php if(!empty($_POST['pass_new'])) echo $_POST['pass_new']; ?>">
            </div>
            </label>
            <div class="area-msg form_wide_option">
                <?php if(!empty($_POST['pass_new'])) echo $err_msg['pass_new']; ?>
            </div>

           <label>
            <span class="form_subtitle form_wide_option">新しいパスワード（再入力）</span>
            <div class="form_input form_wide_option">
                <input type="password" name="pass_new_re" value="<?php if(!empty($_POST['pass_new_re'])) echo $_POST['pass_new_re']; ?>">
            </div>
            </label>
            <div class="area-msg form_wide_option">
                <?php if(!empty($_POST['pass_new_re'])) echo $err_msg['pass_new_re']; ?>
            </div>
            
            </div>
           </div>
           
            <div class="form_submit form_wide_option_submit">
                <input type="submit" value="パスワードを変更する">
            </div>
           
        </form>
            
     </div>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>