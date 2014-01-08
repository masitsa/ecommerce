// The contact form
$(document).ready(function() {
    $('span#error-span').hide();
    $('.text-input').css({backgroundColor:"#FFFFFF"});
    $('.text-input').focus(function () {
        $(this).css({backgroundColor:"#FCFCFC"});
    });
    $('.text-input').blur(function () {
        $(this).css({backgroundColor:"#FFFFFF"});
    });

    $(".form-button").click(function () {
		
		var submitUrl = $(this).attr('action');
        // validate and process form
        // first hide any error messages
       	$('span#error-span').hide();

        var name = $("input#name").val();
        if (name == "" || name == "Name") {
            $("span.name-error").show();
            $("span.name-error").html("Your name is required.");
            $("input#name").focus();
            return false;
        }
		
        var email = $("input#email").val();
        var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
        console.log(filter.test(email));
        if (!filter.test(email)) {
            $("span.email-error").show();
            $("span.email-error").html("Your email address is required and must be valid.");
            $("input#email").focus();
            return false;
        }
		
		var subject = $("input#subject").val();
        if (subject == "" || subject == "Subject") {
            $("span.subject-error").show();
            $("span.subject-error").html("A subject is required.");
            $("#subject").focus(); 
            return false;
        }
		
        var message = $("#message").val();
        if (message == "" || message == "Message") {
            $("span.message-error").show();
            $("span.message-error").html("Your message is required.");
            $("#message").focus(); 
            return false;
        }
		
		var bot_honey = $("#bot_honey").val();
		
        var dataString = 'name=' + name + '&email=' + email + '&subject=' + subject + '&message=' + message + '&bot_honey=' +bot_honey;
		
        $.ajax({
            type:"POST",
            url: submitUrl,
            data: dataString,
            success:function () {
				$('.af-form')[0].reset();
				$('span#error-span').hide();
                $('.af-form').prepend("<div class=\"alert alert-success fade in\"><button class=\"close\" data-dismiss=\"alert\" type=\"button\">&times;</button><strong>Thank you, you enquiry has been sent.</div>");
            }
        });
        return false;
    });
});