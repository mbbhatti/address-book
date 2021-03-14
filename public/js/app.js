$(function() {

    // Use for correct image selection on change
    $("#avatar").change(function () {
        let regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
        if (regex.test($(this).val().toLowerCase())) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $(".file-upload").css('background-image','url(' + e.target.result + ')');
            }
            reader.readAsDataURL($(this)[0].files[0]);
        } else {
            $(this).val('');
            alert("Please select valid image.");
        }
    });

    // Hide and remove symfony flash message
    setTimeout(function() {
        $('div.alert').delay(1500).slideUp( "slow", function() {
            $("div.alert").remove();
        });
    }, 3000 );

    // Select date for user birthday
    $('input[name="birthday"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        maxDate: new Date()
    });

    // Custom validation rule for letters
    $.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Only letters are allowed.");

    // Implement form validation
    $("form[name='addressForm']").validate({
        rules: {
            firstname: "required",
            lastname: "required",
            email: {
                required: true,
                email: true
            },
            phoneNumber: "required",
            birthDay: "required",
            streetNumber: "required",
            zip: {
                required: true,
                minlength: 5
            },
            city: {
                required: true,
                lettersonly: true
            },
            country: {
                required: true,
                lettersonly: true
            }
        },
        messages: {
            firstname: "Please enter your firstname",
            lastname: "Please enter your lastname",
            email: "Please enter your valid email address",
            phoneNumber: "Please enter your phone number",
            birthDay: "Please select your birthday",
            streetNumber: "Please enter street and number",
            zip: {
                required: "Please enter your zip code",
                minlength: "Your zip must be at least 5 characters long"
            },
            city: {
                required: "Please provide your city",
                lettersonly: "Letters and spaces only please"
            },
            country: {
                required: "Please enter your country",
                lettersonly: "Letters and spaces only please"
            }
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function(form) {
            form.submit();
        }
    });
});
