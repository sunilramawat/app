<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Post Comment Report'), ['action' => 'edit', $postCommentReport->pcr_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Post Comment Report'), ['action' => 'delete', $postCommentReport->pcr_id], ['confirm' => __('Are you sure you want to delete # {0}?', $postCommentReport->pcr_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Post Comment Reports'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Post Comment Report'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="postCommentReports view large-9 medium-8 columns content">
    <h3><?= h($postCommentReport->pcr_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Pcr Id') ?></th>
            <td><?= $this->Number->format($postCommentReport->pcr_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Pc Id') ?></th>
            <td><?= $this->Number->format($postCommentReport->pc_id) ?></td>
        </tr>
        <tr>
            <th><?= __('P Id') ?></th>
            <td><?= $this->Number->format($postCommentReport->p_id) ?></td>
        </tr>
        <tr>
            <th><?= __('U Id') ?></th>
            <td><?= $this->Number->format($postCommentReport->u_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($postCommentReport->status) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Comment') ?></h4>
        <?= $this->Text->autoParagraph(h($postCommentReport->comment)); ?>
    </div>
</div>
