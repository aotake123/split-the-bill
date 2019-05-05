<?php
//==============================
//ログ
//==============================
//ログを取るか否かの設定
ini_set('log_error','on');
//ログの出力ファイルを指定
ini_set('error_log','php.log');

//==============================
//デバッグ
//==============================
//デバッグフラグ
$debug_flg = true;
//デバッグログ関数
function debug($str){
    global $debug_flg;
    if(!empty($debug_flg)){
        error_log('デバッグ：'.$str);
    }
}

//==============================
//セッション準備・セッション有効期限を伸ばす
//==============================
//セッションファイルの置場変更(/var/tmp/以下に置くと30日は削除されない)
session_save_path("/var/tmp");
//ガーベッジコレクションが削除するセッションの有効期限を設定(30日経過後のファイルを100/1の確立で削除)
ini_set('session.bc.maxlifetime',60*60*24*30);
//ブラウザを閉じても削除されないようにクッキー自体の有効期限を延長
ini_set('session.cookie_lifetime',60*60*24*30);
//セッションを使う
session_start();
//現在のセッションIDを新しく生成し直す（なりすましのセキュリティ対策）
session_regenerate_id();

//==============================
//画面表示処理開始ログ吐き出し関数
//==============================

function debugLogstart(){
    debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>画面表示処理開始');
    debug('セッションID：'.session_id());
    debug('セッション変数の中身：'.print_r($_SESSION,true));
    debug('現在日時タイムスタンプ：'.time());
    if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
        debug('ログイン期限日時タイムスタンプ：'.($_SESSION['login_date'] + $_SESSION['login_limit']));
    }
}

//==============================
//エラーメッセージ用の定数
//==============================
define('MSG01','未入力の項目があります');
define('MSG02','255文字以下で入力してください');
define('MSG03','6文字以上で入力してください');
define('MSG04','Emailの形式で入力してください');
define('MSG05','パスワード（再入力の値）が違います');
define('MSG06','半角英数字で入力してください');
define('MSG07','エラーが発生しました');
define('MSG08','既に登録されているメールアドレスです');

define('MSG09','変更前のパスワードが違います');
define('MSG10','変更前のパスワードと同じです');
define('SUC01','パスワードを変更しました');
define('SUC02','プロフィールを変更しました');
define('SUC03','メールを送信しました');

//グローバル関数
$err_msg = "";

//==============================
//バリデーション関数
//==============================
//未入力検出
function validRequire($str,$key){
    if($str === ""){    //金額フォームなどを考えると数値の0はOKにし、空文字はダメにする
        global $err_msg;
        $err_msg[$key] = MSG01;         
    }
}
//最大文字数超過確認
function validMaxLen($str,$key,$max = 256){
     if(mb_strlen($str) > $max){
        global $err_msg;
        $err_msg[$key] = MSG02;         
     } 
}     
//最小文字数未到達確認
function validMinLen($str,$key,$min = 6){
     if($min > $mb_strlen($str)){
        global $err_msg;
        $err_msg[$key] = MSG03;         
     } 
}      
//Emain形式チェック
function validEmail($str,$key){
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG04;
    }       
}
//同じ値かの確認
function validSame($str1,$str2,$key){
    if($str1 !== $str2){
        global $err_msg;
        $err_msg[$key] = MSG05;
    }
}
//半角文字か否かの確認
function validHalf($str,$key){
    if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG06;
    }
}

//Email重複確認関数
//function validEmailDup($str,$key){
    //try{
   //DB接続準備
    //$dbh = dbConnect();
    //$sql = SELECT email FROM bill WHERE user_id （セッションの関数を作ってから再編集）
    //$data = array(編集中);
    
    //クエリー実行
    //querypost = ($dbh,$sql,$data);
    //$stmt->fetch(確認中);
    //return $stmt;
    //} catch(Exception $e){
        //error_log('クエリ失敗'. $e->getMessage());
        //$err_msg['common'] = MSG07;
    //}
//}


//==============================
//DB接続関連
//==============================

//DB接続関数
function dbConnect(){
    //DB接続準備
    $dsn = 'mysql:dbname=ikizama_splitbill;host=localhost;charset=utf8';
    $user = 'root';
    $password = 'root';
    $options = array(
        //SQL実行時に例外をスロー
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // デフォルトフェッチモードを連想配列形式に設定
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
        // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        );
    //PDOオブジェクト生成
    $dbn = new PDO($dsn, $user, $password, $options);
    return $dbn;
}

//クエリー実行関数
function qureyPost($dbh,$sql,$data){
    //クエリー作成
    $stmt = $dbh->prepare($sql);
    //プレースホルダに値をセットして、SQL文を実行
    if(!$stmt->excute($data)){
        debug('クエリに失敗しました。');
        debug('失敗したSQL'.print_r($stmt,true));
        $err_msg['common'] = MSG07;
        return 0;
    }else{
        debug('クエリ成功'.print_r);
        return $stmt;
    }
}

//==============================
//その他
//==============================
//パスワード変更用キーの発行
function makeRandkey($length = 8){
    static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for($i=1; $i < $lenght; ++$i){
        $str .= $chars[mt_rand(0,61)];
    }
    return $str;
}

