<div class="modal fade" id="delModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-trash"></span> Are you sure you want to delete executed log?</h4>
            </div>
            {{ form(url('report/deleteLog', ['id': log.getId()]), "method":"post", "autocomplete" : "off") }}
            <div class="modal-body">
                <div>
                    <i>Log:</i> <strong>{{ utility.formatDate(log.startTime) }} <i>({{ log.totalTime }} sec execution time)</i> by {% if log.runType == 'user' %}<span class="glyphicon glyphicon-user" title="User"></span>{% else %}<span class="glyphicon glyphicon-time" title="Cron job"></span>{% endif %}</strong><br/>
                    <i>Report:</i> {{ log.getReport().name }}<br/>
                    <i>Database:</i> {{ log.getReport().getDb().name }}
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" name="confirm" value="Yes, delete it" class="btn btn-danger pull-right" />
                <input type="button" value="No, keep it" data-dismiss="modal" class="btn btn-default pull-right btn-mrg-right" />
            </div>
            <script>
                $(function() {
                    $('#delModal').modal('show');
                });
            </script>
            </form>
        </div>
    </div>
</div>
