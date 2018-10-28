<div class="header">
  <div class="nav">
    <ul class="leftNav">
      <li><a href="showmodels.php">All Models</a></li>


      <?php
      if (isset($_SESSION['loggedIn'])) {
        echo '<li><a href="#">Watchlist</a></li>';
        echo '<li><a href="logOut.php">Log Out</a></li>';
      } else {
        echo '<li><a href="login.php">Login</a></li>';
      }

      ?>
    </ul>
  </div>
</div>
