<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-play"></span>  Run <strong> {{ report.getDb().name }}/{{ report.name }}</strong></h4>
</div>
<div class="modal-body">
    Run progress
    <div class="progress">
        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="{% if lastRun %}100{% else %}0{% endif %}" aria-valuemin="0" aria-valuemax="100" style="width: {% if lastRun %}100{% else %}0{% endif %}%"></div>
    </div>

    Rows: <strong class="rows"> {% if lastRun and report.getLatestLog().rows %}{{ report.getLatestLog().rows }}{% else %}--{% endif %} </strong><br/>
    Format: <strong class="fileFormat"> {{ report.format }} </strong><br/>
    File size: <strong class="fileFormat"> {% if lastRun and report.getLatestLog().fileSize %}{{ utility.formatBytes(report.getLatestLog().fileSize) }}{% else %}--{% endif %} </strong><br/>
    Starts at: <strong class="startTime"> {% if lastRun and report.getLatestLog().startTime %}{{ report.getLatestLog().startTime }}{% else %}--{% endif %} </strong><br/>
    Ends at: <strong class="endTime"> {% if lastRun and report.getLatestLog().endTime %}{{ report.getLatestLog().endTime}}{% else %}--{% endif %} </strong><br/>
    Total time: <strong class="totalTime"> {% if lastRun and report.getLatestLog().totalTime %}{{ report.getLatestLog().totalTime }} sec{% else %}--{% endif %} </strong>
    {% if report.getLatestLog() %}<span class="info">( estimated {{ report.getLatestLog().totalTime }} sec)</span>{% endif %}

    {% if lastRun and report.getLatestLog() and report.getLatestLog().errors  %}<div class="sqlError">{{ report.getLatestLog().errors }}</div>{% endif %}

</div>
<div class="modal-footer">
    {% if lastRun and report.getLatestLog() and report.getLatestLog().errors == false %}
        <a href="{{ utility.getFile(report.getLatestLog().fileLocation) }}" class="btn btn-success pull-right" title="Get report"><span class="glyphicon glyphicon-save"></span> Download file</a>
    {% else %}
        <a href="{{ url('report/run', ['id': report.getId()]) }}" title="Run now" class="btn btn-success pull-right" id="startRunningReport"><span class="glyphicon glyphicon glyphicon-play"></span> Start now</a>
    {% endif %}
</div>
<script>
    var timeoutRunModal;
    var runDuration = {% if report.getLatestLog() %}{{ report.getLatestLog().totalTime }}{% else %}30{% endif %};
    $(function(){
        $('#runReportModal').modal('show');
        hideAlertSuccess();
        $('#startRunningReport').click(function(e){
            $(this).html('<span class="glyphicon glyphicon-refresh"></span> Running').addClass('disabled');
            var startTime = new Date();
            $('.startTime').text(startTime.toISOString('YYYY-MM-DD HH:mm:ss'));

            $.get($(this).attr('href'), function(data){
                clearTimeout(timeoutRunModal);
                $('#loadModal').find('#runReportModal .modal-content').replaceWith($(data).find('.modal-content'));
            });

            function refreshTime(){
                var currentTime = new Date();
                var time = Math.round((currentTime.getTime() - startTime.getTime())/10, 2)/100;
                $('#runReportModal .totalTime').text( time  + ' sec');
                var percent = time*100/runDuration;
                if(percent < 95){
                    $('#runReportModal .progress-bar').css("width", percent + '%').addClass('active');
                }
                timeoutRunModal = setTimeout(function(){refreshTime();}, 100);
            }

            refreshTime();
            e.preventDefault();

        });
    });
</script>