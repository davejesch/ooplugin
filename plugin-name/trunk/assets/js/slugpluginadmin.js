/*
 * @copyright Copyright (C) 2014 SpectrOMtech.com. - All Rights Reserved.
 * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author SpectrOMtech.com
 * @url https://SpectrOMtech.com
 */

/*
 * Admin browser actions for SlugPlugin
 * @package SlugPlugin
 * @author SpectrOMtech
 */
function SlugPluginAdmin()
{
	this.attribute = null;
}

var slugpluginadmin = new SlugPluginAdmin();

/**
 * Initializes this SlugPlugin Admin page
 */
SlugPluginAdmin.prototype.init = function()
{
	var _self = this;
};

jQuery(document).ready(function($) {
	slugpluginadmin.init();
});

// EOF
