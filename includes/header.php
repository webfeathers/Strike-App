<!-- // header -->
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
<a class="navbar-brand" href="/">Strike CRM</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li <?php if($selectedHeaderTab == "home"){?> class="active" <?php } ?>><a href="/">Home</a></li>
				<?php if(isset($_SESSION['MM_Username'])) { ?>
				<li <?php if($selectedHeaderTab == "checkin"){?> class="active" <?php } ?>><a href="/customer-check-in.php">Check in</a></li>
				<li <?php if($selectedHeaderTab == "customer"){?> class="active" <?php } ?>><a href="/customers.php">Customers</a></li>
				<li <?php if($selectedHeaderTab == "admin"){?> class="active" <?php } ?>><a href="/admin">Admin</a></li>
				<li><a href="/logout.php">Log out</a></li>
				<?php } else { ?>
				<li><a href="/login.php">Log in</a></li>
				<?php } ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
<!-- // EOF header -->