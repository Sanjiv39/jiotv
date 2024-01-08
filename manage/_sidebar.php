<div id="layoutSidenav_nav">
   <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
      <div class="sb-sidenav-menu">
         <div class="nav">
            <a class="nav-link" href="dashboard.php" <?php if(stripos(basename($_SERVER['SCRIPT_NAME']), "dashboard") !== false){ ?> style="color: #FFFFFF; font-weight: body;"<?php } ?>>
               <div class="sb-nav-link-icon"><i class="fa-sharp fa-solid fa-list-check" <?php if(stripos(basename($_SERVER['SCRIPT_NAME']), "dashboard") !== false){ ?> style="color: #FFFFFF; font-weight: body;"<?php } ?>></i></div>
               Dashboard
            </a>
            <a class="nav-link" href="applogin.php" <?php if(stripos(basename($_SERVER['SCRIPT_NAME']), "applogin") !== false){ ?> style="color: #FFFFFF; font-weight: body;"<?php } ?>>
               <div class="sb-nav-link-icon"><i class="fa-sharp fa-solid fa-list-check" <?php if(stripos(basename($_SERVER['SCRIPT_NAME']), "applogin") !== false){ ?> style="color: #FFFFFF; font-weight: body;"<?php } ?>></i></div>
               Jio Settings
            </a>
            <a class="nav-link" href="streamsetttings.php" <?php if(stripos(basename($_SERVER['SCRIPT_NAME']), "streamsetttings") !== false){ ?> style="color: #FFFFFF; font-weight: body;"<?php } ?>>
               <div class="sb-nav-link-icon"><i class="fa-solid fa-list" <?php if(stripos(basename($_SERVER['SCRIPT_NAME']), "streamsetttings") !== false){ ?> style="color: #FFFFFF; font-weight: body;"<?php } ?>></i></div>
               Stream Settings
            </a>
         </div>
      </div>
      <div class="sb-sidenav-footer">
         <div class="small" id="kcef0c2">Logged in as:</div>
         <span style="color: white; font-weight: bold;">admin</span>
      </div>
   </nav>
</div>