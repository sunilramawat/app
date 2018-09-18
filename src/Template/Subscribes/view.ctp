<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Subscribe'), ['action' => 'edit', $subscribe->s_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Subscribe'), ['action' => 'delete', $subscribe->s_id], ['confirm' => __('Are you sure you want to delete # {0}?', $subscribe->s_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Subscribes'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Subscribe'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="subscribes view large-9 medium-8 columns content">
    <h3><?= h($subscribe->s_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('S Name') ?></th>
            <td><?= h($subscribe->s_name) ?></td>
        </tr>
        <tr>
            <th><?= __('S Id') ?></th>
            <td><?= $this->Number->format($subscribe->s_id) ?></td>
        </tr>
        <tr>
            <th><?= __('S Type') ?></th>
            <td><?= $this->Number->format($subscribe->s_type) ?></td>
        </tr>
    </table>
</div>
