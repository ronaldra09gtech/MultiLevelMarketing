var DropzoneExample = function () {
    var DropzoneDemos = function () {
        Dropzone.options.dropZoneUploadForm = {
            url: $ajax_url,
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 100,
            maxFiles: 1,
            maxFilesize: 4,
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            init: function () {
                $btn = $("#dropZoneUploadForm").find("button[type=submit]");
                $form = $("#dropZoneUploadForm").data("form");
                var myDropzone = this;
                this.element.querySelector("button[type=submit]").addEventListener("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    myDropzone.processQueue();
                });
                this.on('sendingmultiple', function (data, xhr, formData) {
                    disable_btn($btn);
                    formData.append("action", "support_action");
                    formData.append("case", "upload_images");
                });
                this.on("successmultiple", function (files, $response) {
                    if (isJSON($response)) {
                        $response = JSON.parse($response);
                        $message = $response.message;
                        $images = $response.images;
                        $images = JSON.stringify($images);
                        sweetalert("success",$message);
                        $($form).attr("images", $images);
                        enable_btn($btn);
                        $btn.remove();
                        $(".dz-remove").remove();
                    } else {
                        sweetalert("error", $response);
                    }
                });

                this.on("error", function (file, message) {
                    message = message == "You can not upload any more files." ? "Maximum 1 image is allowed" : message;
                    sweetalert("error",message);
                    this.removeFile(file);
                });
               
            }

        }


    }
    return {
        init: function () {
            DropzoneDemos();
        }
    };
}();
DropzoneExample.init();