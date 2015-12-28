<div class="modal fade" id="msgModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-envelope"></span> Mail notification for <strong>{{ report.getDb().name }}/{{ report.name }}</strong></h4>
            </div>
            {{ form(url('report/msgModal', ['id': report.getId()]), "method":"post", "autocomplete" : "off") }}
                <div class="modal-body">
                    <ul>
                        {{ flash.output()}}
                        {{ form.renderErrorsDecorated() }}
                        {{ form.renderDecorated('mail') }}
                    </ul>
                </div>
                <div class="modal-footer">
                    <input type="submit" value="Save" class="btn btn-success pull-right" />
                </div>
                <script>
                    $(function() {
                        $('#msgModal').modal('show');
                        hideAlertSuccess();
                        $('#msgModal form').submit(function(e){
                            $.post($(this).attr('action'), $(this).serialize(), function(data){
                                $('#msgModal form').replaceWith($(data).find('form'));
                            });
                            e.preventDefault();
                        });
                    });
                </script>
            </form>
        </div>
    </div>
</div>
