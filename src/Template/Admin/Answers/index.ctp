<!-- Main content -->
<div class="content">
  <div class="row">
    <div class="col-lg-12">
      <!-- Default box -->
      <div class="panel panel-default">
        <div class="panel-heading">
         User Answer List
         <!--  <a class="btn btn-primary btn-sm float_Right" href="<?php //echo $this->Url->build(['controller'=>'email_contents','action'=>'compose_mail']);?>">Compose mail</a> -->
        </div>
        <!-- /.panel-heading -->
        <div class="emailContents index large-9 medium-8 columns content">
          <table class="table table-striped table-bordered table-hover table-full-width" id="list">
            <thead>
              <tr>
                <th>S.No</th>
               <!--  <th>Username</th> -->
                <th>Query</th>
              </tr>
            </thead>
            <tbody>
    <?php if(isset($answers) && !empty($answers)){
            $cnt = 1;
            foreach ($answers as $answer){ 
              //echo '<pre>';print_r($answer);
              ?>
              <tr>
                <td><?php echo h($answer->uk_id); ?></td>
                <!-- <td><?php echo h($answer->user->username); ?></td> -->
                <td><?php echo h($answer->keyin->k_query); ?></td> 
               
              </tr>   
    <?php     $cnt++;
            }
          } ?>
          </tbody>
          </table>
         
        </div>
        
      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
  $('#email_content').addClass('active');
  $(document).ready(function() {
    var t = $('#list').DataTable( {
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]]
    } );
 
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
} );
</script>
