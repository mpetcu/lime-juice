<div class="modal fade" id="permissionsModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-remove-sign"></span> Manage permissions for <strong>{{ report.name }}</strong>  </h4>
            </div>
            <form action="{{ url('settings/changePermission', ['report':report.getId()])}}" id="userPermissonsForm">

                <div class="modal-body">
                    {{ flash.output()}}
                    <table class="table table-striped permissionsList">
                        <thead>
                        <tr>
                            <th>Users <i class="gray">({{ users|length }})</i></th>
                            <th class="text-center" width="70px"><span class="glyphicon glyphicon-eye-open"></span> View</th>
                            <th class="text-center" width="70px"><span class="glyphicon glyphicon-play"></span> Run</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for user in users %}
                            <tr>
                                <td class="text-left">{{ user.getName() }}</td>
                                <td class="text-center"><input type="checkbox" value="view" name="perm[{{ user.getId() }}][]" class="main" id="r-{{ user.getId() }}" {% if user.hasPermission(report, 'view') %}checked{% endif %} /></td>
                                <td class="text-center"><input type="checkbox" value="run" name="perm[{{ user.getId() }}][]" class="main" id="wr-{{ user.getId() }}" {% if user.hasPermission(report, 'run') %}checked{% endif %} disabled /></td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>

                </div>

                <div class="modal-footer">
                    <input type="submit" value="Save and close" class="btn btn-success pull-right" />
                </div>

            </form>

        </div>
    </div>
    <script>
        $(function() {
            $('#permissionsModal').modal('show');
            $("input[type='checkbox'][value='view']").each(function(){
                $('#w' + $(this).attr('id')).prop('disabled', !$(this).prop('checked'));
            });
            hideAlertSuccess();

            $('.listExpander').click(function(){
                if($('.'+$(this).attr('id')).is(":visible")) {
                    $(this).removeClass('glyphicon-minus-sign').addClass('glyphicon-plus-sign').attr('title', 'Expand');
                }else{
                    $(this).removeClass('glyphicon-plus-sign').addClass('glyphicon-minus-sign').attr('title', 'Collapse');
                }
                $('.'+$(this).attr('id')).toggle();
            });

            $(".permissionsList input[type='checkbox']").change(function(){
                if($(this).hasClass('main')){
                    $('.'+$(this).attr('id')).prop('checked', $(this).prop('checked'));
                }else{
                    if($('.'+$(this).attr('class')+':checked').length > 0)
                        $('#'+$(this).attr('class')).prop('checked', true);
                    else
                        $('#'+$(this).attr('class')).prop('checked', false);
                }
                $("input[type='checkbox'][value='view']").each(function(){
                    $('#w' + $(this).attr('id')).prop('disabled', !$(this).prop('checked'));
                });
            });

            $("#userPermissonsForm").submit(function(){
                $.post($(this).attr('action'), $(this).serialize(), function(data){
                    if(data){
                        $('#permissionsModal').modal('hide');
                    }
                });
                return false;
            });
        });
    </script>
</div>

