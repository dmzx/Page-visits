<?php
/**
*
* @package phpBB Extension - Page visits in footer
* @copyright (c) 2016 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\pagevisits\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;
	
	/** @var \phpbb\template\template */
	protected $template;

	/**
	* Constructor
	*
	* @param \phpbb\config\config		$config
	* @param \phpbb\template\template	$template
	*
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\template\template $template)
	{
		$this->config = $config;
		$this->template = $template;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'		=> 'load_language_on_setup',
			'core.page_footer'		=> 'page_footer',
		);
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'dmzx/pagevisits',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function page_footer($event)
	{
		$pagevisits = (isset($this->config['pageviews'])) ? ($this->config['pageviews'] + 1) : 1;
		$this->config->set('pageviews', $pagevisits, 1);
		$pagevisits = number_format($pagevisits, 0, '.', ',');
		
		$this->template->assign_vars(array(
			'PAGEVISITS'		=> $pagevisits,
		));
	}
}
