<div class="modal fade" id="jobModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-time"></span> Schedule run for <strong>{{ report.getDb().name }}/{{ report.name }}</strong></h4>
            </div>
            {{ form(url('report/jobModal', ['id': report.getId()]), "method":"post", "autocomplete" : "off") }}
                <div class="modal-body">
                    <ul>
                        {{ flash.output()}}
                        {{ form.renderErrorsDecorated() }}
                        {{ form.renderDecorated('type') }}
                        {{ form.renderDecorated('datetime') }}
                        {{ form.renderDecorated('job_sel') }}
                        {{ form.renderDecorated('job', ['placeholder':'* * * * *']) }}
                        {{ form.renderDecorated('status') }}
                    </ul>
                    <div class="console">
                        {% if report.logs is defined %}
                            <strong>Last run:</strong> <span class="glyphicon glyphicon-user"></span>
                            {{utility.formatDate(report.getLatestLog().startTime)}}
                            {% if report.getLatestLog().errors %}<span style="color: red"> with errors</span>{% endif %}
                            <br/>
                        {% endif %}
                        {% if job.nextRun is defined %}
                            <strong>Next run:</strong> <span class="glyphicon glyphicon-time"></span>
                            {% if job.status %}
                                {{utility.formatDate(job.getNextRun())}}
                                {% if job.type == 'once' %} run once {% else %} repetitiv {% endif %}
                            {% else %}
                                disabled
                            {% endif %}
                        {% endif %}
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" value="Save schedule" class="btn btn-success pull-right" />
                </div>
                <script>
                    $(function() {
                        $('#jobModal').modal('show');
                        hideAlertSuccess();
                        $('#jobModal form').submit(function(e){
                            $.post($(this).attr('action'), $(this).serialize(), function(data){
                                $('#jobModal form').replaceWith($(data).find('form'));
                            });
                            e.preventDefault();
                        });

                        function customCron(){
                            if($('#job_sel').val() == 'custom'){
                               $('#job').prop('disabled', false).parent().show();
                            }else{
                               $('#job').prop('disabled', true).parent().hide();
                            }
                        }

                        function changeType(){
                            if($('#type').val() == 'once'){
                                $('.datetime').prop('disabled', false).parent().show();
                                $('#job_sel').prop('disabled', true).parent().hide();
                                $('#job').prop('disabled', true).parent().hide();
                            }else{
                               $('.datetime').prop('disabled', true).parent().hide();
                               $('#job_sel').prop('disabled', false).parent().show();
                               customCron();
                            }
                        }

                        $('#job_sel').change(function(){
                            customCron();
                        });

                        $('#type').change(function(){
                            changeType();
                        });

                        changeType();
                    });
                </script>
            </form>
        </div>
    </div>
</div>
