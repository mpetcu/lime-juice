<h1 class="head"><span class="glyphicon glyphicon-cog"></span> Users & permissions</h1>
<div class="user">
    <table class="table table-hover table-condensed table-striped table-users">
        <thead>
            <tr>
                <th>Name/Email</th>
                <th width="200px">Last access</th>
                <th width="85px">Type</th>
                <th width="280x">Actions</th>
            </tr>
        </thead>
        <tbody>
            {% for user in users %}
                <tr>
                    <td>
                        {% if user.type == 'master' %}<span class="glyphicon glyphicon-king"></span>{% endif %}
                        <strong>{{ user.getName() }}</strong> {% if user.getName() != user.email %}<i>({{ user.email }})</i>{% endif %}
                    </td>
                    <td>{% if user.sessionDate %}{{ utility.formatDate(user.sessionDate) }}{% else %}-{% endif %}</td>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-xs btn-default dropdown-toggle" type="button" id="dm1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                               <span class="glyphicon glyphicon-user"></span> {{ user.type }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dm1">
                                {% if user.type == 'master' %}{% set user_type = 'operator' %}{% else %}{% set user_type = 'master' %}{% endif %}
                                <li><a href="{{ url('settings/changeUserType', ['id':user.getId(),'type':user_type]) }}" class="change-type"><span class="glyphicon glyphicon-user"></span> {{ user_type }}</a></li>
                            </ul>
                        </div>
                    </td>
                    <td>
                        <a href="{{ url('settings/changeUserStatus', ['id':user.getId()]) }}" class="btn btn-xs btn-{% if user.status%}success{% else %}danger{% endif %} change-status">
                            {% if user.status%}<span class="glyphicon glyphicon-ok"></span> Active</a>{% else %}<span class="glyphicon glyphicon-remove"></span> Disabled</a>{% endif %}
                        {% if user.type != 'master' %}
                            <a href="{{ url('settings/permissionModal', ['id':user.getId()]) }}" class="btn btn-xs btn-default runModal"><span class="glyphicon glyphicon-remove-sign"></span> Permissions</a>
                        {% endif %}
                        <a href="{{ url('settings/changeUserPass', ['id':user.getId()]) }}" class="btn btn-xs btn-default change-pass"><span class="glyphicon glyphicon-refresh"></span> Reset pass</a><br/>
                    </td>
                </tr>
            {% endfor %}
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">
                    {{ form(url('settings/index'), "method":"post", "autocomplete":"off") }}
                        {{ form.renderErrorsDecorated() }}
                        {{ form.render('email', ['placeholder':'Email address']) }}
                        {{ form.render('type', ['style':'min-width:auto']) }}
                        <button type="submit" class="btn btn-default" style="margin-top: -4px"><span class="glyphicon glyphicon-plus"></span> Add user</button>
                    </form>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    $(function(){

        $('.runModal').click(function(e){
            $.get($(this).attr('href'), function(data){
                $('#loadModal').empty().html(data);
            });
            e.preventDefault();
        });

        $('.change-status').click(function(){
            var a = $(this);
            $.get($(this).attr('href'), function(data){
                if(data == 0){
                    a.removeClass('btn-success').addClass('btn-danger').html('<span class="glyphicon glyphicon-remove"></span> Disabled');
                }else if(data == 1){
                    a.removeClass('btn-danger').addClass('btn-success').html('<span class="glyphicon glyphicon-ok"></span> Active');
                }
            });
            return false;
        });

        $('.change-type').click(function(){
            var a = $(this);
            $.get($(this).attr('href'), function(data){
                if(data){
                    var oldText = a.closest('div').find('a.dropdown-toggle').text();
                    a.html('<span class="glyphicon glyphicon-user"></span> ' + oldText);
                    a.attr('href', '{{ url('settings/changeUserType', ['id':user.getId(),'type':'']) }}' + oldText );
                    a.closest('div').find('a.dropdown-toggle').html('<span class="glyphicon glyphicon-user"></span> '+ data +' <span class="caret"></span>');
                }
            });
            return false;
        });

        $('.change-pass').click(function(){
            var a = $(this);
            $.get($(this).attr('href'), function(data){
                if(data){
                    if(data == 'Error'){
                        a.html('<span class="glyphicon glyphicon-warning-sign"></span> ' + data).removeClass('btn-default').addClass('btn-danger');
                    }else{
                        a.html('<span class="glyphicon glyphicon-envelope"></span> ' + data).removeClass('btn-default').addClass('btn-warning');
                    }

                }
            });
            return false;
        });
    });
</script>