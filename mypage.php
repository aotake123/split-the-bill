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
                <div class="form_title_subject line_blue"><h2>今月（5月）の terrace厚木 の割り勘状況</h2></div>
            </div>
            <div class="form_main">
            <div class="form_main_wrap">

            <div class="area-msg">
                <?php if(!empty($_POST['common'])) echo $err_msg['common']; ?>
            </div>
            
            <div class="prof_whole">
                <div class="prof_whole_left_graph">
                    <div class="prof_whole_wrap">
                        つなお
                    </div>
                </div>
                <div class="prof_whole_right">
                    <div class="img_wrap">
                        <div class="img_upload_left">
                            <img src="" alt="ログイン者の写真" class="img_prev">
                        </div>

                        <div class="graph">
                            <div class="graph1">
                                <div class="graph_left">
                                    <p class="graph_title">立替金合計</p>
                                    <p class="graph_money">11000円</p>
                                </div>
                                <div class="graph_right">
                                    <div class="graph1_main"></div>
                                </div>
                            </div>
                            <div class="graph2">
                                <div class="graph_left">
                                    <p class="graph_title">未精算合計</p>
                                    <p class="graph_money"> 4500円</p>
                                </div>
                                <div class="graph_right">
                                    <div class="graph2_main"></div>
                                </div>
                            </div>
                            <div class="graph3">
                                <div class="graph_left">
                                    <p class="graph_title">収支合計　</p>
                                    <p class="graph_money"> 6500円</p>
                                </div>
                                <div class="graph_right">
                                    <div class="graph3_main"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="p_w_r_right"></div>
                </div>
            </div>
            
            <div class="prof_whole">
                <div class="prof_whole_left_graph">
                    <div class="prof_whole_wrap">
                        あやね
                    </div>
                </div>
                <div class="prof_whole_right">
                    <div class="img_wrap">
                        <div class="img_upload_left">
                            <img src="" alt="ログイン者の写真" class="img_prev">
                        </div>

                        <div class="graph">
                            <div class="graph1">
                                <div class="graph_left">
                                    <p class="graph_title">立替金合計</p>
                                    <p class="graph_money">11000円</p>
                                </div>
                                <div class="graph_right">
                                    <div class="graph1_main"></div>
                                </div>
                            </div>
                            <div class="graph2">
                                <div class="graph_left">
                                    <p class="graph_title">未精算合計</p>
                                    <p class="graph_money"> 4500円</p>
                                </div>
                                <div class="graph_right">
                                    <div class="graph2_main"></div>
                                </div>
                            </div>
                            <div class="graph3">
                                <div class="graph_left">
                                    <p class="graph_title">収支合計　</p>
                                    <p class="graph_money"> 6500円</p>
                                </div>
                                <div class="graph_right">
                                    <div class="graph3_main"></div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="p_w_r_right"></div>
                </div>
            </div>

            <div class="prof_whole">
                <div class="prof_whole_left_graph">
                    <div class="prof_whole_wrap">
                        鈴木
                    </div>
                </div>
                <div class="prof_whole_right">
                    <div class="img_wrap">
                        <div class="img_upload_left">
                            <img src="" alt="ログイン者の写真" class="img_prev">
                        </div>

                        <div class="graph">
                            <div class="graph1">
                                <div class="graph_left">
                                    <p class="graph_title">立替金合計</p>
                                    <p class="graph_money">11000円</p>
                                </div>
                                <div class="graph_right">
                                    <div class="graph1_main"></div>
                                </div>
                            </div>
                            <div class="graph2">
                                <div class="graph_left">
                                    <p class="graph_title">未精算合計</p>
                                    <p class="graph_money"> 4500円</p>
                                </div>
                                <div class="graph_right">
                                    <div class="graph2_main"></div>
                                </div>
                            </div>
                            <div class="graph3">
                                <div class="graph_left">
                                    <p class="graph_title">収支合計　</p>
                                    <p class="graph_money"> 6500円</p>
                                </div>
                                <div class="graph_right">
                                    <div class="graph3_main"></div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="p_w_r_right"></div>
                </div>
            </div>

            <div class="prof_whole">
                <div class="prof_whole_left_graph">
                    <div class="prof_whole_wrap">
                        青木
                    </div>
                </div>
                <div class="prof_whole_right">
                    <div class="img_wrap">
                        <div class="img_upload_left">
                            <img src="" alt="ログイン者の写真" class="img_prev">
                        </div>

                        <div class="graph">
                            <div class="graph1">
                                <div class="graph_left">
                                    <p class="graph_title">立替金合計</p>
                                    <p class="graph_money">11000円</p>
                                </div>
                                <div class="graph_right">
                                    <div class="graph1_main"></div>
                                </div>
                            </div>
                            <div class="graph2">
                                <div class="graph_left">
                                    <p class="graph_title">未精算合計</p>
                                    <p class="graph_money"> 4500円</p>
                                </div>
                                <div class="graph_right">
                                    <div class="graph2_main"></div>
                                </div>
                            </div>
                            <div class="graph3">
                                <div class="graph_left">
                                    <p class="graph_title">収支合計　</p>
                                    <p class="graph_money"> 6500円</p>
                                </div>
                                <div class="graph_right">
                                    <div class="graph3_main"></div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="p_w_r_right"></div>
                </div>
            </div>
            
            </div>
            </div>
            
            <div class="prof_whole">
                <p class="form_last_comment"><a href="payEdit.php">月毎の割り勘状況を確認する</a></p>
            </div>

        </form>
        
        <form class="form" action="" method="post">
            <div class="form_title_wrap">
                <div class="form_title_subject line_blue"><h2>最新のあなたの割り勘</h2></div>
            </div>
            <div class="form_main">
            <div class="form_main_wrap">

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


                </thead>
            </table>           
        </div>

 

            
            </div>
            </div>

            <div class="prof_whole">
                <p class="form_last_comment"><a href="payEdit.php">全ての割り勘を一覧で確認する</a></p>
            </div>
                                    
        </form>

        <form class="form" action="" method="post">
            <div class="form_title_wrap">
                <div class="form_title_subject line_blue"><h2>最新のコメント</h2></div>
            </div>
            <div class="form_main">
            <div class="form_main_wrap">

            <div class="reco_wrap">
            <table class="record">
                <thead><tr>
                <th class="reco_date">日時</th>
                <th class="reco_title">割り勘タイトル</th>
                <th class="reco_total">総額</th>
                <th class="reco_main">記入者</th>
                <th class="reco_sub">内容</th>
                <th class="reco_detail">詳細</th>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あなた</td>
                <td class="reco_item">これ○円の間違いでは</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">つなお</td>
                <td class="reco_item">ドンキのセール品とても安い</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">あやね</td>
                <td class="reco_item">私の食費にはアリバイがある</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>

                <tr>
                <td class="reco_item">2019/4/27</td>
                <td class="reco_item">豚バラの細切れ肉</td>
                <td class="reco_item">498円</td>
                <td class="reco_item">赤井</td>
                <td class="reco_item">僕の恋人は、この肉さ</td>
                <td class="reco_item"><a href="payDetail.php">詳細を見る</a></td>
                </tr>


                </thead>
            </table>           
        </div>

 

            
            </div>
            </div>

            <div class="prof_whole">
                <p class="form_last_comment"><a href="payEdit.php">全てのコメントを一覧で確認する</a></p>
            </div>
                                    
        </form>


        </div>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>