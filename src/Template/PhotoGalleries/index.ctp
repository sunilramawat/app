<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Photo Gallery'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List My Cuisines'), ['controller' => 'MyCuisines', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New My Cuisine'), ['controller' => 'MyCuisines', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="photoGalleries index large-9 medium-8 columns content">
    <h3><?= __('Photo Galleries') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('pg_id') ?></th>
                <th><?= $this->Paginator->sort('pg_mc_id') ?></th>
                <th><?= $this->Paginator->sort('ph_photo') ?></th>
                <th><?= $this->Paginator->sort('pg_u_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($photoGalleries as $photoGallery): ?>
            <tr>
                <td><?= $this->Number->format($photoGallery->pg_id) ?></td>
                <td><?= $photoGallery->has('my_cuisine') ? $this->Html->link($photoGallery->my_cuisine->mc_id, ['controller' => 'MyCuisines', 'action' => 'view', $photoGallery->my_cuisine->mc_id]) : '' ?></td>
                <td><?= h($photoGallery->ph_photo) ?></td>
                <td><?= $photoGallery->has('user') ? $this->Html->link($photoGallery->user->id, ['controller' => 'Users', 'action' => 'view', $photoGallery->user->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $photoGallery->pg_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $photoGallery->pg_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $photoGallery->pg_id], ['confirm' => __('Are you sure you want to delete # {0}?', $photoGallery->pg_id)]) ?>
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
