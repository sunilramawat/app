<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Answer'), ['action' => 'edit', $answer->a_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Answer'), ['action' => 'delete', $answer->a_id], ['confirm' => __('Are you sure you want to delete # {0}?', $answer->a_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Answers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Answer'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="answers view large-9 medium-8 columns content">
    <h3><?= h($answer->a_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('A Id') ?></th>
            <td><?= $this->Number->format($answer->a_id) ?></td>
        </tr>
        <tr>
            <th><?= __('K Id') ?></th>
            <td><?= $this->Number->format($answer->k_id) ?></td>
        </tr>
        <tr>
            <th><?= __('U Id') ?></th>
            <td><?= $this->Number->format($answer->u_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Date') ?></th>
            <td><?= h($answer->date) ?></td>
        </tr>
    </table>
</div>
