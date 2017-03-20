<html>

<head>
  <title>CRNShare</title>
  <link href="<?= BASE_URL ?>/public/css/style.css" type="text/css" rel="stylesheet">
</head>

<body>

  <div id="med_rectangle">
    <div id="signout">Sign Out</div>
    <div id="signedin">Signed in as <em><?php echo $_SESSION['username']?></em></div>
  </div>

  <div id="main">

    <h1>CRNShare</h1>

    <div id="flat_rectangle">
      <ul class="tabrow">
        <li class="selected">Home</li>
        <li>Calendar</li>
        <li>Chat</li>
      </ul>
    </div>

    <div id="flat_rectangle">
      <div class="dropdown">
        <button onclick="myFunction()" class="dropbtn">Change Current CRN</button>
        <div id="myDropdown" class="dropdown-content">
          <a href="#">CRN 00000</a>
          <a href="#">CRN 11111</a>
          <a href="#">CRN 22222</a>
        </div>
      </div>
    </div>

    <div id="tall_rectangle"></div>

    <div id="cal_box"><img src="<?= BASE_URL ?>/public/images/calendar.png" alt="Calendar" style="width:200px;height:200px;"></div>
    <div id="newforumpost">New Forum Post</div>
    <table id="t1">


        <?php
        if($posts != null){
            foreach($posts as $post){
                echo '
              	    <tr>
                	<td id="t1">
                		<div id="up_vote"><img src="'.BASE_URL.'/public/images/up_arrow.png" alt="Up Vote" style="width:20px;height:20px;float:center;"></div>
                		<h3>5</h3>
                		<div id="down_vote"><img src="'.BASE_URL.'/public/images/down_arrow.png" alt="Down Vote" style="width:20px;height:20px;float:center;"></div>
                	</td>
                	<td id="t1"><table id="t2">
                		<tr>
                			<td>
                            <h2>'.$post->get('title').'</h2>

                              <div id="edit_box"><button id="edit_'.$post->get('id').'" class="edit_button" name="button">
                                <img style="width:20px;height:20px;" src="'.BASE_URL.'/public/images/edit.png"/>
                              </button></div>
                              <div id="delete_box"><button id="delete_'.$post->get('id').'" class="delete_button" name="button">
                                <img style="width:20px;height:20px;" src="'.BASE_URL.'/public/images/trash.png"/>
                              </button></div>





                            </td>
                		</tr>
                		<tr>';
                            //get username from id
                            $userid = $post->get('userId');
                            $user_row = User::loadById($userid);
                            $author = $user_row->get('username');
                			echo '<td><h5>submitted on '.$post->get('timestamp').' by <em>'.$author.'</em> to <em>'.$post->get('tag').'</em></h5></td>
                		</tr>
                		<tr>
                			<td>'.$post->get('description').'</td>
                		</tr>
                		<tr>
                			<td><div id="comments">9 comments</h5></div>
                		</tr>
                	</table></td>
              	  </tr>';
              }// end loop
          } //end if
    ?>
	</table>

    <div id="chat_box"><img src="<?= BASE_URL ?>/public/images/chat.png" alt="Chat" style="width:200px;height:250px;"></div>

  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-2.2.0.js"></script>
  <script type="text/javascript" src="<?= BASE_URL ?>/public/js/main.js"></script>
  <script>
  $('.edit_button').click(function(){

          var id = $(this).attr('id');
          var res = id.split("_");
          var edit = res[0];
          var postid = res[1];

          $.post(
              '/CRNShare/editpost/?',
              {
                "postid": postid
               }, "json")
              .done(function(data){
          });

      });
  </script>
</body>

</html>
