<?php

//共通変数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('マイページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//画面表示用データ取得

//自分の最新割勘のgetパラメータ
//自分の最新コメントのgetパラメータ
//割勘当月最新情報のDB回収配列
//自分の最新割勘のDB回収配列
//自分の最新コメントのDB回収配列
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

        <div class="main_1colum_wide2">

        <form class="form" action="" method="post">
            <div class="form_title_wrap">
                <div class="form_title_subject line_blue"><h2>TERRACE厚木の〜月の割り勘一覧</h2></div>
            </div>
            <div class="form_main">
            <div class="form_main_wrap">

            <div class="reco_wrap reco_option">
            <table class="record">
                <thead><tr>
                <th class="reco_personal">メンバー名</th>
                <th class="reco_money_month">総額</th>
                <th class="reco_count">件数</th>
                <th class="reco_list">詳細</th>
                </tr>

                <tr>
                <td class="reco_item">つなお</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">14件</td>
                <td class="reco_item"><a href="payDetail.php">一覧を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">つなお</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">14件</td>
                <td class="reco_item"><a href="payDetail.php">一覧を見る</a></td>
                </tr>
                <tr>
                <td class="reco_item">つなお</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">14件</td>
                <td class="reco_item"><a href="payDetail.php">一覧を見る</a></td>
                </tr>
                <tr>
                <td class="reco_item">つなお</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">14件</td>
                <td class="reco_item"><a href="payDetail.php">一覧を見る</a></td>
                </tr>


                </thead>
            </table>           
        </div>


        <div class="reco_wrap">
            <table class="record">
                <thead><tr>
                <th class="reco_date">日時</th>
                <th class="reco_title">割り勘タイトル</th>
                <th class="reco_total">総額</th>
                <th class="reco_main">立替/支払者</th>
                <th class="reco_sub">割り勘対象者</th>
                <th class="reco_detail">詳細</th>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あなた</td>
                <td class="reco_item">太朗、つなお、他●名</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あなた</td>
                <td class="reco_item">太朗、つなお、他●名</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あなた</td>
                <td class="reco_item">太朗、つなお、他●名</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あなた</td>
                <td class="reco_item">太朗、つなお、他●名</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あなた</td>
                <td class="reco_item">太朗、つなお、他●名</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あなた</td>
                <td class="reco_item">太朗、つなお、他●名</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あなた</td>
                <td class="reco_item">太朗、つなお、他●名</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あなた</td>
                <td class="reco_item">太朗、つなお、他●名</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あなた</td>
                <td class="reco_item">太朗、つなお、他●名</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あなた</td>
                <td class="reco_item">太朗、つなお、他●名</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あなた</td>
                <td class="reco_item">太朗、つなお、他●名</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あなた</td>
                <td class="reco_item">太朗、つなお、他●名</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                </thead>
            </table>           
        </div>

 

            
            </div>
            </div>

                                    
        </form>


        </div>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>