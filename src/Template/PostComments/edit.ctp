<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $postComment->pc_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $postComment->pc_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Post Comments'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Posts'), ['controller' => 'Posts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Post'), ['controller' => 'Posts', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="postComments form large-9 medium-8 columns content">
    <?= $this->Form->create($postComment) ?>
    <fieldset>
        <legend><?= __('Edit Post Comment') ?></legend>
        <?php
            echo $this->Form->input('pc_p_id', ['options' => $posts]);
            echo $this->Form->input('pc_u_id', ['options' => $users]);
            echo $this->Form->input('comment');
            echo $this->Form->input('added_date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
