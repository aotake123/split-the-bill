<?php

//共通変数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('退会ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//==============================
//画面処理
//==============================
//POST送信がされていた場合
if(!empty($_POST)){
    debug('POST通信があります');
    //例外処理
    try{
        $dbh = dbConnect();
        $sql = 'UPDATE users SET isDelete = 1 WHERE id = :u_id';
        $data = array(':u_id' => $_SESSION['user_id']);
        $stmt = queryPost($dbh,$sql,$data);
        if($stmt){
            session_destroy();
            debug('セッション変数の中身'.print_r($_SESSION,true));
            debug('TOPページへ遷移します');
            header("Location:index.php");
        }else{
            debug('クエリが失敗しました。');
            $err_msg['common'] = MSG07;
        }

    } catch(Exception $e){
        error_log('エラー発生：' .$e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
debug('画面表示処理終了　<<<<<<<<<<<<<<<<<<<<<<');


?>

<?php
$siteTitle = '退会 | 割り勘シェアハウス';
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
               <div class="form_title_subject"><h2>退会申請</h2></div>
           </div>

           <p class="form_introduction form_wide_option">退会をすると、これまでの割り勘の履歴は残りますが<br />
           新たに同じグループ内で割り勘の申請や支払をすることは出来なくなります。<br />
           本当に退会しても宜しいですか？</p>
           
            <div class="area-msg">
                <?php if(!empty($_POST['common'])) echo $err_msg['common']; ?>
            </div>

           
            <div class="form_submit form_wide_option_submit">
                <input type="submit" value="今すぐ退会を完了する" name="submit">
            </div>
           
        </form>
            
     </div>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>