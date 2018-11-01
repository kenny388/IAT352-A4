<div class="header">
  <div class="nav">
    <ul class="leftNav">
      <li><a href="<?php echo 'http://' . $_SERVER["HTTP_HOST"] . '/kycheung/A4/IAT352-A4/showmodels.php'; ?>">All Models</a></li>


      <?php
      if (isset($_SESSION['loggedIn'])) {
        echo '<li><a href="http://' . $_SERVER["HTTP_HOST"] . '/kycheung/A4/IAT352-A4/watchlist.php">Watchlist</a></li>';
        echo '<li><a href="logOut.php">Log Out</a></li>';
        //Say hi to user    o/ Hi!! Can you hear me?       \o hii!!!YES!!!             \o/ \o/ yay! \o/ \o/
        echo "<h3 class='hiUser'>Hi {$_SESSION['firstName']}</h3>";
      } else {
        echo '<li><a href= "https://' . $_SERVER["HTTP_HOST"] . '/kycheung/A4/IAT352-A4/login.php">Login</a></li>';
      }

      ?>
    </ul>
  </div>
</div>
