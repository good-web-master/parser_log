<div class="card block <?=$class?> mb-4" data-id="<?=$id?>" style="max-width: 30rem;">
	<div class="card-header title">
		<?=$number?>
		<?=$title?>
	</div>
	<div class="card-body">
		<h5 class="card-title"><?=$caption?></h5>
		<p class="card-text"><?=$pageflow?></p>
		<p class="card-text"><?=$parent_pageflow?></p>
		<p class="card-text">Input Params <span class="badge badge-info"><?=count($input_params)?></span></p>
		<p class="card-text">Output Params <span class="badge badge-info"><?=count($output_params)?></span></p>
	</div>
</div>