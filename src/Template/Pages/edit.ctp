<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $page->p_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $page->p_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Pages'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="pages form large-9 medium-8 columns content">
    <?= $this->Form->create($page) ?>
    <fieldset>
        <legend><?= __('Edit Page') ?></legend>
        <?php
            echo $this->Form->input('p_title');
            echo $this->Form->input('p_description');
            echo $this->Form->input('p_showing_order');
            echo $this->Form->input('p_status');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
