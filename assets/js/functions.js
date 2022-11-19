$ajax_error = "Error in sending request";


initSelect2();
function initSelect2() {
    $('select:not(.no-select)').each(function () {
        $(this).select2({
            placeholder: 'Select an option',
            minimumResultsForSearch: -1,
            initSelection: function (element, callback) {}
        });
    });
}


// Check if data is Json
function isJSON(something) {
    if (typeof something != "string") something = JSON.stringify(something);

    try {
        JSON.parse(something);
        return true;
    } catch (e) {
        return false;
    }
}

// Check if url is valid
function is_valid_http_url(string) {
    let url;

    try {
        url = new URL(string);
    } catch (_) {
        return false;
    }

    return url.protocol === "http:" || url.protocol === "https:";
}

function sanitize_text($text) {
    if ($text != undefined && $text != null && $text != "") {
        if (!$.isNumeric($text)) {
            $text = $text.trim();
        }
        return $text;
    }
}

function is_empty(text) {
    text = sanitize_text(text);
    if (text == "" || text == undefined || text == null) {
        return true;
    }
    return false;
}


// Get url parameters
function _GET(name, url = window.location.href) {
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function set_storage(item, value) {
    return localStorage.setItem(item, value);
}

function get_storage(item) {
    let value = localStorage.getItem(item);
    return value;
}

function remove_storage(item) {
    return localStorage.removeItem(item);
}

function clear_storage() {
    return localStorage.clear();
}

function isset_storage(item) {
    var value = get_storage(item);
    if (!is_empty(value)) {
        return true;
    } else {
        return false;
    }
}

function disable_btn($button, $disable_btn_only) {
    $btn_text = $button.text();
    if (typeof $disable_btn_only !== "undefined") {
        $button.attr("disabled", true).css({
            opacity: ".7",
            cursor: "not-allowed",
        });
        return;
    }

    $button.attr("disabled", true).attr('text', $btn_text).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Please wait...').css({
        opacity: ".7",
        cursor: "not-allowed",
    });
}

function enable_btn($button) {
    $btn_text = $button.attr('text');
    $button.attr("disabled", false).removeAttr('text').html($btn_text).css({
        opacity: "1",
        cursor: "pointer",
    });
}

function disable_element($element) {
    $element.attr("disabled", true);
    $element.append('<div class="absolute-preloader" ><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>').css({
        position: "relative",
        opacity: ".5",
        cursor: "not-allowed",
    });
}

function enable_element($element) {
    $element.attr("disabled", false);
    $element.find('.absolute-preloader').remove();
    $element.css({
        opacity: "1",
        cursor: "pointer",
    });
}



add_form_validation();

function add_form_validation() {
    $("form.needs-validation").each(function () {
        let form = $(this);
        form.on("submit", function (evt) {
            if (!form[0].checkValidity()) {
                evt.preventDefault();
                evt.stopPropagation();
            } else {
                evt.preventDefault();
            }
            form[0].classList.add('was-validated');
        })
    });
}


function disable_form($form) {
    $form.find(".form-control").each(function () {
        $is_readonly = $(this).attr("readonly");
        if (typeof $is_readonly !== typeof undefined && $is_readonly !== false) {} else {
            $(this).addClass("disable").attr("readonly", true);
        }
    });

    $btn = $form.find("button[type=submit]");
    $btn_txt = $btn.html();
    $btn
        .attr("disabled", true)
        .attr("text", $btn_txt)
        .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Please wait...')
        .css({
            opacity: ".7",
            cursor: "not-allowed",
        });
    // $($form).prepend('<div id="unit_preloader"> <svg width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"> <g> <path fill="#2accfc" d="M48.3 56.8l-4.4 1.5c-0.9-1.3-1.5-2.7-1.7-4.3l-1.9 0.3c0.5 2.6 1.7 5 3.6 6.9c1.9 1.9 4.3 3.1 6.9 3.6 l0.3-1.9c-1.6-0.3-3-0.9-4.3-1.7L48.3 56.8z"></path> <path fill="#2accfc" d="M57.8 47.2l4.4-1.5c0.9 1.3 1.5 2.7 1.7 4.3l1.9-0.3c-0.5-2.6-1.7-5-3.6-6.9c-1.9-1.9-4.3-3.1-6.9-3.6L55 41.1 c1.6 0.3 3 0.9 4.3 1.7L57.8 47.2z"></path> <path fill="#2accfc" d="M62.2 58.2l-4.4-1.5l1.5 4.4C58 62 56.5 62.6 55 62.9l0.3 1.9c2.6-0.5 5-1.7 6.9-3.6c1.9-1.9 3.1-4.3 3.6-6.9 L64 53.9C63.7 55.5 63.1 56.9 62.2 58.2z"></path> <path fill="#2accfc" d="M43.9 42.8c-1.9 1.9-3.1 4.3-3.6 6.9l1.9 0.3c0.3-1.6 0.9-3 1.7-4.3l4.4 1.5l-1.5-4.4c1.3-0.9 2.7-1.5 4.3-1.7 l-0.3-1.9C48.2 39.7 45.8 40.9 43.9 42.8z"></path> <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="4s" keyTimes="0;1" values="0 53.064 52;360 53.064 52"></animateTransform> </g> <g class="ldio-d9dqg8a5m87-st2"> <path fill="#2accfc" d="M36 61.9c-1.7-3-2.7-6.4-2.7-9.9c0-10.9 8.8-19.7 19.7-19.7v1c-10.3 0-18.8 8.4-18.8 18.8 c0 3.3 0.9 6.5 2.5 9.4L36 61.9z"></path> <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="2s" keyTimes="0;1" values="360 53.064 52;0 53.064 52"></animateTransform> </g> <g class="ldio-d9dqg8a5m87-st2"> <path fill="#2accfc" d="M57 75.3l-0.5-3c9.9-1.7 17.2-10.2 17.2-20.3c0-11.4-9.2-20.6-20.6-20.6S32.5 40.6 32.5 52 c0 1.6 0.2 3.2 0.5 4.7l-3 0.7c-0.4-1.8-0.6-3.6-0.6-5.4c0-13.1 10.6-23.7 23.7-23.7S76.7 38.9 76.7 52 C76.7 63.6 68.4 73.4 57 75.3z"></path> <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1.332s" keyTimes="0;1" values="0 53.064 52;360 53.064 52"></animateTransform> </g> <g> <path fill="#2accfc" d="M90.5 45.4c-1.5-8.8-6.2-16.8-13-22.5l0 0c-3.4-2.9-7.3-5.1-11.4-6.6s-8.5-2.3-13-2.3v2.4v1.4v2.4 c3.7 0 7.4 0.6 10.9 1.9l0.8-2.3c0 0 0 0 0 0c3.7 1.4 7.2 3.4 10.3 5.9l1.2-1.5L75 25.8c0 0 0 0 0 0l-1.5 1.8 c5.7 4.8 9.6 11.5 10.9 18.8l3.8-0.7c0 0 0 0 0 0L90.5 45.4z"></path> <path fill="#2accfc" d="M29.7 22l4.7 6.1c3.5-2.8 7.5-4.6 11.9-5.6l-1.7-7.5C39.2 16.2 34.2 18.5 29.7 22z"></path> <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="2s" keyTimes="0;1" values="360 53.064 52;0 53.064 52"></animateTransform> </g> <g class="ldio-d9dqg8a5m87-st2"> <path fill="#2accfc" d="M53.1 92.4v-1c21.8 0 39.5-17.7 39.5-39.5c0-21.8-17.7-39.5-39.5-39.5c-15.8 0-30 9.4-36.2 23.8L15.9 36 c6.4-14.8 21-24.4 37.1-24.4c22.3 0 40.4 18.1 40.4 40.4C93.5 74.3 75.3 92.4 53.1 92.4z"></path> <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1.332s" keyTimes="0;1" values="0 53.064 52;360 53.064 52"></animateTransform> </g> <g> <path fill="#2accfc" d="M39.7 28.5l0.6 1c3.9-2.2 8.3-3.4 12.8-3.4V25C48.4 25 43.7 26.2 39.7 28.5z"></path> <path fill="#2accfc" d="M28.6 60.6l-1.1 0.4C31.3 71.8 41.6 79 53.1 79v-1.2C42.1 77.9 32.3 70.9 28.6 60.6z"></path> <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="4s" keyTimes="0;1" values="360 53.064 52;0 53.064 52"></animateTransform> </g> </svg> </div>');
}

function enable_form($form) {
    $form.find(".form-control.disable").attr("readonly", false);
    $btn = $form.find("button[type=submit]");
    $btn_txt = $btn.attr("text");
    $btn.attr("disabled", false).removeAttr("text").html($btn_txt).css({
        opacity: "1",
        cursor: "pointer",
    });
    // $form.find("#unit_preloader").remove();
}


// Prevent to write keywords other than 0-9
$(document).on("keypress input keyup", "input[data-validate=number]", function (eve) {
    numbers_only(eve, $(this));
});

// Prevent to write keywords other than  a-zA-z
$(document).on("keypress input keyup", "input[data-validate=decimal_numeric]", function (eve) {
    decimal_numeric(eve, $(this));
});
// Prevent to write keywords other than  a-zA-z
$(document).on("keypress input keyup", "input[data-validate=alpha]", function (eve) {
    alpha_only(eve, $(this));
});

// Prevent to write keywords other than  0-9a-zA-z
$(document).on("keypress input keyup", "input[data-validate=alpha_numeric]", function (eve) {
    alphanumeric_only(eve, $(this));
});


// Prevent to write keywords other than  0-9a-zA-z
$(document).on("keypress input keyup", "input[data-validate=mobile_number]", function (eve) {
    numbers_only(eve, $(this));
    $(this).attr("maxlength", 10);
});

// Prevent to write keywords other than email
$(document).on("input blur", "input[data-validate=email]", function (eve) {
    email_only($(this));
});


$(document).on("input blur", "input[data-validate=password]", function (eve) {
    validate_password($(this));
});

$(document).on("input blur", "input[data-validate=confirm_password]", function (eve) {
    validate_password($(this));
});

// Validate password
function validate_password() {
    $password_elem = $("input[data-validate=password]");
    $confirm_password_elem = $("input[data-validate=confirm_password]");

    if (!$password_elem[0]) return;
    if (!$confirm_password_elem[0]) return;

    $password = $password_elem.val();
    $confirm_password = $confirm_password_elem.val();

    if (!is_empty($password) && !is_empty($confirm_password)) {
        if ($password !== $confirm_password) {
            $password_elem.addClass("is-invalid")
                .siblings(".invalid-feedback")
                .html("Passwords are not matching");
            $confirm_password_elem.addClass("is-invalid")
                .siblings(".invalid-feedback")
                .html("Passwords are not matching");
        } else {
            $password_elem.removeClass("is-invalid");
            $confirm_password_elem.removeClass("is-invalid");
        }
    } else {
        $password_elem.removeClass("is-invalid");
        $confirm_password_elem.removeClass("is-invalid");
    }
}

// Force to write numbers only
function numbers_only(eve, $this) {
    $val = $this.val();
    $input_element = '<div class="input-feedback">Only numbers are allowed</div>';
    $enterkey = 13;
    $backkey = 8;
    $this.siblings(".input-feedback").remove();
    var ch = String.fromCharCode(event.which);
    if (!/[0-9]/.test(ch)) {
        if (eve.which == $backkey || eve.which == $enterkey) {} else event.returnValue = false;
        if (event.returnValue == false) {
            $this.addClass("input-invalid");
            if ($this.siblings("div.input-group-append").length == 0) {
                $this.after($input_element);
            } else {
                $this.siblings("div.input-group-append").after($input_element);
            }

            setTimeout(function () {
                $this.removeClass("input-invalid");
                $this.siblings(".input-feedback").remove();
            }, 1000);
        }
    }
    $this.val($this.val().replace(/[^0-9]/g, ""));

    if ($val == "") {
        $this.removeClass("is-valid").removeClass("is-invalid");
    }
}

// Force to write numbers . only
function decimal_numeric(evt, $this) {

    $val = $this.val();
    $this.val($this.val().replace(/[^0-9.]/g, ""));


    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46) {
        $index = $val.indexOf('.');
        if ($index === -1) {
            return true;
        }
        if ($index >= 0) {
            evt.preventDefault();
            return false;
        }
    } else {
        if (charCode > 31 &&
            (charCode < 48 || charCode > 57))
            evt.preventDefault();
        return false;
    }

    if ($val == "") {
        $this.removeClass("is-valid").removeClass("is-invalid");
    }

    return true;

    $val = $this.val();
    $input_element = '<div class="input-feedback">Only numbers and . are allowed</div>';
    $enterkey = 13;
    $backkey = 8;
    $this.siblings(".input-feedback").remove();
    var ch = String.fromCharCode(event.which);
    if (!/[0-9.]/.test(ch)) {
        if (eve.which == $backkey || eve.which == $enterkey) {} else event.returnValue = false;
        if (event.returnValue == false) {
            $this.addClass("input-invalid");
            if ($this.siblings("div.input-group-append").length == 0) {
                $this.after($input_element);
            } else {
                $this.siblings("div.input-group-append").after($input_element);
            }

            setTimeout(function () {
                $this.removeClass("input-invalid");
                $this.siblings(".input-feedback").remove();
            }, 1000);
        }
    }
    $this.val($this.val().replace(/[^0-9.]/g, ""));

    if ($val == "") {
        $this.removeClass("is-valid").removeClass("is-invalid");
    }
}

// Force to write aplhabets only i.e. a-zA-Z
function alpha_only(eve, $this) {
    $val = $this.val();
    $input_element =
        '<div class="input-feedback">Only alphabets are allowed</div>';
    $enterkey = 13;
    $backkey = 8;
    $this.siblings(".input-feedback").remove();
    var ch = String.fromCharCode(event.which);
    if (!/[a-zA-Z ]/.test(ch)) {
        if (eve.which == $backkey || eve.which == $enterkey) {} else event.returnValue = false;
        if (event.returnValue == false) {
            $this.addClass("input-invalid");
            $this.after($input_element);
            setTimeout(function () {
                $this.removeClass("input-invalid");
                $this.siblings(".input-feedback").remove();
            }, 1000);
        }
    }
    $this.val($this.val().replace(/[^a-zA-Z ]/g, ""));

    if ($val == "") {
        $this.removeClass("is-valid").removeClass("is-invalid");
    }
}

// Force to write numbers space only
function alphanumeric_only(eve, $this) {
    $val = $this.val();
    $input_element = '<div class="input-feedback">Only numbers and alphabets are allowed</div>';
    $enterkey = 13;
    $backkey = 8;
    $this.siblings(".input-feedback").remove();
    var ch = String.fromCharCode(event.which);
    if (!/[a-zA-Z0-9 ]/.test(ch)) {
        if (eve.which == $backkey || eve.which == $enterkey) {} else event.returnValue = false;
        if (event.returnValue == false) {
            $this.addClass("input-invalid");
            if ($this.siblings(".input-group-append").length) {
                $this.siblings(".input-group-append").after($input_element);
            } else {
                $this.after($input_element);
            }
            setTimeout(function () {
                $this.removeClass("input-invalid");
                $this.siblings(".input-feedback").remove();
            }, 1000);
        }
    }
    $this.val($this.val().replace(/[^a-zA-Z0-9 ]/g, ""));
    if ($val == "") {
        $this.removeClass("is-valid").removeClass("is-invalid");
    }
}

function email_only($element) {
    $email = $element.val();
    if (!is_empty($email) && !validateEmail($email)) {
        $element
            .removeClass("is-valid")
            .addClass("is-invalid")
            .siblings(".invalid-feedback")
            .html("Invalid email format");
    } else {
        $element.removeClass("is-invalid");
    }
}

// Validate email
function validateEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}


function ajax_page_redirect($url, $replace) {
    if ($url == "reload") {
        location.reload();
    } else if (is_valid_http_url($url)) {
        if ($replace == true) {
            location.replace($url);
        } else {
            location.href = $url;
        }
    }
    location.href = $base_url;
}

$(document).on("click", "#enable_form", function () {
    let $this = $(this);
    let $form = $this.data("form");
    $form = $($form);
    $is_form_disabled = $form.attr("disabled");
    if ($is_form_disabled) {
        $form.find(".form-control").attr("disabled", false);
        $form.attr("disabled", false);
    } else {
        $form.find(".form-control").attr("disabled", true);
        $form.attr("disabled", true);
    }
});

function diable_ajax_form($form) {
    $form.attr("disabled", true);
    $form.find(".form-control").attr("disabled", true);
}

function init_datatbl() {
    $("[id=data_tbl]").each(function () {
        $(this).DataTable();
          initSelect2();
    })
}

$(document).on("click", "#submit_form", function () {
    let $button = $(this);
    let $form = $button.data("form");
    $form = $($form);
    $form.trigger("submit");
})


function init_tree_chart($nodes) {
    chart = new OrgChart(document.getElementById("tree_chart"), {
        template: "ula",
        mouseScrool: OrgChart.action.none,
        nodeMouseClick: OrgChart.action.none,
        collapse: {
            level: 1,
            allChildren: false,
        },
        toolbar: {
            zoom: true,
            fit: true,
            fullScreen: true
        },
        scaleInitial: .8,
        zoom: false,
        nodeBinding: {
            img_0: "img",
            field_0: "name",
            field_1: "title"
        },
        nodes: $nodes
    });
}


let $confirmItem = '';

function confirmDelete($element, $text, $btn_text) {
    if ($confirmItem[0] === $element[0]) {
        return true;
    }
    console.log($btn_text)
    $confirmItem = $element;
    if ($("#confirmModal")[0]) {
        $("#confirmModal").remove();
    }
    $("body").append('<div id="confirmModal" class="modal fade ">\
                    <div class="modal-dialog modal-confirm">\
                        <div class="modal-content">\
                            <div class="modal-header flex-column">\
                                <div class="icon-box">\
                                    <i class="material-icons-outlined">delete</i>\
                                </div>\
                                <h4 class="modal-title w-100">Are you sure?</h4>\
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\
                            </div>\
                            <div class="modal-body">\
                                <p> ' + $text + ' </p>\
                            </div>\
                            <div class="modal-footer justify-content-center">\
                                <button id="cancelConfirm" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\
                                <button id="deleteCofirmed" type="button" class="btn btn-danger">' + $btn_text + '</button>\
                            </div>\
                        </div>\
                    </div>\
                </div>');
    $("#confirmModal").modal("toggle");
}
$(document).on("hidden.bs.modal", "#confirmModal", function () {
    $confirmItem = '';
    $("#confirmModal").modal('hide');
    setTimeout(function () {
        $("#confirmModal").remove();
    }, 400)
});

$(document).on("click", "#cancelConfirm", function () {
    $confirmItem = '';
    $("#confirmModal").modal('hide');
    setTimeout(function () {
        $("#confirmModal").remove();
    }, 400)
})

$(document).on("click", "#confirmModal #deleteCofirmed", function () {
    if ($confirmItem.length > 0) {
        $confirmItem.trigger("click");
        $confirmItem = '';
    }
    $("#confirmModal").modal('hide');
    setTimeout(function () {
        $("#confirmModal").remove();
    }, 400)
});



$(document).on("click", "#remove_element", function () {
    let $element = $(this);
    let $remove_element = $element.data("element");
    $remove_element = $element.parents().eq($remove_element);
    $remove_element.remove();
})


$(document).on("click", "img[target]", function () {
    let $img = $(this);
    let $link = $img.attr("src");
    window.open($link);
})

$('body').tooltip({
    selector: '[data-toggle="tooltip"]'
});