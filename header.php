<div class="header">
  <div class="nav">
    <ul class="leftNav">
      <!-- First link in nav bar is unsecure showmodels page -->
      <li><a href="<?php echo 'http://' . $_SERVER["HTTP_HOST"] . '/kycheung/A4/IAT352-A4/showmodels.php'; ?>">All Models</a></li>

      <?php
      if (isset($_SESSION['loggedIn'])) {
        //Second link would be watchlist, if logged in, redirecting to unsecure watchlist.php
        echo '<li><a href="http://' . $_SERVER["HTTP_HOST"] . '/kycheung/A4/IAT352-A4/watchlist.php">Watchlist</a></li>';
        //If logged in, third link would be log out button going to logout.php
        echo '<li><a href="logOut.php">Log Out</a></li>';
        //Say hi to user    o/ Hi!! Can you hear me?       \o hii!!!YES!!!             \o/ \o/ yay! \o/ \o/
        echo "<h3 class='hiUser'>Hi {$_SESSION['firstName']}</h3>";
      } else {
        //if not logged in, third button would be log in button redirecting to login page with https on
        echo '<li><a href= "https://' . $_SERVER["HTTP_HOST"] . '/kycheung/A4/IAT352-A4/login.php">Login</a></li>';
      }

      ?>
    </ul>
  </div>
</div>
