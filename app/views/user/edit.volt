<h1 class="head"><span class="glyphicon glyphicon-user"></span> Edit your profile</h1>
{{ form(url('user/edit'), "method":"post", "autocomplete" : "off") }}
<ul>
    {{ form.renderErrorsDecorated() }}
    {{ form.renderDecorated('firstName') }}
    {{ form.renderDecorated('lastName') }}
    {{ form.renderDecorated('email') }}
    <li class="button"><input type="submit" class="btn btn-primary" value="Save"/></li>
</ul>
</form>