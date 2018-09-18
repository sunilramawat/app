<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Review'), ['action' => 'edit', $review->r_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Review'), ['action' => 'delete', $review->r_id], ['confirm' => __('Are you sure you want to delete # {0}?', $review->r_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Reviews'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Review'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List My Cuisines'), ['controller' => 'MyCuisines', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New My Cuisine'), ['controller' => 'MyCuisines', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="reviews view large-9 medium-8 columns content">
    <h3><?= h($review->r_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('R Ratting') ?></th>
            <td><?= h($review->r_ratting) ?></td>
        </tr>
        <tr>
            <th><?= __('My Cuisine') ?></th>
            <td><?= $review->has('my_cuisine') ? $this->Html->link($review->my_cuisine->mc_id, ['controller' => 'MyCuisines', 'action' => 'view', $review->my_cuisine->mc_id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('R Id') ?></th>
            <td><?= $this->Number->format($review->r_id) ?></td>
        </tr>
        <tr>
            <th><?= __('R By') ?></th>
            <td><?= $this->Number->format($review->r_by) ?></td>
        </tr>
        <tr>
            <th><?= __('R To') ?></th>
            <td><?= $this->Number->format($review->r_to) ?></td>
        </tr>
        <tr>
            <th><?= __('Added Date') ?></th>
            <td><?= h($review->added_date) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('R Comment') ?></h4>
        <?= $this->Text->autoParagraph(h($review->r_comment)); ?>
    </div>
</div>
