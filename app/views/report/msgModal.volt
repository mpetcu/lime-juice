<div class="modal fade" id="msgModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-envelope"></span> Mail notification</h4>
            </div>
            {{ form(url('report/msgModal', ['id': report.getId()]), "method":"post", "autocomplete" : "off") }}
                <div class="modal-body">
                    <div class="notifBox">
                        <strong>Note: </strong>
                        {% if report.getNotif(authenticatedUser.getId()) %}
                            <span class="green">Email notification active.</span>
                        {% else %}
                            <span class="red">Email notification disable.</span>
                        {% endif %}
                    </div><br/>
                    <i>Report:</i> {{ report.name }}<br/>
                    <i>Database:</i> {{ report.getDb().name }}<br/>
                    <h3 class="text-center">
                        {% if change is not defined %}
                            Do you want to recive an email notifications<br/> when a new report has been generated?
                        {% endif %}
                    </h3>
                </div>
                <div class="modal-footer">
                    {% if change is defined %}
                        <a data-dismiss="modal" class="btn btn-warning pull-right" >Close window</a>
                    {% else %}
                        <input type="button" data-value="yes" value="Yes, I do" class="btn btn-success pull-right" />
                        <input type="button" data-value="no" value="No, I don't" class="btn btn-danger pull-right btn-mrg-right" />
                    {% endif %}
                </div>
                <script>
                    $(function() {
                        $('#msgModal').modal('show');
                        var response = null;
                        $('#msgModal form').submit(function(e){
                            $.post($(this).attr('action'), {'notif':response}, function(data){
                                $('#msgModal form').replaceWith($(data).find('form'));
                            });
                            e.preventDefault();
                        });
                        $('#msgModal form input[type=button]').click(function(){
                            response = $(this).attr('data-value');
                            $('#msgModal form').submit();
                        });
                    });
                </script>
            </form>
        </div>
    </div>
</div>
