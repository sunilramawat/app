<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Post Report'), ['action' => 'edit', $postReport->pr_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Post Report'), ['action' => 'delete', $postReport->pr_id], ['confirm' => __('Are you sure you want to delete # {0}?', $postReport->pr_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Post Reports'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Post Report'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="postReports view large-9 medium-8 columns content">
    <h3><?= h($postReport->pr_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Pr Id') ?></th>
            <td><?= $this->Number->format($postReport->pr_id) ?></td>
        </tr>
        <tr>
            <th><?= __('P Id') ?></th>
            <td><?= $this->Number->format($postReport->p_id) ?></td>
        </tr>
        <tr>
            <th><?= __('U Id') ?></th>
            <td><?= $this->Number->format($postReport->u_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($postReport->status) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Comment') ?></h4>
        <?= $this->Text->autoParagraph(h($postReport->comment)); ?>
    </div>
</div>
