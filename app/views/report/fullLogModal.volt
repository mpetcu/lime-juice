<div class="modal fade bs-example-modal-lg" id="logsModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-folder-open"></span> &nbsp; Logs for <strong>{{ report.getDb().name }}/{{ report.name }}</strong></h4>
            </div>
            <div class="modal-body" style="">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Last run</th>
                            <th>Execution time</th>
                            <th colspan="2">Rows/Return</th>
                        </tr>
                    </thead>
                    <tbody>
                    {% for index, itm in  report.getLogs() %}
                        <tr>
                            <td>{{ index+1 }}</td>
                            <td>{% if itm.startTime %}{{ utility.formatDate(itm.startTime) }}{% endif %}</td>
                            <td>{% if itm.totalTime %}{{ itm.totalTime }}{% endif %} sec</td>
                            <td {% if itm.errors %} colspan="2"{% endif %}>
                                {% if itm.errors %}
                                    <small style="color: red">{{ itm.errors }}</small>
                                {% else %}
                                    {% if itm.rows is defined %}{{ itm.rows }} rows{% endif %}
                                    </td><td>File generated: <a href="{{ utility.getFile(itm.fileLocation) }}"><b>Download ({{ utility.formatBytes(itm.fileSize) }})</b></a>
                                {% endif %}
                            </td>
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