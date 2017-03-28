<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="utf-8">
        <title>Gobbler Exchange</title>
        <script src="<?= BASE_URL ?>/public/js/jquery.min.js"></script>
        <script src="<?= BASE_URL ?>/public/js/bootstrap.min.js"></script>
        <script src="<?= BASE_URL ?>/public/js/base.js"></script>
        <link href="<?= BASE_URL ?>/public/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASE_URL ?>/public/css/template.css?ver=<?php echo filemtime('<?= BASE_URL ?>/public/css/template.css');?>" type="text/css" rel="stylesheet">
        <script src="https://use.fontawesome.com/625f8d2098.js"></script>
<script>
$(function(){
    //Listen for a click on any of the dropdown items
    $(".type li").click(function(){
        //Get the value
        var value = $(this).attr("value");
        //Put the retrieved value into the hidden input
        $("input[name='type']").val(value);
    });
});
</script>
    </head>
    <body>
      <div id="banner">
      <div class="container">
        <div class="row">
        <div class="col-lg-8">
            <h1>
                <a class="title" href="<?= BASE_URL ?>" style="text-decoration:none"><span class="maroon">Gobbler</span></a>
                <a class="title" href="<?= BASE_URL ?>" style="text-decoration:none"><span class="orange">Exchange</span></a>

                <!-- <span class="orange">Exchange</span> -->
            </h1>
        </div>
        <div class="col-lg-2">
            <p id = "signedinas" class="orange description" style="float: right;">
            Signed in as
            <?php
            $user = User::loadByid($_SESSION['userId']);
            echo $user->get("username");
             ?>
            </p>
        </div>
        <!-- <div class="col-lg-2"> -->
        <form action="<?= BASE_URL ?>/logout">
            <button id = "signout" type="submit"  class="btn btn-primary" style="float: right;">
                Sign Out
            </button>
          </form>
        <!-- </div> -->
      </div>

<!-- Search bar -->
<div class="container">
    <div class="row">
      <div class="col-lg-2"></div>
      <div class="col-lg-8">
        <div class="input-group">
          <div class="input-group-btn">
            <form method="POST" action="<?= BASE_URL ?>/search" id="searchForm">
            <button id = "searchdropdown" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                CRN <span class="caret"><span>
            </button>
            <ul class="dropdown-menu type" role = "menu" name="types">
              <li value="crn"><a href="#">CRN</a></li>
              <li value="group"><a href="#">Group</a></li>
              <li value="username"><a href="#">Username</a></li>
              <li value="email"><a href="#">Email</a></li>
            </ul>
          </div><!-- /btn-group -->
          <input type="hidden" name="type">
          <input type="text" class="form-control" name="search" placeholder="Search term...">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit">
              <i class="fa fa-search" aria-hidden="true"></i>
            </button>
          </form>
          </span>
        </div><!-- /input-group -->
      </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->
</div>

<br>

    <!-- Class navigation (forum, calendar, notes, whiteboard) -->
    <div class="container">
      <div class="row">
        <div class="col-lg-2" style="text-align: center;">
          <form method="POST" action="<?= BASE_URL ?>/newgroup">
            <button id = "button" type="submit" class="btn btn-primary">
                Create New Group
            </button>
          </form>
        </div>
        <div class="col-lg-8">
            <ul class="nav nav-tabs">
              <li id = "forum" role="presentation"><a href="<?= BASE_URL ?>/forum">Forum</a></li>
              <li id = "calendar" role="presentation"><a href="<?= BASE_URL ?>/calendar">Calendar</a></li>
              <li id = "notes" role="presentation"><a href="<?= BASE_URL ?>/notes">Notes</a></li>
              <li id = "polls" role="presentation"><a href="<?= BASE_URL ?>/polls">Polls</a></li>
              <li id = "whiteboard" role="presentation"><a href="#">Whiteboard</a></li>
            </ul>
        </div>
        <div class="col-lg-2"></div>
      </div>
    </div>
  </div>
</div>


<!-- <br> -->
<div class = "container" id="classTabs">
        <div class="row">
            <div class="col-lg-2">
                <?php
                    $user = User::loadById($_SESSION['userId']);
                    $usergroups = $user->getGroups(); //class UserGroup, not Group!
                    if ($usergroups != null){
                            foreach($usergroups as $usergroup){
                                $usergroupId = $usergroup->get('id');
                                $group = Group::loadById($usergroupId);

                                $id = $group->get('id');
                                $groupname = $group->get('group_name');
                                $link = BASE_URL.'/viewgroup/'.$id;
                ?>
                <a href=<?php echo $link ?> class="list-group-item <?php if($_SESSION['groupId'] == $id) echo 'active' ?>"><?php echo $groupname ?></a>

                <?php }} ?>
            </div>
            <div class="col-lg-7">

                <!-- Main space -->
                <div id="module">


                  <?php
                      if(isset($_SESSION['error']))
                      {
                        if($_SESSION['error'] != '')
                        {
                          echo "<div class='alert alert-danger' role='alert'>".$_SESSION['error']."</div>";
                          $_SESSION['error'] = '';
                        }
                      }
                    ?>
