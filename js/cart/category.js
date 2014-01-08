$(document).ready(function(){
    var id;
    var baseurl = $('#baseurl').val();
    $('.submitComment').submit(function(){
        
        $.ajax({
            url : baseurl + 'index.php/comment/insert',
            data : $(' form').serialize(),
            type: "POST",
            success : function(comment){
                
                $(comment).hide().insertBefore('#insertbeforMe').slideDown('slow');
            }
        })
        return false;
    })
    $('.deleteComment').click(function(e){
       id = $(this).attr('href');
       $('.deleteConfirm').dialog('open');
       
       e.preventDefault();
    })
    $('.deleteConfirm').dialog({
        autoOpen : false,
        modal : true,
        buttons : {
            'Yes' : function(){
               $.ajax({
                   url : baseurl + 'index.php/products/delete_category/' + id,
                   success : function(){
                       // i must remove the div
                		$(this).dialog('close');
                      // $('#silde' + id).slideUp('slow');
					   window.location.href = baseurl + 'index.php/category/';
                   }
               })
            },
            'No' : function(){
                $(this).dialog('close');
            }
        }
    })
})