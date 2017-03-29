</div>

</div>
<div class="col-lg-3">

<!-- Chat box -->
      
<iframe id="chat" src="" style="height: 70vh; width: 20vw;"></iframe>
      <script type="text/javascript">
    var chat_token = "<?php
echo $_SESSION['chat_token'];
?>";
$("#chat").attr('src', "<?php echo $_SESSION['chat_server'] ?>/chat?session="+chat_token);
      </script>
</div>
</div>
</div>

</body>
</html>
