<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand"><a href="{{ url('db/index') }}" title="Go to dababase connections page!"><span class="glyphicon glyphicon-tags"></span> &nbsp;Databases ({{ dbs|length }})</a></li>
        {% for itm in dbs %}
            <li>
                <a href="{{ url('db/show', ['id': itm.getId()]) }}" {% if currentDbId == itm.getId() %} class="sel" {% endif %} >{{ itm.name }} <span class="badge pull-right">{{ itm.countReports() }}</span></a>
            </li>
        {% endfor %}
    </ul>
    <a href="{{ url('db/new')}}" class="btn btn-warning"><span class="glyphicon glyphicon-plus"></span> Database connection</a>
</div>
