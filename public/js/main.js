/**
 * Created by Mihai on 11.10.2015.
 */
$("a[reload]").click(function(){
    alert($(this).attr('href'));
});

function hideAlertSuccess() {
    window.setTimeout(function() {
        $(".alert-success").fadeTo(1000, 0).slideUp(200, function(){
            $(this).remove();
        });
    }, 4000);
}