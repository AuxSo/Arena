<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->fetch('css') ?>
    <?= $this->Html->css('base.css')?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->css('webarena.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->Html->script('http://code.jquery.com/jquery.min.js') ?>
    <?= $this->Html->script('tabs') ?>
    <?= $this->Html->script('infoDisplayer') ?>
</head>
<body>

<nav class="top-bar expanded" data-topbar role="navigation">
    <ul class="title-area large-3 medium-4 columns">
        <li class="name">
            <h1><a href=""><?= $this->fetch('title') ?></a></h1>
        </li>
    </ul>
    <div class="top-bar-section">
        <ul class="right">
            <li> <?= $this->Html->link('Home', ['controller' => 'Arenas', 'action' => 'index']) ?></li>
            <?php if($this->request->session()->check('myPlayerId')) { ?>
                <li> <?= $this->Html->link('Fighters', ['controller' => 'Arenas', 'action' => 'fighter']) ?></li>
                <li> <?= $this->Html->link('Sight', ['controller' => 'Arenas', 'action' => 'sight']) ?></li>
                <li> <?= $this->Html->link('Diary', ['controller' => 'Arenas', 'action' => 'diary']) ?></li>
                <li> <?= $this->Html->link('Log out', ['controller' => 'Arenas', 'action' => 'login']) ?></li>
            <?php }
            else {?>
                <li> <?= $this->Html->link('Log in', ['controller' => 'Arenas', 'action' => 'login']) ?></li>
            <?php } ?>
            <!--<li><a target="_blank" href="http://book.cakephp.org/3.0/">Documentation</a></li>
            <li><a target="_blank" href="http://api.cakephp.org/3.0/">API</a></li>-->
        </ul>
    </div>
</nav>
<?= $this->Flash->render() ?>
<div class="container clearfix">
    <?= $this->fetch('content') ?>
</div>
<footer>
    Groupe : Gr1-03-AG
    Membres : Baticle, Bourhim, Gauthier, Thouary
</footer>
</body>
</html>
