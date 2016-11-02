<section class="inscriptionSection">
    <h3>Join the game</h3>
    <form method="post">
        <?php if (!isset($mail)) $mail = "" ?>
        <input type="text" name="email" id="email" value="<?= $mail ?>" placeholder="email">
        <input type="password" name="password" id="password" placeholder="password">
        <a id="forgot">I've forgotten my password</a>
        <article id="buttons">
            <input class="loginButton" type="submit" id="inscription" name="inscription" value="Sign up">
            <input class="loginButton" type="submit" id="connexion" name="connexion" value="Log in">
        </article>
    </form>
    <?php
    $myTemplates = [
        'inputContainer' => '{{content}}',
        'input'          => '<input type="{{type}}" name="{{name}}" {{attrs}}>',

    ];
    $this->Form->templates($myTemplates);
    ?>

    <?= $this->Form->create('user', [
        'role'  => 'form-role',
        'class' => 'form-login'
    ]);
    ?>
    <a class="btn btn-block google btn-danger" href="<?= $this->Url->build(['action' => 'googlelogin']); ?>"> <i
            class="fa fa-google-plus modal-icons"></i><?= $this->Html->image("btn_google.png", ['alt' => 'Se connecter avec google', 'class' => 'googleButton']) ?>
    </a>
    <?= $this->Form->end(); ?>
</section>

<section id="lostMdp" class="inscriptionSection hidden">
    <form method="post">
        <input type="text" name="email" id="email" placeholder="email">
        <input class="loginButton" type="submit" name="lostMdp" value="Send me my password">
    </form>
    <?php if ($emailContent) {
        echo($emailContent);
    } ?>
</section>

<?php $this->assign('title','Log in');?>
