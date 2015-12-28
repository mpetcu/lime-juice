<h1 class="head"><span class="glyphicon glyphicon-play"></span> Edit report <strong>{{ report.getDb().name }}/{{ report.name }}</strong></h1>
{{ form(url('report/edit', ['id': report.getId()]), "method":"post", "autocomplete" : "off") }}
<ul>
    {{ form.renderErrorsDecorated() }}
    {{ form.renderDecorated('name') }}
    {{ form.renderDecorated('qry') }}
    <li class="button"><input type="submit" class="btn btn-primary" value="Save"/><input type="button" location="{{ url('report/new', ['db': report.did]) }}" class="save-as btn btn-default" value="Save as new"/></li>
</ul>
</form>
<script>
    $('.save-as').click(function(){
        $('form').attr('action', $(this).attr('location')).submit();
    });
</script>