<?php

// Admin user Functions Start

if (!isset($base_url)) {
    exit();
}

$admin_base_url = $base_url . '/admin';

function admin_session_id()
{
    global $web_name;
    if (isset($_COOKIE[$web_name . '_asession_id'])) {
        $session_id = $_COOKIE[$web_name . '_asession_id'];
        return $session_id;
    }
    return false;
}

if (is_admin_loggedin()) {
    global $conn;
    global $login_session_tbl;
    $session_id = admin_session_id();
    $query = mysqli_query($conn, "SELECT * FROM $login_session_tbl WHERE session_id = '$session_id' ");
    $data = mysqli_fetch_array($query);
    $user_id = $data['user_id'];
    $loggedin_admin_id =  $user_id;
}


function is_admin_loggedin()
{
    global $conn;
    global $login_session_tbl;
    global $current_date;

    $admin_session_id = admin_session_id();

    if (!$admin_session_id) {
        return false;
    } else {
        if (is_empty($admin_session_id)) {
            return false;
        } else {
            $query = mysqli_query($conn, "SELECT * FROM $login_session_tbl WHERE session_id = '$admin_session_id' ");
            if (!mysqli_num_rows($query)) {
                return false;
            } else {
                $data = mysqli_fetch_array($query);
                $valid_till = $data['valid_till'];
                if ($valid_till < $current_date) {
                    return false;
                } else {
                    return true;
                }
            }
        }
    }
}

function is_admin_already_loggedin()
{
    global $admin_base_url;
    if (is_admin_loggedin()) {
        locate_to($admin_base_url);
    }
}

function check_admin_login()
{
    global $admin_base_url;
    if (!is_admin_loggedin()) {
        locate_to($admin_base_url . '/login');
    }
}
// Admin user Functions End

function _support_tbl($status)
{
    global $conn;
    global $tickets_tbl;
    global $admin_base_url;
    $query = mysqli_query($conn, "SELECT * FROM $tickets_tbl ORDER BY status ASC ");

    if ($status == 'pending')
        $query = mysqli_query($conn, "SELECT * FROM $tickets_tbl WHERE status = '1' ORDER BY ticket_id DESC ");

    if ($status == 'open')
        $query = mysqli_query($conn, "SELECT * FROM $tickets_tbl WHERE status = '2' ORDER BY ticket_id DESC ");

    if ($status == 'closed')
        $query = mysqli_query($conn, "SELECT * FROM $tickets_tbl WHERE status = '3' ORDER BY ticket_id DESC ");

    if (!mysqli_num_rows($query)) return;
    $output = '';
    $count = 0;
    while ($row = mysqli_fetch_array($query)) {
        $user_id = $row["ticket_creator"];
        $ticket_id = $row['ticket_id'];
        $count++;
        $subject = $row["ticket_subject"];
        $subject = mb_strimwidth($subject, 0, 30, '...');
        $status = $row["status"];
        $date = date_time($row["created_at"]);
        $view_ticket_url = $admin_base_url . '/support/view-ticket?ticket=' . $ticket_id;
        $user_name = user_name($user_id);
        $last_reply_date = $row["last_reply_date"];
        $last_reply_date = date_time($last_reply_date);
        if ($status == "1") {
            $status = '<label class="badge alert-warning" >pending</label>';
        } else
        if ($status == "2") {
            $status = '<label class="badge alert-success" >active</label>';
        } else 
        if ($status == "3") {
            $status = '<label class="badge alert-danger" >closed</label>';
        }

        $output .= '  <tr>
            <td>' . $count . '</td>
            <td>' . $ticket_id . '</td>
            <td>' . $subject . '</td>
            <td>' . $status . '</td>
            <td>' . $user_id . '</td>
            <td>' . $user_name . '</td>
            <td>' . $last_reply_date . '</td>
            <td>' . $date . '</td>
            <td><a href="' . $view_ticket_url . '" class="justify-align-center"><i class="material-icons">visibility</i></a></td>
        </tr>';
    }
    return $output;
}



function _withdraw_tbl($withdraw_status)
{
    global $conn;
    global $admin_base_url;
    global $withdraw_tbl;
    $count = 0;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $withdraw_tbl ORDER BY withdraw_id DESC ");
    if ($withdraw_status == "pending") {
        $query = mysqli_query($conn, "SELECT * FROM $withdraw_tbl WHERE status = 'pending' ORDER BY withdraw_id DESC ");
    }
    if ($withdraw_status == "approved") {
        $query = mysqli_query($conn, "SELECT * FROM $withdraw_tbl WHERE status = 'approved' ORDER BY success_date DESC ");
    }
    if ($withdraw_status == "rejected") {
        $query = mysqli_query($conn, "SELECT * FROM $withdraw_tbl WHERE status = 'rejected' ORDER BY withdraw_id DESC ");
    }

    if (!mysqli_num_rows($query)) return;
    while ($data = mysqli_fetch_array($query)) {
        $count = $count + 1;
        $user_id = $data["user_id"];
        $user_name = user_name($user_id);
        $trx_id = $data["transaction_id"];
        $amount = add_currency($data['amount']);
        $net_amount = add_currency($data["net_amount"]);
        $requested_date = date_time($data['requested_date']);
        $success_date = date_time($data['success_date']);
        $success_date = is_empty($success_date) ? "NA" : $success_date;
        $charge =  add_currency($data["total_charge"]);
        $status = $data['status'];
        if ($status == "approved") {
            $status = '<label class="badge alert-success">' . $status . '</label>';
        } elseif ($status == "pending") {
            $status = '<label class="badge alert-warning">' . $status . '</label>';
        } elseif ($status == "rejected") {
            $status = '<label class="badge alert-danger">' . $status . '</label>';
        }
        $method = $data["payment_method"];
        $success_column = '<td>' . $success_date . '</td>';
        $success_column = $withdraw_status == "pending" ? "" : $success_column;
        $url = $admin_base_url . '/withdraw/withdraw-preview.php?id=' . $trx_id;

        $output .= '<tr>
            <td>' . $count . '</td>
            <td>' . $trx_id . '</td>
            <td>' . $amount . '</td>
            <td>' . $charge . '</td>
            <td>' . $net_amount . '</td>
            <td>' . $method . '</td>
            <td>' . $requested_date . '</td>  
            <td>' . $user_id . '</td>  
            <td>' . $user_name . '</td>
		' . $success_column . '  
            <td>' . $status . '</td>
            <td><a href="' . $url . '"  class="ml-2 btn btn-success align-center width-max btn-sm" ><i class="material-icons-outlined">visibility</i></a></td>
        </tr>';
    }
    return $output;
}

function _user_withdraw_tbl($user_id) 
{
    global $conn;
    global $admin_base_url;
    global $withdraw_tbl;
    $count = 0;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $withdraw_tbl WHERE user_id = '$user_id' ORDER BY withdraw_id DESC ");
    if (!mysqli_num_rows($query)) return;
    while ($data = mysqli_fetch_array($query)) {
        $count = $count + 1;
        $trx_id = $data["transaction_id"];
        $amount =  add_currency($data['amount']);
        $net_amount = add_currency($data["net_amount"]);
        $requested_date = date_time($data['requested_date']);
        $success_date = date_time($data['success_date']);
        $success_date = is_empty($success_date) ? "NA" : $success_date;
        $charge = add_currency($data["total_charge"]);
        $status = $data['status'];
        if ($status == "pending") {
            $status = '<label class="badge alert-warning">' . $status . '</label>';
        } else
        if ($status == "approved") {
            $status = '<label class="badge alert-success">' . $status . '</label>';
        } elseif ($status == "rejected") {
            $status = '<label class="badge alert-danger">' . $status . '</label>';
        }
        $payment_method = $data["payment_method"];
        $url = $admin_base_url . '/withdraw/?id=' . $trx_id;

        $output .= '<tr>
            <td>' . $count . '</td>
            <td>' . $trx_id . '</td>
            <td>' . $amount . '</td>
            <td>' . $charge . '</td>
            <td>' . $net_amount . '</td>
            <td>' . $payment_method . '</td>
            <td>' . $requested_date . '</td>  
            <td>' . $success_date . '</td>
            <td>' . $status . '</td>
            <td> 
            <a href="' . $url . '"  class="ml-2 btn btn-success align-center width-max  btn-sm" ><i class="material-icons-outlined">visibility</i></a>
            </td>
        </tr>';
    }
    return $output;
}

function _deposit_tbl($deposit_status)
{
    global $conn;
    global $admin_base_url;
    global $deposit_tbl;
    $count = 0;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM deposit_tickets ORDER BY ticket_id DESC ");

    if ($deposit_status == "approved") {
        $query = mysqli_query($conn, "SELECT * FROM deposit_tickets WHERE status = '2' ORDER BY ticket_id DESC ");
    } else 
    if ($deposit_status == "pending") {
        $query = mysqli_query($conn, "SELECT * FROM deposit_tickets WHERE status = '1' ORDER BY ticket_id DESC ");
    } else
    if ($deposit_status == "rejected") {
        $query = mysqli_query($conn, "SELECT * FROM deposit_tickets WHERE status = '3' ORDER BY ticket_id DESC ");
    } 


    if (!mysqli_num_rows($query)) return;
    while ($data = mysqli_fetch_array($query)) {
        $count = $count + 1; 
        $user_id = $data["ticket_creator"];
        $user_name = user_name($user_id);
        $amount =  add_currency($data['amount']);
        $requested_date = date_time($data['created_at']);
        $status = $data['status'];
        $status_label = '';
        if ($status == "1") {
            $status_label = '<label class="badge alert-warning">Pending</label>';
        } elseif ($status == "2") {
            $status_label = '<label class="badge alert-success">Approved</label>';
        } elseif ($status == "3") {
            $status_label = '<label class="badge alert-danger">Rejected</label>';
        }
        $gateway = $data["gateway"];
        $transaction_id = $data["transaction_id"];
        $output .= '<tr>
            <td>' . $count . '</td>
            <td>' . $amount . '</td>
            <td>' . $user_name . '</td>
            <td>' . $requested_date . '</td>
            <td>' . $status_label . '</td>  
            <td><a href="' . $admin_base_url . '/deposit/deposit-preview?id=' . $transaction_id . '" class="ml-2 btn btn-success align-center width-max btn-sm"><i class="material-icons-outlined">visibility</i></a></td>
        </tr>';
    }
    return $output;
}

function all_deposit_tbl($deposit_status)
{
    global $conn;
    global $admin_base_url;
    global $deposit_tbl;
    $count = 0;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $deposit_tbl  ORDER BY transaction_id DESC ");

    if (!mysqli_num_rows($query)) return;
    while ($data = mysqli_fetch_array($query)) {
        $count = $count + 1; 
        $user_id = $data["user_id"];
        $user_name = user_name($user_id);
        $amount =  add_currency($data['amount']);
        $requested_date = date_time($data['date']);
        $status = $data['status'];
	$success_date = date_time($data['success_date']);
	$payment_method = $data['gateway'];
        $transaction_id = $data["transaction_id"];
	$status_label = '';
        if ($status == "review") {
            $status_label = '<label class="badge alert-warning">Pending</label>';
        } elseif ($status == "approved") {
            $status_label = '<label class="badge alert-success">Approved</label>';
        } elseif ($status == "rejected") {
            $status_label = '<label class="badge alert-danger">Rejected</label>';
        }
        $output .= '<tr>
            <td>' . $count . '</td>
	    <td>'. $transaction_id.'</td>
            <td>' . $amount . '</td>
	    <td>' . $payment_method . '</td>
	    <td>' . $success_date . '</td>
	    <td>' . $user_id . '</td>
            <td>' . $user_name . '</td>
            <td>' . $status_label . '</td>  
            <td><a href="' . $admin_base_url . '/deposit/deposit-preview?id=' . $transaction_id . '" class="ml-2 btn btn-success align-center width-max btn-sm"><i class="material-icons-outlined">visibility</i></a></td>
        </tr>';
    }
    return $output;
}

function rejected_deposit_tbl($deposit_status)
{
    global $conn;
    global $admin_base_url;
    global $deposit_tbl;
    $count = 0;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM user_deposits where status ='rejected'  ORDER BY transaction_id DESC ");

    if (!mysqli_num_rows($query)) return;
    while ($data = mysqli_fetch_array($query)) {
        $count = $count + 1; 
        $user_id = $data["user_id"];
        $user_name = user_name($user_id);
        $amount =  add_currency($data['amount']);
        $requested_date = date_time($data['date']);
        $status = $data['status'];
        $success_date = date_time($data['success_date']);
        $payment_method = $data['gateway'];
        $transaction_id = $data["transaction_id"];
        $status_label = '';
	$status_label = '<label class="badge alert-danger">Rejected</label>';
        $output .= '<tr>
            <td>' . $count . '</td>
            <td>'. $transaction_id.'</td>
            <td>' . $amount . '</td>
            <td>' . $payment_method . '</td>
            <td>' . $success_date . '</td>
            <td>' . $user_id . '</td>
            <td>' . $user_name . '</td>
            <td>' . $status_label . '</td>
	    <td><a href="' . $admin_base_url . '/deposit/deposit-preview?id=' . $transaction_id . '" class="ml-2 btn btn-success align-center width-max btn-sm"><i class="material-icons-outlined">visibility</i></a></td>  
        </tr>';
    }
    return $output;
}

function approve_deposit_tbl($deposit_status)
{
    global $conn;
    global $admin_base_url;
    global $deposit_tbl;
    $count = 0;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $deposit_tbl WHERE status = 'approved'  ORDER BY transaction_id DESC ");

    if (!mysqli_num_rows($query)) return;
    while ($data = mysqli_fetch_array($query)) {
        $count = $count + 1; 
        $user_id = $data["user_id"];
        $user_name = user_name($user_id);
        $amount =  add_currency($data['amount']);
        $requested_date = date_time($data['date']);
        $status = $data['status'];
        $success_date = date_time($data['success_date']);
        $payment_method = $data['gateway'];
        $transaction_id = $data["transaction_id"];
	$status_label = '<label class="badge alert-success">Approved</label>';
        $output .= '<tr>
            <td>' . $count . '</td>
            <td>'. $transaction_id.'</td>
            <td>' . $amount . '</td>
            <td>' . $payment_method . '</td>
            <td>' . $success_date . '</td>
            <td>' . $user_id . '</td>
            <td>' . $user_name . '</td>
            <td>' . $status_label . '</td>
	    <td><a href="' . $admin_base_url . '/deposit/deposit-preview?id=' . $transaction_id . '" class="ml-2 btn btn-success align-center width-max btn-sm"><i class="material-icons-outlined">visibility</i></a></td>
        </tr>';
    }
    return $output;
}

function _user_deposit_tbl($user_id)
{
    global $conn;
    global $admin_base_url;
    global $deposit_tbl;
    $count = 0;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $deposit_tbl WHERE user_id ='$user_id' ORDER BY deposit_id DESC ");

    if (!mysqli_num_rows($query)) return;
    while ($data = mysqli_fetch_array($query)) {
        $count = $count + 1;
        $user_id = $data["user_id"];
        $trx_id = $data["transaction_id"];
        $amount = add_currency($data['amount']);
        $requested_date = date_time($data['date']);
        $status = $data['status'];
        $success_date = date_time($data["success_date"]);
        $status_label = '';
        if ($status == "approved") {
            $status_label = '<label class="badge alert-success">' . $status . '</label>';
        } elseif ($status == "review") {
            $status_label = '<label class="badge alert-warning">' . $status . '</label>';
        }
        if ($status == "rejected") {
            $status_label = '<label class="badge alert-danger">' . $status . '</label>';
        }
        $gateway = $data["gateway"];

        $output .= '<tr>
            <td>' . $count . '</td>
            <td>' . $trx_id . '</td>
            <td>' . $amount . '</td>
            <td>' . $requested_date . '</td>
            <td>' . $success_date . '</td>
            <td>' . $status_label . '</td>  
            <td><a href="' . $admin_base_url . '/deposit/deposit-preview?id=' . $trx_id . '" class="ml-2 btn btn-success align-center width-max btn-sm"><i class="material-icons-outlined">visibility</i></a></td>
        </tr>';
    }
    return $output;
}


function _users_tbl($status)
{
    global $conn;
    global $users_tbl;
    $output = '';
    $count = 0;
    $query = mysqli_query($conn, "SELECT * FROM $users_tbl");
    if ($status == "active") {
        $query = mysqli_query($conn, "SELECT * FROM $users_tbl WHERE status = 'active' ");
    }
    if ($status == "blocked") {
        $query = mysqli_query($conn, "SELECT * FROM $users_tbl WHERE status = 'blocked' ");
    }
    while ($row = mysqli_fetch_array($query)) {
        $count++;
        $user_id = $row["user_id"];
        $user_name = $row["user_name"];
        $user_image = user_image($user_id);
        $registered_on = user_registration_date($user_id);
        $user_email = user_email($user_id);
        $referral_id = user_referred_by($user_id);
        $user_status = user_status_label($user_id);
        $user = '<div class="align-center">
                           <img style="width:50px;" class="img-fluid rounded-circle" src="' . $user_image . '" alt="' . $user_name . '">
                            <div class="ml-3">
                              <h6 class="f-w-600">' . $user_id . '</h6>
                              <p>' . $user_name . '</p>
                            </div>
                          </div>';
        $output .= '<tr>
            <td>' . $count . '</td>
            <td>' . $user . '</td>
            <td>' . $user_email . '</td>
            <td>' . $referral_id . '</td>
            <td>' . $registered_on . '</td>
            <td id="user_status_' . $user_id . '" >' . $user_status . '</td>
            <td class="dropdown-basic">
                <div class="dropdown">
                      <div class="btn-group mb-0">
                        <button class="dropbtn btn-info" type="button" data-bs-original-title="" title="">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                        <div class="dropdown-content">
                        <a data-id="' . $user_id . '" id="_block_user" class="c-pointer align-center"><i class="material-icons-outlined" >lock</i><span class="ml-2 mt-1">Block user</span></a>
                        <a data-id="' . $user_id . '" id="_unblock_user" class="c-pointer align-center"><i class="material-icons-outlined" >lock</i><span class="ml-2 mt-1">Unblock User</span></a>
                      </div>
                </div>
            </td>
        </tr>';
    }
    return $output;
}

function _withdraw_methods_tbl()
{
    global $conn;
    global $admin_base_url;
    global $withdraw_method_tbl;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROm $withdraw_method_tbl ");
    while ($row = mysqli_fetch_array($query)) {
        $gateway_id = $row["gateway_id"];
        $gateway_name = $row["gateway_name"];
        $processing_time = $row["processing_time"];
        $date_added = date_time($row["date_added"]);
        $status = $row["status"];
        $gateway_image = get_image_src($row["gateway_image"]);
        $btn_class = $status == "enabled" ? "danger" : "success";
        $visibilty = ($status == "enabled") ? "visibility_off" : "visibility";
        $title = ($status == "enabled") ? "Disable" : "Enable";
        $action = '
        <div class="d-flex"><a data-toggle="tooltip" title="Edit" class="btn btn-primary align-center width-max  btn-sm" href="' . $admin_base_url . '/withdraw/edit-withdraw-method?id=' . $gateway_id . '"><i class="material-icons-outlined">edit</i>
        </a><button data-toggle="tooltip" title="' . $title . '" status="' . $status . '" id="_disable_withdraw_gateway" data-name="' . $gateway_name . '" data-id="' . $gateway_id . '" class="ml-2 btn btn-sm btn-' . $btn_class . '" ><i class="material-icons-outlined">' . $visibilty . '</i></button></div>';
        $gateway_name = '
        <div class="align-center" >
            <img class="img-fluid rounded-circle" style="height:50px;width:50px" src=' . $gateway_image . ' >
            <div class="ml-3">' . $gateway_name . '</div>
        </div>';
        if ($status == "enabled") {
            $status = '<label  id="_withdraw_label_' . $gateway_id . '"class="badge alert-success">' . $status . '</label>';
        } elseif ($status == "disabled") {
            $status = '<label id="_withdraw_label_' . $gateway_id . '" class="badge alert-danger">' . $status . '</label>';
        }
        $output .= '
            <tr>
                <td>' . $gateway_name . '</td>
                <td>' . $processing_time . '</td>
                <td>' . $date_added . '</td>
                <td>' . $status . '</td>
                <td>' . $action . '</td>
            </tr>
        ';
    }
    return $output;
}



function _withdraw_requirements($gateway_id)
{
    global $conn;
    global $withdraw_require_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $withdraw_require_tbl WHERE gateway_id = '$gateway_id' ");
    if (!mysqli_num_rows($query)) return;
    $output = '';
    $count = 0;
    while ($row = mysqli_fetch_array($query)) {

        $requirement_id = $row["requirement_id"];
        $label_text = $row["label_text"];
        $is_image_chooser = $row["is_image_chooser"];
        if ($is_image_chooser == 1) {
            $output .= '<div class="col-lg-12 image-chooser mb-3"> <label>' . $label_text . ' </label> (Image Chooser)
                                        <div class="input-group">
                                            <input name="withdraw_image_chooser" data-id="' . $requirement_id . '" data-validate="alpha_numeric" required="" type="text" class="form-control" value="' . $label_text . '" placeholder="Add Label" autocomplete="off">
                                            <div class="input-group-append">
                                                <button data-element="2" id="remove_element" type="button" class="btn btn-danger align-center py-0"><i class="fa fa-minus"></i></button>
                                            </div>
                                            <div class="invalid-feedback">Please provide a valid ' . $label_text . '</div>
                                        </div>
                                    </div>
                                   ';
        } else {
            $output .= '<div class="col-lg-12 mb-3"> <label>' . $label_text . ' </label>
                                        <div class="input-group">
                                            <input name="withdraw_requirements[]" data-id="' . $requirement_id . '" data-validate="alpha_numeric" required="" type="text" class="form-control" value="' . $label_text . '" placeholder="Add Label" autocomplete="off">
                                            <div class="input-group-append">
                                                <button data-element="2" id="remove_element" type="button" class="btn btn-danger align-center py-0"><i class="fa fa-minus"></i></button>
                                            </div>
                                            <div class="invalid-feedback">Please provide a valid ' . $label_text . '</div>
                                        </div>
                                    </div>
                                   ';
        }
    }
    return $output;
}



function _delete_gateway_all_data($gateway_id)
{
    global $conn;
    global $withdraw_method_tbl;
    global $withdraw_require_tbl;
    global $user_gateway_val_tbl;

    $query = mysqli_query($conn, "DELETE FROM $withdraw_method_tbl WHERE gateway_id = '$gateway_id' ");
    if (!$query) {
        return_exit("Error in deleting Withdraw Method");
    }

    $query = mysqli_query($conn, "SELECT * FROM $withdraw_require_tbl WHERE gateway_id = '$gateway_id' ");
    while ($row = mysqli_fetch_array($query)) {
        $requirement_id = $row["requirement_id"];
        $sub_query = mysqli_query($conn, "DELETE FROM $user_gateway_val_tbl WHERE requirement_id = '$requirement_id' ");
    }
    $query = mysqli_query($conn, "DELETE FROM $withdraw_require_tbl WHERE gateway_id = '$gateway_id'");
}

function _manual_deposit_gateways()
{
    global $conn;
    global $web_currency;
    global $admin_base_url;
    global $manual_deposit_method;
    $count = 0;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $manual_deposit_method");
    while ($row = mysqli_fetch_array($query)) {
        $count++;
        $gateway_id = $row["gateway_id"];
        $gateway_name = $row["gateway_name"];
        $processing_time = $row["processing_time"];
        $status = $row["status"];
        $date_created = $row["date_created"];
        $date_created = date_time($date_created);
        $gateway_img = $row['gateway_image'];
        if ($status == "active") {
            $status = '<label class="badge alert-success">' . $status . '</label>';
        } elseif ($status == "inactive") {
            $status = '<label class="badge alert-danger">' . $status . '</label>';
        }
        $gateway_img = get_image_src($gateway_img);
        $gateway = '<div class="align-center">
        <img class="img-fluid rounded-circle" style="height:50px;width:50px" src="' . $gateway_img . '"><div class="ml-3">' . $gateway_name . '</div></div>';
        $output .= '
            <tr>
                <td>' . $count . '</td>
                <td>' . $gateway . '</td>
                <td>' . $web_currency . '</td>
                <td>' . $processing_time . '</td>
                <td>' . $date_created . '</td>
                <td>' . $status . '</td>
                <td><a class="btn btn-primary align-center width-max  btn-sm" href="' . $admin_base_url . '/deposit/edit-deposit-gateway?id=' . $gateway_id . '"><i class="material-icons-outlined">edit</i>
        </a></td>
            </tr>
        ';
    }
    return $output;
}

function _admin_tree_nodes()
{
    global $loggedin_admin_id;
    $users_under_user_tree = users_under_user_tree($loggedin_admin_id);
    foreach ($users_under_user_tree as $user_id) {
        $pid = user_placement_id($user_id);
        $name = user_name($user_id);
        $email = user_email($user_id);
        $img = user_image($user_id);
        $p_t = placement_type($user_id);
        $p_t = ($p_t == "left") ? "0" : "1";
        $output[] = array(
            'id' => $user_id,
            'pid' => $pid,
            'name' => $name,
            'title' => $user_id,
            'email' => $email,
            'img' => $img,
            'p_t' => $p_t
        );
    }
    $tree_p_t = array_column($output, 'p_t');
    array_multisort($tree_p_t, SORT_ASC, $output);
    echo json_encode($output, JSON_PRETTY_PRINT);
}

function _packages_tbl()
{
    global $conn;
    global $admin_base_url;
    global $packages_tbl;
    $count = 0;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $packages_tbl");
    while ($row = mysqli_fetch_array($query)) {
        $count++;
        $package = $row["package"];
        $package_id = $row["package_id"];
        $pair_income = add_currency($row["pair_income"]);
        $max_pair_income = add_currency($row["maximum_pair_income"]);
        $price = add_currency($row["price"]);
        $minimum_withdraw = add_currency($row["minimum_withdraw"]);
        $withdraw_charge = $row["withdraw_charge"] . '%';
        $validity = $row["validity"];
        $status = $row["status"];
        $validity =  ($validity == '0') ? "Unlimited" : $validity . ' Days';


        if ($status == "enable") {
            $status = '<label class="badge alert-success">' . $status . '</label>';
        } elseif ($status == "disable") {
            $status = '<label  class="badge alert-danger">' . $status . '</label>';
        }

        $output .= '
        <tr>
            <td>' . $count . '</td>
            <td>' . $package . '</td>
            <td>' . $price . '</td>
            <td>' . $pair_income . '</td>
            <td>' . $max_pair_income . '</td>
            <td>' . $minimum_withdraw . '</td>
            <td>' . $withdraw_charge . '</td>
            <td>' . $validity . '</td>
            <td>' . $status . '</td>
            <td><a href="' . $admin_base_url . '/settings/edit-package?id=' . $package_id . '" ><i class="material-icons-outlined" >edit</i></a></td>
        </tr>
        ';
    }
    return $output;
}

function _advertisement_list()
{

    global $conn;
    global $admin_base_url;
    global $ads_tbl;
    $count = 0;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $ads_tbl");
    while ($row = mysqli_fetch_array($query)) {
        $adver_id = $row["adver_id"];
        $title = $row["title"];
        $count++;
        $link = $row["link"];
        $price = $row["price"];
        $quantity = $row["quantity"];
        $action = '<a href="' . $admin_base_url . '/ads/edit-advertisement?id=' . $adver_id . '" class="btn btn-info">Edit</a>';
        $output .= '
        <tr>
            <td>' . $count . '</td>
            <td>' . $title . '</td>
            <td>' . $price . '</td>
            <td>' . $quantity . '</td>
            <td>' . $action . '</td>
        </tr>
        ';
    }
    return $output;
}

function _all_packages_charge()
{
    global $conn;
    global $packages_tbl;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $packages_tbl  WHERE status = 'enable'");
    while ($row = mysqli_fetch_array($query)) {
        $package = $row["package"];
        $withdraw_charge = $row["withdraw_charge"] . '%';
        $output .= '<li>
                                                <span class="list-name">' . $package . '</span>
                                                <span class="icon check">' . $withdraw_charge . '</span>
                                            </li>';
    }
    return $output;
}


/// ** Dashboard Functions Start

function primary_id()
{
    global $conn;
    global $users_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $users_tbl ORDER BY id ASC LIMIT 1");
    $row = mysqli_fetch_array($query);
    return $row["user_id"];
}

function _left_joining_count()
{
    $primary_id = primary_id();
    $user_left_count = user_left_count($primary_id);
    return $user_left_count;
}

function _right_joining_count()
{
    $primary_id = primary_id();
    $user_right_count = user_right_count($primary_id);
    return $user_right_count;
}

function _active_members()
{
    global $conn;
    global $users_tbl;
    $query = mysqli_query($conn, "SELECT COUNT(*) FROM $users_tbl WHERE status = 'active' ");
    return mysqli_fetch_array($query)[0];
}

function _today_users_joining()
{
    global $conn;
    global $users_tbl;

    $from_date = strtotime(date("d-m-Y 00:00:00"));
    $to_date = $from_date + 86400;

    $query = mysqli_query($conn, "SELECT COUNT(*) FROM $users_tbl WHERE user_registration_date BETWEEN '$from_date' AND '$to_date' ");
    return mysqli_fetch_array($query)[0];
}

function _withdraw_requests_count()
{
    global $conn;
    global $withdraw_tbl;
    $query = mysqli_query($conn, "SELECT COUNT(*) FROM $withdraw_tbl WHERE status = 'pending' ");
    return mysqli_fetch_array($query)[0];
}

function _withdraw_in_pending()
{
    global $conn;
    global $withdraw_tbl;
    $amount = 0;
    $query = mysqli_query($conn, "SELECT * FROM $withdraw_tbl WHERE status = 'pending' ");
    while ($row = mysqli_fetch_array($query)) {
        $amount += $row["amount"];
    }
    return $amount;
}

function _deposit_requests_count()
{
    global $conn;
    global $deposit_tbl;
    $query = mysqli_query($conn, "SELECT COUNT(*) FROM $deposit_tbl WHERE status = 'review' ");
    return mysqli_fetch_array($query)[0];
}

function _deposit_in_pending()
{
    global $conn;
    global $deposit_tbl;
    $amount = 0;
    $query = mysqli_query($conn, "SELECT * FROM $deposit_tbl WHERE status = 'review' ");
    while ($row = mysqli_fetch_array($query)) {
        $amount += $row["amount"];
    }
    return $amount;
}

function _total_tickets()
{
    global $conn;
    global $tickets_tbl;
    $query = mysqli_query($conn, "SELECT COUNT(*) FROM $tickets_tbl ");
    return mysqli_fetch_array($query)[0];
}

function _pending_tickets()
{
    global $conn;
    global $tickets_tbl;
    $query = mysqli_query($conn, "SELECT COUNT(*) FROM $tickets_tbl WHERE status = '1' ");
    return mysqli_fetch_array($query)[0];
}

function _open_tickets()
{
    global $conn;
    global $tickets_tbl;
    $query = mysqli_query($conn, "SELECT COUNT(*) FROM $tickets_tbl WHERE status = '2'");
    return mysqli_fetch_array($query)[0];
}



function _closed_tickets()
{
    global $conn;
    global $tickets_tbl;
    $query = mysqli_query($conn, "SELECT COUNT(*) FROM $tickets_tbl WHERE status = '3'");
    return mysqli_fetch_array($query)[0];
}

/// ** Dashboard Functions End
