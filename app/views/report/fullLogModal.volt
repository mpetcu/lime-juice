<div class="modal fade bs-example-modal-lg" id="logsModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-folder-open"></span> &nbsp; Logs for <strong>{{ report.name }}</strong></h4>
            </div>
            <div class="modal-body log">
                <table cellpadding="5" width="100%" class="table table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-center" width="50px">Type</th>
                        <th>Last run</th>
                        <th>Time</th>
                        <th class="text-center">Rows</th>
                        <th class="text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% for index, log in report.getLogs() %}
                        <tr>
                            <td><b>{{ index+1 }}</b></td>
                            <td class="text-center">
                                {% if log.runType == 'user' %}
                                    <span class="glyphicon glyphicon-user" title="Executed by user"></span>
                                {% else %}
                                    <span class="glyphicon glyphicon-time" title="Executed by cron"></span>
                                {% endif %}
                            </td>
                            <td>{{ utility.formatDate(log.startTime) }}</td>
                            <td>{{ log.totalTime }} sec</td>
                            {% if log.errors %}
                                <td align="center"> - </td>
                                <td align="right" style="color: lightgray;">
                                    <span class="red" title="{{ log.errors }}"><span class="glyphicon glyphicon-warning-sign" ></span> Error!</span>
                                    | <a href="{{ url('report/deleteLog', ['id': log.getId()]) }}" title="Delete" class="red runModal" title="Remove"><span class="glyphicon glyphicon-remove"></span></a>
                                </td>
                            {% else %}
                                <td align="center">{{ log.rows }}</td>
                                <td style="color: lightgray;" width="170px" align="right">
                                    <a class="orange" href="{{ utility.getFile(log.fileLocation) }}"><span class="glyphicon glyphicon-save"></span> {{ utility.formatBytes(log.fileSize) }}</a>
                                    | <a class="runModal" href="{{ url('report/viewModal', ['id': log.getId()]) }}" title="Preview"><span class="glyphicon glyphicon-eye-open"></span></a>
                                    | <a href="{{ url('report/deleteLog', ['id': log.getId()]) }}" title="Delete" class="red runModal" title="Remove"><span class="glyphicon glyphicon-remove"></span></a>
                                </td>
                            {% endif %}
                        </tr>
                    {% endfor %}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#logsModal').modal('show');
        hideAlertSuccess();
    });
</script>