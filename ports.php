<?php
/**
 * WHMCS SDK Sample Provisioning Module
 *
 * Provisioning Modules, also referred to as Product or Server Modules, allow
 * you to create modules that allow for the provisioning and management of
 * products and services in WHMCS.
 *
 * This sample file demonstrates how a provisioning module for WHMCS should be
 * structured and exercises all supported functionality.
 *
 * Provisioning Modules are stored in the /modules/servers/ directory. The
 * module name you choose must be unique, and should be all lowercase,
 * containing only letters & numbers, always starting with a letter.
 *
 * Within the module itself, all functions must be prefixed with the module
 * filename, followed by an underscore, and then the function name. For this
 * example file, the filename is "ports" and therefore all
 * functions begin "ports_".
 *
 * If your module or third party API does not support a given function, you
 * should not define that function within your module. Only the _ConfigOptions
 * function is required.
 *
 * For more information, please refer to the online documentation.
 *
 * @see https://developers.whmcs.com/provisioning-modules/
 *
 * @copyright Copyright (c) WHMCS Limited 2017
 * @license https://www.whmcs.com/license/ WHMCS Eula
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

require_once __DIR__ . '/lib/ports_class.php';
// Require any libraries needed for the module to function.
// require_once __DIR__ . '/path/to/library/loader.php';
//
// Also, perform any initialization required by the service's library.

/**
 * Define module related meta data.
 *
 * Values returned here are used to determine module related abilities and
 * settings.
 *
 * @see https://developers.whmcs.com/provisioning-modules/meta-data-params/
 *
 * @return array
 */
function ports_MetaData()
{
    return array(
        'DisplayName' => 'IP Address / Ports',
        'APIVersion' => '1.1', // Use API Version 1.1
        'RequiresServer' => false, // Set true if module requires a server to work
/*        'DefaultNonSSLPort' => '0', // Default Non-SSL Connection Port
        'DefaultSSLPort' => '0', // Default SSL Connection Port
        'ServiceSingleSignOnLabel' => 'Login to Panel as User',
        'AdminSingleSignOnLabel' => 'Login to Panel as Admin',*/
    );
}

/**
 * Define product configuration options.
 *
 * The values you return here define the configuration options that are
 * presented to a user when configuring a product for use with the module. These
 * values are then made available in all module function calls with the key name
 * configoptionX - with X being the index number of the field from 1 to 24.
 *
 * You can specify up to 24 parameters, with field types:
 * * text
 * * password
 * * yesno
 * * dropdown
 * * radio
 * * textarea
 *
 * Examples of each and their possible configuration parameters are provided in
 * this sample function.
 *
 * @see https://developers.whmcs.com/provisioning-modules/config-options/
 *
 * @return array
 */
function ports_ConfigOptions()
{
    return array(
        // a text field type allows for single line text input
        'No. Of IP/PORTS' => array(
            'Type' => 'text',
            'Size' => '25',
            'Default' => '0',
            'Description' => '',
        ),
		 'Package Quota' => array(
            'Type' => 'text',
            'Size' => '25',
            'Default' => '0',
            'Description' => '',
        ));
}

/**
 * Provision a new instance of a product/service.
 *
 * Attempt to provision a new instance of a given product/service. This is
 * called any time provisioning is requested inside of WHMCS. Depending upon the
 * configuration, this can be any of:
 * * When a new order is placed
 * * When an invoice for a new order is paid
 * * Upon manual request by an admin user
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return string "success" or an error message
 */
function ports_CreateAccount(array $params)
{
	
	$ports = new Ports_class();
	//When the Service is Purchased.
	//Lets Add the IP / Ports From the Pool.
	//No. Of IP/PORTS From POOL
	$number = $params['configoption1'];
	$quantity = $params['configoption2'];
	$serviceid = $params['serviceid'];
	
	//First Dum all
	//$ports->unreservePorts( $serviceid );
	//$ports->removeQuantity( $serviceid );
	
	$reservedPorts = count($ports->getreservePorts( $serviceid ));
	
	if($reservedPorts == $number):
		
		//Do Nothing
		
	else:
	
	
		$ports->setQuantity( $serviceid , $quantity );
		
		$available_ports = $ports->getAvailablePorts();
		
		foreach($available_ports as $key => $value):
			if($key < $number):
				$ports->reservePort( $value['id'] , $serviceid );		
			endif;
		endforeach;
	
	endif;
    return 'success';
}

/**
 * Suspend an instance of a product/service.
 *
 * Called when a suspension is requested. This is invoked automatically by WHMCS
 * when a product becomes overdue on payment or can be called manually by admin
 * user.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return string "success" or an error message
 */
function ports_SuspendAccount(array $params)
{
    //Don't Do anything

    return 'success';
}

/**
 * Un-suspend instance of a product/service.
 *
 * Called when an un-suspension is requested. This is invoked
 * automatically upon payment of an overdue invoice for a product, or
 * can be called manually by admin user.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return string "success" or an error message
 */
function ports_UnsuspendAccount(array $params)
{
   //Don't Do anything

    return 'success';
}

/**
 * Terminate instance of a product/service.
 *
 * Called when a termination is requested. This can be invoked automatically for
 * overdue products if enabled, or requested manually by an admin user.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return string "success" or an error message
 */
function ports_TerminateAccount(array $params)
{
    //Don't Do Anything

	$ports = new Ports_class();
	//When the Service is Purchased.
	//Lets Add the IP / Ports From the Pool.
	//No. Of IP/PORTS From POOL
	$serviceid = $params['serviceid'];
	
	$ports->unreservePorts( $serviceid );		
	$ports->removeQuantity( $serviceid );
	
    return 'success';
}

/**
 * Change the password for an instance of a product/service.
 *
 * Called when a password change is requested. This can occur either due to a
 * client requesting it via the client area or an admin requesting it from the
 * admin side.
 *
 * This option is only available to client end users when the product is in an
 * active status.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return string "success" or an error message
 */
function ports_ChangePassword(array $params)
{
    //Don't Do anything

    return 'success';
}

/**
 * Upgrade or downgrade an instance of a product/service.
 *
 * Called to apply any change in product assignment or parameters. It
 * is called to provision upgrade or downgrade orders, as well as being
 * able to be invoked manually by an admin user.
 *
 * This same function is called for upgrades and downgrades of both
 * products and configurable options.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return string "success" or an error message
 */
function ports_ChangePackage(array $params)
{
    //Don't Do ANything

    return 'success';
}

/**
 * Test connection with the given server parameters.
 *
 * Allows an admin user to verify that an API connection can be
 * successfully made with the given configuration parameters for a
 * server.
 *
 * When defined in a module, a Test Connection button will appear
 * alongside the Server Type dropdown when adding or editing an
 * existing server.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return array
 */
function ports_TestConnection(array $params)
{
   //Put the IP Vack To Pool

    return array(
        'success' => $success,
        'error' => $errorMsg,
    );
}

/**
 * Additional actions an admin user can invoke.
 *
 * Define additional actions that an admin user can perform for an
 * instance of a product/service.
 *
 * @see ports_buttonOneFunction()
 *
 * @return array
 */
function ports_AdminCustomButtonArray()
{
    return array();
}

/**
 * Additional actions a client user can invoke.
 *
 * Define additional actions a client user can perform for an instance of a
 * product/service.
 *
 * Any actions you define here will be automatically displayed in the available
 * list of actions within the client area.
 *
 * @return array
 */
function ports_ClientAreaCustomButtonArray()
{
    return array();
}

/**
 * Custom function for performing an additional action.
 *
 * You can define an unlimited number of custom functions in this way.
 *
 * Similar to all other module call functions, they should either return
 * 'success' or an error message to be displayed.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 * @see ports_AdminCustomButtonArray()
 *
 * @return string "success" or an error message
 */
function ports_buttonOneFunction(array $params)
{
    //No Action

    return 'success';
}

/**
 * Custom function for performing an additional action.
 *
 * You can define an unlimited number of custom functions in this way.
 *
 * Similar to all other module call functions, they should either return
 * 'success' or an error message to be displayed.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 * @see ports_ClientAreaCustomButtonArray()
 *
 * @return string "success" or an error message
 */
function ports_actionOneFunction(array $params)
{
    //No Action

    return 'success';
}

/**
 * Admin services tab additional fields.
 *
 * Define additional rows and fields to be displayed in the admin area service
 * information and management page within the clients profile.
 *
 * Supports an unlimited number of additional field labels and content of any
 * type to output.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 * @see ports_AdminServicesTabFieldsSave()
 *
 * @return array
 */
function ports_AdminServicesTabFields(array $params)
{
    //No Action

	$ports = new Ports_class();
	//When the Service is Purchased.
	//Lets Add the IP / Ports From the Pool.
	//No. Of IP/PORTS From POOL
	$serviceid = $params['serviceid'];
	
	
	$reserved_ports = $ports->getreservePorts( $serviceid );	
	
	$port_box = [];
	
	$port_box['Quota:'] = $ports->getQuantity( $serviceid );
	
	foreach($reserved_ports as $key => $value):
		
		$port_box['IP/Port: ' . ($key + 1)] = $value['ipaddress'] . ':' . $value['port'];
		
	endforeach;
	
	return $port_box;

}

/**
 * Execute actions upon save of an instance of a product/service.
 *
 * Use to perform any required actions upon the submission of the admin area
 * product management form.
 *
 * It can also be used in conjunction with the AdminServicesTabFields function
 * to handle values submitted in any custom fields which is demonstrated here.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 * @see ports_AdminServicesTabFields()
 */
function ports_AdminServicesTabFieldsSave(array $params)
{
    //Nothing
	return array();
}

/**
 * Perform single sign-on for a given instance of a product/service.
 *
 * Called when single sign-on is requested for an instance of a product/service.
 *
 * When successful, returns a URL to which the user should be redirected.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return array
 */
function ports_ServiceSingleSignOn(array $params)
{
    //No Action.
	return array();
}

/**
 * Perform single sign-on for a server.
 *
 * Called when single sign-on is requested for a server assigned to the module.
 *
 * This differs from ServiceSingleSignOn in that it relates to a server
 * instance within the admin area, as opposed to a single client instance of a
 * product/service.
 *
 * When successful, returns a URL to which the user should be redirected to.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return array
 */
function ports_AdminSingleSignOn(array $params)
{
    //Action
	return array();
}

/**
 * Client area output logic handling.
 *
 * This function is used to define module specific client area output. It should
 * return an array consisting of a template file and optional additional
 * template variables to make available to that template.
 *
 * The template file you return can be one of two types:
 *
 * * tabOverviewModuleOutputTemplate - The output of the template provided here
 *   will be displayed as part of the default product/service client area
 *   product overview page.
 *
 * * tabOverviewReplacementTemplate - Alternatively using this option allows you
 *   to entirely take control of the product/service overview page within the
 *   client area.
 *
 * Whichever option you choose, extra template variables are defined in the same
 * way. This demonstrates the use of the full replacement.
 *
 * Please Note: Using tabOverviewReplacementTemplate means you should display
 * the standard information such as pricing and billing details in your custom
 * template or they will not be visible to the end user.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return array
 */
function ports_ClientArea(array $params)
{
	
	$ports = new Ports_class();
	//When the Service is Purchased.
	//Lets Add the IP / Ports From the Pool.
	//No. Of IP/PORTS From POOL
	$serviceid = $params['serviceid'];
	
	$port_qty = $ports->getQuantity( $serviceid );
	
	$reserved_ports = $ports->getreservePorts( $serviceid );	
	
	$port_box = [];
	
	foreach($reserved_ports as $key => $value):
		
		$port_box[] = ['ipaddress' => $value['ipaddress'] , 'port' => $value['port']];
		//$port_box['IPPort'.$key] = ['ipaddress' => $value['ipaddress'] , 'port' => $value['port']];;
		
	endforeach;
	
	//return $port_box;
	
	
	
    
	$templateFile = 'templates/overview.tpl';

	// Call the service's function based on the request action, using the
	// values provided by WHMCS in `$params`.
	return array(
		'tabOverviewReplacementTemplate' => $templateFile,
		'templateVariables' => ['port_box' => $port_box , 'qty' => $port_qty],
	);
    
	
    //return array();
}
