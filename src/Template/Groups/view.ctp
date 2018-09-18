<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Group'), ['action' => 'edit', $group->g_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Group'), ['action' => 'delete', $group->g_id], ['confirm' => __('Are you sure you want to delete # {0}?', $group->g_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Groups'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Group'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="groups view large-9 medium-8 columns content">
    <h3><?= h($group->g_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('G Name') ?></th>
            <td><?= h($group->g_name) ?></td>
        </tr>
        <tr>
            <th><?= __('User') ?></th>
            <td><?= $group->has('user') ? $this->Html->link($group->user->id, ['controller' => 'Users', 'action' => 'view', $group->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('G Image') ?></th>
            <td><?= h($group->g_image) ?></td>
        </tr>
        <tr>
            <th><?= __('G Id') ?></th>
            <td><?= $this->Number->format($group->g_id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('G Discription') ?></h4>
        <?= $this->Text->autoParagraph(h($group->g_discription)); ?>
    </div>
    <div class="row">
        <h4><?= __('G Category') ?></h4>
        <?= $this->Text->autoParagraph(h($group->g_category)); ?>
    </div>
</div>
