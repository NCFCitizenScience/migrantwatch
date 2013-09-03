<?php
	// This should actually build up dynamically, for the time being hardcoded.
	$currentScript = basename($_SERVER["PHP_SELF"]);
	$baseUrl = '/';

	if (($_POST['last'] == 1 || $_GET['last'] == 1 || $_SESSION['trackspecies']['last']) && $currentScript == 'trackspecies.php') {
		$currentScript = 'trackspecies.php?last=1';
	}

	/**
	 * This array maps the current scripts to the menu items
	 * The menu scripts are limited but the number of scripts accessed from those
	 * (menu) scripts can be many, so what we do is create any array where
	 * we point the script that falls under a menu to point to that (menu) script.
	 */
	$menuScripts = array(
						'myspecies.php'     => 'myspecies.php'
						,'speciessighting.php' => 'myspecies.php'
						,'mylocations.php'  => 'mylocations.php'
						,'locsightings.php' => 'mylocations.php'
						,'rpt_level1.php'   => 'reports/rpt_level1.php'
						,'trackspecies.php?last=1' => 'trackspecies.php?last=1'
						,'trackspecies.php' => 'trackspecies.php'
						,'main.php'         => 'main.php'
					  );

	/**
	 * If we have a matching script then use that as the current script
	 * What this will do is if we want a menu to appear selected for pages
	 * not directly pointing to the menu script then we simply check if this
	 * item exists in the menu if not no menu item is selected.
	 */
	if (in_array($currentScript,array_keys($menuScripts))) {
		$currentScript = $menuScripts[$currentScript];
	} else {
		// Do nothing .. no link should appear selected
		$currentScript = '';
	}

	// The links and their lables
	$menuItems = array(
						'main.php'         => 'My Account Home',
						'myspecies.php'    => 'My Species',
						'mylocations.php'  => 'My Locations',
						'trackspecies.php' => 'Report First Sighting',
						'traclspecies.php' => 'Report Last Sighting',
						/* 'piedcuckoo.php' => 'Pied Cuckoo Campaign', */
						'reports/rpt_level1.php' => 'Generate Report'
					  )
?>
 <p>
	  <div id="navcontainer" style="width:900px">
	<ul id="navlist">
		<?php
			foreach($menuItems as $href => $label) {
				$class =  ($currentScript == $href) ? 'current' : $class = 'normal';
				echo "<li><a href='".$baseUrl.$href."' class='$class'>$label</a></li>";
			}
		?>
	</ul>

	<?php
	//Always in the next row
	if ($_SESSION['loginReferrer'] == 'byPass.php') {
        if(basename($_SERVER['PHP_SELF']) == 'rpt_level1.php') {
            $backToAdmin = "../admin/login.php";
        } else {
            $backToAdmin = "admin/login.php";
        }
		echo "<ul id='navlist'>";
				echo "<li><a href='$backToAdmin' class='normal'>Back to Admin Panel</a></li>";
		echo '</ul>';
	}
	?>
</div>
</p>
