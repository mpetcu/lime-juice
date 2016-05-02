<div class="reportsRefresh">
    <h1 class="head">
        <a href="{{ url('report/default') }}" title="Go back!" class="go-back">Back</a>
        <span class="glyphicon glyphicon-tag"></span> {{ dbm.name }}
        {% if userRole == 'master' %}
            <a href="{{ url('db/delete', ['id': dbm.getId()]) }}" title="Delete database connection" class="btn btn-default pull-right btn-danger runModal"><span class="glyphicon glyphicon-trash"></span></a>
            <a href="{{ url('db/edit', ['id': dbm.getId()]) }}" title="Edit database connection" class="btn btn-default pull-right"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="{{ url('report/new', ['db': dbm.getId()]) }}" class="btn btn-success pull-right" title="Create new report"><span class="glyphicon glyphicon-plus"></span> <span class="lng">New report</span></a>
        {% endif %}
    </h1>
    <ul class="reports">
        {% for itm in dbm.getReports() %}
            {% if authenticatedUser.hasPermission(itm, 'view') %}
                <li {% if itm.logs is defined %}class="{% if itm.getLatestLog().errors %}red{%else%}green{%endif%}"{% endif %}>
                    <h2 class="title">{{ itm.name }} <i class="gray lng">({{ itm.getLogCount() }} logs)</i></h2>
                    {% if userRole == 'master' %}
                        <a href="{{ url('report/delete', ['id': itm.getId()]) }}" title="Delete" class="btn btn-sm btn-danger pull-right runModal"><span class="glyphicon glyphicon-trash"></span></a>
                        <a href="{{ url('report/edit', ['id': itm.getId()]) }}" title="Edit" class="btn btn-sm btn-default pull-right"><span class="glyphicon glyphicon-pencil"></span></a>
                        <a href="{{ url('settings/permissionUserModal', ['id': itm.getId()]) }}" title="Edit" class="btn btn-sm btn-default pull-right runModal"><span class="glyphicon glyphicon-user"></span></a>
                        <a href="{{ url('report/jobModal', ['id': itm.getId()]) }}" title="Cron job" class="btn btn-sm btn-default pull-right runModal"><span class="glyphicon glyphicon-time"></span></a>
                    {% else %}
                        <a href="{{ url('report/msgModal', ['id': itm.getId()]) }}" title="Notifications" class="btn btn-sm btn-default pull-right runModal"><span class="glyphicon glyphicon-envelope"></span></a>
                    {% endif %}
                    {% if authenticatedUser.hasPermission(itm, 'run') %}
                        <a href="{{ url('report/runModal', ['id': itm.getId()]) }}" title="Run now" class="btn btn-sm btn-success pull-right runModal"><span class="glyphicon glyphicon-play"></span></a>
                    {% endif %}
                    <div class="data">
                        {% if date('Y-m-d H:i:s') < itm.getJob().getNextRun() and itm.getJob().status %}<span class="glyphicon glyphicon-time"></span> Next run: {{ utility.formatDate(itm.getJob().getNextRun()) }}<br/>{% endif %}
                        {% if userRole == 'operator' %}{% if authenticatedUser.hasPermission(itm, 'email') %}<span class="glyphicon glyphicon-envelope"></span> Email notification active.{% else %}<span class="red"><span class="glyphicon glyphicon-envelope"></span> Email notification disable.</span>{% endif %}<br/>{% endif %}
                        {% if userRole == 'master' and users is defined %}<span class="glyphicon glyphicon-user"></span>
                            {% set userExist = false %}
                            {% for user in users %}
                                {% if user.hasPermission(itm, 'view') %}
                                     {{ user.getName() }};&nbsp;
                                        {% set userExist = true %}
                                {% endif %}
                            {% endfor %}
                            {% if userExist == false %}No operator assigned.{% endif %}
                        {% endif %}
                    </div>
                    {% if itm.getLatestlog()%}
                        <div class="log">
                            <table width="100%" class="table table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th width="30px" class="lng">#</th>
                                    <th class="text-center lng" width="40px">Type</th>
                                    <th>Last run</th>
                                    <th class="lng">Time</th>
                                    <th class="text-center lng">Rows</th>
                                    <th class="text-right" width="170px">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                {% for index, log in itm.getLatestLog(3) %}
                                    <tr>
                                        <td class="lng"><b>{{ itm.getLogCount() - index }}</b></td>
                                        <td class="text-center lng">
                                            {% if log.runType == 'user' %}
                                                <span class="glyphicon glyphicon-user" title="Executed by user"></span>
                                            {% else %}
                                                <span class="glyphicon glyphicon-time" title="Executed by cron"></span>
                                            {% endif %}
                                        </td>
                                        <td>{{ utility.formatDate(log.startTime) }}</td>
                                        <td class="lng">{{ log.totalTime }} sec</td>
                                        {% if log.errors %}
                                            <td class="text-center lng"> - </td>
                                            <td class="lightgray text-right">
                                                <span class="red" title="{{ log.errors }}"><span class="glyphicon glyphicon-warning-sign" ></span> Error!</span>
                                                {% if userRole == 'master' %}
                                                    | <a href="{{ url('report/deleteLog', ['id': log.getId()]) }}" title="Delete" class="red runModal" title="Remove"><span class="glyphicon glyphicon-remove"></span></a>
                                                {% endif %}
                                            </td>
                                        {% else %}
                                            <td align="center" class="lng">{{ log.rows }}</td>
                                            <td class="lightgray text-right">
                                                <a class="orange" href="{{ utility.getFile(log.fileLocation) }}"><span class="glyphicon glyphicon-save"></span> {{ utility.formatBytes(log.fileSize) }}</a>
                                                | <a class="runModal" href="{{ url('report/viewModal', ['id': log.getId()]) }}" title="Preview"><span class="glyphicon glyphicon-eye-open"></span></a>
                                                {% if userRole == 'master' %}
                                                    | <a href="{{ url('report/deleteLog', ['id': log.getId()]) }}" title="Delete" class="red runModal" title="Remove"><span class="glyphicon glyphicon-remove"></span></a>
                                                {% endif %}
                                            </td>
                                        {% endif %}
                                    </tr>
                                {% endfor %}
                                </tbody>
                            </table>
                        </div>
                        <div class="view-more"><a href="{{ url('report/fullLogModal', ['id': itm.getId()]) }}" class="runModal" title="View more ..."><span class="glyphicon glyphicon-option-horizontal"></span></a></div>
                    {% endif %}
                </li>
            {% endif %}
        {% endfor %}
        {% if dbm.getReports()|length < 1 %}
            <li class="first-report"><a href="{{ url('report/new', ['db': dbm.getId()]) }}" title="Create report"><span class="glyphicon glyphicon-plus"></span> No reports added! Click here to add first report.</a></li>
        {% endif %}
    </ul>
    <script>
        $(function(){
            $('.runModal').click(function(e){
                $.get($(this).attr('href'), function(data){
                    $('#loadModal').empty().html(data);
                });
                e.preventDefault();
            });
        });
    </script>
</div>

