<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Group Subscribe'), ['action' => 'edit', $groupSubscribe->gs_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Group Subscribe'), ['action' => 'delete', $groupSubscribe->gs_id], ['confirm' => __('Are you sure you want to delete # {0}?', $groupSubscribe->gs_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Group Subscribes'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Group Subscribe'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="groupSubscribes view large-9 medium-8 columns content">
    <h3><?= h($groupSubscribe->gs_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Gs Id') ?></th>
            <td><?= $this->Number->format($groupSubscribe->gs_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Gs G Id') ?></th>
            <td><?= $this->Number->format($groupSubscribe->gs_g_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Gs U Id') ?></th>
            <td><?= $this->Number->format($groupSubscribe->gs_u_id) ?></td>
        </tr>
    </table>
</div>
