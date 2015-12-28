<h1 class="head"><span class="glyphicon glyphicon-user"></span> Change your pass</h1>
{{ form(url('user/pass'), "method":"post", "autocomplete" : "off") }}
<ul>
    {{ form.renderErrorsDecorated() }}
    {{ form.renderDecorated('oldPass') }}
    {{ form.renderDecorated('pass') }}
    {{ form.renderDecorated('pass2') }}
    <li class="button"><input type="submit" class="btn btn-primary" value="Save"/></li>
</ul>
</form>