<?php
/**
 * Plugin Name: TeamSpeak
 * Plugin Description: TeamSpeak Eklentisi
 * Plugin WebSite: https://mirarus.com/plugin/teamspeak
 *
 * @author Name: Mirarus - Ali Güçlü
 * @author Mail: aliguclutr@gmail.com
 * @author WebSite: https://mirarus.com/
*/

class TeamSpeak extends ServerPlugin
{

	private $api;
	private $service;

    /**
     *
     * @author Mirarus
    */
    public function __construct()
    {
    	self::load(__CLASS__);
    	self::library('ts3admin.class');
    	self::helper('general');
    }

	/**
	 *
	 * @author Mirarus
	*/
	public static function Meta()
	{
		return [
			'name' => __CLASS__,
			'version' => '1.0'
		];
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $server
	 * @return array $api | bool
	*/
	public static function API($server)
	{
		try {
			$api = new ts3admin($server['server_ip'], $server['server_port']);
			if ($api->getElement('success', $api->connect())) {
				if ($api->getElement('success', $api->login($server['server_username'], $server['server_password']))) {
					return $api;
				} else{
					return false;
				}
			} else{
				return false;
			}
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $product_data
	*/
	public function product_load($product_data)
	{
		try {
			add_action('cppnt', function($nav_tabs) {
				$nav_tabs[] = [
					'name' => 'teamspeak',
					'title' => self::___('server_name'),
				];
				return $nav_tabs;
			});
			add_action('cppnc', function($nav_contents) {
				$nav_contents[] = [
					'name' => 'teamspeak',
					'form_groups' => [ [
						'label' => [ 
							'class' => 'text-primary',
							'title' => self::___('server_name'),
						],
						'input' => [ 
							'type' => 'text',
							'name' => 'server_name',
							'placeholder' => self::___('enter_server_name'),
							'required' => true,
							'required_text' => self::___('this_field_cannot_be_empty'),
						]
					] ]
				];
				return $nav_contents;
			});
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $product_data
	 * @param array $service_data
	 * @param array $form_params
	 * @return array
	*/
	public static function product_post($product_data, $service_data, $form_params)
	{
		try {
			foreach (self::DB()->from('servers')->where('server_plugin', __CLASS__)->all() as $server) {
				if ($server && $server['server_status'] == 1) {
					if (self::DB()->from('teamspeak')->where('teamspeak_server_id', $server['server_id'])->rowCount() <= $server['server_max_accounts']) {
						if ($api = self::API($server)) {
							
							$server_port_data = self::DB()->from('teamspeak_ports')->where('teamspeak_port_server_id', $server['server_id'])->first();
							$server_port = ($server_port_data['teamspeak_port_value']+1);
							self::DB()->update('teamspeak_ports')->where('teamspeak_port_server_id', $server['server_id'])->set(['teamspeak_port_value' => $server_port]);

							$create_account = $api->serverCreate([
								"virtualserver_name"							=> $form_params['product_plugin__server_name'],
								"virtualserver_port"							=> $server_port,
								"virtualserver_maxclients"						=> $product_data["product_package"],
								"virtualserver_log_query"						=> '1',
								"virtualserver_log_permission"					=> '1',
								"virtualserver_log_server"						=> '-1',
								"virtualserver_log_client"						=> '-1',
								"virtualserver_log_channel"						=> '-1',
								"virtualserver_log_filetransfer"				=> '-1',
								"virtualserver_download_quota"					=> '-1',
								"virtualserver_upload_quota"					=> '-1',
								"virtualserver_max_download_total_bandwidth"	=> '-1',
								"virtualserver_max_upload_total_bandwidth"		=> '-1',
								"virtualserver_weblist_enabled"					=> '0',
								"virtualserver_codec_encryption_mode"			=> '0'
							]);
							if ($create_account['success'] == true) {
								$insert_control = self::DB()->insert('teamspeak')
								->set([
									'teamspeak_service_id' => $service_data['service_id'],
									'teamspeak_server_id' => $server['server_id'],
									'teamspeak_user_id' => customer_data('customer_id'),
									'teamspeak_port' => $server_port,
									'teamspeak_time' => time()
								]);
								if ($insert_control) {
									return ['status' => true];
								} else{
									return ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
								}
							} else{
								return ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
							}
						} else{
							return ['status' => false, 'error' => self::___('unable_to_connect_to_teamspeak_server')];
						}
					} else{
						self::DB()->update('servers')->where('server_id', $server['server_id'])->set(['server_status' => 2]);
					}
				} else{
					return ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
				}
			}
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $service_data
	*/
	public function customerArea($service_data)
	{
		if ($this->service = self::service_control($service_data)) {
			add_action('cf' , function () {
				asset('js', self::url('Public/js/index.js'), false);
			});
			if (self::API($this->service['server_data'])) {
				$this->service['api']->selectServer($this->service['teamspeak_data']['teamspeak_port']);
				self::service_index($this->service);
			} else{
				echo '<div class="alert icon-custom-alert alert-outline-danger alert-danger-shadow" role="alert"><i data-feather="alert-circle" class="alert-icon"></i><div class="alert-text"><strong>' . self::___('error') . '</strong> ' . self::___('unable_to_connect_to_teamspeak_server') . '</div></div>';
			}
		}
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $service_data
	 * @return array
	*/
	public static function service_control($service_data)
	{
		if ($service_data) {
			$teamspeak_data = self::DB()->from('teamspeak')->where('teamspeak_service_id', $service_data['service_id'])->first();
			if ($teamspeak_data && $teamspeak_data['teamspeak_status'] == 1) {
				if (customer_check($teamspeak_data['teamspeak_user_id'])) {
					if ($server_data = self::server_data($teamspeak_data['teamspeak_server_id'])) {
						return [
							'api' => self::API($server_data),
							'service_data' => $service_data,
							'teamspeak_data' => $teamspeak_data,
							'server_data' => $server_data,
						];
					} else{
						return false;
					}
				} else{
					return false;
				}
			} else{
				return false;
			}
		} else{
			return false;
		}
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $service
	*/
	public static function service_check($service)
	{
		foreach ($service['api']->getElement('data', $service['api']->serverList()) as $result) {
			if ($result['virtualserver_port'] == $service['teamspeak_data']['teamspeak_port']) {
				$service['api']->selectServer($service['teamspeak_data']['teamspeak_port']);
				return $result;
			} else{
				return false;
			}
		}
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $service
	*/
	public static function service_index($service)
	{
		try {
			if ($service) {
				$data = [];

				foreach ($service['api']->getElement('data', $service['api']->serverList()) as $result) {
					if ($result['virtualserver_port'] == $service['teamspeak_data']['teamspeak_port']) {
						$service['api']->selectServer($service['teamspeak_data']['teamspeak_port']);
						$data['server_'] = $result;
					}
				}

				$data['api'] = $service['api'];
				$data['server_ip'] = $service['server_data']['server_ip'];
				$data['server_port'] = $service['teamspeak_data']['teamspeak_port'];
				$data['server_additional_options'] = json_decode($service['server_data']['server_additional_options'], true);

				self::call_view('service', $data);
			} else{
				return false;
			}
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $service_data
	 * @param array $values
	*/
	public static function service_post($service_data, $values)
	{
		if ($SERVICE = self::service_control($service_data)) {
			if (self::API($SERVICE['server_data'])) {
				if (res('HTTP_REFERER') === service_url($service_data['service_id'])) {
					if ($values == 'Server_Stop') {
						return self::service_post__Server_Stop($SERVICE);
					} elseif ($values == 'Server_Start') {
						return self::service_post__Server_Start($SERVICE);
					} elseif ($values == 'Server_Restart') {
						return self::service_post__Server_Restart($SERVICE);
					} elseif ($values == 'Server_Edit') {
						return self::service_post__Server_Edit($SERVICE);
					} elseif ($values == 'Give_A_Permission') {
						return self::service_post__Give_A_Permission($SERVICE);
					} elseif ($values == 'Get_A_Permission') {
						return self::service_post__Get_A_Permission($SERVICE);
					} elseif ($values == 'Message_Send') {
						return self::service_post__Message_Send($SERVICE);
					} elseif ($values == 'Poke_Send') {
						return self::service_post__Poke_Send($SERVICE);
					} elseif ($values == 'Move') {
						return self::service_post__Move($SERVICE);
					} elseif ($values == 'Kick') {
						return self::service_post__Kick($SERVICE);
					} elseif ($values == 'Ban') {
						return self::service_post__Ban($SERVICE);
					} elseif ($values == 'Ban_Delete') {
						return self::service_post__Ban_Delete($SERVICE);
					} elseif ($values == 'Create_Channel') {
						return self::service_post__Create_Channel($SERVICE);
					} else{
						red(res('HTTP_REFERER'));
					}
				} else{
					red(service_url($service_data['service_id']));
				}
			} else{
				red(service_url($service_data['service_id']));
			}
		} else{
			red(service_url($service_data['service_id']));
		}
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $SERVICE
	*/
	public static function service_post__Server_Stop($SERVICE)
	{
		foreach ($SERVICE['api']->getElement('data', $SERVICE['api']->serverList()) as $result) {
			if ($result['virtualserver_port'] == $SERVICE['teamspeak_data']['teamspeak_port']) {
				$SERVICE['api']->selectServer($SERVICE['teamspeak_data']['teamspeak_port']);
				$server_ = $result;
			}
		}
		if ($server_['virtualserver_status'] == 'online') {
			$sid = $SERVICE['api']->serverIdGetByPort($SERVICE['teamspeak_data']['teamspeak_port']);
			$server_stop = $SERVICE['api']->serverStop($sid['data']['server_id']);
			if ($server_stop) {
				$json_data = ['status' => true];
			} else{
				$json_data = ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
			}
		} elseif ($server_['virtualserver_status'] == 'offline' || $server_['virtualserver_status'] == 'online virtual') {
			$json_data = ['status' => false, 'error' => self::___('server_already_closed')];
		} else{
			$json_data = ['status' => false, 'error' => self::___('unable_to_determine_server_status')];
		}
		echo json_data($json_data, self::___('successful_wait'), self::href($SERVICE['service_data']['service_id']), 1000);
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $SERVICE
	*/
	public static function service_post__Server_Start($SERVICE)
	{
		foreach ($SERVICE['api']->getElement('data', $SERVICE['api']->serverList()) as $result) {
			if ($result['virtualserver_port'] == $SERVICE['teamspeak_data']['teamspeak_port']) {
				$SERVICE['api']->selectServer($SERVICE['teamspeak_data']['teamspeak_port']);
				$server_ = $result;
			}
		}
		if ($server_['virtualserver_status'] == 'online') {
			$json_data = ['status' => false, 'error' => self::___('server_already_open')];
		} else{
			$sid = $SERVICE['api']->serverIdGetByPort($SERVICE['teamspeak_data']['teamspeak_port']);
			$server_start = $SERVICE['api']->serverStart($sid['data']['server_id']);
			if ($server_start) {
				$json_data = ['status' => true];
			} else{
				$json_data = ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
			}
		}
		echo json_data($json_data, self::___('successful_wait'), self::href($SERVICE['service_data']['service_id']), 1000);
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $SERVICE
	*/
	public static function service_post__Server_Restart($SERVICE)
	{
		foreach ($SERVICE['api']->getElement('data', $SERVICE['api']->serverList()) as $result) {
			if ($result['virtualserver_port'] == $SERVICE['teamspeak_data']['teamspeak_port']) {
				$SERVICE['api']->selectServer($SERVICE['teamspeak_data']['teamspeak_port']);
				$server_ = $result;
			}
		}
		$sid = $SERVICE['api']->serverIdGetByPort($SERVICE['teamspeak_data']['teamspeak_port']);
		$server_stop = $SERVICE['api']->serverStop($sid['data']['server_id']);
		$server_start = $SERVICE['api']->serverStart($sid['data']['server_id']);
		if ($server_stop && $server_start) {
			$json_data = ['status' => true];
		} else{
			$json_data = ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
		}
		echo json_data($json_data, self::___('successful_wait'), self::href($SERVICE['service_data']['service_id']), 1000);
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $SERVICE
	*/
	public static function service_post__Server_Edit($SERVICE)
	{
		$server_name = form_filter('server_name');
		$server_weblist = form_filter('server_weblist');
		$server_host_message = form_filter('server_host_message');
		$server_host_message_mode = form_filter('server_host_message_mode');
		$server_banner_link = form_filter('server_banner_link');
		$server_banner_image_link = form_filter('server_banner_image_link');
		$server_host_button_name = form_filter('server_host_button_name');
		$server_host_button_link = form_filter('server_host_button_link');
		$server_host_button_image_link = form_filter('server_host_button_image_link');
		$server_welcome_message = form_filter('server_welcome_message');
		if (isset($server_name) && !empty($server_name)) {
			$SERVICE['api']->selectServer($SERVICE['teamspeak_data']['teamspeak_port']);
			$SERVICE['api']->setName('TSPANEL');
			$server_edit_data = array(
				"virtualserver_name"				=> $server_name,
				"virtualserver_weblist_enabled"		=> $server_weblist,
				"virtualserver_hostmessage"			=> $server_host_message,
				"virtualserver_hostmessage_mode"	=> $server_host_message_mode,
				"virtualserver_hostbanner_url"		=> $server_banner_link,
				"virtualserver_hostbanner_gfx_url"	=> $server_banner_image_link,
				"virtualserver_hostbutton_tooltip"	=> $server_host_button_name,
				"virtualserver_hostbutton_url"		=> $server_host_button_link,
				"virtualserver_hostbutton_gfx_url"	=> $server_host_button_image_link,
				"virtualserver_welcomemessage"		=> $server_welcome_message
			);
			$server_edit_control = $SERVICE['api']->serverEdit($server_edit_data);
			if ($server_edit_control['success']) {
				$json_data = ['status' => true];
			} else{
				$json_data = ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
			}
		} else{
			$json_data = ['status' => false, 'error' => self::___('fill_in_the_required_fields')];
		}
		echo json_data($json_data, self::___('successful_wait'), self::href($SERVICE['service_data']['service_id']));
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $SERVICE
	*/
	public static function service_post__Give_A_Permission($SERVICE)
	{
		$person = form_filter('person');
		$authority = form_filter('authority');
		if (isset($person) && !empty($person) && isset($authority) && !empty($authority)) {
			if ($authority == '1' || $authority == '2' || $authority == '3' || $authority == '4' || $authority == '5') {
				$json_data = ['status' => false, 'error' => self::___('incorrect_authority_selection')];
			} else{
				$SERVICE['api']->selectServer($SERVICE['teamspeak_data']['teamspeak_port']);
				$SERVICE['api']->setName('TSPANEL');
				$server_group_add_client = $SERVICE['api']->serverGroupAddClient($authority, $person);
				if ($server_group_add_client['success']) {
					$json_data = ['status' => true];
				} elseif ($server_group_add_client['errors']['0']) {
					if ($server_group_add_client['errors']['0'] == 'ErrorID: 512 | Message: invalid clientID') {
						$json_data = ['status' => false, 'error' => self::___('the_id_of_the_selected_contact_could_not_be_determined_the_contact_may_not_be_on_the_server_at_this_time')];
					}
					if ($server_group_add_client['errors']['0'] == 'ErrorID: 516 | Message: invalid client type') {
						$json_data = ['status' => false, 'error' => self::___('client_type_of_selected_person_invalid')];
					}
					if ($server_group_add_client['errors']['0'] == 'ErrorID: 2561 | Message: duplicate entry') {
						$json_data = ['status' => false, 'error' => self::___('the_selected_person_has_already_been_granted_the_selected_authority')];
					}
					if ($server_group_add_client['errors']['0'] == 'ErrorID: 2564 | Message: access to default group is forbidden') {
						$json_data = ['status' => false, 'error' => self::___('selected_authority_a_default_authority')];
					}
				} else{
					$json_data = ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
				}
			}
		} else{
			$json_data = ['status' => false, 'error' => self::___('fill_in_the_required_fields')];
		}
		echo json_data($json_data, self::___('successful_wait'), self::href($SERVICE['service_data']['service_id']));
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $SERVICE
	*/
	public static function service_post__Get_A_Permission($SERVICE)
	{
		$person = form_filter('person');
		$authority = form_filter('authority');
		if (isset($person) && !empty($person) && isset($authority) && !empty($authority)) {
			if ($authority == '1' || $authority == '2' || $authority == '3' || $authority == '4' || $authority == '5') {
				$json_data = ['status' => false, 'error' => self::___('incorrect_authority_selection')];
			} else{
				$SERVICE['api']->selectServer($SERVICE['teamspeak_data']['teamspeak_port']);
				$SERVICE['api']->setName('TSPANEL');
				$server_group_delete_client = $SERVICE['api']->serverGroupDeleteClient($authority, $person);
				if ($server_group_delete_client['success']) {
					$json_data = ['status' => true];
				} elseif ($server_group_delete_client['errors']['0']) {
					if ($server_group_delete_client['errors']['0'] == 'ErrorID: 512 | Message: invalid clientID') {
						$json_data = ['status' => false, 'error' => self::___('the_id_of_the_selected_contact_could_not_be_determined_the_contact_may_not_be_on_the_server_at_this_time')];
					}
					if ($server_group_delete_client['errors']['0'] == 'ErrorID: 516 | Message: invalid client type') {
						$json_data = ['status' => false, 'error' => self::___('client_type_of_selected_person_invalid')];
					}
					if ($server_group_delete_client['errors']['0'] == 'ErrorID: 2561 | Message: duplicate entry') {
						$json_data = ['status' => false, 'error' => self::___('the_selected_person_has_already_been_granted_the_selected_authority')];
					}
					if ($server_group_delete_client['errors']['0'] == 'ErrorID: 2564 | Message: access to default group is forbidden') {
						$json_data = ['status' => false, 'error' => self::___('selected_authority_a_default_authority')];
					}
					if ($server_group_delete_client['errors']['0'] == 'ErrorID: 2563 | Message: empty result set') {
						$json_data = ['status' => false, 'error' => self::___('selected_person_does_not_have_selected_authority')];
					}
				} else{
					$json_data = ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
				}
			}
		} else{
			$json_data = ['status' => false, 'error' => self::___('fill_in_the_required_fields')];
		}
		echo json_data($json_data, self::___('successful_wait'), self::href($SERVICE['service_data']['service_id']));
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $SERVICE
	*/
	public static function service_post__Message_Send($SERVICE)
	{
		$person = form_filter('person');
		$message = form_filter('message');
		if (isset($person) && !empty($person) && isset($message) && !empty($message)) {
			$SERVICE['api']->selectServer($SERVICE['teamspeak_data']['teamspeak_port']);
			$SERVICE['api']->setName('TSPANEL');
			$send_message = $SERVICE['api']->sendMessage('1', $person, $message);
			if ($send_message['success']) {
				$json_data = ['status' => true];
			} elseif ($send_message['errors']['0']) {
				if ($send_message['errors']['0'] == 'ErrorID: 512 | Message: invalid clientID') {
					$json_data = ['status' => false, 'error' => self::___('the_id_of_the_selected_contact_could_not_be_determined_the_contact_may_not_be_on_the_server_at_this_time')];
				}
				if ($send_message['errors']['0'] == 'ErrorID: 2568 | Message: insufficient client permissions failed_permid=180') {
					$json_data = ['status' => false, 'error' => self::___('you_do_not_have_permission_to_send_the_selected_message_the_selected_person_may_have_high_authority')];
				}
			} else{
				$json_data = ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
			}
		} else{
			$json_data = ['status' => false, 'error' => self::___('fill_in_the_required_fields')];
		}
		echo json_data($json_data, self::___('successful_wait'), self::href($SERVICE['service_data']['service_id']), null, 0, false);
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $SERVICE
	*/
	public static function service_post__Poke_Send($SERVICE)
	{
		$person = form_filter('person');
		$message = form_filter('message');
		if (isset($person) && !empty($person) && isset($message) && !empty($message)) {
			$SERVICE['api']->selectServer($SERVICE['teamspeak_data']['teamspeak_port']);
			$SERVICE['api']->setName('TSPANEL');
			$client_poke = $SERVICE['api']->clientPoke($person, $message);
			if ($client_poke['success']) {
				$json_data = ['status' => true];
			} elseif ($client_poke['errors']['0']) {
				if ($client_poke['errors']['0'] == 'ErrorID: 512 | Message: invalid clientID') {
					$json_data = ['status' => false, 'error' => self::___('the_id_of_the_selected_contact_could_not_be_determined_the_contact_may_not_be_on_the_server_at_this_time')];
				}
				if ($client_poke['errors']['0'] == 'ErrorID: 2568 | Message: insufficient client permissions failed_permid=180') {
					$json_data = ['status' => false, 'error' => self::___('you_do_not_have_permission_to_send_the_selected_message_the_selected_person_may_have_high_authority')];
				}
			} else{
				$json_data = ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
			}
		} else{
			$json_data = ['status' => false, 'error' => self::___('fill_in_the_required_fields')];
		}
		echo json_data($json_data, self::___('successful_wait'), self::href($SERVICE['service_data']['service_id']), null, 0, false);
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $SERVICE
	*/
	public static function service_post__Move($SERVICE)
	{
		$person = form_filter('person');
		$channel = form_filter('channel');
		$channel_password = form_filter('channel_password');
		if (isset($person) && !empty($person) && isset($channel) && !empty($channel)) {
			$SERVICE['api']->selectServer($SERVICE['teamspeak_data']['teamspeak_port']);
			$SERVICE['api']->setName('TSPANEL');
			$client_move = $SERVICE['api']->clientMove($person, $channel, $channel_password=null);
			if ($client_move['success']) {
				$json_data = ['status' => true];
			} elseif($client_move['errors']['0']){
				if ($client_move['errors']['0'] == 'ErrorID: 512 | Message: invalid clientID') {
					$json_data = ['status' => false, 'error' => self::___('the_id_of_the_selected_contact_could_not_be_determined_the_contact_may_not_be_on_the_server_at_this_time')];
				}
				if ($client_move['errors']['0'] == 'ErrorID: 2568 | Message: insufficient client permissions failed_permid=180') {
					$json_data = ['status' => false, 'error' => self::___('you_do_not_have_permission_to_send_the_selected_message_the_selected_person_may_have_high_authority')];
				}
				if ($client_move['errors']['0'] == 'ErrorID: 516 | Message: invalid client type') {
					$json_data = ['status' => false, 'error' => self::___('client_type_of_selected_person_invalid')];
				}
				if ($client_move['errors']['0'] == 'ErrorID: 770 | Message: already member of channel') {
					$json_data = ['status' => false, 'error' => self::___('the_contact_is_already_on_the_standard_channel')];
				}
			} else{
				$json_data = ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
			}
		} else{
			$json_data = ['status' => false, 'error' => self::___('fill_in_the_required_fields')];
		}
		echo json_data($json_data, self::___('successful_wait'), self::href($SERVICE['service_data']['service_id']));
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $SERVICE
	*/
	public static function service_post__Kick($SERVICE)
	{
		$person = form_filter('person');
		$type = form_filter('type');
		$message = form_filter('message');
		if (isset($person) && !empty($person) && isset($type) && !empty($type) && isset($message) && !empty($message)) {
			$SERVICE['api']->selectServer($SERVICE['teamspeak_data']['teamspeak_port']);
			$SERVICE['api']->setName('TSPANEL');
			$client_kick = $SERVICE['api']->clientKick($person, $type, $message);
			if ($client_kick['success']) {
				$json_data = ['status' => true];
			} elseif ($client_kick['errors']['0']) {
				if ($client_kick['errors']['0'] == 'ErrorID: 512 | Message: invalid clientID') {
					$json_data = ['status' => false, 'error' => self::___('the_id_of_the_selected_contact_could_not_be_determined_the_contact_may_not_be_on_the_server_at_this_time')];
				}
				if ($client_kick['errors']['0'] == 'ErrorID: 2568 | Message: insufficient client permissions failed_permid=180') {
					$json_data = ['status' => false, 'error' => self::___('you_do_not_have_permission_to_send_the_selected_message_the_selected_person_may_have_high_authority')];
				}
				if ($client_kick['errors']['0'] == 'ErrorID: 516 | Message: invalid client type') {
					$json_data = ['status' => false, 'error' => self::___('client_type_of_selected_person_invalid')];
				}
				if ($client_kick['errors']['0'] == 'ErrorID: 770 | Message: already member of channel') {
					$json_data = ['status' => false, 'error' => self::___('the_contact_is_already_on_the_standard_channel')];
				}
			} else{
				$json_data = ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
			}
		} else{
			$json_data = ['status' => false, 'error' => self::___('fill_in_the_required_fields')];
		}
		echo json_data($json_data, self::___('successful_wait'), self::href($SERVICE['service_data']['service_id']));
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $SERVICE
	*/
	public static function service_post__Ban($SERVICE)
	{
		$person = form_filter('person');
		$time = form_filter('time');
		$message = form_filter('message');
		if (isset($person) && !empty($person) && isset($message) && !empty($message)) {
			$SERVICE['api']->selectServer($SERVICE['teamspeak_data']['teamspeak_port']);
			$SERVICE['api']->setName('TSPANEL');
			$ban_client = $SERVICE['api']->banClient($person, $time, $message);
			if ($ban_client['success']) {
				$json_data = ['status' => true];
			} elseif ($ban_client['errors']['0']) {
				if ($ban_client['errors']['0'] == 'ErrorID: 512 | Message: invalid clientID') {
					$json_data = ['status' => false, 'error' => self::___('the_id_of_the_selected_contact_could_not_be_determined_the_contact_may_not_be_on_the_server_at_this_time')];
				}
				if ($ban_client['errors']['0'] == 'ErrorID: 2568 | Message: insufficient client permissions failed_permid=180') {
					$json_data = ['status' => false, 'error' => self::___('you_do_not_have_permission_to_send_the_selected_message_the_selected_person_may_have_high_authority')];
				}
				if ($ban_client['errors']['0'] == 'ErrorID: 516 | Message: invalid client type') {
					$json_data = ['status' => false, 'error' => self::___('client_type_of_selected_person_invalid')];
				}
				if ($ban_client['errors']['0'] == 'ErrorID: 770 | Message: already member of channel') {
					$json_data = ['status' => false, 'error' => self::___('the_contact_is_already_on_the_standard_channel')];
				}
				if ($ban_client['errors']['0'] == 'ErrorID: 1540 | Message: convert error') {
					$json_data = ['status' => false, 'error' => self::___('enter_time')];
				}
			} else{
				$json_data = ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
			}
		} else{
			$json_data = ['status' => false, 'error' => self::___('fill_in_the_required_fields')];
		}
		echo json_data($json_data, self::___('successful_wait'), self::href($SERVICE['service_data']['service_id']));
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $SERVICE
	*/
	public static function service_post__Ban_Delete($SERVICE)
	{
		$id = form_filter('id');
		if (isset($id) && !empty($id)) {
			$SERVICE['api']->selectServer($SERVICE['teamspeak_data']['teamspeak_port']);
			$SERVICE['api']->setName('TSPANEL');
			$ban_delete = $SERVICE['api']->banDelete($id);
			if ($ban_delete['success']) {
				$json_data = ['status' => true];
			} elseif ($ban_delete['errors']['0']) {
				$json_data = ['status' => false, 'error' => pr($banDelete)];
				$json_data = ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
			} else{
				$json_data = ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
			}
		} else{
			$json_data = ['status' => false, 'error' => self::___('fill_in_the_required_fields')];
		}
		echo json_data($json_data, self::___('successful_wait'), self::href($SERVICE['service_data']['service_id']));
	}

	/**
	 *
	 * @author Mirarus
	 * @param array $SERVICE
	*/
	public static function service_post__Create_Channel($SERVICE)
	{
		$type = form_filter('type');
		$name = form_filter('name');
		if (isset($type) && !empty($type) && isset($name) && !empty($name)) {
			$SERVICE['api']->selectServer($SERVICE['teamspeak_data']['teamspeak_port']);
			$SERVICE['api']->setName('TSPANEL');
			if ($type == '1') {
				$channel_create_data = [
					"channel_name"				  => "[cspacer]" . $name,
					"channel_flag_permanent"	  => 1,
					"channel_flag_semi_permanent" => 0
				];
			} elseif ($type == '2') {
				$channel_create_data = [
					"channel_name"				  => "[spacer]" . $name,
					"channel_flag_permanent"	  => 1,
					"channel_flag_semi_permanent" => 0
				];
			} elseif ($type == '3') {
				$channel_create_data = [
					"channel_name"				  => "[rspacer]" . $name,
					"channel_flag_permanent"	  => 1,
					"channel_flag_semi_permanent" => 0
				];
			} else{
				$json_data = ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
			}
			$channel_create = $SERVICE['api']->channelCreate($channel_create_data);
			if ($channel_create['success']) {
				$json_data = ['status' => true];
			} elseif ($channel_create['errors']['0']) {
				if ($channel_create['errors']['0'] == 'ErrorID: 512 | Message: invalid clientID') {
					$json_data = ['status' => false, 'error' => self::___('the_id_of_the_selected_contact_could_not_be_determined_the_contact_may_not_be_on_the_server_at_this_time')];
				}
				if ($channel_create['errors']['0'] == 'ErrorID: 516 | Message: invalid client type') {
					$json_data = ['status' => false, 'error' => self::___('client_type_of_selected_person_invalid')];
				}
				if ($channel_create['errors']['0'] == 'ErrorID: 771 | Message: channel name is already in use') {
					$json_data = ['status' => false, 'error' => self::___('channel_name_already_used')];
				}
			} else{
				$json_data = ['status' => false, 'error' => self::___('an_error_occurred_while_processing')];
			}
		} else{
			$json_data = ['status' => false, 'error' => self::___('fill_in_the_required_fields')];
		}
		echo json_data($json_data, self::___('successful_wait'), self::href($SERVICE['service_data']['service_id']));
	}
}