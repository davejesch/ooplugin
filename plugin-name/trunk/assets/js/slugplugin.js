/*
 * @copyright Copyright (C) 2014 SpectrOMtech.com. - All Rights Reserved.
 * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author SpectrOMtech.com
 * @url https://SpectrOMtech.com
 */

/*
 * Browser actions for SlugPlugin
 * @package SlugPlugin
 * @author SpectrOMtech
 */
function SlugPlugin()
{
	this.attribute = null;
}

var slugplugin = new SlugPlugin();

/**
 * Initializes this SlugPlugin page
 */
SlugPlugin.prototype.init = function()
{
	var _self = this;
};

jQuery(document).ready(function($) {
	slugplugin.init();
});

// EOF
