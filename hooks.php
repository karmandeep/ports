<?php
use WHMCS\Database\Capsule;

/**
 * WHMCS SDK Sample Provisioning Module Hooks File
 *
 * Hooks allow you to tie into events that occur within the WHMCS application.
 *
 * This allows you to execute your own code in addition to, or sometimes even
 * instead of that which WHMCS executes by default.
 *
 * WHMCS recommends as good practice that all named hook functions are prefixed
 * with the keyword "hook", followed by your module name, followed by the action
 * of the hook function. This helps prevent naming conflicts with other addons
 * and modules.
 *
 * For every hook function you create, you must also register it with WHMCS.
 * There are two ways of registering hooks, both are demonstrated below.
 *
 * @see https://developers.whmcs.com/hooks/
 *
 * @copyright Copyright (c) WHMCS Limited 2017
 * @license https://www.whmcs.com/license/ WHMCS Eula
 */

// Require any libraries needed for the module to function.
// require_once __DIR__ . '/path/to/library/loader.php';
//
// Also, perform any initialization required by the service's library.

/**
 * Client edit sample hook function.
 *
 * This sample demonstrates making a service call whenever a change is made to a
 * client profile within WHMCS.
 *
 * @param array $params Parameters dependant upon hook function
 *
 * @return mixed Return dependant upon hook function
 */
function hook_provisioningmodule_clientedit(array $params)
{
    try {
        // Call the service's function, using the values provided by WHMCS in
        // `$params`.
    } catch (Exception $e) {
        // Consider logging or reporting the error.
    }
}

/**
 * Register a hook with WHMCS.
 *
 * add_hook(string $hookPointName, int $priority, string|array|Closure $function)
 */
add_hook('ClientEdit', 1, 'hook_ports_clientedit');

/**
 * Insert a service item to the client area navigation bar.
 *
 * Demonstrates adding an additional link to the Services navbar menu that
 * provides a shortcut to a filtered products/services list showing only the
 * products/services assigned to the module.
 *
 * @param \WHMCS\View\Menu\Item $menu
 */

add_hook('ClientAreaPrimarySidebar', 1, function($menu) {	

	 
		 
	  if (!is_null($menu->getChild('Service Details Actions'))) {
		  // Add a link to the module filter.
		  $menu->removeChild('Service Details Actions');
			  
	  }
			
});
 //AfterCronJob
 //DailyCronJob
add_hook('AfterCronJob', 1, function($vars) {
	
	//Check The Package Quota, and then
			 Capsule::table('mod_ports_package_quantity')
				->join('mod_ports_ipaddress_ports', 'mod_ports_package_quantity.package', '=', 'mod_ports_ipaddress_ports.package')
				->where('mod_ports_package_quantity.qty', 0)
				->update(['mod_ports_ipaddress_ports.status' => 0 , 'mod_ports_ipaddress_ports.package' => 0]);
	
}); 

//Service Details Overview