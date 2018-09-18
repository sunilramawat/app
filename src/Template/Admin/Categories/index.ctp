<?php echo $this->Html->script("table-advanced.js"); ?>
<!-- Main content -->
<div class="content">
  <div class="row">
    <div class="col-lg-12">
      <!-- Default box -->
      <div class="panel panel-default">
        <div class="panel-heading">
          Categories List<?php echo $this->Html->link(__('Add Category'), ['action' => 'add'],['class'=>'btn btn-primary btn-sm float_Right']); ?>
        </div>
        <!-- /.panel-heading -->
        <div class="Cmspagess index large-9 medium-8 columns content">

          <table class="table table-striped table-bordered table-hover table-full-width" id="list">
            <thead>
              <tr>
              <!--  <th><input name="select_all" id="select_all" value="1" type="checkbox"></th> -->
                <th><?php echo __('S.no') ?></th>
                <th><?php echo __('Category name') ?></th>
                <th class="actions"><?php echo __('Actions') ?></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
<?php $url = (['controller' => 'categories', 'action' => 'index', 'prefix'=>'admin']); ?>
var PATH_GRID = '<?php echo $this->Url->build($url);?>';
var table;
$(document).ready(function (){
   // Array holding selected row IDs
   var rows_selected = [];
   table = $('#list').DataTable({
      responsive: true,
      'processing': false,
      'serverSide': true,
      'lengthMenu': [10,20,50, 100],//[[2,3,10, 25, 50, -1], [2,3,10, 25, 50, "All"]],
      'pageLength': 10,
      'ajax': {
         'url': PATH_GRID 
      },
      'columnDefs': [{
         'targets': 0,
         'searchable': false,
         'orderable': false,
         'width': '1%',
         'className': 'dt-body-center',
         /*'render': function (data, type, full, meta){
             return '<input type="checkbox">';
         }*/
      }],
      "columns": [
          { "name": "c_id","orderable":false,"searchable":false,'width':'5%', 'sClass': 'text-center'},
          { "name": "c_name", 'width':'15%',"searchable":true,'regex':true},
          { "name": "action" ,"orderable":false,"searchable":false,'width':'10%', 'sClass': 'text-center'},
      ],
      'order': [[0, "DESC"]],

      
   });


});
$('#categories').addClass('active');
  function changeStatus(id,status){
      URL = '<?php echo $this->Url->Build(["controller" => "categories","action" => "change_status","prefix"=>"admin"]);?>';
      $.ajax({
          url : URL,
          type: "POST",
          data : ({id:id,status:status}),
          beforeSend: function (XMLHttpRequest) {
          },
          complete: function (XMLHttpRequest, textStatus) {

          },
          success : function(data){
              if(data ==1 ) {  
                  $("#list").dataTable().fnReloadAjax(null, null, true);
              }
              else {
                 bootbox.alert("Some error occurred in changing status.", function() { });
              }
          }
      });
  }
  function deleteRow(id){
    bootbox.confirm("Are you sure you want to delete user", function(r) {
        if (r == true ) {
            URL = '<?php echo $this->Url->Build(["controller" => "categories","action" => "delete_row"]);?>';
            $.ajax({
                url: URL,
                type: 'POST',
                data: ({id: id}),
                success: function(data) {
                    if(data ==1) {    
                        $("#list").dataTable().fnReloadAjax(null, null, true);
                    }
                    else {
                        bootbox.alert("Some error occur in delete.", function() { });
                    }
                }
            });
        }
    });
  }

  
</script>
