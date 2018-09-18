<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Vote'), ['action' => 'edit', $vote->v_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Vote'), ['action' => 'delete', $vote->v_id], ['confirm' => __('Are you sure you want to delete # {0}?', $vote->v_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Votes'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Vote'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Posts'), ['controller' => 'Posts', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Post'), ['controller' => 'Posts', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="votes view large-9 medium-8 columns content">
    <h3><?= h($vote->v_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Post') ?></th>
            <td><?= $vote->has('post') ? $this->Html->link($vote->post->p_id, ['controller' => 'Posts', 'action' => 'view', $vote->post->p_id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('User') ?></th>
            <td><?= $vote->has('user') ? $this->Html->link($vote->user->id, ['controller' => 'Users', 'action' => 'view', $vote->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('V Id') ?></th>
            <td><?= $this->Number->format($vote->v_id) ?></td>
        </tr>
    </table>
</div>
