<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="utf-8">
        <title>Gobbler Exchange</title>
        <script src="<?= BASE_URL ?>/public/js/jquery.min.js"></script>
        <script src="<?= BASE_URL ?>/public/js/bootstrap.min.js"></script>
        <script src="<?= BASE_URL ?>/public/js/base.js"></script>
        <link href="<?= BASE_URL ?>/public/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASE_URL ?>/public/css/template.css?ver=<?php echo filemtime('<?= BASE_URL ?>/public/css/base.css');?>" type="text/css" rel="stylesheet">
        <script src="https://use.fontawesome.com/625f8d2098.js"></script>
    </head>
    <body>
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
            <p id = "signedinas" class="description" style="float: right;">
            Signed in as...
            </p>
        </div>
        <!-- <div class="col-lg-2"> -->
            <button id = "signout" type="button" class="btn btn-primary" style="float: right;">
                Sign Out
            </button>
        <!-- </div> -->
      </div>

<!-- Search bar -->
<div class="container">
    <div class="row">
      <div class="col-lg-2"></div>
      <div class="col-lg-8">
        <div class="input-group">
          <div class="input-group-btn">
            <button id = "searchdropdown" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                CRN <span class="caret"><span>
            </button>
            <ul class="dropdown-menu" role = "menu">
              <li><a href="#">CRN</a></li>
              <li><a href="#">Group</a></li>
              <li><a href="#">Username</a></li>
              <li><a href="#">Email</a></li>
            </ul>
          </div><!-- /btn-group -->
          <input type="hidden" name="search_param" value="all" id="search_param">
          <input type="text" class="form-control" name="x" placeholder="Search term...">
          <span class="input-group-btn">
            <button class="btn btn-default" type="button">
              <i class="fa fa-search" aria-hidden="true"></i>
            </button>
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
          <form method="POST" action="<?= BASE_URL ?>/jsontable">
            <button id = "button" type="submit" class="btn btn-primary">
                New Class
            </button>
          </form>
        </div>
        <div class="col-lg-8">
            <ul class="nav nav-tabs">
                <?php
                    $forumlink = BASE_URL."/forum/".$_SESSION['groupId'];
                    $calendarlink = BASE_URL."/calendar/".$_SESSION['groupId'];
                    $noteslink = BASE_URL."/notes/".$_SESSION['groupId'];
                    $pollslink = BASE_URL."/polls/".$_SESSION['groupId'];
                ?>
              <li id = "tab" role="presentation"><a href=<?php echo $forumlink ?>>Forum</a></li>
              <li id = "tab" role="presentation"><a href=<?php echo $calendarlink ?>>Calendar</a></li>
              <li id = "tab" role="presentation"><a href=<?php echo $noteslink ?>>Notes</a></li>
              <li id = "tab" role="presentation"><a href=<?php echo $pollslink ?>>Polls</a></li>
              <li id = "tab" role="presentation"><a href="#">Whiteboard</a></li>
            </ul>
        </div>
        <div class="col-lg-2"></div>
      </div>
    </div>

<br>
<div class = "container" id="classTabs">
        <div class="row">
            <div class="col-lg-2">
                <?php
                    $user = User::loadByid(1/*$_SESSION['userId']*/);                   //TODO IMPLEMENT
                    $usergroups = $user->getGroups(); //class UserGroup, not Group!
                    if ($usergroups != null){
                            foreach($usergroups as $usergroup){
                                $usergroupId = $usergroup->get('id');
                                $group = Group::loadById($usergroupId);

                                $id = $group->get('id');
                                $groupname = $group->get('group_name');
                                $link = BASE_URL.'/viewgroup/'.$id;
                ?>
                <a href=<?php echo $link ?> class="list-group-item"><?php echo $groupname ?></a>

                <?php }} ?>
            </div>
            <div class="col-lg-7">

                <!-- Main space -->
                <div id="module">
