<?php 
		$active_menu = !empty($active_menu)?$active_menu:false;
?>
<nav id="sidenav-wrapper" class="hidden-print">
	<ul id="accordion" class="sidenav panel-group" role="tablist" aria-multiselectable="true">
		<li>
			<a href="#min-toggle" id="min-toggle" class="sidebar-toggle collapsed">
				<div class="burger">
					<span class="burger-bar"></span>
					<span class="burger-bar"></span>
					<span class="burger-bar"></span>
				</div>
			</a>
		</li>
		<?php 
				echo $this->element('blocks/common/menus');
		?>
	</ul>
</nav>