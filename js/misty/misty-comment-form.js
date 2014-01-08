// The comment form
$(function () {
    $('.error').hide();
    $('.text-input').css({backgroundColor:"#FFFFFF"});
    $('.text-input').focus(function () {
        $(this).css({backgroundColor:"#FCFCFC"});
    });
    $('.text-input').blur(function () {
        $(this).css({backgroundColor:"#FFFFFF"});
    });

    $(".form-button").click(function () {
        // validate and process form
        // first hide any error messages
       	$('.error').hide();

        var name = $("input#name").val();
        if (name == "" || name == "Your Name") {
            $("label#name_error").show();
            $("label#name_error").html("Your name is required.");
            $("input#name").focus();
            return false;
        }
        var email = $("input#email").val();
        var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
        console.log(filter.test(email));
        if (!filter.test(email)) {
            $("label#email_error").show();
            $("label#email_error").html("Your email address is required and must be valid.");
            $("input#email").focus();
            return false;
        }
        var message = $("#input-message").val();
        if (message == "" || message == "Your Message") {
            $("label#message_error").show();
            $("label#message_error").html("Your message is required.");
            $("#input-message").focus(); 
            return false;
        }
		
		var post_uuid = $("#post_uuid").val();
		var bot_honey = $("#bot_honey").val();

        var dataString = 'name=' + name + '&email=' + email + '&message=' + message + '&post_uuid=' + post_uuid + '&bot_honey=' +bot_honey;

        $.ajax({
            type:"POST",
            url: CI.base_url+"site/blog/comment/",
            data: dataString,
            success:function () {
				$('.error').hide();
                $('#af-form').prepend("<div class=\"alert alert-success fade in\"><button class=\"close\" data-dismiss=\"alert\" type=\"button\">&times;</button><strong>Your comment has been submitted!!</div>");
                $('#af-form')[0].reset();
    			$(".comments-list").fadeOut("slow").load(CI.base_url+'site/blog/reload_comments/'+post_uuid).fadeIn('slow');
            }
        });
        return false;
    });
});