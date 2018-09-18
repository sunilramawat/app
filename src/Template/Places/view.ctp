<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Place'), ['action' => 'edit', $place->p_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Place'), ['action' => 'delete', $place->p_id], ['confirm' => __('Are you sure you want to delete # {0}?', $place->p_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Places'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Place'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="places view large-9 medium-8 columns content">
    <h3><?= h($place->p_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('P Name') ?></th>
            <td><?= h($place->p_name) ?></td>
        </tr>
        <tr>
            <th><?= __('P Id') ?></th>
            <td><?= $this->Number->format($place->p_id) ?></td>
        </tr>
    </table>
</div>
