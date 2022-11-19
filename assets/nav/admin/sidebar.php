    <div class="sidebar-wrapper">
        <div>
            <div class="logo-wrapper">
                <a href="<?php echo $admin_base_url; ?>">
                    <img class="img-fluid for-light" src="<?php echo web_logo(); ?>" alt="<?php echo $web_name; ?>">
                    <h3 class="small">Admin Panel</h3>
                </a>
            </div>
            <div class="logo-icon-wrapper">
                <a href="<?php echo $admin_base_url; ?>">
                    <img style="width: 45px;" class="img-fluid" src="<?php echo web_logo_icon(); ?>" alt="">
                </a>
            </div>
            <nav class="sidebar-main">
                <div id="sidebar-menu">
                    <ul class="sidebar-links" id="simple-bar">
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo is_page_active("dashboard"); ?>" href="<?php echo $admin_base_url; ?>/dashboard/"><i class="material-icons-outlined">grid_view</i><span>Dashboard</span></a></li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo is_page_active("package"); ?>" href="<?php echo $admin_base_url; ?>/package/"><i class="material-icons-outlined">workspace_premium</i><span>Package</span></a></li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo is_page_active("admin-tree"); ?>" href="<?php echo $admin_base_url; ?>/tree/"><i class="material-icons-outlined"> account_tree</i><span>Tree</span></a></li>

                        <!-- manage users  -->
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title <?php echo is_page_active("all-users");
                                                                                        echo
                                                                                        is_page_active("active-users");
                                                                                        echo is_page_active("blocked-users"); ?> "><i class="material-icons-outlined">groups</i><span>Manage Users</span>
                                <div class="according-menu"><i class="material-icons-outlined">angle_down</i></div>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a class="<?php echo is_page_active("all-users"); ?>" href="<?php echo $admin_base_url; ?>/users/all-users">All Users</a></li>
                                <li><a class="<?php echo is_page_active("active-users"); ?>" href="<?php echo $admin_base_url; ?>/users/active-users">Active Users</a></li>
                                <li><a class="<?php echo is_page_active("blocked-users"); ?>" href="<?php echo $admin_base_url; ?>/users/blocked-users">Blocked Users</a></li>
                            </ul>
                        </li>
                        <!--  -->

                        <!--withdraw system -->
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title <?php echo is_page_active("withdraw-methods");
                                                                                        echo is_page_active("pending-withdraws");
                                                                                        echo is_page_active("approved-withdraws");
                                                                                        echo is_page_active("rejected-withdraws");
                                                                                        echo is_page_active("all-withdraws"); ?> "><i class="material-icons-outlined">account_balance</i><span>Withdraw System</span>
                                <div class="according-menu"><i class="material-icons-outlined">angle_down</i></div>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a class="<?php echo is_page_active("pending-withdraws"); ?>" href="<?php echo $admin_base_url; ?>/withdraw/pending-withdraws">Pending Withdraws</a></li>
                                <li><a class="<?php echo is_page_active("approved-withdraws"); ?>" href="<?php echo $admin_base_url; ?>/withdraw/approved-withdraws">Approved Withdraws</a></li>
                                <li><a class="<?php echo is_page_active("rejected-withdraws"); ?>" href="<?php echo $admin_base_url; ?>/withdraw/rejected-withdraws">Rejected Withdraws</a></li>
                                <li><a class="<?php echo is_page_active("all-withdraws"); ?>" href="<?php echo $admin_base_url; ?>/withdraw/all-withdraws">All Withdraws</a></li>
                                <li><a class="<?php echo is_page_active("withdraw-methods"); ?>" href="<?php echo $admin_base_url; ?>/withdraw/withdraw-methods">Withdraw Methods</a></li>
                            </ul>
                        </li>
                        <!--  -->

                        <!--Deposit System -->
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title <?php echo is_page_active("automatic-gateway");
                                                                                        echo is_page_active("automatic-deposits");
                                                                                        echo is_page_active("manual-methods");
                                                                                        echo is_page_active("pending-deposits");
                                                                                        echo is_page_active("approved-deposits");
                                                                                        echo is_page_active("rejected-deposits");
                                                                                        echo is_page_active("all-deposits"); ?> "><i class="material-icons-outlined">payment</i><span>Deposit System</span>
                                <div class="according-menu"><i class="material-icons-outlined">angle_down</i></div>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a class="<?php echo is_page_active("pending-deposits"); ?>" href="<?php echo $admin_base_url; ?>/deposit/pending-deposits">Pending Deposits</a></li>
                                <li><a class="<?php echo is_page_active("approved-deposits"); ?>" href="<?php echo $admin_base_url; ?>/deposit/approved-deposits">Approved Deposits</a></li>
                                <li><a class="<?php echo is_page_active("rejected-deposits"); ?>" href="<?php echo $admin_base_url; ?>/deposit/rejected-deposits">Rejected Deposits</a></li>
                                <li><a class="<?php echo is_page_active("all-deposits"); ?>" href="<?php echo $admin_base_url; ?>/deposit/all-deposits">All Deposits</a></li>
                                <li><a class="<?php echo is_page_active("manual-methods"); ?>" href="<?php echo $admin_base_url; ?>/deposit/manual-methods">Manual Gateway</a></li>
                            </ul>
                        </li>
                        <!--  -->

                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo is_page_active("support"); ?>" href="<?php echo $admin_base_url; ?>/support/"><i class="material-icons-outlined"> help_center</i><span>Support Tickets</span></a></li>

                        <!--  -->
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title <?php echo is_page_active("email-setting");
                                                                                        echo is_page_active("package-setting");
                                                                                        echo  is_page_active("basic-setting");  ?> "><i class="material-icons-outlined">settings</i><span>Settings</span>
                                <div class="according-menu"><i class="material-icons-outlined">angle_down</i></div>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a class="<?php echo is_page_active("email-setting"); ?>" href="<?php echo $admin_base_url; ?>/settings/email-setting">Email</a></li>
                                <li><a class="<?php echo is_page_active("package-setting"); ?>" href="<?php echo $admin_base_url; ?>/settings/package-setting">Package</a></li>
                                <li><a class="<?php echo is_page_active("basic-setting"); ?>" href="<?php echo $admin_base_url; ?>/settings/basic-setting">Basic</a></li>

                            </ul>
                        </li>
                        <!--  -->

                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav " href="<?php echo $admin_base_url; ?>/export-database"><i class="material-icons-outlined">download</i><span>Download Database</span></a></li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo is_page_active("change-password"); ?>" href="<?php echo $admin_base_url; ?>/change-password"><i class="material-icons-outlined">lock</i><span>Change Password</span></a></li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo $admin_base_url; ?>/logout"><i class="material-icons-outlined"> logout</i><span>Log Out</span></a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>