<?php

//共通変数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('パスワード認証キー発行ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証は、この機能はログインができない人が使う為行わない

//==============================
// パスワード再発行メール送信ページ 画面処理
//==============================
//POST通信が行われて中身があった時
if(!empty($_POST)){
    debug('POST通信があります');
    debug('POST情報：'.print_r($_POST,true));

    //POST通信の値を代入
    $email = $_POST['email'];
    //未入力チェック
    validRequired($email,'email');
    //バリデーション
    if(empty($err_msg)){
        //Email形式
        validEmail($email,'email');
        //最大文字数
        validMaxLen($email,'email');
        //最大最小や半角は、あくまでもデータを探して
        //DB上のEmail情報と照合する為の入力情報なので、省略
        if(empty($err_msg)){
            debug('バリデーションOK');
            //例外処理
            try{
                $dbh = dbConnect();
                $sql = 'SELECT count(*) FROM users WHERE email = :email AND isDelete = 0';
                $data = array(':email' =>$email);
                $stmt = queryPost($dbh,$sql,$data);
                //クエリ結果の値を取得
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                //EmailがDBに登録されている場合
                if($stmt && array_shift($result)){
                    debug('クエリ成功。DBにメールアドレス登録有り');
                    $_SESSION['msg_success'] = SUC03;

                    $auth_key = makeRandKey();  //認証キー生成

                    //メールを送信(情報の盗難防止の為、ユーザーの名前は記載しない)
                    $from = 'tasukuoki3@gmail.com';
                    $to = $email;
                    $subject = 'パスワード再発行認証 | 割り勘シェアハウス';
                    //EOTはEndOfFileの略。ABCでもなんでもいい。先頭の<<<の後の文字列と合わせること。最後のEOTの前後に空白など何も入れてはいけない
                    //EOT内の半角空白もすべてそのまま半角空白として扱われるのでインデントはしないこと
                    $comment = <<<EOT
本メールアドレス宛にパスワード再発行のご依頼がありました。
下記のURLにて認証キーをご入力頂くとパスワードが再発行されます。

パスワード再発行認証キー入力ページ：http://ikizama-design.com/splitbill/passRemindRecieve.php
認証キー：{$auth_key}
※認証キーの有効期限は30分となります

認証キーを再発行されたい場合は下記ページより再度再発行をお願い致します。
http://ikizama-design.com/splitbill/passRemindSend.php

///////////////////////////////////////////
割り勘シェアハウス 管理事務局
E-mail　tasukuoki3@gmail.com
///////////////////////////////////////////
EOT;
                    debug('auth情報(開発中のみ)：'.print_r($auth_key,true));
                    sendMail($from, $to, $subject, $comment);

                    //認証に必要な情報をセッションへ保存
                    $_SESSION['auth_key'] = $auth_key;
                    $_SESSION['auth_email'] = $email;
                    $_SESSION['auth_key_limit'] = time()+(60*30);
                    debug('SESSION情報(開発中のみ)：'.print_r($_SESSION,true));


                    header("Location:passRemindRecieve.php");
                }else{
                    debug('クエリ失敗 もしくは未登録のEmailアドレスが入力されました。');
                    $err_msg['common'] = MSG07;
                }

            } catch(Exception $e){
                error_log('エラー発生：' .$e->getMessage());
                $err_msg['common'] = MSG07;
            }
        }
    }
}
debug('画面表示処理終了　<<<<<<<<<<<<<<<<<<<<<<');

?>

<?php
$siteTitle = 'パスワード再発行 | 割り勘シェアハウス';
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