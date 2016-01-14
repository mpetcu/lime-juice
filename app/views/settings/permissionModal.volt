<div class="modal fade" id="permissionsModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-remove-sign"></span> <strong>{{ user.getName() }}</strong> permissions </h4>
            </div>
            <form action="{{ url('settings/changePermission', ['id':user.getId()])}}" id="userPermissonsForm">

                <div class="modal-body">
                    {{ flash.output()}}
                    <table class="table table-striped permissionsList">
                        <thead>
                            <tr>
                                <th>Databases & Reports</th>
                                <th class="text-center" width="70px"><span class="glyphicon glyphicon-eye-open" title="Expand"></span> View</th>
                                <th class="text-center" width="70px"><span class="glyphicon glyphicon-play" title="Expand"></span> Run</th>
                            </tr>
                        </thead>
                        <tbody>
                        {% for itm in dbm %}
                                <tr style="background-color: #f2eeff">
                                    <td><span id="{{ itm.getId() }}" class="glyphicon orange glyphicon-minus-sign listExpander" title="Expand" ></span> <b>{{ itm.name }} <i class="gray">({{ itm.getReports()|length }})</i></b></td>
                                    <td class="text-center"><input type="checkbox" value="view" name="perm[Db][{{ itm.getId() }}][]" class="main" id="ck1-{{ itm.getId() }}" {% if user.hasPermission(itm, 'view') %}checked{% endif %} /></td>
                                    <td class="text-center"><input type="checkbox" value="run" name="perm[Db][{{ itm.getId() }}][]" class="main" id="ck2-{{ itm.getId() }}" {% if user.hasPermission(itm, 'run') %}checked{% endif %} /></td>
                                </tr>
                                {% for itm2 in itm.getReports() %}
                                    <tr class="{{ itm.getId() }}">
                                        <td class="padding-left: 30px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ itm2.name }}</td>
                                        <td class="text-center"><input type="checkbox" value="view" name="perm[Report][{{ itm2.getId() }}][]" class="ck1-{{ itm.getId() }}" {% if user.hasPermission(itm2, 'view') %}checked{% endif %} /></td>
                                        <td class="text-center"><input type="checkbox" value="run" name="perm[Report][{{ itm2.getId() }}][]" class="ck2-{{ itm.getId() }}" {% if user.hasPermission(itm2, 'run') %}checked{% endif %} /></td>
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
            hideAlertSuccess();

            $('.listExpander').click(function(){
                if($('.'+$(this).attr('id')).is(":visible")) {
                    $(this).find('.glyphicon').removeClass('glyphicon-minus-sign').addClass('glyphicon-plus-sign').attr('title', 'Expand');
                }else{
                    $(this).find('.glyphicon').removeClass('glyphicon-plus-sign').addClass('glyphicon-minus-sign').attr('title', 'Collapse');
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

