     <style>
         :root {
             --theme-default: <?php echo web_primary_color(); ?>
         }
     </style>
     <div class="page-header">
         <div class="header-wrapper row m-0">
             <div class="left-header d-flex col horizontal-wrapper ps-0">
                 <div class="align-center toggle-sidebar" checked="checked">
                     <i class="material-icons-outlined">menu_open</i>
                 </div>
             </div>
             <ul class="nav-menus">
                 <li class="profile-nav">
                     <div class="header-profile">
                         <img src="<?php echo user_image($loggedin_user_id); ?>" alt="user">
                         <div class="media-body">
                             <div><?php echo user_name($loggedin_user_id); ?></div>
                             <div class="font-12 text-muted "><?php echo $loggedin_user_id; ?></div>
                         </div>
                     </div>
                 </li>
             </ul>
         </div>
     </div>