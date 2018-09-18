<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Page'), ['action' => 'edit', $page->p_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Page'), ['action' => 'delete', $page->p_id], ['confirm' => __('Are you sure you want to delete # {0}?', $page->p_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Pages'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Page'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="pages view large-9 medium-8 columns content">
    <h3><?= h($page->p_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('P Title') ?></th>
            <td><?= h($page->p_title) ?></td>
        </tr>
        <tr>
            <th><?= __('P Id') ?></th>
            <td><?= $this->Number->format($page->p_id) ?></td>
        </tr>
        <tr>
            <th><?= __('P Showing Order') ?></th>
            <td><?= $this->Number->format($page->p_showing_order) ?></td>
        </tr>
        <tr>
            <th><?= __('P Status') ?></th>
            <td><?= $this->Number->format($page->p_status) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('P Description') ?></h4>
        <?= $this->Text->autoParagraph(h($page->p_description)); ?>
    </div>
</div>
