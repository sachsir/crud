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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $user->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users form content">
            <?= $this->Form->create($user, ["enctype" => "multipart/form-data"]) ?>
            <fieldset>
                <legend><?= __('Edit User') ?></legend>
                <?= $this->Html->link(__('Logout'), ['action' => 'logout'], ['class' => 'button float-right']) ?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $this->Form->control('image', ['type' => 'file', 'required' => false]) ?>
                        <span class="error-message" id="file-name-error"></span>
                    </div>
                    <div class="col-md-6">
                        <td><?= $this->Html->image(h($user->image), array('width' => '200px')) ?></td>
                    </div>
                </div>
                <?php
                echo $this->Form->control('first_name', ['required' => false]);
                echo $this->Form->control('last_name', ['required' => false]);
                echo $this->Form->control('email', ['required' => false]);
                echo $this->Form->control('phone_number', ['required' => false]);
                ?>
                <div class="col-md-6">
                    <label for="gender">Gender</label>
                    <div class="rahul">
                        <?= $this->Form->radio(
                            'gender',
                            ['Male' => 'Male', 'Female' => 'Female'],
                            ['required' => false]
                        ) ?>
                    </div>
                    <span class="error-message" id="gender-error"></span>
                    <?php
                    if ($this->Form->isFieldError('gender')) {
                        echo $this->Form->error('gender', 'Please select your Gender');
                    }
                    ?>
                </div>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>