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
               <div class="form_title_subject line_blue"><h2>自分で買ったものをメンバーへ割り勘申請する</h2></div>
           </div>
           <div class="form_main">
           <div class="form_main_wrap">

            <div class="area-msg">
                <?php if(!empty($_POST['common'])) echo $err_msg['common']; ?>
            </div>
            
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">割り勘タイトル</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
                <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
           
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">割り勘項目名</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
                <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">新規項目追加はこちら
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
           
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">負担した合計金額</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
               			<div class="p_w_r_left_option1">
                <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
                		</div>
                		<div class="p_w_r_left_option2">円
						</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
           
			
            <div class="prof_whole">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">割り勘相手と金額</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
            			<div class="img_upload_left">
            				<img src="" alt="ログイン者の写真" class="img_prev">
						</div>
            			<div class="img_upload_right equal_btn">
							<div class="img_upload_btn">
         						選択した人物全員に対し<br />
         						均等に割り勘をする
          						<input type="hidden" name="MAX_FILE_SIZE" size="3145728">
          					</div>
            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
			
			 <div class="prof_whole">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form"><!-- タイトル無し --></div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
             			<div class="img_upload_left">
            				<!--  profile写真 -->
            				<img src="" alt="profile" class="img_prev">
						</div>
           				<div class="img_upload_check">
            				<input type="checkbox" name="" value="" class="pay_checkbox">
            			</div>
            			<div class="img_upload_right pay_separate_option">
              			<div class="p_w_r_left_option1">
               				<input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
                		</div>
                		<div class="p_w_r_left_option2">円
						</div>
          				</div>
            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>

			 <div class="prof_whole">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form"><!-- タイトル無し --></div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
             			<div class="img_upload_left">
            				<!--  profile写真 -->
            				<img src="" alt="profile" class="img_prev">
						</div>
           				<div class="img_upload_check">
            				<input type="checkbox" name="" value="" class="pay_checkbox">
            			</div>
            			<div class="img_upload_right pay_separate_option">
              			<div class="p_w_r_left_option1">
               				<input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
                		</div>
                		<div class="p_w_r_left_option2">円
						</div>
          				</div>
            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>

			 <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form"><!-- タイトル無し --></div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
             			<div class="img_upload_left">
            				<!--  profile写真 -->
            				<img src="" alt="profile" class="img_prev">
						</div>
           				<div class="img_upload_check">
            				<input type="checkbox" name="" value="" class="pay_checkbox">
            			</div>
            			<div class="img_upload_right pay_separate_option">
              			<div class="p_w_r_left_option1">
               				<input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
                		</div>
                		<div class="p_w_r_left_option2">円
						</div>
          				</div>
            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			
			
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">レシート画像</div>
            			<div class="p_w_l_attention_self">任意</div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
            			<div class="img_upload_left">
            				<img src="" alt="profile" class="img_prev">
						</div>
            			<div class="img_upload_right">
							<div class="img_upload_btn">
         						アップロードしたい画像を選択
          						<input type="hidden" name="MAX_FILE_SIZE" size="3145728">
          					</div>
           					<p class="img_comment">※イメージ画像・写真を設定できます</p>
            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>

            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">概要/コメント</div>
            			<div class="p_w_l_attention_self">任意</div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
						<textarea name="comment" rows="6" cols="36" class="pay_comment"></textarea>	
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
            
            </div>
           </div>
           

           
            <div class="form_submit form_wide_option_submit">
                <input type="submit" value="メンバーに割り勘を申請する">
            </div>
           
        </form>
            
     </div>

    </main>

    <!-- footer -->

<?php
require('footer.php');
?>