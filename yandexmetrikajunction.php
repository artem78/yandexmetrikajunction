<?php
if (!defined('_PS_VERSION_'))
	exit;

class YandexMetrikaJunction extends Module
{	
	function __construct()
	{
	 	$this->name = 'yandexmetrikajunction';
	 	$this->tab = 'analytics_stats';
	 	$this->version = '1.4.3';
		$this->author = 'kartzum';
		$this->displayName = 'Yandex Metrika';		
		
	 	parent::__construct();
		
		if ($this->id AND !Configuration::get('YANDEX_METRIKA_ID'))
			$this->warning = $this->l('You have not yet set your Yandex Metrika ID');
		$this->description = $this->l('Integrate Yandex Metrika script into your shop');
		$this->confirmUninstall = $this->l('Are you sure you want to delete your details?');
	}

	function install()
	{
		if (!parent::install() ||
				!$this->registerHook('header'))
			return false;
		return true;
	}
	
	function uninstall()
	{
		if (!Configuration::deleteByName('YANDEX_METRIKA_ID') || !parent::uninstall())
			return false;
		return true;
	}
	
	public function getContent()
	{
		$output = '<h2>Yandex Metrika</h2>';
		if (Tools::isSubmit('submitYandexMetrika') AND ($gai = Tools::getValue('yandex_metrika_id')))
		{
			Configuration::updateValue('YANDEX_METRIKA_ID', $gai);
			$output .= '
			<div class="conf confirm">
				<img src="../img/admin/ok.gif" alt="" title="" />
				'.$this->l('Settings updated').'
			</div>';
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		$output = '
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset class="width2">
				<legend><img src="../img/admin/cog.gif" alt="" class="middle" />'.$this->l('Settings').'</legend>
				<label>'.$this->l('Your username').'</label>
				<div class="margin-form">
					<input type="text" name="yandex_metrika_id" value="'.Tools::safeOutput(Tools::getValue('yandex_metrika_id', Configuration::get('YANDEX_METRIKA_ID'))).'" />
					<p class="clear">'.$this->l('Example:').' 99451161</p>
				</div>
				<center><input type="submit" name="submitYandexMetrika" value="'.$this->l('Update ID').'" class="button" /></center>
			</fieldset>
		</form>';					
		return $output;
	}
	
	function hookHeader($params)
	{		
		$this->context->smarty->assign('yandex_metrika_id', Configuration::get('YANDEX_METRIKA_ID'));
		
		return $this->display(__FILE__, 'header.tpl');
	}	
}
