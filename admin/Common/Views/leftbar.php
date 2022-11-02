<ul class="nav-main ss">
	<?php foreach ($menus as $menu) { ?>
	<li id="<?php echo $menu['id']; ?>">
		<?php if ($menu['href']) { ?>
		<a href="<?php echo $menu['href']; ?>"><i class="<?php echo $menu['icon']; ?> "></i> <span class="sidebar-mini-hide"><?php echo $menu['name']; ?></span></a>
		<?php } else { ?>
		<a class="<?php echo $menu['children']?'nav-submenu':'';?>" data-toggle="<?php echo $menu['children']?'nav-submenu':'';?>" href="#"><i class="<?php echo $menu['icon']; ?> "></i> <span class="sidebar-mini-hide"><?php echo $menu['name']; ?></span></a>
		<?php } ?>
		<?php if ($menu['children']) { ?>
		<ul class="list-unstyled">
			<?php foreach ($menu['children'] as $children_1) { ?>
			<li>
				<?php if ($children_1['href']) { ?>
				<a href="<?php echo $children_1['href']; ?>"><?php echo $children_1['name']; ?></a>
				<?php } else { ?>
				<a class="parent waves-effect"><?php echo $children_1['name']; ?></a>
				<?php } ?>
				<?php if ($children_1['children']) { ?>
				<ul>
					<?php foreach ($children_1['children'] as $children_2) { ?>
					<li>
						<?php if ($children_2['href']) { ?>
						<a href="<?php echo $children_2['href']; ?>"><?php echo $children_2['name']; ?></a>
						<?php } else { ?>
						<a class="parent waves-effect"><?php echo $children_2['name']; ?></a>
						<?php } ?>
						<?php if ($children_2['children']) { ?>
						<ul>
							<?php foreach ($children_2['children'] as $children_3) { ?>
							<li><a href="<?php echo $children_3['href']; ?>"><?php echo $children_3['name']; ?></a></li>
							<?php } ?>
						</ul>
						<?php } ?>
					</li>
					<?php } ?>
				</ul>
				<?php } ?>
			</li>
			<?php } ?>
		</ul>
		<?php } ?>
	</li>
	<?php } ?>
</ul>