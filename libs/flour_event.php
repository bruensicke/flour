<?php
/**
 * Flour Event Manager
 * 
 * Allows to trigger an unlimited number of events with subscribers that will get called
 *
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
Class FlourEvent extends Object {

	/**
	 * Triggers any event
	 *
	 * @param string $type You can choose what event to trigger
	 * @param string $data hand over an array with data, whatever you need
	 * @param string $options used for options, none so far
	 * @return bool true on success, false otherwise
	 */
	public static function trigger($type, $data = array(), $options = array()) {

		if($type != 'all') {
			$options = Set::merge($options, array('event_type' => $type));
			FlourEvent::trigger('all', $data, $options);
		}
		$subscribers = Configure::read("Flour.Event.subscribers.$type");

		if(empty($subscribers)) {
			return false;
		}

		// make sure, it is wrapped as array
		$subscribers = (is_string($subscribers))
			? array($subscribers)
			: $subscribers;

		//walk through all subscribers all call them
		foreach($subscribers as $subscriber) {

			$subscriber = (stristr('::', $subscriber))
				? $subscriber
				: "$subscriber::$type";
			
			if(is_callable($subscriber)) {
				call_user_func($subscriber, $data, $options);
			}
		}
		return true;
	}

	/**
	 * Subscribe to an Event
	 *
	 * if $type = 'all' - every trigger will get you called
	 *
	 * A Subscriber is a Class with a static callable method in the form of:
	 *
	 *  Activity::all
	 *  AppMailer::user_confirm
	 *  AppController
	 *
	 * If Method is omitted, $type will be appended
	 *
	 * @param string $type Type of Event to subscribe to
	 * @param string $subscribers Array of subsribers, see above
	 * @return true on success, false otherwise
	 */
	public static function subscribe($type, $subscribers = array()) {

		$subscribers = (is_string($subscribers))
			? array($subscribers)
			: $subscribers;

		$existing_subscribers = Configure::read("Flour.Event.subscribers.$type");
		Configure::write("Flour.Event.subscribers.$type", Set::merge($subscribers, $existing_subscribers));
		return true;
	}

	/**
	 * Remove Subscriber from list
	 *
	 * @param string $type Type of Event to unsubscribe
	 * @param string $subscribers Name of Classes/Methods to unsubscribe
	 * @return true in success, false otherwise
	 */
	public static function unsubscribe($type, $subscribers) {
		
		$subscribers = (is_string($subscribers))
			? array($subscribers)
			: $subscribers;

		foreach($subscribers as $subscriber) {
			Configure::delete("Flour.Event.subscribers.$type.$subscriber");
		}
		return true;
	}

	/**
	 * Returns a list of all known Subscribers to an Event
	 *
	 * @param string $type Type of Event to deliver all Subscribers to
	 * @return array An Array of Subscribers or empty Array
	 */
	public static function getSubscribers($type) {
		$ret = Configure::read("Events.subscriptions.$type");
		return (empty($ret))
			? array()
			: $ret;
	}
}