<h1 class="head"><span class="glyphicon glyphicon-tag"></span> Edit <strong>{{ dbm.name }}</strong> database connection</h1>
{{ form( url('db/edit', ['id': dbm.getId()]), "method":"post", "autocomplete" : "off") }}
<ul>
    {{ form.renderErrorsDecorated() }}
    {{ form.renderDecorated('name') }}
    <li class="space"></li>
    {{ form.renderDecorated('adapter') }}
    {{ form.renderDecorated('host') }}
    {{ form.renderDecorated('username') }}
    {{ form.renderDecorated('password') }}
    {{ form.renderDecorated('dbname') }}
    <li class="button"><input type="submit" class="btn btn-primary" value="Save"/> <input type="button" location="{{ url('db/new') }}" class="save-as btn btn-default" value="Save as new"/></li>
</ul>
</form>
<script>
    $('.save-as').click(function(){
        $('form').attr('action', $(this).attr('location')).submit();
    });
</script>