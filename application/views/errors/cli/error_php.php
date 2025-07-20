<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

Um Erro PHP foi encontrado

Severidade:    <?php echo $severity, "\n"; ?>
Mensagem:     <?php echo $message, "\n"; ?>
Arquivo:    <?php echo $filepath, "\n"; ?>
Número da Linha: <?php echo $line; ?>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

Rastreamento:
<?php	foreach (debug_backtrace() as $error): ?>
<?php		if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>
	Arquivo: <?php echo $error['file'], "\n"; ?>
	Linha: <?php echo $error['line'], "\n"; ?>
	Função: <?php echo $error['function'], "\n\n"; ?>
<?php		endif ?>
<?php	endforeach ?>

<?php endif ?>
