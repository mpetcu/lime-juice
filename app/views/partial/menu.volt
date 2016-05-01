<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand"><span class="glyphicon glyphicon-tags"></span> &nbsp;Databases ({{ dbs|length }})</li>
        {% for itm in dbs %}
            {% if authenticatedUser.hasPermission(itm, 'view') %}
                <li>
                    <a href="{{ url('report/index', ['id': itm.getId()]) }}" {% if currentDbId == itm.getId() %} class="sel" {% endif %} >{{ itm.name }} <span class="badge pull-right">{{ itm.countReports() }}</span></a>
                </li>
            {% endif %}
        {% endfor %}
    </ul>
    {% if userRole == 'master' %}
        <a href="{{ url('db/new')}}" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> New connection</a>
    {% endif %}
</div>
