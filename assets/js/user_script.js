// Users Actions Function Start
$(document).on("submit", "#login_form", function (event) {
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
        data: $form_data + "&keeplogged=" + $keeplogged + "&case=login&action=user_account",
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
        }
    });
});


$(document).on("submit", "#registration_form", function () {
    event.preventDefault();
    let $form = $(this);
    let $form_data = $form.serialize();
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $form_data + "&case=register&action=user_account",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $page_url = $response.page_url;
                $message = $response.message;
                sweetalert_redirect("success", $message, $page_url, "replace");
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
    })
});

$(document).on("submit", "#forgot_password_form", function (event) {
    event.preventDefault();
    let $form = $(this);
    let $form_data = $form.serialize();
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $form_data + "&case=forgot_password&action=user_account",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $reset_html = $response.reset_html;
                $("#card_container").html($reset_html);
                sweetalert("success", $message);
                add_form_validation();
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
    })
});

$(document).on("submit", "#reset_password_form", function (event) {
    event.preventDefault();
    let $form = $(this);
    let $form_data = $form.serialize();
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $form_data + "&case=reset_password&action=user_account",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $url = $response.url;
                $message = $response.message;
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
        },
    })
});

$(document).on("click", "#resend_reset_pwd_otp", function () {
    let $button = $(this);
    $user_id = $button.data("user");
    if ($button.attr("disabled") == "disabled") return false;
    else {
        $.ajax({
            url: $ajax_url,
            method: "POST",
            data: {
                user_id: $user_id,
                case: "resend_reset_otp",
                action: "user_account",
            },
            beforeSend: function () {
                disable_element($button);
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
                enable_element($button);
            },
        });
    }
})


$(document).on("change input", "#referral_id", function () {
    let $element = $(this);
    $referral_id = $element.val();
    $.ajax({
        url: $ajax_url,
        data: {
            user_id: $referral_id,
            error_text: "Referral Id",
            case: "get_user_name_by_id",
            action: "user_account",
        },
        method: "POST",
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $user_name = $response.user_name;
                $element.removeClass("is-invalid").addClass("is-valid");
                $element.siblings(".invalid-feedback").hide();
                $element.siblings(".valid-feedback").show().html("Referral id matched");
                $("#referral_name").removeClass("is-invalid").addClass("is-valid").val($user_name);
            } else {
                $element.removeClass("is-valid").addClass("is-invalid");
                $element.siblings(".valid-feedback").hide();
                $element.siblings(".invalid-feedback").show().html($response);
                $("#referral_name").addClass("is-invalid").val("");
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        }
    });
});


$(document).on("change input", "#placement_id", function () {
    let $element = $(this);
    $placement_id = $element.val();
    $.ajax({
        url: $ajax_url,
        data: {
            placement_id: $placement_id,
            case: "get_placement_type",
            action: "user_account",
        },
        method: "POST",
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $user_name = $response.user_name;
                $left_id = $response.left_id;
                $right_id = $response.right_id;

              $element.siblings(".valid-feedback").show().html("Placement id matched");


                $("#placement_name")
                    .removeClass("is-invalid")
                    .addClass("is-valid")
                    .val($user_name);

                    $("#placement_type").attr("readonly",false);

                    console.log($left_id)
                    console.log($right_id)

                if ($left_id == "false") {
                    $("#placement_type").find("option[value=left]").attr("disabled", true);
                    if ($("#placement_type").val() == "left") {
                        $("#placement_type").prop('selectedIndex', 0);
                    }
                } else {
                    $("#placement_type").find("option[value=left]").attr("disabled", false);
                    $("#placement_type").removeClass("is-invalid");
                }

                if ($right_id == "false") {
                    $("#placement_type").find("option[value=right]").attr("disabled", true);
                    if ($("#placement_type").val() == "right") {
                        $("#placement_type").prop('selectedIndex', 0);
                    }
                } else {
                    $("#placement_type").find("option[value=right]").attr("disabled", false);
                    $("#placement_type").removeClass("is-invalid");
                }

                if ($left_id == "false" && $right_id == "false") {
                    $element.removeClass("is-valid").addClass("is-invalid")
                        .siblings(".invalid-feedback")
                        .html("left and right both sides are in use");
                    $("#placement_type")
                        .removeClass("is-valid")
                        .addClass("is-invalid")
                        .siblings(".invalid-feedback")
                        .html("left and right both sides are in use");
                    $("#placement_type").prop('selectedIndex', 0);
                } else {
                    $element.removeClass("is-invalid").addClass("is-valid");
                    $("#placement_type").removeClass("is-invalid");
                }

            } else {
                $element.siblings(".valid-feedback").hide();
                $("#placement_type").prop('selectedIndex', 0);
                $element
                    .removeClass("is-valid")
                    .addClass("is-invalid")
                    .siblings(".invalid-feedback")
                    .html($response);
                $("#placement_name")
                    .removeClass("is-valid")
                    .addClass("is-invalid")
                    .val("");
                $("#placement_type")
                    .removeClass("is-valid")
                    .removeClass("is-invalid")
                    .siblings(".invalid-feedback")
                    .html("");
                $("#placement_type").find("option[value=left]").attr("disabled", true);
                $("#placement_type").find("option[value=right]").attr("disabled", true);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        }
    });
});

$(document).on("click", "#send_registration_otp", function () {
    let $button = $(this);
    $otp_input = $("input#otp");
    $email_input = $("input#user_email");
    $user_email = $email_input.val();
    $email_err_txt = "Please provide a valid email.";
    if (is_empty($user_email) || !validateEmail($user_email)) {
        $email_input
            .removeClass("is-valid")
            .addClass("is-invalid")
            .siblings(".invalid-feedback")
            .html($email_err_txt);
    } else {
        $.ajax({
            url: $ajax_url,
            method: "POST",
            data: {
                user_email: $user_email,
                case: "send_registration_otp",
                action: "user_account",
            },
            beforeSend: function () {
                disable_btn($button);
            },
            success: function ($response) {
                if (isJSON($response)) {
                    $response = JSON.parse($response);
                    $message = $response.message;
                    sweetalert("success", $message);
                    $otp_input.val("").removeClass("is-invalid")
                        .addClass("is-valid")
                        .siblings(".valid-feedback")
                        .html("An otp has sent to your email");
                } else {
                    sweetalert("error", $response);
                }
            },
            error: function () {
                sweetalert("error", $ajax_error);
            },
            complete: function () {
                enable_btn($button);
            },
        });
    }
});


// --- Users Profile functions start

$("#profile_img_upload").on("mouseover", function () {
    $(this).find(".upload_container").show();
});
$("#profile_img_upload").on("mouseout", function () {
    $(this).find(".upload_container").hide();
});

$(document).on("click", "#profile_img_upload", function () {
    $element = $(this);
    $.ajax({
        url: $ajax_url,
        data: {
            case: "get_profile_modal",
            action: "profile_action",
        },
        method: "POST",
        beforeSend: function () {
            disable_element($element);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $modal_data = $response.modal_data;
                $("#profileModal").on("hide.bs.modal", function () {
                    $("#cropimage").html('<img id="imageprev" src=""/>');
                });
                $("body").append($modal_data);
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_element($element);
        },
    });
})

//


function remove_pop_imgupload_container() {
    $("#pop_img_upload").remove();
}

$(document).on("click", "#close_pop_imgupload", function () {
    remove_pop_imgupload_container();
});

var input = $("#img_upload_input");
$(document).on("change", "#img_upload_input", function (e) {
    remove_pop_imgupload_container();
    var image = document.querySelector("#imageprev");
    var files = e.target.files;
    var done = function (url) {
        input.value = "";
        image.src = url;
        $("#profileModal").modal({
            backdrop: "static"
        });
        cropImage();
    };
    var reader;
    var file;
    var url;
    if (files && files.length > 0) {
        file = files[0];
        if (URL) {
            done(URL.createObjectURL(file));
        } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
                done(reader.result);
            };
            reader.readAsDataURL(file);
        }
    }
});

var cropper;

function cropImage() {
    var image = document.querySelector("#imageprev");
    var minAspectRatio = 1;
    var maxAspectRatio = 1;
    cropper = new Cropper(image, {
        aspectRatio: 1,
        minCropBoxWidth: 200,
        minCropBoxHeight: 200,
        ready: function () {
            var cropper = this.cropper;
            var containerData = cropper.getContainerData();
            var cropBoxData = cropper.getCropBoxData();
            var aspectRatio = cropBoxData.width / cropBoxData.height;
            var newCropBoxWidth;
            cropper.setDragMode("move");
            if (aspectRatio < minAspectRatio || aspectRatio > maxAspectRatio) {
                newCropBoxWidth =
                    cropBoxData.height * ((minAspectRatio + maxAspectRatio) / 2);
                cropper.setCropBoxData({
                    left: (containerData.width - newCropBoxWidth) / 2,
                    width: newCropBoxWidth,
                });
            }
        },
    });
}

$(document).on("click", "#saveAvatar", function () {
    event.preventDefault();
    var $progress = $(".progress");
    var $progressBar = $(".progress-bar");
    var avatar = document.getElementById("avatarimage");
    canvas = cropper.getCroppedCanvas({
        width: 400,
        height: 400
    });
    $progress.show();
    canvas.toBlob(function (blob) {
        var formData = new FormData();
        formData.append("avatar", blob, "avatar.jpg");
        formData.append("case", "change_profile_image");
        formData.append("action", "profile_action");

        $.ajax($ajax_url, {
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            xhr: function () {
                var xhr = new XMLHttpRequest();
                xhr.upload.onprogress = function (e) {
                    var percent = "0";
                    var percentage = "0%";
                    if (e.lengthComputable) {
                        percent = Math.round((e.loaded / e.total) * 100);
                        percentage = percent + "%";
                        $progressBar
                            .width(percentage)
                            .attr("aria-valuenow", percent)
                            .text(percentage);
                    }
                };
                return xhr;
            },
            success: function ($response) {
                $("#profileModal").modal("hide");
                $progress.hide();
                if (isJSON($response)) {
                    $response = JSON.parse($response);
                    $message = $response.message;
                    $image_src = $response.image_src;
                    avatar.src = $image_src;
                    sweetalert("success", $message);
                } else {
                    sweetalert("error", $response);
                }
            },
            error: function () {
                avatar.src = initialAvatarURL;
                sweetalert("error", "Something Went Wrong");
            },
        });
    });
});


$(document).on("click", "#edit_profile_img", function () {
    $element = $(this);
    remove_pop_imgupload_container();
    $.ajax({
        url: $ajax_url,
        data: {
            action: "profile_action",
            case: "preview_profile_img",
        },
        method: "POST",
        beforeSend: function () {
            disable_btn($element);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $data = $response.data;
                $("#cropimage").html($data);
                $("#profileModal").modal({
                    backdrop: "static"
                });
                cropImage();
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_btn($element);
        },
    });
});

$(document).on("click", "#delete_profile_img", function () {
    remove_pop_imgupload_container();
    $element = $(this);
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: {
            action: "profile_action",
            case: "delete_profile_img",
        },
        beforeSend: function () {
            disable_btn($element);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $image_src = $response.image_src;
                $message = $response.message;
                $("#avatarimage").attr("src", $image_src);
                sweetalert("success", $message);
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", $ajax_error);
        },
        complete: function () {
            enable_btn($element);
        },
    });
});

$(document).on("submit", "#user_detail_form", function () {
    let $form = $(this);
    let $formdata = $form.serialize();
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata + "&case=update_profile_detail&action=profile_action",
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

$(document).on("submit", "#user_adress_form", function () {
    let $form = $(this);
    let $formdata = $form.serialize();
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata + "&case=update_address_detail&action=profile_action",
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


$(document).on("click", "#img_upload_card", function () {
    let $this = $(this);
    let $select = $this.data("upload");
    $("#kyc_image_chooser").attr("select", $select).trigger("click");
});


$(document).on('change', '#kyc_image_chooser', function () {
    var name = document.getElementById("kyc_image_chooser").files[0].name;
    var form_data = new FormData();
    var ext = name.split('.').pop().toLowerCase();
    if (jQuery.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
        sweetalert("error", "Invalid Image File");
        return;
    }
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("kyc_image_chooser").files[0]);
    var f = document.getElementById("kyc_image_chooser").files[0];
    var fsize = f.size || f.fileSize;
    if (fsize > 4000000) {
        sweetalert("error", "Image File Size is greater than 4 MB");
    } else {
        let $select = $("#kyc_image_chooser").attr("select");
        form_data.append("card_file", document.getElementById('kyc_image_chooser').files[0]);
        form_data.append("case", $select);
        form_data.append("action", "profile_action");
        $.ajax({
            url: $ajax_url,
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function ($response) {
                if (isJSON($response)) {
                    $response = JSON.parse($response);
                    $image_src = $response.image_src;
                    $message = $response.message;
                    sweetalert("success", $message);
                    $("div[data-upload=" + $select + "]").find("img").attr("src", $image_src);
                    $("div[data-upload=" + $select + "]").find("#img_upload_inner").hide();
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

$(document).on("click", "#submit_kyc", function () {
    let $button = $(this);
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: {
            case: "submit_kyc",
            action: "profile_action"
        },
        beforeSend: function () {
            disable_btn($button);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                sweetalert("success", $message);
                setTimeout(function () {
                    location.reload();
                }, 1000)
            } else {
                sweetalert("error", $response);
            }
        },
        error: function () {
            sweetalert("error", "Something went wrong");
        },
        complete: function () {
            enable_btn($button);
        }
    })
})

$(document).on("click", "#add_to_payment_gateway", function () {
    let $button = $(this);
    if ($button.html() == "Remove") {
        let $confirmDelete = confirmDelete($button, "Want to delete the withdraw method.<br> All data will be loosed.", "Delete");
        if (!$confirmDelete) {
            return;
        }
    }

    let $gateway_id = $button.data("id");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: {
            gateway_id: $gateway_id,
            case: "add_to_payment_gateway",
            action: "profile_action"
        },
        beforeSend: function () {
            disable_btn($button, false);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $action = $response.action;
                if ($action == "removed") {
                    $button.html("Add").removeClass("btn-danger").addClass("btn-success");
                }
                if ($action == "added") {
                    $button.html("Remove").addClass("btn-danger").removeClass("btn-success");
                }
                sweetalert("success", $message);
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

// --- Users Profile functions end


// --- Add Ticket Functions Start

$(document).on("submit", "#add_ticket_form", function () {
    let $form = $(this);
    let $formdata = $form.serialize();
    let $images = $form.attr("images");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata + "&images=" + $images + "&case=add_ticket&action=support_action",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) { 
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                sweetalert_redirect("success", $message, $url, "href");
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

$(document).on("submit", "#add_deposit_ticket_form", function () {
    let $form = $(this);
    let $formdata = $form.serialize();
    let $images = $form.attr("images");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata + "&images=" + $images + "&case=add_deposit_ticket&action=support_action",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) { 
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                sweetalert_redirect("success", $message, $url, "href");
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

$(document).on("submit", "#add_withdraw", function () {
    let $form = $(this);
    let $formdata = $form.serialize();
    let $images = $form.attr("images");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata + "&images=" + $images + "&case=add_withdraw&action=support_action",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) { 
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                sweetalert_redirect("success", $message, $url, "href");
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

$(document).on("submit", "#reply_ticket_form", function () {
    let $form = $(this);
    let $formdata = $form.serialize();
    let $images = $form.attr("images");
    let $ticket_id = _GET("ticket");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata + "&images=" + $images + "&ticket_id=" + $ticket_id + "&case=reply_ticket&action=support_action",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                sweetalert_redirect("success", $message, $url, "href");
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

// --- Add Ticket Functions End


// --- Change Password Start
$(document).on("submit", "#change_password_form", function () {
    let $form = $(this);
    let $formdata = $form.serialize();
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata + "&case=change_password&action=profile_action",
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

// -- Balance Tab Functions Start
$(document).on("submit", "#deposit_paytm_form", function () {
    let $form = $(this);
    let $formdata = $form.serialize();
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata + "&case=deposit_money_by_paytm&action=balance_action",
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url = $response.url;
                $form.trigger("reset").removeClass("was-validated");
                location.href = $url;
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


// $(document).on("submit", "#withdraw_bank_form", function () {
//     let $form = $(this);
//     let $formdata = $form.serialize();
//     $.ajax({
//         url: $ajax_url,
//         method: "POST",
//         data: $formdata + "&case=withdraw_by_bank&action=balance_action",
//         beforeSend: function () {
//             disable_form($form);
//         },
//         success: function ($response) {
//             if (isJSON($response)) {
//                 $response = JSON.parse($response);
//                 $message = $response.message;
//                 $url = $response.url;
//                 $form.trigger("reset").removeClass("was-validated");
//                 $("#bankWithdrawModal").modal("hide");
//                 sweetalert_redirect("success", $message, $url, "href");
//             } else {
//                 sweetalert("error", $response);
//             }
//         },
//         error: function () {
//             sweetalert("error", $ajax_error);
//         },
//         complete: function () {
//             enable_form($form);
//         }
//     })
// });

$(document).on("click", "#triggerBankModal", function () {
    $("#bank_transfer_choose_image").val("");
})

$(document).on("submit", "#deposit_gateway_form", function (event) {
    event.preventDefault();
    let $gateway_id = _GET("id");
    let $form = $(this);
    $formdata = new FormData();
    $paid_amount = $form.find("input[name=paid_amount]").val();
    $formdata.append('payment_image', $('#bank_transfer_choose_image')[0].files[0]);
    $formdata.append("gateway_id", $gateway_id);
    $formdata.append("paid_amount", $paid_amount);
    $formdata.append("case", "deposit_manual_money");
    $formdata.append("action", "balance_action");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata,
        processData: false,
        contentType: false,
        beforeSend: function () {
            disable_form($form);
        },
        success: function ($response) {
            if (isJSON($response)) {
                $response = JSON.parse($response);
                $message = $response.message;
                $url =$response.url;
                sweetalert_redirect("success", $message, $url, "href");
                $form.trigger("reset").removeClass("was-validated");
                $("#bankTransferModal").modal("hide");
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


// $(document).on("keyup input", "#withdraw_bank_form input[name=withdraw_amount]", function () {
//     let $this = $(this);
//     $this.attr("maxlength", "6");

//     $symbol = $this.data("symbol");
//     $tds_charge_per = $this.data("tds");
//     $service_charge_per = $this.data("charge");
//     $min_withdraw_amt = $this.data("min-withdraw");;

//     $amount = $this.val();
//     if ($amount == "" || $amount < $min_withdraw_amt) {
//         $amount = 0;
//     }

//     $request_amount = parseFloat($amount);


//     $tds_charge = ($amount * $tds_charge_per) / 100;
//     $service_charge = ($amount * $service_charge_per) / 100;
//     $rec_amt = parseFloat($request_amount) - parseFloat($tds_charge) - parseFloat($service_charge);

//     $amount = parseFloat($amount).toFixed(2);
//     $tds_charge = parseFloat($tds_charge).toFixed(2);
//     $service_charge = parseFloat($service_charge).toFixed(2);
//     $rec_amt = parseFloat($rec_amt).toFixed(2);

//     if ($rec_amt <= 0) {
//         $this.removeClass("is-valid").addClass("is-invalid").siblings(".invalid-feedback").html("Minimum withdraw amount is " + $symbol + $min_withdraw_amt);
//     } else {
//         $this.removeClass("is-invalid");
//     }

//     $tds_input = $("input[name=tds]");
//     $service_charge_input = $("input[name=service_charge]");
//     $receive_amount_input = $("input[name=receive_amount]");

//     $tds_input.val($symbol + $tds_charge);
//     $service_charge_input.val($symbol + $service_charge);
//     $receive_amount_input.val($symbol + $rec_amt);


//     if ($this.val() == "") {
//         $this.removeClass("is-valid").removeClass("is-invalid");
//     }
// })
// -- Balance Tab Functions End





$(document).on("submit", "#withdraw_payment_form", function () {
    let $form = $(this);
    let $formdata = $form.serialize();
    let $gateway_id = _GET("id");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $formdata + "&gateway_id=" + $gateway_id +"&case=withdraw_payment&action=balance_action",
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
})

$(document).on("submit", ".payment_gateway_form", function () {
    let $form = $(this);
    let $form_data = $form.serialize();
    let $form_id = $form.data("id");
    $.ajax({
        url: $ajax_url,
        method: "POST",
        data: $form_data + "&form_id=" + $form_id + "&case=payment_gateway_form&action=profile_action",
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



$(document).on("change", "[id^=gateway_img_chooser]", function () {
    let $this = $(this);
    let $input_id = $this.attr("id");
    var name = document.getElementById($input_id).files[0].name;
    var form_data = new FormData();
    var ext = name.split('.').pop().toLowerCase();
    if (jQuery.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
        sweetalert("error", "Invalid Image File");
        return;
    }
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById($input_id).files[0]);
    var f = document.getElementById($input_id).files[0];
    var fsize = f.size || f.fileSize;
    if (fsize > 4000000) {
        sweetalert("error", "Image File Size is greater than 4 MB");
    } else {
        form_data.append("payment_gateway_file", document.getElementById($input_id).files[0]);
        form_data.append("case", 'payment_gateway_image_upload');
        form_data.append("action", "profile_action");
        $.ajax({
            url: $ajax_url,
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function ($response) {
                if (isJSON($response)) {
                    $response = JSON.parse($response);
                    $image_id = $response.image_id;
                    $image_src = $response.image_src;
                    $("input[data-id=" + $input_id + "]").val($image_id);
                    $("label[for=" + $input_id + "]").find(".inner-div").hide();
                    $("label[for=" + $input_id + "]").find("img").attr("src", $image_src);
                } else {
                    sweetalert("error", $response);
                }
            },
            error: function () {
                sweetalert("error", $ajax_error);
            }
        });
    }
})


$(document).on("keyup input", "#withdraw_amt_input", function () {
    let $input = $(this);
    let $amount = $input.val();
    let $gateway_id = _GET("id");
    $.ajax({
        url:$ajax_url,
        method:"POST",
        data:{
            gateway_id: $gateway_id,
            amount: $amount,
            case :"fetch_withdraw_gateway_charge_details",
            action:"balance_action"
        },
        success:function ($response) {
            if(isJSON($response)){
                $response = JSON.parse($response);
                $withdraw_charge = $response.withdraw_charge;
                $final_amount = $response.final_amount;
                $("#withdraw_charge").val($withdraw_charge);
                $("#final_withdraw_amount").val($final_amount);
            }else{
                sweetalert("error",$response);
            }
        },
        error:function () { 
            sweetalert("error",$ajax_error);
        }
    })
});


$(document).on("click", "#purchase_package",function () {
    let $button = $(this);
    let $package_id = $button.data("id");
    $.ajax({
        url:$ajax_url,
        method:"POST",
        data:{
            package_id: $package_id,
            case:"buy_package",
            action:"balance_action"
        },
        beforeSend:function () {
            disable_btn($button);
          },
          success:function ($response) {  
              if(isJSON($response)){
                  $response = JSON.parse($response);
                  $message = $response.message;
                  sweetalert("success",$message);
              }else{
                  sweetalert("error",$response)
              }
          },error:function () {  
              sweetalert("error",$ajax_error)
          },
          complete:function () { 
              enable_btn($button);
           }
    })
  })