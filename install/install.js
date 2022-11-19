"use strict";

function scroll_to_class(element_class, removed_height) {
    var scroll_to = $(element_class).offset().top - removed_height;
    if ($(window).scrollTop() != scroll_to) {
        $('html, body').stop().animate({
            scrollTop: scroll_to
        }, 0);
    }
}

function bar_progress(progress_line_object, direction) {
    var number_of_steps = progress_line_object.data('number-of-steps');
    var now_value = progress_line_object.data('now-value');
    var new_value = 0;
    if (direction == 'right') {
        new_value = now_value + (100 / number_of_steps);
    } else if (direction == 'left') {
        new_value = now_value - (100 / number_of_steps);
    }
    progress_line_object.attr('style', 'width: ' + new_value + '%;').data('now-value', new_value);
}

(function ($) {
    "use strict";
    $.backstretch;
    $('#top-navbar-1').on('shown.bs.collapse', function () {
        $.backstretch("resize");
    });
    $('#top-navbar-1').on('hidden.bs.collapse', function () {
        $.backstretch("resize");
    });
    $('.f1 fieldset:first').fadeIn('slow');

    $('.f1 input[type="text"], .f1 input[type="password"], .f1 textarea').on('keyup', function () {
        $(this).removeClass('is-invalid');
    });

    $('.f1 .btn-next').on('click', function () {
        var parent_fieldset = $(this).parents('fieldset');
        var next_step = true;
        var current_active_step = $(this).parents('.f1').find('.f1-step.active');
        var progress_line = $(this).parents('.f1').find('.f1-progress-line');
        parent_fieldset.find('input[type="text"][required], input[type="password"][required], textarea[required]').each(function () {
            if ($(this).val() == "") {
                $(this).addClass('is-invalid');
                next_step = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        if (next_step) {
            parent_fieldset.fadeOut(400, function () {
                current_active_step.removeClass('active').addClass('activated').next().addClass('active');
                bar_progress(progress_line, 'right');
                $(this).next().fadeIn();
                scroll_to_class($('.f1'), 20);
            });
        }
    });
    $('.f1 .btn-previous').on('click', function () {
        var current_active_step = $(this).parents('.f1').find('.f1-step.active');
        var progress_line = $(this).parents('.f1').find('.f1-progress-line');
        $(this).parents('fieldset').fadeOut(400, function () {
            current_active_step.removeClass('active').prev().removeClass('activated').addClass('active');
            bar_progress(progress_line, 'left');
            $(this).prev().fadeIn();
            scroll_to_class($('.f1'), 20);
        });
    });
    $('.f1').on('submit', function (e) {
        $(this).find('input[type="text"][required], input[type="password"][required], textarea[required]').each(function () {
            if ($(this).val() == "") {
                e.preventDefault();
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
    });
})(jQuery);



$(document).on("submit", "#setup_from",function (event) {  
    let $url = location.href;
    $url = $url.substring(0, $url.lastIndexOf('/'));
    $url = $url.replace("install","");
    $url = $url.substring(0, $url.lastIndexOf('/'));    
    event.preventDefault();
    let $form = $(this);
    let $formdata = $form.serialize();
    $.ajax({
        method: "POST",
        url:"../setup",
        data: $formdata,
        beforeSend:function(){
            disable_form($form);
        },
        success:function ($response) {
            if(isJSON($response)){
                $response = JSON.parse($response);
                let $message = $response.message;
                let $url = $response.url;
                if(!is_empty($url)){
              sweetalert_redirect("success", $message,$url,"replace");
                }
              else{
                    sweetalert("success", $message);
              }
                $form.siblings(".f1-buttons").find("button").trigger("click");
            }else{
                sweetalert("error",$response);
            }
        },error:function(){
            sweetalert("error","Error in sending request");
        },
        complete:function(){
            enable_form($form);
        }
    });
})