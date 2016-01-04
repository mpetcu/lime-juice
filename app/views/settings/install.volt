<h1 class="head"><span class="glyphicon glyphicon-cog"></span> Install your project</h1>
<div class="alert alert-danger" role="alert" id="errmsg"></div>
<form method="post" id="install-form">
    <ul>
        <li>
            <label class="icon"><span class="permission glyphicon glyphicon-{% if permission['status'] %}ok green{% else %}remove red{% endif %}"></span></label>
            <div class="msg"><strong>Reports directory permission</strong>: {{ permission['message'] }}</div>
        </li>
        <li>
            <label class="icon"><span class="mongo glyphicon glyphicon-{% if mongo['status'] %}ok green{% else %}remove red{% endif %}"></span></label>
            <div class="msg"><strong>Mongo Database</strong>: {{ mongo['message'] }}</div>
        </li>
        <li>
            <label class="icon"><span class="mail glyphicon glyphicon-{% if mail['status'] %}ok green{% else %}remove red{% endif %}"></span></label>
            <div class="msg"><strong>Mail Server (PHPMailer)</strong>: {{ mail['message'] }}</div>
        </li>
        <li><label></label></li>

        <li>
            <label for="title">Master email</label>
            <input type='text' id="master_user" name="conf[master_user]" placeholder="Your email address"/>
        </li>
        <li>
            <label for="master_pass">Master pass</label>
            <input type='password' id="master_pass" placeholder="Min. 6 chars" name="conf[master_pass]"/>
        </li>
        <li>
            <label for="master_pass2">Master pass check</label>
            <input type='password' id="master_pass2" placeholder="Retype master pass" name="conf[master_pass2]"/>
        </li>
        <li>
            <br/>
            <label></label>
            <input type='submit' class="btn btn-primary" value="Install now" />
            <input type='submit' class="btn btn-warning" name="conf[skip]" value="Skip install" />
            <br/>
            <br/>
        </li>

    </ul>
</form>
<script>
    $(function(){
        $('#errmsg').hide();

        $('#install-form').submit(function(){
            return validateForm();
        });

        function validateForm(){
            var errors = [];
            if(!isEmailvalid($('#master_user').val()))
                errors.push('Your email address is invalid!');
            if($.trim($('#master_pass').val()).length < 6 )
                errors.push('Your password in invalid!');
            else
                if($.trim($('#master_pass').val()) != $.trim($('#master_pass2').val()))
                    errors.push('Pass & pass check are not identical!');
            if($('.mongo.glyphicon-remove').parent().html())
                errors.push('Your <b>Mongo database connection</b> is not correctly setup in <b>[YourProjectRoot]/app/config/config.php</b>');
            if($('.mail.glyphicon-remove').parent().html())
                errors.push('Your <b>mail server configuration</b> is not correctly setup in <b>[YourProjectRoot]/app/config/config.php</b>');
            if($('.permission.glyphicon-remove').parent().html())
                errors.push('You can\'t install without permission to read, write and execute for directory: <b>[YourProjectRoot]/public/reports</b>');

            $('#errmsg').html(errors.join('<br/>')).show();
            if(errors.length == 0){
                $('#errmsg').hide();
                return true;
            }
            return false;
        }

        function isEmailvalid(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }
    });
</script>
