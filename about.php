<div class="wrap about-wrap mb-about-wrap">
	<h1><?php printf( __( 'Welcome to Mighty Builder v%s' ), MP_PB_VER ); ?></h1>
	<p class="about-text"><?php printf( __( 'Awesome! You are using the best free WordPress page builder. Do you need any help? Please submit your query at community forum %s' ), '<a href="http://bit.ly/MightyBuilderSupport" target="_blank">http://bit.ly/MightyBuilderSupport</a>' ); ?></p>
	<div class="wp-badge"><?php printf( __( 'Version %s' ), MP_PB_VER ); ?></div>

	<h2 class="nav-tab-wrapper wp-clearfix">
		<a href="#whats-new" class="nav-tab nav-tab-active"><?php _e( 'What&#8217;s New' ); ?></a>
		<a href="#settings" class="nav-tab"><?php _e( 'Settings' ); ?></a>
		<a href="#extensions" class="nav-tab"><?php _e( 'Extensions' ); ?></a>
		<a href="#themes" class="nav-tab"><?php _e( 'Themes' ); ?></a>
	</h2>

	<div class="mb-settings-panel mb-panel-active" id="whats-new">
		<div class="feature-section one-col">
			<h2>Improved UI/UX</h2>
			<p class="lead-description">We have improved UI/UX for better editing and page building experience.</p>
			<img src="<?php echo MP_PB_URL?>assets/images/about/ui.png" alt="">
			<p>We have improved UI/UX for better editing and page building experience.</p>
		</div>
		<hr />
		<div class="feature-section one-col">
			<h2>Your Site, Your Way</h2>
			<p class="lead-description">WordPress 4.7 adds new features to the customizer to help take you through the initial setup of a theme, with non-destructive live previews of all your changes in one uninterrupted workflow.</p>
		</div>
		<div class="feature-section two-col">
			<div class="col">
				<h3>Bootstrap Grid</h3>
				<img src="<?php echo MP_PB_URL?>assets/images/about/ui.png" alt="">
				<p>We have improved UI/UX for better editing and page building experience.</p>
			</div>
			<div class="col">
				<h3>Bootstrap Grid</h3>
				<img src="<?php echo MP_PB_URL?>assets/images/about/ui.png" alt="">
				<p>We have improved UI/UX for better editing and page building experience.</p>
			</div>
		</div>
		<div class="feature-section two-col">
			<div class="col">
				<h3>Bootstrap Grid</h3>
				<img src="<?php echo MP_PB_URL?>assets/images/about/ui.png" alt="">
				<p>We have improved UI/UX for better editing and page building experience.</p>
			</div>
			<div class="col">
				<h3>Bootstrap Grid</h3>
				<img src="<?php echo MP_PB_URL?>assets/images/about/ui.png" alt="">
				<p>We have improved UI/UX for better editing and page building experience.</p>
			</div>
		</div>
	</div>
	<div class="mb-settings-panel" id="settings">
		<?php
			$post_types = get_post_types(array( 'public' => true ));
			unset($post_types['attachment']);

			$enabled_post_types = get_option( 'mb_post_types', array('post') );
		?>
		<form action="">
			<p>
				<?php foreach( $post_types as $post_type ): ?>
					<?php if(in_array( $post_type, $enabled_post_types )): ?>
						<label for=""><input type="checkbox" value="<?php echo $post_type; ?>" checked="checked"> <?php echo $post_type; ?></label>
					<?php else: ?>
						<label for=""><input type="checkbox" value="<?php echo $post_type; ?>"> <?php echo $post_type; ?></label>
					<?php endif; ?>
					
				<?php endforeach; ?>
				
				<!-- <label for=""><input type="checkbox"> Post</label> -->
			</p>
		</form>
	</div>
	<div class="mb-settings-panel" id="extensions"></div>
	<div class="mb-settings-panel" id="themes">
		<h3>Coming soon...</h3>
	</div>
</div>