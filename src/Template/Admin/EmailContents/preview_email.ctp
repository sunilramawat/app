<?php
use Cake\Core\Configure;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Preview</h4>
        </div>
        <div class="modal-body">
            <?php if (!empty($EmailContentData)) { ?>

                <div class="modal-header email_header"><?php echo Configure::read('Site.title'); ?></div>
                <div class="modal-header contant_email"><?php echo $EmailContentData['ec_message']; ?></div>
                <div class="modal-header footer_email"><?php echo Configure::read('Site.title'); ?> . All rights reserved.</div>


                <?php
            } else {
                echo "Data Not Found";
            }
            ?>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
