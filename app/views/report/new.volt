<h1 class="head"><span class="glyphicon glyphicon-play"></span> Create report for "{{ dbm.name }}"</h1>
{{ form(url('report/new', ['db': dbm.getId()]), "method":"post", "autocomplete" : "off") }}
<ul>
    {{ form.renderErrorsDecorated() }}
    {{ form.renderDecorated('name', ['placeholder':'Sugestive short label here']) }}
    {{ form.renderDecorated('qry',['placeholder':'Valid query script here'])}}
    <li class="button"><input type="submit" class="btn btn-primary" value="Create report"/></li>
</ul>
</form>