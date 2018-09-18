<!-- Main content -->
<div class="content">
  <div class="row">
    <div class="col-lg-12">
      <!-- Default box -->
      <div class="panel panel-default">
        <div class="panel-heading">
          Email Content List
          <a class="btn btn-primary btn-sm float_Right" href="<?php echo $this->Url->build(['controller'=>'email_contents','action'=>'compose_mail']);?>">Compose mail</a>
        </div>
        <!-- /.panel-heading -->
        <div class="emailContents index large-9 medium-8 columns content">
          <table class="table table-striped table-bordered table-hover table-full-width" id="list">
            <thead>
              <tr>
                <th></th>
                <th>Title</th>
                <th>Keywords</th>
                <th>Subject</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
    <?php if(isset($emailContents) && !empty($emailContents)){
            $cnt = 1;
            foreach ($emailContents as $emailContent) { ?>
              <tr>
                <td></td>
                <td><?php echo $emailContent->ec_title; ?></td>
                <td><?php echo $emailContent->ec_keywords; ?></td>
                <td><?php echo $emailContent->ec_subject; ?></td>
                <td><?php
                  $edit_link = $this->Url->build(['controller'=>'email_contents','action'=>'edit', $emailContent->ec_id]);
                    
                ?>
                <a href="<?php echo $edit_link?>" title="Edit Email Content"><i class="fa fa-edit fa-lg"></i></a>
                    <?php echo '&nbsp;&nbsp';?>
                            <?php 
                            
                            echo $this->Html->link(
                                '<i class="fa fa-eye fa-lg"></i>',
                                ['controller'=>'email_contents','action'=>'preview_email',$emailContent->ec_id],
                                ['class' => 'popuplink', 'data-toggle' => 'modal', 'data-target' => '#myModal','escape' => false]
                            );

                            ?>
                
                </td>
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