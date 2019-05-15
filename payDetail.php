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
               <div class="form_title_subject line_blue"><h2>2019/04/27 「豚肉のバラ肉購入」詳細情報</h2></div>
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
                		<span class="detail_contents">豚肉のバラ肉購入</span>
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
                		<p class="detail_contents">肉類</p>
             		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
           
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">割り勘前の支出総額</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left">
               			<div class="p_w_r_left_option1">
                		<p class="detail_contents">498円</p>
 
                		</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
           
			
            <div class="prof_whole">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">割り勘精算者・金額</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
            			<div class="img_upload_left pay_separate_option pay_detail">
            				<img src="" alt="ログイン者の写真" class="img_prev">
						</div>
            			<div class="img_upload_right pay_separate_option pay_detail pay_detail2">
            			155円
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
             			<div class="img_upload_right pay_separate_option pay_detail">
						155円
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
             			<div class="img_upload_right pay_separate_option pay_detail">
						155円
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
             			<div class="img_upload_right pay_separate_option pay_detail">
						155円
          				</div>
            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			
			
            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">レシート画像</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="img_wrap">
            			<div class="img_upload_left">
            				<img src="" alt="profile" class="img_prev">
						</div>
            			<div class="img_upload_right">
            			</div>
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>

            <div class="prof_whole prof_whole_line">
            	<div class="prof_whole_left">
            		<div class="prof_whole_wrap">
            			<div class="p_w_l_form">概要/コメント</div>
            			<div class="p_w_l_attention_off"></div>
            		</div>
            	</div>
            	<div class="prof_whole_right">
            		<div class="p_w_r_left textarea_option">
					testtesttesttesttesttesttesttestttesttesttesttesttesttesttesttesttesttesttesttestttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest	
            		</div>
            		<div class="p_w_r_right"></div>
            	</div>
			</div>
            
            </div>
           </div>
           
           	<div class="prof_whole">
				<p class="form_last_comment"><a href="payEdit.php">割り勘の内容を編集する</a></p>
			</div>

        </form>
		
		<form class="form" action="" method="post">
           <div class="form_title_wrap">
               <div class="form_title_subject line_blue"><h2>割り勘に対するコメント</h2></div>
           </div>
           <div class="form_main">
           <div class="form_main_wrap">

            <div class="area-msg">
                <?php if(!empty($_POST['common'])) echo $err_msg['common']; ?>
			</div>
			
			<div class="comment_form">
				<div class="form_input form_input_option1">
					<input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
				</div>
				<div class="comment_button">
					<input type="submit" value="送信" id="comment_submit">
				</div>
			</div>
			
			
            <div class="area-msg">
                <?php if(!empty($_POST['email'])) echo $err_msg['email']; ?>
            </div>
			
			<div class="comment_wrap">
				<div class="comment_left">
					<img src="" alt="" class="img_prev">
				</div>
				<div class="comment_right">
					<div class="comment_right_top">
						<div class="comment_name">大木</div>
						<div class="comment_time">2019/04/12 18:16</div>
					</div>
					<div class="comment_right_bottom">
						<p class="comment_main_contents">割り勘で浮いたお金で、カントリーマアムを買おう！</p>
					</div>
				</div>
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