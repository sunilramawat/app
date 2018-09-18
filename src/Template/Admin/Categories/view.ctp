<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Category'), ['action' => 'edit', $category->c_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Category'), ['action' => 'delete', $category->c_id], ['confirm' => __('Are you sure you want to delete # {0}?', $category->c_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="categories view large-9 medium-8 columns content">
    <h3><?= h($category->c_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('C Name') ?></th>
            <td><?= h($category->c_name) ?></td>
        </tr>
        <tr>
            <th><?= __('C Id') ?></th>
            <td><?= $this->Number->format($category->c_id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Description') ?></h4>
        <?= $this->Text->autoParagraph(h($category->description)); ?>
    </div>
</div>
