<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Register'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('Login User') ?></legend>
                <?php
                echo $this->Form->control('email', ['required' => false]);
                echo $this->Form->control('password', ['required' => false]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Html->link(__('Forgot'), ['action' => 'forgot'], ['class' => 'button float-center']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>