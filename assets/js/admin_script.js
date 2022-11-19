//

$(document).on("submit", "#_login_form", function (event) {
    event.preventDefault();
    let $form = $(this);
    let $form_data = $form.serialize();
    let $keeplogged = 0;
    if ($("input#rememberme").is(":checked")) {
        $keeplogged = 1;
    }
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $form_data + "&keeplogged=" + $keeplogged + "&case=login&action=_admin_account",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $url = $response.url;
                $message = $response.message;
                sweetalert_redirect("success", $message, $url, "replace");
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_form($form);
        },
    });
});


$(document).on("submit", "#_reply_ticket_form", function () {
    let $form = $(this);
    let $formdata = $form.serialize();
    let $images = $form.attr("images");
    let $ticket_id = _GET("ticket");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata + "&images=" + $images + "&ticket_id=" + $ticket_id + "&case=reply_ticket&action=_support_action",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                sweetalert_redirect("success", $message, $url, "href");
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_form($form);
        }
    })
});

$(document).on("click", "#_cls_btn", function () {
    let $button = $(this);
    let $ticket_id = _GET("ticket");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: {
            ticket_id: $ticket_id,
            case: "close_ticket",
            action: "_support_action"
        },
        beforeSend: function () {
            disable_btn($button);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                sweetalert_redirect("success", $message, $url, "href");
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_btn($button);
        }
    })
});

// --- Change Password Start
$(document).on("submit", "#_change_password_form", function () {
    let $form = $(this);
    let $formdata = $form.serialize();
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata + "&case=change_password&action=_admin_account",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $form.trigger("reset").removeClass("was-validated");
                sweetalert("success", $message);
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_form($form);
        }
    })
})
// --- Change Password End

$(document).on("submit", "#_email_setting_form", function () {
    let $form = $(this);
    let $formdata = $form.serialize();
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata + "&case=email_setting&action=_setting",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                sweetalert("success", $message);
                diable_ajax_form($form);
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_form($form);
        }
    })
});

$(document).on("submit", "#_test_email_form", function () {
    let $form = $(this);
    let $formdata = $form.serialize();
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata + "&case=test_email&action=_setting",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                sweetalert("success", $message);
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_form($form);
        }
    })
});

$(document).on("click", "#_block_user", function () {
    let $button = $(this);
    let $user_id = $button.data("id");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: {
            status: "block",
            user_id: $user_id,
            case: "change_user_status",
            action: "_user_action"
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                sweetalert("success", $message);
                $("#user_status_" + $user_id).find("label").html("blocked").removeClass("alert-success").addClass("alert-danger");
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        }
    })
});

$(document).on("click", "#_unblock_user", function () {
    let $button = $(this);
    let $user_id = $button.data("id");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: {
            status: "unblock",
            user_id: $user_id,
            case: "change_user_status",
            action: "_user_action"
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                sweetalert("success", $message);
                $("#user_status_" + $user_id).find("label").html("active").removeClass("alert-danger").addClass("alert-success");
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        }
    })
})

$(document).on("change", "#gateway_img", function () {
    let $input = $(this);
    let $filename = document.getElementById("gateway_img").files[0].name;
    let $form_data = new FormData();
    let $ext = $filename.split('.').pop().toLowerCase();
    if (jQuery.inArray($ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
        sweetalert("error", "Invalid Image File Type");
        return;
    }
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("gateway_img").files[0]);
    $file = document.getElementById("gateway_img").files[0];
    let $fsize = $file.size || $file.fileSize;
    if ($fsize > 4000000) {
        sweetalert("error", "Image File Size is very big");
    } else {
        $form_data.append("gateway_thumbnail", $file);
        $form_data.append("case", "upload_gateway_thumbnail");
        $form_data.append("action", "_withdraw_action");
        $.ajax({
            url: $ajax_url,
            method: "POST",
            data: $form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function ($response) {
                if (isJSON($response)) {
                    $response = JSON.parse($response);
                    $image_url = $response.image_url;
                    $image_id = $response.image_id;
                    $("#gateway_img_preview").attr("src", $image_url);
                    $("#gateway_file_name").val($image_id)
                } else {
                    sweetalert("error", $response);
                }
            },
            error: function () {
                sweetalert("error", $ajax_error);
            }
        });
    }
});


$(document).on("submit", "#create_withdraw_method_form", function () {
    let $form = $(this);
    let $formdata = $form.serialize();
    let $action_type = $form.data("action");
    let $requirements = [];
    $('input[name="withdraw_requirements[]"]').each(function () {
        $this = $(this);
        $id = $this.data('id');
        $val = $this.val();
        $array = [$id, $val];
        $requirements.push($array);
    })
    $requirements = JSON.stringify($requirements);
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata + "&requirements=" + $requirements + "&action_type=" + $action_type + "&case=create_withdraw_method&action=_withdraw_action",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                sweetalert_redirect("success", $message, $url, "href");
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_form($form);
        }
    })
});

$(document).on("click", "#_disable_withdraw_gateway", function () {
    let $button = $(this);
    let $gateway_id = $button.data("id");
    let $gateway_name = $button.data("name");
    let $status = $button.attr("status");
    let $message = "Want to disable <b class='text-original'>" + $gateway_name + "</b> method?";
    let $btn_text = "Disable";
    if ($status == "disabled") {
        $message = "Want to enable <b class='text-original'>" + $gateway_name + "</b> method?";
        $btn_text = "Enable";
    }
    let $confirmDelete = confirmDelete($button, $message, $btn_text);
    if (!$confirmDelete) {
        return;
    }
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: {
            gateway_id: $gateway_id,
            case: "withdraw_gateway_status",
            action: "_withdraw_action"
        },
        beforeSend: function () {
            disable_btn($button, false);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                sweetalert("success", $message);
                $status = $response.status;
                if ($status == "enabled") {
                     $button.attr("data-original-title", "Disable");
                    $button.attr("status", "enabled").addClass("btn-danger").removeClass("btn-success");
                    $button.find("i").html("visibility_off");
                    $("#_withdraw_label_" + $gateway_id).html("enabled").addClass("alert-success").removeClass("alert-danger");
                }
                if ($status == "disabled") {
                     $button.attr("data-original-title", "Enable");
                    $button.attr("status", "disabled").addClass("btn-success").removeClass("btn-danger");
                    $button.find("i").html("visibility");
                    $("#_withdraw_label_" + $gateway_id).html("disabled").removeClass("alert-success").addClass("alert-danger");
                }

            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_btn($button);
        }
    })
})


$(document).on("click", "#_delete_withdraw_method", function () {
    let $button = $(this);
    let $confirmDelete = confirmDelete($button, "Want to delete the withdraw method <br> All user data will be loosed", "Delete");
    if (!$confirmDelete) {
        return;
    }
    let $gateway_id = _GET("id");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: {
            gateway_id: $gateway_id,
            case: "delete_withdraw_gateway",
            action: "_withdraw_action"
        },
        beforeSend: function () {
            disable_btn($button);
        },
        success: function ($response) {
           if(isJSON($response)){
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                sweetalert_redirect("success", $message, $url, "replace");
           }else{
               sweetalert("error",$response);
           }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_btn($button);
        }
    })
})

$(document).on("click", "#_add_withdraw_req_row", function () {
    let $container = $("#withdraw_requirements_card").find(".card-body");
    let $maximum_childs = 10;
    let $present_childs = $("#withdraw_requirements_card").find(".card-body > div").length;
    if ($present_childs <= $maximum_childs) {
        $new_row = '<div class="col-lg-12 mb-3">\
    <label>Add Label</label>\
                                        <div class="input-group">\
                                            <input name="withdraw_requirements[]" data-id="0" data-validate="alpha_numeric" required="" type="text" class="form-control" placeholder="Add Label" autocomplete="off">\
                                            <div class="input-group-append">\
                                                <button data-element="2" id="remove_element" type="button" class="btn btn-danger align-center py-0"><i class="fa fa-minus"></i></button>\
                                            </div>\
                                            <div class="invalid-feedback">Please provide a valid Value</div>\
                                        </div>\
                                    </div>';

        $container.append($new_row);
    } else {
        sweetalert("error", "Maximum " + $maximum_childs + " label rows are allowed");
    }
});

$(document).on("keyup input", '#withdraw_requirements_card input:not([name=requirement_card_heading])', function () {
    let $input = $(this);
    let $text = $input.val();
    if (is_empty($text)) {
        $text = "Add Label";
    }
    $input.parent().siblings("label").html($text);
})

$(document).on("keyup input", "input[name=requirement_card_heading]", function () {
    let $input = $(this);
    let $value = $input.val();
    let $header = $("#withdraw_requirements_card").find(".card-header").find("h3");
    $header.html($value);
});


$(document).on("click", "#_add_gateway_image_chooser", function () {
    let $maximum_childs = 1;
    let $present_childs = $("#withdraw_requirements_card").find(".card-body").find(".image-chooser").length;
    if ($present_childs < $maximum_childs) {
        $html = '<div class="col-lg-12 mb-3 image-chooser">\
    <label>Add Label </label><small> (Image Chooser)</small>\
                                        <div class="input-group">\
                                            <input name="withdraw_image_chooser" data-validate="alpha_numeric" required="" type="text" class="form-control" placeholder="Add Label" autocomplete="off">\
                                            <div class="input-group-append">\
                                                <button data-element="2" id="remove_element" type="button" class="btn btn-danger align-center py-0"><i class="fa fa-minus"></i></button>\
                                            </div>\
                                            <div class="invalid-feedback">Please provide a valid Value</div>\
                                        </div>\
                                    </div>';
        $("#withdraw_requirements_card").find(".card-body").append($html);
    } else {
        sweetalert("error", "Maximum " + $maximum_childs + " image row is allowed");
    }
})


$(document).on("submit", "#_approve_withdraw", function () {
    let $form = $(this);
    let $message_to_user = $form.find("textarea[name=message_to_user]").val();
    let $transaction_id = _GET("id");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: {
            transaction_id: $transaction_id,
            message_to_user: $message_to_user,
            case: "approve_user_withdraw",
            action: "_withdraw_action"
        },
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                sweetalert_redirect("success", $message, $url, "replace");
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $response);
        },
        complete: function () {
            enable_form($form);
        }
    })
})

$(document).on("submit", "#_reject_withdraw", function () {
    let $form = $(this);
    let $reject_reason = $form.find("textarea[name=reject_reason]").val();
    let $transaction_id = _GET("id");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: {
            transaction_id: $transaction_id,
            reject_reason: $reject_reason,
            case: "reject_user_withdraw",
            action: "_withdraw_action"
        },
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                sweetalert_redirect("success", $message, $url, "replace");
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $response);
        },
        complete: function () {
            enable_form($form);
        }
    })
})


$(document).on("submit", "#_approve_deposit", function () {
    let $form = $(this);
    let $message_to_user = $form.find("textarea[name=message_to_user]").val();
    let $transaction_id = _GET("id");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: {
            transaction_id: $transaction_id,
            message_to_user: $message_to_user,
            case: "approve_user_deposit",
            action: "_withdraw_action"
        },
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                sweetalert_redirect("success", $message, $url, "replace");
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $response);
        },
        complete: function () {
            enable_form($form);
        }
    })
})

$(document).on("submit", "#_reject_deposit", function () {
    let $form = $(this);
    let $reject_reason = $form.find("textarea[name=reject_reason]").val();
    let $transaction_id = _GET("id");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: {
            transaction_id: $transaction_id,
            reject_reason: $reject_reason,
            case: "reject_user_deposit",
            action: "_withdraw_action"
        },
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                sweetalert_redirect("success", $message, $url, "replace");
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $response);
        },
        complete: function () {
            enable_form($form);
        }
    })
})

$(document).on("click", "#_add_deposit_detail_row", function () {
    let $maximum_childs = 10;
    let $present_childs = $("#_deposit_detail_input_container > .row").length;
    if ($present_childs <= $maximum_childs) {
        let $row = '<div class="row">\
                                        <div class="col-lg-5 mb-3">\
                                            <div class="input-group">\
                                                <div class="input-group-prepend">\
                                                    <span class="input-group-text"><i class="material-icons-outlined">attach_money</i></span>\
                                                </div>\
                                                <input required="" name="detail_label[]" type="text" class="form-control" placeholder="Add label" autocomplete="off">\
                                                <input required="" value="0" name="charge_ids[]" type="text" class="form-control d-none" autocomplete="off">\
                                                <div class="invalid-feedback">Please provide a valid Label</div>\
                                            </div>\
                                        </div>\
                                        <div class="col-lg-5  mb-3">\
                                            <div class="input-group">\
                                                <div class="input-group-prepend">\
                                                    <span class="input-group-text"><i class="material-icons-outlined">attach_money</i></span>\
                                                </div>\
                                                <input required="" name="detail_value[]" type="text" class="form-control" placeholder="Value" autocomplete="off">\
                                                <div class="invalid-feedback">Please provide a valid Value</div>\
                                            </div>\
                                        </div>\
                                        <div class="col-lg-2 mb-3">\
                                            <button type="button" id="remove_element" data-element="1" class="btn btn-danger align-center"><i class="fa fa-minus"></i></button>\
                                        </div></div>';
        $("#_deposit_detail_input_container").append($row);
    } else {
        sweetalert("error", "Maximum " + $maximum_childs + " charge rows are allowed");
    }


})


$(document).on("submit", "#_create_deposit_method_form", function () {
    let $form = $(this);
    let $formdata = $form.serialize();
    let $action_type = $form.data("action");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata + "&action_type=" + $action_type + "&case=create_new_deposit_method&action=_withdraw_action",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                sweetalert_redirect("success", $message, $url, "href");
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_form($form);
        }
    });
});


$(document).on("click", "#_delete_deposit_gateway", function () {
    let $button = $(this);
    let $gateway_id = _GET("id");
    let $confirmDelete = confirmDelete($button, "Want to delete this deposit method. <br> All user data will be loosed.", "Delete");
    if (!$confirmDelete) {
        return;
    }
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: {
            gateway_id: $gateway_id,
            case: "delete_deposit_method",
            action: "_withdraw_action"
        },
        beforeSend: function () {
            disable_btn($button);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                sweetalert_redirect("success", $message, $url, "replace");
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete:function () {
            enable_btn($button);
          }
    })
})


$(document).on("click", "#_disable_auto_deposit_method", function () {
    let $button = $(this);
    let $gateway_id = $button.data("id");
    let $gateway_name = $button.data("name");
    let $status = $button.attr("status");
    let $message = "Want to inactive <b class='text-original'>" + $gateway_name + "</b> method?";
    let $btn_text = "Inactive";
    if ($status == "inactive") {
        $message = "Want to Active <b class='text-original'>" + $gateway_name + "</b> method?";
        $btn_text = "Active";
    }

    let $confirmDelete = confirmDelete($button, $message, $btn_text);
    if (!$confirmDelete) {
        return;
    }
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: {
            gateway_id: $gateway_id,
            case: "deposit_gateway_status",
            action: "_withdraw_action"
        },
        beforeSend: function () {
            disable_btn($button, false);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                sweetalert("success", $message);
                $status = $response.status;
                if ($status == "active") {
                    $button.attr("data-original-title", "Inactive this payment gateway");
                    $button.attr("status", "active").addClass("btn-danger").removeClass("btn-success");
                    $button.find("i").html("visibility_off");
                    $("#_deposit_label_" + $gateway_id).html("active").addClass("alert-success").removeClass("alert-danger");
                }
                if ($status == "inactive") {
                    $button.attr("data-original-title", "Active this payment gateway");
                    $button.attr("status", "inactive").addClass("btn-success").removeClass("btn-danger");
                    $button.find("i").html("visibility");
                    $("#_deposit_label_" + $gateway_id).html("inactive").removeClass("alert-success").addClass("alert-danger");
                }

            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_btn($button);
        }
    })
})



// Package

$(document).on("submit", "#_add_new_package",function () {  
    let $form = $(this);
    let $form_data = $form.serialize();
    let $action_type = $form.data("action");
    $.ajax({
        url:$ajax_url,
        method:"POST",
        data: $form_data + "&action_type=" + $action_type + "&action=_setting&case=add_package",
        beforeSend:function () { 
            disable_form($form);
         },
         success:function ($response) {
             if(isJSON($response)){
                 $response = JSON.parse($response);
                 $message = $response.message;
                 $url = $response.url;
                 sweetalert_redirect("success",$message,$url,"href");
             }else{
                 sweetalert("error",$response);
             }
           },
           error:function () { 
               sweetalert("error",$ajax_error);
            },
            complete:function () { 
                enable_form($form);
             }
    })
})


$(document).on("submit", "#_notice_form", function () {
    let $form = $(this);
    let $form_data = $form.serialize();
    $.ajax({
        url:$ajax_url,
        method:"POST",
        data:$form_data+"&action=_setting&case=notice_setting",
        beforeSend:function () { 
            disable_form($form);
         },
         success:function ($response) {
             if(isJSON($response)){
                 $response = JSON.parse($response);
                 $message = $response.message;
                 $notice_text = $response.notice_text;
                 sweetalert("success", $message);
                 $("#_NoticeModal").modal("hide");
                 $("#notice_text").html($notice_text);
             }else{
                 sweetalert("error",$response);
             }
           },
           error:function () { 
               sweetalert("error",$ajax_error);
            },
            complete:function () { 
                enable_form($form);
             }
    })
})


$(document).on("submit", "#_basic_setting_form", function () {
    let $form = $(this);
    let $form_data = $form.serialize();
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $form_data + "&action=_setting&case=basic_setting",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                sweetalert("success", $message);
                diable_ajax_form($form);
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_form($form);
        }
    })
})



$(document).on("submit", "#_create_admin_form", function () {
    let $form = $(this);
    let $form_data = $form.serialize();
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $form_data + "&action=_admin_account&case=create_admin",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                sweetalert_redirect("success", $message, $url, "replace");
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_form($form);
        }
    })
})


const ChangeLogo = {
    id: '',
    init: function () {
        const $form = $("#changeLogoForm");
        const $imageChoose = $("#changeLogo");

        $form.find("label[data-id]").on("click", function () {
            let $changeBtn = $(this);
            let $id = $changeBtn.data("id");
            ChangeLogo.id = $id;
            $imageChoose.trigger("click");
        });

        $imageChoose.on("change", function () {
            ChangeLogo.upload(this);
        });

        $form.on("submit", function (event) {
            event.preventDefault();
            ChangeLogo.saveChanges($(this));
        });
    },
    upload: function (chooser) {
        const $id = ChangeLogo.id;
        const $file_name = chooser.files[0].name;
        const $form_data = new FormData();
        const $ext = $file_name.split('.').pop().toLowerCase();
        if (jQuery.inArray($ext, ['png', 'jpg', 'jpeg']) == -1) {
            sweetalert("error", "Invalid Image File");
            return;
        }
        var oFReader = new FileReader();
        oFReader.readAsDataURL(chooser.files[0]);
        $form_data.append("image", chooser.files[0]);
        $form_data.append("case", "change_logo");
        $form_data.append("event", $id);
        $form_data.append("action", "_setting");
        const $progressBar = $("input[name=" + $id + "]").parent().find(".preloader");
        $.ajax({
            url: $ajax_url,
            method: "POST",
            data: $form_data,
            contentType: false,
            cache: false,
            processData: false,
            xhr: function () {
                var xhr = new XMLHttpRequest();
                xhr.upload.onprogress = function (e) {
                    var percent = "0";
                    var percentage = "0%";
                    if (e.lengthComputable) {
                        percent = Math.round((e.loaded / e.total) * 100);
                        percentage = percent + "%";
                        $progressBar.width(percentage);
                    }
                };
                return xhr;
            },
            success: function ($response) {
                if (isJSON($response)) {
                    $response = JSON.parse($response);
                    $image_id = $response.image_id;
                    $image_src = $response.image_src;
                    $("input[name=" + $id + "]").val($image_id);
                    $("input[name=" + $id + "]").parent().find("img").attr("src", $image_src);
                } else {
                    sweetalert("error", $response);
                }
            },
            error: function () {
                sweetalert("error", $ajax_error);
            },
            complete: function () {
                $progressBar.width(0);
            }
        });

    },
    saveChanges: function ($form) {
        const $form_data = $form.serialize();
        $.ajax({
            url: $ajax_url,
            method: "POST",
            data: $form_data + "&action=_setting&case=save_logo",
            beforeSend: function () {
                disable_form($form);
            },
            success: function ($response) {
                if (isJSON($response)) {
                    $response = JSON.parse($response);
                    $message = $response.message;
                    sweetalert("success", $message);
                } else {
                    sweetalert("error", $response);
                }
            },
            error: function (param) {
                sweetalert("error", $ajax_error);
            },
            complete: function () {
                enable_form($form);
            }
        });
    }
}