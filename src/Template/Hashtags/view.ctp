<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Hashtag'), ['action' => 'edit', $hashtag->h_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Hashtag'), ['action' => 'delete', $hashtag->h_id], ['confirm' => __('Are you sure you want to delete # {0}?', $hashtag->h_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Hashtags'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Hashtag'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="hashtags view large-9 medium-8 columns content">
    <h3><?= h($hashtag->h_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('H Hashtag') ?></th>
            <td><?= h($hashtag->h_hashtag) ?></td>
        </tr>
        <tr>
            <th><?= __('H Id') ?></th>
            <td><?= $this->Number->format($hashtag->h_id) ?></td>
        </tr>
    </table>
</div>
