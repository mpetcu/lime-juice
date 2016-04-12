<div class="modal fade bs-example-modal-lg" id="viewModal">
    <div class="modal-dialog modal-lg-max">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-folder-open"></span> &nbsp; {{ log.getReport().name }} - {{ utility.formatDate(log.startTime) }} </h4>
            </div>
            <div class="modal-body">
                <div>
                    <table class="viewLog">
                        {% for index, row in logDataArr %}
                            {% if index == 0 %}
                                <thead>
                                    <tr>
                                        {% for cell in row %}
                                        <th>{{ cell }}</th>
                                        {% endfor %}
                                    </tr>
                                </thead>
                            {% else %}
                                <tbody>
                                    <tr>
                                        {% for cell in row %}
                                        <td>{{ cell }}</td>
                                        {% endfor %}
                                    </tr>
                                </tbody>
                            {% endif %}
                        {% endfor %}
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#viewModal').modal('show');
        hideAlertSuccess();
    });
</script>