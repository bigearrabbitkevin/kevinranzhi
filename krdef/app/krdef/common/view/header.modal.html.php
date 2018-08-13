<?php if (helper::isAjaxRequest()): ?>
<?php
$webRoot       = $config->webRoot;
$jsRoot        = $webRoot."js/";
$themeRoot     = $webRoot."theme/";
$modalSizeList = array('lg' => '900px', 'sm' => '300px');
if (!isset($modalWidth)) $modalWidth = 800;
if (is_numeric($modalWidth)) {
	$modalWidth .= 'px';
} else if (isset($modalSizeList[$modalWidth])) {
	$modalWidth = $modalSizeList[$modalWidth];
}
if (isset($pageCSS)) css::internal($pageCSS);

/* set requiredField. */
if (isset($config->$moduleName->require->$methodName)) {
	$requiredFields = str_replace(' ', '', $config->$moduleName->require->$methodName);
	js::execute("config.requiredFields = \"$requiredFields\"; setRequiredFields();");
}
?>
<div class="modal-dialog" style="width:<?php echo $modalWidth; ?>;">
	<div class="modal-content">
		<div class="modal-header">
			<?php echo html::closeButton(); ?>
			<strong class="modal-title"><?php if (!empty($title)) echo $title; ?></strong>
			<?php if (!empty($subtitle)) echo "<label class='text-important'>".$subtitle.'</label>'; ?>
		</div>
		<div class="modal-body">
			<?php else: ?>
				<?php include $this->app->getAppRoot().'/common/view/header.html.php'; ?>
			<?php endif; ?>

			<?php include $this->app->getBasePath().'app/sys/common/view/chosen.html.php'; ?>



