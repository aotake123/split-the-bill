  <header>
     
      <div class="header_top">
        <nav class="h_t_nav">
          <ul class="h_t_nav_list">
          <?php
           if(empty($_SESSION['user_id'])){
            ?>
            <li class="h_t_nav_list_item"><a href=index.php>新規登録（無料）</a></li>
            <li class="h_t_nav_list_item"><a href="login.php">ログイン</a></li>
            <?php
           }else{
            ?>
            <li class="h_t_nav_list_item"><a href=mypage.php>マイページ</a></li>
            <li class="h_t_nav_list_item"><a href="logout.php">ログアウト</a></li>
            <?php        
           }
            ?>
          </ul>
        </nav>
      </div>
      
      <div class="header_sub">
        <div class="h_logo_wrap">
          <div class="h_logo_top">
            <img src="images/header_logo.jpg" class="h_logo_jpg" alt="割り勘シェアハウス">
            <h1 class="title">割り勘シェアハウス</h1>
          </div>
          <div class="h_logo_bottom">
            <p>あなたの家の、割り勘の記録・管理をお手伝い</p>
          </div>
        </div>
      </div>
      
      <div class="header_last">
       <div class="header_last_wrap">
        <div class="h_l_tab">
          <ul class="h_l_tab_ul">
            <li class="h_l_tab_li"><a href="index.php">トップ</a></li>
            <li class="h_l_tab_li"><a href="payEditSeparate.php">割り勘の申請</a></li>
            <li class="h_l_tab_li"><a href="payEditReturn.php">割り勘の支払い</a></li>
            <li class="h_l_tab_li"><a href="payList.php">割り勘の一覧</a></li>
            <li class="h_l_tab_li"><a href="profEdit.php">プロフィール編集</a></li>
          </ul>
        </div>
        
        </div>
      </div>
      
    </header>