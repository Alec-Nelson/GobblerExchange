</div>

</div>
<div class="col-lg-3">

<!-- Chat box -->
      
<iframe id="chat" src="http://104.236.205.162/" style="height: 70vh; width: 20vw;"></iframe>
      <script type="text/javascript">
    var chat_token = "<?php
echo $_SESSION['chat_token'];
?>";
$("#chat").attr('src', "http://104.236.205.162/chat?session="+chat_token);
      </script>
</div>
</div>
</div>

</body>
</html>
