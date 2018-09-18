<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $postReport->pr_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $postReport->pr_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Post Reports'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="postReports form large-9 medium-8 columns content">
    <?= $this->Form->create($postReport) ?>
    <fieldset>
        <legend><?= __('Edit Post Report') ?></legend>
        <?php
            echo $this->Form->input('p_id');
            echo $this->Form->input('u_id');
            echo $this->Form->input('comment');
            echo $this->Form->input('status');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
