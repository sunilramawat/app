<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $postCommentReport->pcr_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $postCommentReport->pcr_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Post Comment Reports'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="postCommentReports form large-9 medium-8 columns content">
    <?= $this->Form->create($postCommentReport) ?>
    <fieldset>
        <legend><?= __('Edit Post Comment Report') ?></legend>
        <?php
            echo $this->Form->input('pc_id');
            echo $this->Form->input('p_id');
            echo $this->Form->input('u_id');
            echo $this->Form->input('comment');
            echo $this->Form->input('status');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
