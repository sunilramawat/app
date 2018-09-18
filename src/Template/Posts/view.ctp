<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Post'), ['action' => 'edit', $post->p_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Post'), ['action' => 'delete', $post->p_id], ['confirm' => __('Are you sure you want to delete # {0}?', $post->p_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Posts'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Post'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="posts view large-9 medium-8 columns content">
    <h3><?= h($post->p_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Photo') ?></th>
            <td><?= h($post->photo) ?></td>
        </tr>
        <tr>
            <th><?= __('Description') ?></th>
            <td><?= h($post->description) ?></td>
        </tr>
        <tr>
            <th><?= __('Lat') ?></th>
            <td><?= h($post->lat) ?></td>
        </tr>
        <tr>
            <th><?= __('Lng') ?></th>
            <td><?= h($post->lng) ?></td>
        </tr>
        <tr>
            <th><?= __('P Id') ?></th>
            <td><?= $this->Number->format($post->p_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Type') ?></th>
            <td><?= $this->Number->format($post->type) ?></td>
        </tr>
        <tr>
            <th><?= __('U Id') ?></th>
            <td><?= $this->Number->format($post->u_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Added Date') ?></th>
            <td><?= h($post->added_date) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Category') ?></h4>
        <?= $this->Text->autoParagraph(h($post->category)); ?>
    </div>
    <div class="row">
        <h4><?= __('Group') ?></h4>
        <?= $this->Text->autoParagraph(h($post->group)); ?>
    </div>
</div>
