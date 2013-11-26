/**
 * Workspace Panel View
 *
 * @module     WorkspacePanelView
 * @author     Ushahidi Team <team@ushahidi.com>
 * @copyright  2013 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

define(['marionette', 'handlebars', 'App', 'text!templates/WorkspacePanel.html'],
	function(Marionette, Handlebars, App, template)
	{
		return Marionette.ItemView.extend(
		{
			template : Handlebars.compile(template),
			events : {
				'click .js-title' : 'toggleSection',
				//'click .workspace-menu li' : 'toggleMenuItem'
			},
			initialize : function ()
			{
				App.vent.on('page:change', this.selectMenuItem, this);
			},
			toggleSection : function(e)
			{
				var $el = this.$(e.currentTarget.parentNode);
				$el.toggleClass('active');
				e.preventDefault();
			},
			toggleMenuItem : function(e)
			{
				// @todo base this on some state, not click actions.
				//e.preventDefault();
				this.$('.workspace-menu li').removeClass('active');
				this.$(e.currentTarget).addClass('active');
			},
			selectMenuItem : function(page)
			{
				var target = this.$('.workspace-menu li[data-page='+page+']');
				if (target.length > 0)
				{
					this.$('.workspace-menu li').removeClass('active');
					target.addClass('active');
				}
			}
		});
	});