<?php

if ( ! defined( 'WPINC' ) )
	die;

?>

<div class="wrap">
	<h2><?php echo __( 'DistroPress', 'distropress' ); ?> <a href="<?php echo admin_url( 'admin.php' ); ?>?page=distropress" class="add-new-h2"><?php echo __( 'Settings', 'distropress' ) ?></a></h2>
	<div class="module-grid">
		<h2><?php echo __( 'DistroPress Packages', 'distropress' ); ?></h2>

		<div class="modules">
<?php

$modules = array(
	'fluxbb' => array(
		'name' => __( 'FluxBB' ),
		'desc' => __( 'Add a forum to your site.' )
	),
	'indefero' => array(
		'name' => __( 'Indefero' ),
		'desc' => __( 'Repository' )
	),
	'mediawiki' => array(
		'name' => __( 'MediaWiki' ),
		'desc' => __( 'Wiki' )
	)
);

add_thickbox();

foreach ( $modules as $key => $module ) {
?>
			<div style="height: 92px;" href="" tabindex="0" data-index="0" data-name="<?php echo $module['name']; ?>" class="module">
				<h3 class="icon <?php echo $key; ?>"><?php echo $module['name']; ?></h3>
				<p><?php echo $module['desc']; ?></p>
				<p><a href="<?php echo admin_url( 'update.php' ) . '?action=distropress-install-package&package=' . $key . '&TB_iframe=true&width=900&height=600'; ?>" class="thickbox"><?php echo __( 'Install', 'distropress' ); ?></a></p>
			</div>
<?php

}

?>
		</div>
	</div>
</div>