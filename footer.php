    <footer>
     <div class="footer_contents">
      <p class="copywrite">Copyright AOTAKE. ALL RIGHT RESERVED.</p>
      </div>
    </footer>
    
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script>
        $(function(){
        //テキストエリアカウント
          var $countUp = $('#js-count'),
              $countView = $('#js-count-view');
          $countUp.on('keyup', function(e){
              $countView.html($(this).val().length);
          });
        }):
    </script>
  </body>  
</html>
