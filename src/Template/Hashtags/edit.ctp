<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $hashtag->h_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $hashtag->h_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Hashtags'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="hashtags form large-9 medium-8 columns content">
    <?= $this->Form->create($hashtag) ?>
    <fieldset>
        <legend><?= __('Edit Hashtag') ?></legend>
        <?php
            echo $this->Form->input('h_hashtag');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
