    <div class="sidebar-wrapper">
        <div>
            <div class="logo-wrapper">
                <a href="<?php echo $base_url; ?>">
                    <img class="img-fluid for-light" src="<?php echo web_logo(); ?>" alt="<?php echo $web_name; ?>">
                </a>
            </div>
            <div class="logo-icon-wrapper">
                <a href="<?php echo $base_url; ?>">
                    <img style="width: 45px;" class="img-fluid" src="<?php echo web_logo_icon(); ?>" alt="">
                </a>
            </div>
            <nav class="sidebar-main">
                <div id="sidebar-menu">
                    <ul class="sidebar-links" id="simple-bar">
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo is_page_active("dashboard"); ?>" href="<?php echo $base_url; ?>/dashboard/"><i class="material-icons-outlined">grid_view</i><span>Dashboard</span></a></li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo is_page_active("user-tree"); ?>" href="<?php echo $base_url; ?>/tree/"><i class="material-icons-outlined"> account_tree</i><span>Tree</span></a></li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo is_page_active("total-team"); ?>" href="<?php echo $base_url; ?>/total-team/"><i class="material-icons-outlined"> groups</i><span>Total Team</span></a></li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo is_page_active("my-referrals"); ?>" href="<?php echo $base_url; ?>/my-referrals/"><i class="material-icons-outlined">person_add_alt</i><span>My Referrals</span></a></li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo is_page_active("package"); ?>" href="<?php echo $base_url; ?>/package/"><i class="material-icons-outlined">workspace_premium</i><span>Package</span></a></li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title <?php echo is_page_active("transactions-history");
                                                                                        echo is_page_active("balance");
                                                                                        echo is_page_active("deposit");
                                                                                        echo is_page_active("deposit-history");
                                                                                        echo is_page_active("withdraw");
                                                                                        echo is_page_active("withdraw-history"); ?> "><i class="material-icons-outlined">account_balance_wallet</i><span>Wallet</span>
                                <div class="according-menu"><i class="material-icons-outlined">angle_down</i></div>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a class="<?php echo is_page_active("transactions-history"); ?>" href="<?php echo $base_url; ?>/wallet/transactions-history">Transactions History</a></li>
                                <li><a class="<?php echo is_page_active("deposit"); ?>" href="<?php echo $base_url; ?>/wallet/deposit">Deposit</a></li>
                                <li><a class="<?php echo is_page_active("deposit-history"); ?>" href="<?php echo $base_url; ?>/wallet/deposit-history">Deposit History</a></li>
                                <li><a class="<?php echo is_page_active("withdraw"); ?>" href="<?php echo $base_url; ?>/wallet/withdraw">Withdraw</a></li>
                                <li><a class="<?php echo is_page_active("withdraw-history"); ?>" href="<?php echo $base_url; ?>/wallet/withdraw-history">Withdraw History</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title <?php echo is_page_active("referral-commission");
                                                                                        echo is_page_active("binary-commission"); ?> "><i class="material-icons-outlined">history</i><span>Income History</span>
                                <div class="according-menu"><i class="material-icons-outlined">angle_down</i></div>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a class="<?php echo is_page_active("binary-commission"); ?>" href="<?php echo $base_url; ?>/income-history/binary-commission">Binary Commission</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo is_page_active("profile"); ?>" href="<?php echo $base_url; ?>/profile/"><i class="material-icons-outlined"> person</i><span>Profile</span></a></li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo is_page_active("support"); ?>" href="<?php echo $base_url; ?>/support/"><i class="material-icons-outlined"> help_center</i><span>Support</span></a></li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo is_page_active("change-password"); ?>" href="<?php echo $base_url; ?>/change-password/"><i class="material-icons-outlined"> lock</i><span>Change Password</span></a></li>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo $base_url; ?>/logout"><i class="material-icons-outlined"> logout</i><span>Log Out</span></a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>