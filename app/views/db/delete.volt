<div class="modal fade" id="delModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-trash"></span> Are you sure you want to delete database connection?</h4>
            </div>
            {{ form(url('db/delete', ['id': dbm.getId()]), "method":"post", "autocomplete" : "off") }}
            <div class="modal-body">
                <div>
                    <i>Database:</i> <strong>{{ dbm.name }}</strong>
                </div>
                <div class="sqlError">
                    <strong>Attention:</strong><br/>
                    All reports assigned to this database connection will be deleted.<br/>
                    Generated report data will also be deleted.<br/>
                    Operation can't be undone!
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
