<?php
function grahlie_admin_page() {
	$grahlie_options = get_option('grahlie_framework_options'); ?>

	<?php print_r(get_option('grahlie_framework_values')); ?>

	<?php if(isset($_GET['tab'])){
		$tab = $_GET[ 'tab' ];
	} else {
		$tab = 'theme_options';
	} ?>

	<div id="grahlie-messages">
		<?php if(isset($_GET['activated'])){ ?>
			<div class="grahlie-updated" id="active">
				<span class="message-icon"><i class="fa fa-heart"></i></span>
				<p class="message-text"><?php _e($grahlie_options['theme_name'] .' is activated', 'grahlie'); ?></p>
			</div>
		<?php } ?>
		<div class="grahlie-success" id="message">
			<span class="message-icon"><i class="fa fa-check"></i></span>
			<p class="message-text"></p>
		</div>
	</div>
	<div id="grahlie-framework">
		<form method="post" action="<?php echo site_url() .'/wp-admin/admin-ajax.php'; ?>" enctype="multipart/form-data">
			<header>
				<a class="author_logo" href="<?php echo $grahlie_options['theme_authorURI']; ?>" alt="<?php echo $grahlie_options['theme_author']; ?>" target="_blank">
					<?php $logo_svg = file_get_contents(GRAHLIE_URL . "/images/grahlie.svg"); ?>
					<?php echo $logo_svg; ?>
				</a>
				<h1 class="theme_logo">
					<?php echo $grahlie_options['theme_name']; ?>
				</h1>
				<span>v.<?php echo $grahlie_options['theme_version']; ?></span>
			</header>
			<div class="main">
				<div class="tabs">
					<ul>
						<?php foreach ($grahlie_options['grahlie_framework'] as $page) : ?>
							<li><a href="?page=grahlieframework&tab=<?php echo $page['id']; ?>" class="<?php echo $tab == $page['id'] ? 'active' : ''; ?>"><?php echo $page['title']; ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="content">
					<?php foreach ($grahlie_options['grahlie_framework'] as $page) { ?>

						<?php if( $tab == $page['id'] ) { ?>

							<div id="<?php echo $page['id']; ?>">
								<h1><?php _e($page['title'], 'grahlie'); ?></h1>
								<p><?php if( isset($page['desc']) ) _e( $page['desc'], 'grahlie' ); ?></p>
								<hr />

								<?php foreach ($page as $item) { ?>
									<?php if(is_array($item)) { ?>

										<?php echo grahlie_create_output($item); ?>

									<?php } ?>
								<?php } ?>

							</div>
						<?php } ?>
						
					<?php } ?>
				</div>
				<div class="footer">
					<input type="hidden" name="action" value="grahlie_framework_save" />
					<input type="hidden" name="grahlie_noncename" id="grahlie_noncename" value="<?php echo wp_create_nonce('grahlie_framework_options'); ?>" />
					<input type="button" value="<?php _e( 'Reset Options', 'grahlie' ); ?>" class="grahlie-button" id="reset-button" />
					<input type="submit" value="<?php _e( 'Save All Changes', 'grahlie' ); ?>" class="grahlie-button-primary right" id="save-button" />
				</div>
			</div>
		</form>
	</div>
<?php } ?>
