Flour supports Configuration via `Configure::write|read` quite well. In fact, it relies on most parts of it.

Whenever you include a Flour Component or a Helper you get flour bootstrapped by itself. In case you need to do it manually, do the following:

	App::import('Lib', 'Flour.init');
	
This inits the Basic Settings Flour expects. To keep it as handy as possible, it tries to guess some settings themes and other parts of the system needs to work correcty.

One part is the `App.Settings` namespace. It sets the following default-params:

	$app_defaults = array(
		'name' => $default_name,
		'version' => '0.1',
		'title' => ':title - :name (:version)',
		'styles' => array('theme', 'app'),
		'scripts' => array('app', 'theme'),
	);

$default_name is the name of your `APP_DIR` in case it is NOT `app`, name of `ROOT`-folder otherwise.

We strongly suggest to add a configuration file at `APP/config/app.php` and load it in bootstrap like that:

	config('app');
	
A typical app.php starts with that:

	Configure::write('App.Settings', array(
		'name' => 'awesome_app',
		'version' => '0.3a',
		'title' => ':title // :name (:version)',
		'styles' => array('theme', 'app', 'whatever_i_need'),
		'scripts' => array('jquery', 'app', 'theme'),
	));

Whenever you add a Model with columns like status or other options-based things, you should add something like that:

	/**
	 * Contents
	 */

	//status
	Configure::write('Flour.Content.status', array(
		'default' => 1,
		'options' => array(
			'0' => __('offline', true),
			'1' => __('draft', true),
			'2' => __('online', true),
		),
	));

	//types
	Configure::write('Flour.Content.types', array(
		'pattern' => 'contents/type_:type',
		'default' => 'article',
		'options' => array(
			'article' => __('Article', true),
			'blog' => __('Blogpost', true),
		),
		'descriptions' => array(
			'article' => __('An Article is a defined block of Content with a title, an excerpt and a body.', true),
			'blog' => __('A Blogpost has a title and body-text.', true),
		),
	));

Guess what, Flour uses this all over the place. Next time, you want to provide a dropdown with Content.types, you just do that:

	echo $this->Form->input('Content.type', array(
		'type' => 'select',
		'class' => 'auto_switch_type',
		'options' => Configure::read('Flour.Content.types.options'),
		'default' => Configure::read('Flour.Content.types.default'),
	));

That way, you are always covered, even when you add/remove or modify types within your controller-logic.