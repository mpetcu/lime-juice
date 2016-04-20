<div class="modal fade" id="permissionsModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-remove-sign"></span> <strong>{{ user.getName() }}</strong> permissions </h4>
            </div>
            <form action="{{ url('settings/changePermission', ['user':user.getId()])}}" id="userPermissonsForm">

                <div class="modal-body">
                    {{ flash.output()}}
                    <table class="table table-striped permissionsList">
                        <thead>
                            <tr>
                                <th>Databases & Reports</th>
                                <th class="text-center" width="35px"><span class="glyphicon glyphicon-eye-open"></span><br/><small>View</small></th>
                                <th class="text-center" width="35px"><span class="glyphicon glyphicon-play"></span><br/><small>Run</small></th>
                                <th class="text-center" width="35px"><span class="glyphicon glyphicon-envelope"></span><br/><small>Email</small></th>
                            </tr>
                        </thead>
                        <tbody>
                        {% for itm in dbm %}
                                <tr style="background-color: #f2eeff">
                                    <td><span id="{{ itm.getId() }}"  style="cursor: pointer" class="glyphicon orange glyphicon-minus-sign listExpander" title="Collapse" ></span> <b>{{ itm.name }} <i class="gray">({{ itm.getReports()|length }})</i></b></td>
                                    <td class="text-center"><input type="checkbox" value="view" class="main" id="r-{{ itm.getId() }}" {% if user.hasPermission(itm, 'view') %}checked{% endif %} /></td>
                                    <td class="text-center"><input type="checkbox" value="run" class="main" id="wr-{{ itm.getId() }}" {% if user.hasPermission(itm, 'run') %}checked{% endif %} disabled /></td>
                                    <td class="text-center"><input type="checkbox" value="email" class="main" id="er-{{ itm.getId() }}" {% if user.hasPermission(itm, 'email') %}checked{% endif %} disabled /></td>
                                </tr>
                                {% for itm2 in itm.getReports() %}
                                    <tr class="{{ itm.getId() }}">
                                        <td class="padding-left: 30px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ itm2.name }}</td>
                                        <td class="text-center"><input type="checkbox" value="view" name="perm[{{ itm2.getId() }}][]" class="r-{{ itm.getId() }}" {% if user.hasPermission(itm2, 'view') %}checked{% endif %} id="r-{{ itm2.getId() }}" /></td>
                                        <td class="text-center"><input type="checkbox" value="run" name="perm[{{ itm2.getId() }}][]" class="wr-{{ itm.getId() }}" {% if user.hasPermission(itm2, 'run') %}checked{% endif %} id="wr-{{ itm2.getId() }}" disabled /></td>
                                        <td class="text-center"><input type="checkbox" value="email" name="perm[{{ itm2.getId() }}][]" class="er-{{ itm.getId() }}" {% if user.hasPermission(itm2, 'email') %}checked{% endif %} id="er-{{ itm2.getId() }}" disabled /></td>
                                    </tr>
                                {% endfor %}
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
                $('#w' + $(this).attr('id')+ ', #e' + $(this).attr('id')).prop('disabled', !$(this).prop('checked'));
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
                    $('#w' + $(this).attr('id')+ ', #e' + $(this).attr('id')).prop('disabled', !$(this).prop('checked'));
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

