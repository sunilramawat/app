<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit My Cuisine'), ['action' => 'edit', $myCuisine->mc_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete My Cuisine'), ['action' => 'delete', $myCuisine->mc_id], ['confirm' => __('Are you sure you want to delete # {0}?', $myCuisine->mc_id)]) ?> </li>
        <li><?= $this->Html->link(__('List My Cuisines'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New My Cuisine'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="myCuisines view large-9 medium-8 columns content">
    <h3><?= h($myCuisine->mc_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Mc Name') ?></th>
            <td><?= h($myCuisine->mc_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Mc Photo') ?></th>
            <td><?= h($myCuisine->mc_photo) ?></td>
        </tr>
        <tr>
            <th><?= __('Timming') ?></th>
            <td><?= h($myCuisine->timming) ?></td>
        </tr>
        <tr>
            <th><?= __('Calories') ?></th>
            <td><?= h($myCuisine->calories) ?></td>
        </tr>
        <tr>
            <th><?= __('User') ?></th>
            <td><?= $myCuisine->has('user') ? $this->Html->link($myCuisine->user->id, ['controller' => 'Users', 'action' => 'view', $myCuisine->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Mc Id') ?></th>
            <td><?= $this->Number->format($myCuisine->mc_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Added Date') ?></th>
            <td><?= h($myCuisine->added_date) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Description') ?></h4>
        <?= $this->Text->autoParagraph(h($myCuisine->description)); ?>
    </div>
    <div class="row">
        <h4><?= __('Ingredients') ?></h4>
        <?= $this->Text->autoParagraph(h($myCuisine->ingredients)); ?>
    </div>
</div>
