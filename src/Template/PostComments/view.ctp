<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Post Comment'), ['action' => 'edit', $postComment->pc_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Post Comment'), ['action' => 'delete', $postComment->pc_id], ['confirm' => __('Are you sure you want to delete # {0}?', $postComment->pc_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Post Comments'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Post Comment'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Posts'), ['controller' => 'Posts', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Post'), ['controller' => 'Posts', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="postComments view large-9 medium-8 columns content">
    <h3><?= h($postComment->pc_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Post') ?></th>
            <td><?= $postComment->has('post') ? $this->Html->link($postComment->post->p_id, ['controller' => 'Posts', 'action' => 'view', $postComment->post->p_id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('User') ?></th>
            <td><?= $postComment->has('user') ? $this->Html->link($postComment->user->id, ['controller' => 'Users', 'action' => 'view', $postComment->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Pc Id') ?></th>
            <td><?= $this->Number->format($postComment->pc_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Added Date') ?></th>
            <td><?= h($postComment->added_date) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Comment') ?></h4>
        <?= $this->Text->autoParagraph(h($postComment->comment)); ?>
    </div>
</div>
