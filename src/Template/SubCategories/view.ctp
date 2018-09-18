<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sub Category'), ['action' => 'edit', $subCategory->sc_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sub Category'), ['action' => 'delete', $subCategory->sc_id], ['confirm' => __('Are you sure you want to delete # {0}?', $subCategory->sc_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sub Categories'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sub Category'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="subCategories view large-9 medium-8 columns content">
    <h3><?= h($subCategory->sc_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Sc Name') ?></th>
            <td><?= h($subCategory->sc_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Category') ?></th>
            <td><?= $subCategory->has('category') ? $this->Html->link($subCategory->category->c_id, ['controller' => 'Categories', 'action' => 'view', $subCategory->category->c_id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Sc Id') ?></th>
            <td><?= $this->Number->format($subCategory->sc_id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Sc Description') ?></h4>
        <?= $this->Text->autoParagraph(h($subCategory->sc_description)); ?>
    </div>
</div>
