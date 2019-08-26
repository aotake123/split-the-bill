    <footer>
     <div class="footer_contents">
      <p class="copywrite">Copyright AOTAKE. ALL RIGHT RESERVED.</p>
      </div>
    </footer>
    
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script>
        
        $(function(){
        //テキストエリアカウント(現在は未実装。割り勘の集計の際に利用)
          var $countUp = $('#js-count'),
              $countView = $('#js-count-view');
          $countUp.on('keyup', function(e){
              $countView.html($(this).val().length);
          });
        });
    </script>
    <script src="slider.js"></script>
  </body>  
</html>
