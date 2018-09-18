<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Hashtag'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="hashtags index large-9 medium-8 columns content">
    <h3><?= __('Hashtags') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('h_id') ?></th>
                <th><?= $this->Paginator->sort('h_hashtag') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hashtags as $hashtag): ?>
            <tr>
                <td><?= $this->Number->format($hashtag->h_id) ?></td>
                <td><?= h($hashtag->h_hashtag) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $hashtag->h_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $hashtag->h_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $hashtag->h_id], ['confirm' => __('Are you sure you want to delete # {0}?', $hashtag->h_id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
