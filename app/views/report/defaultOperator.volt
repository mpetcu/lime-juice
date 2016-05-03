<h1 class="head"><span class="glyphicon glyphicon-play"></span> My reports</h1>
<h2>Databases ({{ dbsl|length }})</h2>
<p><i>Pick a database for listing all associated reports</i></p>
{% for itm in dbsl %}
    {% if authenticatedUser.hasPermission(itm, 'view') %}
        <div class="bitm">
            <a href="{{ url("report/index", ["id" : itm.getId()]) }}" class="cnt small">
                <h2><span class="glyphicon glyphicon-tag"></span> {{ itm.name }}</h2>
                <p>{{ itm.countReports() }} reports</p>
            </a>
        </div>
    {% endif %}
{% endfor %}
{% if userRole == 'master' %}
    <div class="bitm">
        <a href="{{ url("db/new") }}" class="add small" title="New connection">
            <h2>
                <span class="glyphicon glyphicon-plus"></span><br/><br/>
                New database<br/>connection<br/>
            </h2>
        </a>
    </div>
{% endif %}
