<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Photo Gallery'), ['action' => 'edit', $photoGallery->pg_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Photo Gallery'), ['action' => 'delete', $photoGallery->pg_id], ['confirm' => __('Are you sure you want to delete # {0}?', $photoGallery->pg_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Photo Galleries'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Photo Gallery'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List My Cuisines'), ['controller' => 'MyCuisines', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New My Cuisine'), ['controller' => 'MyCuisines', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="photoGalleries view large-9 medium-8 columns content">
    <h3><?= h($photoGallery->pg_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('My Cuisine') ?></th>
            <td><?= $photoGallery->has('my_cuisine') ? $this->Html->link($photoGallery->my_cuisine->mc_id, ['controller' => 'MyCuisines', 'action' => 'view', $photoGallery->my_cuisine->mc_id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Ph Photo') ?></th>
            <td><?= h($photoGallery->ph_photo) ?></td>
        </tr>
        <tr>
            <th><?= __('User') ?></th>
            <td><?= $photoGallery->has('user') ? $this->Html->link($photoGallery->user->id, ['controller' => 'Users', 'action' => 'view', $photoGallery->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Pg Id') ?></th>
            <td><?= $this->Number->format($photoGallery->pg_id) ?></td>
        </tr>
    </table>
</div>
