<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $post->p_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $post->p_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Posts'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="posts form large-9 medium-8 columns content">
    <?= $this->Form->create($post) ?>
    <fieldset>
        <legend><?= __('Edit Post') ?></legend>
        <?php
            echo $this->Form->input('photo');
            echo $this->Form->input('description');
            echo $this->Form->input('type');
            echo $this->Form->input('category');
            echo $this->Form->input('group');
            echo $this->Form->input('lat');
            echo $this->Form->input('lng');
            echo $this->Form->input('added_date');
            echo $this->Form->input('u_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
