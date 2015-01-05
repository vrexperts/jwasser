  <a  class="menu-icon"><span><?php if(@$_SESSION['session_admin_userid']!=""){echo @$_SESSION['session_admin_username'] ;} else { ?> Guest <?php }?></span></a>
        <ul class="top-menu">
          <?php if(@$_SESSION['session_admin_userid']==""){?><li><a href="profile-login.php">Log In</a></li><?php }?>
          
          <?php if(@$_SESSION['session_admin_type']=="1"){?><li><a href="create-profile.php">Create User</a></li>
          <li><a href="user-list.php">View User</a></li>
          
          <?php }?>
          <?php if(@$_SESSION['session_admin_userid']!=""){?><li><a href="post-comment.php">Comment</a></li><li><a href="profile.php">View Profile</a></li>
          <li><a href="profile-edit.php">Edit Profile</a></li>
          <li><a href="reset-password.php">Reset Password</a></li>
          <li><a href="logout.php">Log Out</a></li><?php }?>
        </ul>
        