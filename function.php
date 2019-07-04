<?php
//==============================
//ログ
//==============================
//サーバー上でのエラー排出
ini_set('display_errors',0);
error_reporting(E_ALL);
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
define('MSG07','データ通信中にエラーが発生しました');
define('MSG08','既に登録されているメールアドレスです');

define('MSG09','パスワードがアンマッチです');
define('MSG10','変更前のパスワードが違います');
define('MSG11','変更前のパスワードと同じです');
define('MSG12','認証キーが違います');
define('MSG13','認証キーの有効時間を過ぎています');
define('MSG14','文字で入力してください');
define('MSG15','割り勘の総額が、各メンバーの金額を足した合計値と一致していません。');
define('MSG16','割り勘項目を選択してください。');
define('MSG17','プロフィールは10文字以内で入力してください');
define('MSG18','タイトルは20文字以内で入力してください');
define('MSG19','半角数字で入力してください');

define('SUC01','パスワードを変更しました');
define('SUC02','プロフィールを変更しました');
define('SUC03','メールを送信しました');
define('SUC04','割り勘の申請を完了しました');
define('SUC05','割り勘の支払い申請を完了しました');

//グローバル関数
$err_msg = null;

//==============================
//バリデーション関数
//==============================
//未入力検出
function validRequired($str,$key){
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
//最大文字数超過確認(プロフニックネーム用)
function validMaxLen2($str,$key,$max = 10){
    if(mb_strlen($str) > $max){
       global $err_msg;
       $err_msg[$key] = MSG17;         
    } 
}
//最大文字数超過確認(割り勘タイトル用)
function validMaxLen3($str,$key,$max = 20){
    if(mb_strlen($str) > $max){
       global $err_msg;
       $err_msg[$key] = MSG18;         
    } 
}        
//最小文字数未到達確認
function validMinLen($str,$key,$min = 6){
     if($min > mb_strlen($str)){
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
//半角数字か否かの確認
function validMath($str,$key){
    if(!preg_match("/^[0-9]+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG19;
    }
}
//固定長チェック（認証キー用）
function validLength($str, $key, $len = 8){
    if(mb_strlen($str) !== $len){
        global $err_msg;
        $err_msg[$key] = MSG14;
    }
}

//パスワード総合チェックの関数（更新時に使用）
function validPass($str,$key){
    //半角英数字チェック
    validHalf($str, $key);
    //最大文字数チェック
    validMaxLen($str, $key);
    //最小文字数チェック
    validMinLen($str, $key);
}

//Email重複確認関数
function validEmailDup($str){
    global $err_msg;
    //例外処理
    try{
   //DB接続準備
    $dbh = dbConnect();
    $sql = 'SELECT count(*) FROM users WHERE email = :email AND isDelete = 0';
    $data = array(':email' => $str);
    //クエリ実行
    $stmt = queryPost($dbh,$sql,$data);
    //クエリ結果の値を取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //array_shift関数:配列の先頭を取り出す関数。
    //クエリ結果は配列形式で入っているので、array_shiftで先頭を取り出して判定
    $empty_result = array_shift($result);
    if(!empty($empty_result)){
        $err_msg['email'] = MSG08;
    }   

     } catch(Exception $e){
        error_log('エラー発生'. $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
//割り勘計算の合計値が合っているかの確認
function validCost($str1,$str2,$key){
    if($str1 !== $str2){
        global $err_msg;
        $err_msg[$key] = MSG15;
    }
}
//selectboxのチェック（概要を確認した上で反映する）
function validSelect($str,$key){
    if(empty($str)){
        global $err_msg;
        $err_msg[$key] = MSG16;
    }
}

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
function queryPost($dbh,$sql,$data){
    //クエリー作成
    $stmt = $dbh->prepare($sql);
    //プレースホルダに値をセットして、SQL文を実行
    if(!$stmt->execute($data)){
        debug('クエリに失敗しました。');
        debug('失敗したSQL'.print_r($stmt,true));
        $err_msg['common'] = MSG07;
        return 0;
    }else{
        debug('クエリ成功');
        return $stmt;
    }
}
function getUser($u_id){
    debug('ユーザー情報を取得します。');
    //例外処理
    try{
        //DBへ接続
        $dbh = dbConnect();
        //SQL文作成
        $sql = 'SELECT * FROM users WHERE id = :u_id AND isDelete = 0';
        $data = array(':u_id' => $u_id);
        //クエリ実行
        $stmt = queryPost($dbh,$sql,$data);
        
        //クエリ結果のデータを1レコード返却
        if($stmt){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
        
    } catch(Exception $e){
        error_log('エラー発生：' . $e->getMessage());
    }
}

function getGroup(){
    debug('グループ一覧情報を取得します。');
    //例外処理
    try{
        //DB接続
        $dbh = dbConnect();
        $sql = "SELECT * FROM group_name WHERE isDelete = 0";
        $data = array();
        //クエリ実行
        $stmt = queryPost($dbh,$sql,$data);
        //クエリ結果返却(回収)
        if($stmt){
            return $stmt->fetchALL();
        }else{
            return false;
        }

    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}

function getMyGroup($u_id){
    debug('所属グループ名の情報を取得します。');
    //例外処理
    try{
        //DB接続
        $dbh = dbConnect();
        $sql = "SELECT g.id,g.data FROM group_name AS g
        INNER JOIN users AS u ON g.id = u.group_name 
        WHERE u.id = :id";
        $data = array(':id' => $u_id);
        //クエリ実行
        $stmt = queryPost($dbh,$sql,$data);
        //クエリ結果返却(回収)
        if($stmt){
            return $stmt->fetchALL();
        }else{
            return false;
        }

    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}


function getItem(){
    debug('割り勘の項目リスト情報を取得します。');
    //例外処理
    try{
        //DB接続
        $dbh = dbConnect();
        $sql = "SELECT id,data,isDelete FROM item WHERE isDelete = 0";
        $data = array();
        //クエリ実行
        $stmt = queryPost($dbh,$sql,$data);
        //クエリ結果返却(回収)
        if($stmt){
            return $stmt->fetchALL();
        }else{
            return false;
        }

    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}

function getSplitbill($u_id,$s_id){
    debug('割り勘固有情報を取得します');
    debug('ユーザーID：'.$u_id);
    debug('割り勘ID：'.$s_id);
    //例外処理
    try{
        //DB接続
        $dbh = dbConnect();
        $sql = 'SELECT * FROM payment WHERE users = :u_id AND id = :s_id AND isDelete = 0';
        $data = array(':u_id' => $u_id, 's_id' => $s_id);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            //クエリ結果のデータを1レコード返却
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
    }
}

function getSplitbillDetail($s_id){
    debug('割り勘固有情報を取得します');
    debug('割り勘ID：'.$s_id);
    //例外処理
    try{
        //DB接続
        $dbh = dbConnect();
        $sql = 'SELECT * FROM payment WHERE id = :s_id AND isDelete = 0';
        $data = array('s_id' => $s_id);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            //クエリ結果のデータを1レコード返却
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
    }
}

function getMemberCount($u_id,$group_name){
    debug('ログイン者の所属団体メンバーの人数を返します');
    //debug('ユーザーID：'.$u_id);
    //例外処理
    try{
        //DB接続
        $dbh = dbConnect();
        $sql = 'SELECT * FROM users 
        WHERE group_name = :group_name AND isDelete = 0';
        $data = array(':group_name' => $group_name);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            return $stmt->rowCount(); //グループ人数

        }else{
            return false;
        }
    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
    }
}

function getMemberdata($u_id,$group_name){
    debug('ログイン者の所属団体メンバーの情報を返します');
    //debug('ユーザーID：'.$u_id);
    //例外処理
    try{
        //DB接続
        $dbh = dbConnect();
        $sql = 'SELECT id,nickname,group_name,pic,isDelete FROM users 
        WHERE group_name = :group_name AND isDelete = 0
        ORDER BY id = :id DESC';
        $data = array(':group_name' => $group_name, ':id' => $u_id);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            return $stmt->fetchAll(); //データ全て

        }else{
            return false;
        }
    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
    }
}

function getSumTotalCost($u_id, $group_name, $isClaim, $y_id, $m_id){
    debug('メンバー個人の申請した割り勘/支払いのtotalCostの合計情報を返します');
    //例外処理
    try{
        //DB接続
        $dbh = dbConnect();
        $sql = 'SELECT SUM(totalCost) FROM payment 
        WHERE group_name = :group_name 
        AND g_year = :y_id
        AND g_month = :m_id
        AND users = :users 
        AND isClaim = :isClaim
        AND isDelete = 0
        ';
        $data = array(':group_name' => $group_name, 
        ':users' => $u_id, ':isClaim' => $isClaim, ':y_id' => $y_id, ':m_id' => $m_id
        );
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if($stmt){
            return $stmt->fetchAll(); //データ全て

        }else{
            return false;
        }
    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
    }
}

function getSumUserCost($u_id, $group_name, $isClaim, $y_id, $m_id){
    debug('メンバー個人の申請された割り勘/支払いのuserCostの合計情報を返します');
    //例外処理
    try{
        //DB接続
        $dbh = dbConnect();
        $sql = 'SELECT SUM(splitBill) FROM usersPayment AS up 
        INNER JOIN payment AS p ON up.payment = p.id2
        WHERE group_name = :group_name 
        AND g_year = :y_id
        AND g_month = :m_id
        AND up.users = :users 
        AND up.isClaim = :isClaim
        AND up.isDelete = 0 AND p.isDelete = 0
        ';
        $data = array(':group_name' => $group_name, 
        ':users' => $u_id, ':isClaim' => $isClaim, ':y_id' => $y_id, ':m_id' => $m_id
        );
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            return $stmt->fetchAll(); //データ全て

        }else{
            return false;
        }

    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
    }
}

function getMyNewBills($u_id, $group_name, $span){
    debug('ユーザーの最新X件の割り勘情報を返します');
    //例外処理
    try{
        //DB接続
        $dbh = dbConnect();
        $sql = 'SELECT p.id,p.title,p.g_year,p.g_month,p.g_date,p.item,p.totalCost,p.users,p.isClaim,p.comment,p.createDate 
        FROM payment AS p
        INNER JOIN users AS u ON p.users = u.id
        WHERE u.id = :users
        AND u.group_name = :group_name
        AND p.isDelete = 0
        ORDER BY g_year DESC,g_month DESC,g_date DESC,createDate DESC
        LIMIT '.$span.' OFFSET 0
        ';
        $data = array(':users' => $u_id, ':group_name' => $group_name);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            return $stmt->fetchAll(); //データ全て

        }else{
            return false;
        }

    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
    }
}

function getSplitBillOne($u_id,$s_id){
    debug('単一割り勘の中間テーブル情報を返します');
    //例外処理
    try{
        //DB接続
        $dbh = dbConnect();
        $sql = 'SELECT up.users,up.splitbill,up.isClaim,up.isDelete FROM usersPayment AS up
        LEFT JOIN users AS u ON u.id = up.users
        RIGHT JOIN payment AS p ON p.id2 = up.payment
        WHERE up.users = :users AND p.id = :s_id
        ';
        $data = array(':users' => $u_id, ':s_id' => $s_id);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            return $stmt->fetchAll(); //データ全て

        }else{
            return false;
        }

    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
    }
}

function getItemName($s_id){
    debug('割り勘詳細画面で表示する、項目名の日本語を返します');
    //例外処理
    try{
        //DB接続
        $dbh = dbConnect();
        $sql = 'SELECT p.id,p.title,p.item,i.data FROM payment AS p
        INNER JOIN item AS i ON p.item = i.id
        WHERE p.id = :s_id AND p.isDelete = 0
        ';
        $data = array(':s_id' => $s_id);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            return $stmt->fetchAll(); //データ全て

        }else{
            return false;
        }

    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
    }

}

function getBillView($u_id,$s_id){
    debug('割り勘詳細画面で表示する、項目名の日本語を返します');
    //例外処理
    try{
        //DB接続
        $dbh = dbConnect();
        $sql = 'SELECT up.id,up.users,up.payment,up.splitBill,up.isDelete,
        p.id2,p.title,p.item FROM usersPayment AS up
        INNER JOIN payment AS p ON up.payment = p.id2
        WHERE up.users = :u_id AND p.id = :s_id AND up.isDelete = 0 
        ';
        $data = array(':u_id' => $u_id, ':s_id' => $s_id);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            return $stmt->fetchAll(); //データ全て

        }else{
            return false;
        }

    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
    }
}

function getAllBills($currentPageNum,$group_name,$m_id,$span,$y_id){
    debug('グループ内の全ての割り勘データを取得');
    //例外処理
    try{
        //DB接続
        $dbh = dbConnect();
        //件数取得のためのSQL作成
        $sql = 'SELECT id FROM payment
                WHERE isDelete = 0 
                AND g_year = :y_id 
                AND g_month = :m_id
                AND group_name = :group_name
                AND g_month = :g_month';
        $data = array(':group_name' => $group_name, ':g_month' => $m_id, ':y_id' => $y_id, ':m_id' => $m_id);
        $stmt = queryPost($dbh, $sql, $data);
        $rst['total'] = $stmt->rowCount(); //総レコード数
        $rst['total_page'] = ceil($rst['total']/$span); //総ページ数 ceilは切り上げ関数（端数も含めてページを出してる）
        if(!$stmt){
            return false;
            }

        //データ取得するためのSQL作成
        $sql = 'SELECT p.id,p.title,p.g_year,p.g_month,
        p.g_date,p.item,p.totalCost,p.users,p.isClaim,p.comment 
        FROM payment AS p
        INNER JOIN users AS u ON p.users = u.id
        WHERE u.group_name = :group_name
        AND p.g_month = :g_month
        AND p.isDelete = 0
        ORDER BY g_year DESC,g_month DESC,g_date DESC
        ';
        //ページング表示
        $sql .= ' LIMIT ' .$span. ' OFFSET ' .$currentPageNum;

        $data = array(':group_name' => $group_name, ':g_month' => $m_id);
        debug('SQL文の表示：'.$sql);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            //クエリ結果のデータを全レコードを格納
            $rst['data'] = $stmt->fetchAll();
            return $rst;

        }else{
            return false;
        }

    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
    }
}

function getMyBillCount($u_id,$group_name,$y_id,$m_id){
    debug('指定した個人＆グループ内における、割り勘申請件数を取得');
    try{
        //DB接続
        $dbh = dbConnect();
        $sql = 'SELECT id,group_name,users,isDelete FROM payment
        WHERE group_name = :group_name
        AND g_year = :y_id AND g_month = :m_id 
        AND users = :u_id
        AND isDelete = 0
        ';
        $data = array(':u_id' => $u_id, ':group_name' => $group_name, 
        ':y_id' => $y_id, ':m_id' => $m_id);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            return $stmt->rowCount(); //件数

        }else{
            return false;
        }

    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
    }
  
}

function splitBillCount($s_id){
    debug('指定した割り勘における、関係者の件数を取得');
    try{
        //DB接続
        $dbh = dbConnect();
        $sql = 'SELECT up.splitBill FROM usersPayment AS up
        INNER JOIN payment AS p ON up.payment = p.id2
        WHERE p.id = :s_id
        AND up.isDelete = 0
        ';
        $data = array(':s_id' => $s_id);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            return $stmt->rowCount(); //件数

        }else{
            return false;
        }

    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
    }
   
 
}

//==============================
//メール送信
//==============================

function sendMail($from,$to,$subject,$comment){
    if(!empty($to) && !empty($subject) && !empty($comment)){
        //文字化けしないように設定（お決まりパターン）
        mb_language("Japanese"); //現在使っている言語を設定する
        mb_internal_encoding("UTF-8"); //内部の日本語をどうエンコーディング（機械が分かる言葉へ変換）するかを設計
        
        //メールを送信（送信結果はtrueかfalseで返ってくる）
        $result = mb_send_mail($to, $subject, $comment, "From:".$from);
        //送信結果を判定
        if($result){
            debug('メールを送信しました。');
        }else{
            debug('【エラー発生】メールの送信に失敗しました。');
        }
    }
}

//==============================
//その他
//==============================
//パスワード変更用キーの発行
function makeRandKey($length = 8) {
    static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for ($i = 0; $i < $length; ++$i) {
        $str .= $chars[mt_rand(0, 61)];
    }
    return $str;
}


//サニタイズ
function sanitize($str){
    return htmlspecialchars($str,ENT_QUOTES);
}

//フォーム入力保持
function getFormData($str, $flg = false){
    if($flg){
        $method = $_GET;
    }else{
        $method = $_POST;
    }
    global $dbFormData;
    //ユーザーデータがある場合
    if(!empty($dbFormData)){
        //フォームのエラーがある場合
        if(!empty($err_msg[$str])){
            //POSTにデータがある場合
            if(isset($method[$str])){
                return sanitize($method[$str]);
            }else{
                //ない場合（基本ありえない）はDBの情報を表示
                return sanitize($dbFormData[$str]);
            }
        }else{
            //POSTにデータがあり、DBの情報と違う場合
            if(isset($method[$str]) && $method[$str] !== $dbFormData[$str]){
                return sanitize($method[$str]);
            }else{
                return sanitize($dbFormData[$str]);
            }
        }
    }else{
        if(isset($method[$str])){
            return sanitize($method[$str]);
        }
    }
}

function getBillFormData($str, $flg = false){
    if($flg){
        $method = $_GET;
    }else{
        $method = $_POST;
    }
    global $dbBillData;
    //ユーザーデータがある場合
    if(!empty($dbFormData)){
        //フォームのエラーがある場合
        if(!empty($err_msg[$str])){
            //POSTにデータがある場合
            if(isset($method[$str])){
                return sanitize($method[$str]);
            }else{
                //ない場合（基本ありえない）はDBの情報を表示
                return sanitize($dbBillData[$str]);
            }
        }else{
            //POSTにデータがあり、DBの情報と違う場合
            if(isset($method[$str]) && $method[$str] !== $dbBillData[$str]){
                return sanitize($method[$str]);
            }else{
                return sanitize($dbBillData[$str]);
            }
        }
    }else{
        if(isset($method[$str])){
            return sanitize($method[$str]);
        }
    }
}


//getsessionFlash

//画像処理
function uploadImg($file,$key){
    debug('画像アップロード処理開始');
    debug('FILE情報：'.print_r($file,true));

    if(isset($file['error']) && is_int($file['error'])){
        try{
            //バリデーション
            //$file['error']の値を確認。配列内には「UPLOAD_ERR_OK」などの定数が入っている
            //UPLOAD_ERR_OK などのphpでファイルアップロード時に自動的に定義される
            switch ($file['error']){
                case UPLOAD_ERR_OK: // OK
                    break;
                case UPLOAD_ERR_NO_FILE:    //ファイル未選択の場合
                    throw new RuntimeException('ファイルが選択されていません');
                case UPLOAD_ERR_INI_SIZE:   //php.ini定義の最大サイズが超過した場合
                case UPLOAD_ERR_FORM_SIZE:  //フォーム定義の最大サイズ超過した場合
                    throw new RuntimeException('ファイルサイズが大きすぎます');
                default: //その他の場合
                    throw new RuntimeException('その他のエラーが発生しました');
            }
            // $file['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする
            // exif_imagetype関数は「IMAGETYPE_GIF」「IMAGETYPE_JPEG」などの定数を返す
            $type = @exif_imagetype($file['tmp_name']);
            $array_value = in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true); // 第三引数にはtrueを設定すると厳密にチェックしてくれるので必ずつける
            if (!$array_value){
                throw new RuntimeException('画像形式が未対応です');
            }
            // ファイルデータからSHA-1ハッシュを取ってファイル名を決定し、ファイルを保存する
            // ハッシュ化しておかないとアップロードされたファイル名そのままで保存してしまうと同じファイル名がアップロードされる可能性があり、
            // DBにパスを保存した場合、どっちの画像のパスなのか判断つかなくなってしまう
            // image_type_to_extension関数はファイルの拡張子を取得するもの
            $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
            if (!move_uploaded_file($file['tmp_name'], $path)) { //ファイルを移動する
                throw new RuntimeException('ファイル保存時にエラーが発生しました');
            }
            // 保存したファイルパスのパーミッション（権限）を変更する
            chmod($path, 0644);
            
            debug('ファイルは正常にアップロードされました');
            debug('ファイルパス：'.$path);
            return $path;

            } catch (RuntimeException $e) {

            debug($e->getMessage());
            global $err_msg;
            $err_msg[$key] = $e->getMessage();

    }
  }
}

//ページング
// $currentPageNum : 現在のページ数
// $totalPageNum : 総ページ数
// $link : 検索用GETパラメータリンク
// $pageColNum : ページネーション表示数
function pagination( $currentPageNum, $totalPageNum, $link = '', $pageColNum = 5){
    // 現在のページが、総ページ数と同じ　かつ　総ページ数が表示項目数以上なら、左にリンク3個出す
    if( $currentPageNum == $totalPageNum && $totalPageNum >= $pageColNum){
      $minPageNum = $currentPageNum - 4;
      $maxPageNum = $currentPageNum;
    // 現在のページが、総ページ数の１ページ前なら、左にリンク３個、右に１個出す
    }elseif( $currentPageNum == ($totalPageNum-1) && $totalPageNum >= $pageColNum){
      $minPageNum = $currentPageNum - 3;
      $maxPageNum = $currentPageNum + 1;
    // 現ページが2の場合は左にリンク１個、右にリンク３個だす。
    }elseif( $currentPageNum == 2 && $totalPageNum >= $pageColNum){
      $minPageNum = $currentPageNum - 1;
      $maxPageNum = $currentPageNum + 3;
    // 現ページが1の場合は左に何も出さない。右に５個出す。
    }elseif( $currentPageNum == 1 && $totalPageNum >= $pageColNum){
      $minPageNum = $currentPageNum;
      $maxPageNum = 5;
    // 総ページ数が表示項目数より少ない場合は、総ページ数をループのMax、ループのMinを１に設定
    }elseif($totalPageNum < $pageColNum){
      $minPageNum = 1;
      $maxPageNum = $totalPageNum;
    // それ以外は左に２個出す。
    }else{
      $minPageNum = $currentPageNum - 2;
      $maxPageNum = $currentPageNum + 2;
    }
    
    echo '<div class="pagination">';
      echo '<ul class="pagination-list">';
        if($currentPageNum != 1){
          echo '<li class="list-item"><a href="?p=1&sort_month='.$link.'">&lt;</a></li>';
        }
        for($i = $minPageNum; $i <= $maxPageNum; $i++){
          echo '<li class="list-item ';
          if($currentPageNum == $i ){ echo 'active'; }
          echo '"><a href="?p='.$i.'&sort_month='.$link.'">'.$i.'</a></li>';
        }
        if($currentPageNum != $maxPageNum){
          echo '<li class="list-item"><a href="?p='.$maxPageNum.'&sort_month='.$link.'">&gt;</a></li>';
        }
      echo '</ul>';
    echo '</div>';
  }

?>