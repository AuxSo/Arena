<section id="inscription">
    <form method="post">
        <label>Email</label>
        <?php if (!isset($mail)) $mail = "" ?>
        <input type="text" name="email" id="email" value="<?= $mail ?>">
        <label>Password</label>
        <input type="password" name="password" id="password">
        <input type="submit" id="inscription" name="inscription" value="inscription">
        <input type="submit" id="connexion" name="connexion" value="connexion">
    </form>
</section>

<section id="lostMdp" class="hidden">
    <form method="post">
        <label>Email</label>
        <input type="text" name="email" id="email">
        <input type="submit" id="lostMdp" name="lostMdp" value="Send me my password">
    </form>
    <?php if ($emailContent) {
        echo($emailContent);
    } ?>
</section>

<?php $this->assign('title','Log in');?>
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
            class="fa fa-google-plus modal-icons"></i> Se connecter avec Google+ </a>
</div>
<?= $this->Form->end(); ?>