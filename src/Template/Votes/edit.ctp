<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $vote->v_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $vote->v_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Votes'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Posts'), ['controller' => 'Posts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Post'), ['controller' => 'Posts', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="votes form large-9 medium-8 columns content">
    <?= $this->Form->create($vote) ?>
    <fieldset>
        <legend><?= __('Edit Vote') ?></legend>
        <?php
            echo $this->Form->input('v_p_id', ['options' => $posts]);
            echo $this->Form->input('v_u_id', ['options' => $users]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
