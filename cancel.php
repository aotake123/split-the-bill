<?php

//共通変数・関数ファイル
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('退会ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

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
                <input type="submit" value="今すぐ退会を完了する">
            </div>
           
        </form>
            
     </div>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>