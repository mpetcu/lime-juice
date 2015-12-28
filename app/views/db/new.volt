<h1 class="head"><span class="glyphicon glyphicon-tag"></span> Create database connection</h1>
{{ form('db/new', "method":"post", "autocomplete" : "off") }}
<ul>
    {{ form.renderErrorsDecorated() }}
    {{ form.renderDecorated('name') }}
    <li class="space"></li>
    {{ form.renderDecorated('adapter') }}
    {{ form.renderDecorated('host') }}
    {{ form.renderDecorated('username') }}
    {{ form.renderDecorated('password') }}
    {{ form.renderDecorated('dbname') }}
    <li class="button"><input type="submit" class="btn btn-primary" value="Create"/></li>
</ul>
</form>