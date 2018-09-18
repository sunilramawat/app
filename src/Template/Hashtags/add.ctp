<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Hashtags'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="hashtags form large-9 medium-8 columns content">
    <?= $this->Form->create($hashtag) ?>
    <fieldset>
        <legend><?= __('Add Hashtag') ?></legend>
        <?php
            echo $this->Form->input('h_hashtag');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
