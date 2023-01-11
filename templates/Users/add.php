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
            <?= $this->Html->link(__('Login'), ['action' => 'login'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users form content">
            <?= $this->Form->create($user, ["enctype" => "multipart/form-data"]) ?>
            <fieldset>
                <legend><?= __('Add User') ?></legend>
                <div class="col-md-6">
                    <?= $this->Form->control('image', ['type' => 'file', 'required' => false]) ?>
                    <span class="error-message" id="file-name-error"></span>
                </div>
                <?php
                echo $this->Form->control('first_name', ['required' => false]);
                echo $this->Form->control('last_name', ['required' => false]);
                echo $this->Form->control('email', ['required' => false]);
                echo $this->Form->control('phone_number', ['required' => false]);
                echo $this->Form->control('password', ['required' => false]);
                echo $this->Form->control('confirm_password', ['type' => 'password', 'required' => false]);
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