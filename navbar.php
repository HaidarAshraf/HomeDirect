<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<link rel="stylesheet" href="/FinalYearProject/navbar.css">

<header class="navbar">
  <div class="navbar-container">
  





    <button class="mobile-menu-button" onclick="toggleMobileMenu()">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>











    <a href="index.php" class="navbar-brand">
      <span>Home Direct</span>
    </a>




    <nav class="navbar-nav">
      <a href="/FinalYearProject/properties.php">Properties</a>
      <a href="/FinalYearProject/sell.php">Sell</a>
      <a href="/FinalYearProject/paperwork.php">PaperWork AI</a>
      <a href="/FinalYearProject/mortgage.php">Mortgage</a>
      <a href="/FinalYearProject/map.php">Map</a> 
    </nav>







    <div class="search-signin-wrapper">
      <form action="/FinalYearProject/explore-properties.php" method="GET" class="search-container" style="display: flex; align-items: center;">
        <svg class="search-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
        </svg>
        <input type="text" name="q" placeholder="Search properties..." class="search-input" />
      </form>





      <?php if (isset($_SESSION['full_name'])): 
        $firstName = explode(' ', $_SESSION['full_name'])[0];
      ?>
        <form method="POST" action="api/logout.php" style="margin: 0;">
          <button type="submit" class="sign-in-button" onmouseover="this.innerText='Logout'" onmouseout="this.innerText='<?= $firstName ?>'">
            <?= $firstName ?>
          </button>
        </form>
      <?php else: ?>
        <a href="api/enter-email.php" class="sign-in-button">Sign In</a>
      <?php endif; ?>
    </div>


  </div>



</header>




<div id="mobileMenuOverlay" class="mobile-menu-overlay" onclick="toggleMobileMenu()"></div>

<div id="mobileMenuPanel" class="mobile-menu-panel">



  <div class="mobile-menu-header">
    <h2>Menu</h2>

    <button class="mobile-menu-close" onclick="toggleMobileMenu()">✕</button>
  </div>

  <nav class="mobile-menu-nav">
    <a href="/FinalYearProject/properties.php" class="mobile-menu-link">Properties</a>
    <a href="/FinalYearProject/sell.php" class="mobile-menu-link">Sell</a>
    <a href="/FinalYearProject/paperwork.php" class="mobile-menu-link">PaperWork AI</a>
    <a href="/FinalYearProject/mortgage.php" class="mobile-menu-link">Mortgage</a>
    <a href="/FinalYearProject/map.php" class="mobile-menu-link">Map</a> 
    

  </nav>




  <div class="mobile-menu-footer">
    <?php if (isset($_SESSION['full_name'])): 
      $firstName = explode(' ', $_SESSION['full_name'])[0];
    ?>
      <form method="POST" action="api/logout.php" style="margin: 0;">
        <button type="submit" class="mobile-menu-signin" onmouseover="this.innerText='Logout'" onmouseout="this.innerText='<?= $firstName ?>'">
          <?= $firstName ?>
        </button>


      </form>
    <?php else: ?>
      <a href="api/enter-email.php" class="mobile-menu-signin">Sign In</a>
    <?php endif; ?>
  </div>
</div>




<script>


  function toggleMobileMenu() {
    
    const panel = document.getElementById('mobileMenuPanel');
    const overlay = document.getElementById('mobileMenuOverlay');
    const isOpen = panel.classList.contains('open');

    if (isOpen) {
      panel.classList.remove('open');
      overlay.style.display = 'none';
    } else {
      panel.classList.add('open');
      overlay.style.display = 'block';
    }
  }
</script>
