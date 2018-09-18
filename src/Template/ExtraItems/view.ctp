<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Extra Item'), ['action' => 'edit', $extraItem->ei_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Extra Item'), ['action' => 'delete', $extraItem->ei_id], ['confirm' => __('Are you sure you want to delete # {0}?', $extraItem->ei_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Extra Items'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Extra Item'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List My Cuisines'), ['controller' => 'MyCuisines', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New My Cuisine'), ['controller' => 'MyCuisines', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="extraItems view large-9 medium-8 columns content">
    <h3><?= h($extraItem->ei_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Ei Name') ?></th>
            <td><?= h($extraItem->ei_name) ?></td>
        </tr>
        <tr>
            <th><?= __('My Cuisine') ?></th>
            <td><?= $extraItem->has('my_cuisine') ? $this->Html->link($extraItem->my_cuisine->mc_id, ['controller' => 'MyCuisines', 'action' => 'view', $extraItem->my_cuisine->mc_id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('User') ?></th>
            <td><?= $extraItem->has('user') ? $this->Html->link($extraItem->user->id, ['controller' => 'Users', 'action' => 'view', $extraItem->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Ei Id') ?></th>
            <td><?= $this->Number->format($extraItem->ei_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Ei Status') ?></th>
            <td><?= $this->Number->format($extraItem->ei_status) ?></td>
        </tr>
    </table>
</div>
