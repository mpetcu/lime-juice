<div class="reportsRefresh">
    <h1 class="head"><span class="glyphicon glyphicon-tag"></span> {{ dbm.name }}
        <a href="{{ url('db/delete', ['id': dbm.getId()]) }}" title="Delete" class="btn btn-default pull-right btn-danger runModal"><span class="glyphicon glyphicon-trash"></span> Delete</a>
        <a href="{{ url('db/edit', ['id': dbm.getId()]) }}" title="Edit" class="btn btn-default pull-right"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
    </h1>
    <p class="info" style="margin-top: -10px">
        {% if dbm.getAdapter() %}Type: <b>{{ dbm.getAdapter() }}</b> | {% endif %}
        {% if dbm.getHost() %}Host: <b>{{ dbm.getHost() }}</b> | {% endif %}
        {% if dbm.getUsername() %}User:<b>{{ dbm.getUsername() }}</b> | {% endif %}
        {% if dbm.getDbname() %}Database:<b>{{ dbm.getDbname() }}</b> {% endif %}
    </p>
        <h2 class="subhead">Reports
            <a href="{{ url('report/new', ['db': dbm.getId()]) }}" class="btn btn-warning pull-right" title="Create new report"><span class="glyphicon glyphicon-plus"></span> Create report</a>
        </h2>
        <ul class="reports">
            {% for itm in dbm.getReports() %}
                <li {% if itm.logs is defined %}class="{% if itm.getLatestLog().errors %}red{%else%}green{%endif%}"{% endif %}>
                    <a href="{{ url("report/edit", ["id" : itm.getId()]) }}" title="Edit" class="title">{{ itm.name }} <i class="gray">({{ itm.getLogCount() }} logs)</i></a>
                    <a href="{{ url('report/delete', ['id': itm.getId()]) }}" title="Delete" class="btn btn-sm btn-danger pull-right runModal"><span class="glyphicon glyphicon-trash"></span></a>
                    <a href="{{ url('report/edit', ['id': itm.getId()]) }}" title="Edit" class="btn btn-sm btn-default pull-right"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="{{ url('report/jobModal', ['id': itm.getId()]) }}" title="Cron job" class="btn btn-sm btn-default pull-right runModal"><span class="glyphicon glyphicon-time"></span></a>
                    <a href="{{ url('report/msgModal', ['id': itm.getId()]) }}" title="Delete" class="btn btn-sm btn-default pull-right runModal"><span class="glyphicon glyphicon-envelope"></span></a>
                    <a href="{{ url('report/runModal', ['id': itm.getId()]) }}" title="Run now" class="btn btn-sm btn-success pull-right runModal"><span class="glyphicon glyphicon-play"></span></a>
                    {% if itm.getLatestlog()%}
                        {% if !itm.getLatestLog().errors %}
                            <a href="{{ utility.getFile(itm.getLatestLog().fileLocation) }}" target="_blank" title="Download latest report - {{ itm.getLatestLog().startTime }} ({{ utility.formatBytes(itm.getLatestLog().fileSize) }})" class="btn btn-sm btn-warning pull-right"><span class="glyphicon glyphicon-save"></span></a>
                        {% endif %}
                        <div class="log">
                            Last run: {{ utility.formatDate(itm.getLatestLog().startTime) }}<br/>
                            Time: {{ itm.getLatestLog().totalTime }} sec<br/>
                            {% if itm.getLatestLog().errors %}
                                <span style="color: red">{{ itm.getLatestLog().errors }}</span>
                            {% else %}
                                Rows: {{ itm.getLatestLog().rows }}<br/>
                                File generated: <a href="{{ utility.getFile(itm.getLatestLog().fileLocation) }}"><b>Download ({{ utility.formatBytes(itm.getLatestLog().fileSize) }})</b></a>
                            {% endif %}
                        </div>
                            <small><a href="{{ url('report/fullLogModal', ['id': itm.getId()]) }}" class="pull-right btn btn-xs runModal">Show full log</a><br/></small>
                    {% endif %}
                </li>
            {% endfor %}
            {% if dbm.getReports()|length < 1 %}
                <li class="first-report"><a href="{{ url('report/new', ['db': dbm.getId()]) }}" title="Create report"><span class="glyphicon glyphicon-plus" style="color: orange"></span> No reports added! Click here to add first report.</a></li>
            {% endif %}
        </ul>
    <script>
        var refreshAfter = {{ refreshTime }};
        $(function(){
            //launch modal
            $('.runModal').click(function(e){
                $.get($(this).attr('href'), function(data){
                    $('#loadModal').empty().html(data);
                });
                e.preventDefault();
            });
            //refresh page
            window.setTimeout(function() {
                $.get("{{ url('db/show',['id': dbm.getId()]) }}", function(data){
                    $('div.reportsRef').replaceWith(data);
                });
            }, refreshAfter);
        });
    </script>
</div>

