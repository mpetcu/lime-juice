<h1 class="head"><span class="glyphicon glyphicon-play"></span> My reports</h1>
<h2>Latest reports logs</h2>

 <div class="log latest-logs">
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
        {% for index, log in logs %}
            <tr>
                <td class="lng"><b>{{ (logs|length - index) }}</b></td>
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
                        <a class="orange" href="{{ utility.getFile(log.fileLocation) }}" title="Download file"><span class="glyphicon glyphicon-save"></span> Download <strong>({{ utility.formatBytes(log.fileSize) }})</strong></a>
                        | <a class="runModal" href="{{ url('report/viewModal', ['id': log.getId()]) }}" title="Preview"><span class="glyphicon glyphicon-eye-open"></span> Preview </a>
                    </td>
                {% endif %}
            </tr>
        {% endfor %}
        </tbody>
    </table>
</div>

<h2>Databases ({{ dbsl|length }})</h2>
<p><i>Pick a database for listing(execution) associated reports.</i></p>
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
