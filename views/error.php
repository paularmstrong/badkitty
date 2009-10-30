<?php
/**
 * Debug Error View.
 * You should only see this if __DEBUG__ is TRUE in config/bootstrap.
 */
?>
<h1>Caught exception: <?= $this->e->getMessage(); ?></h1>
<h2>File: <?= $this->e->getFile()?></h2>
<h2>Line: <?= $this->e->getLine()?></h2>
<h3>Trace</h3>
<pre>
<?php print_r($this->e->getTraceAsString()); ?>
</pre>
<h3>Exception Object</h3>
<pre>
<?php print_r($this->e); ?>
</pre>
<h3>Var Dump</h3>
<pre>
<?php debug_print_backtrace(); ?>
</pre>
