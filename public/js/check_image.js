
$(document).ready(function () {
    $('#image').change(function() {

        //check file if image and size...
        let error = false;
        if(this.files && this.files[0]) {
            if (this.files[0].size > 2097152) {
                $("#image_error").html("File too large! Max allowed file size is 2Mb.");

                error = true;
            }

            if ($.inArray(this.files[0].type, ["image/gif", "image/jpeg", "image/png", "image/bmp", "image/jpg"]) < 0) {

                $("#image_error").html("File is not image! Please upload only gif, jpg, png and bmp images.");
                error = true;
            }

            if(error == true){
                $("form").first().submit(function (e) {
                    e.preventDefault();
                });
            }
        }
    });
});
