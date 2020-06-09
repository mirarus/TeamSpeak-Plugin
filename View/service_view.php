<?php if ($server_) {
	if ($server_['virtualserver_status'] == 'online') {
		$server_info		= $api->getElement('data', $api->serverInfo());
		$active_user		= ($server_info['virtualserver_clientsonline'] - $server_info['virtualserver_queryclientsonline']);
		$user_percent		= (round($active_user * 100 / $server_info['virtualserver_maxclients']));
		$user_count			= ($active_user . '/' . $server_info['virtualserver_maxclients'].' (%' . $user_percent . ')');
		$ping				= (round($server_info['virtualserver_total_ping'] * 100) / 100) . ' Ms';
		$package_loss		= '%' . ($server_info['virtualserver_total_packetloss_total'] * 100);
		$uptime				= convertSecondsToStrTime($api->convertSecondsToArrayTime($server_info['virtualserver_uptime']));
		$client_list		= $api->getElement('data', $api->clientList());
		$server_group_list	= $api->getElement('data', $api->serverGroupList());
		$channel_List		= $api->getElement('data', $api->channelList());
		$ban_list			= $api->getElement('data', $api->banList());
		$ban_list2			= $api->banList();
	} ?>
	<div class="card">
		<div class="b-b">
			<div class="nav-active-border b-primary bottom">
				<ul class="nav" role="tablist" id="Tab_List">
					<li class="nav-item">
						<span class="nav-link" style="padding: 0.4rem;">
							<img src="<?php NC(self::url('Public/img/logo.png')); ?>" width="60" widht="25">
						</span>
					</li>
					<li class="nav-item">
						<a class="nav-link active" id="TeamSpeak-main-page-tab" data-toggle="tab" href="#TeamSpeak_main_page" role="tab" aria-controls="TeamSpeak_main_page" aria-selected="true"><i data-feather="home"></i> <?php self::__('main_page'); ?></a>
					</li>
					<?php if ($server_['virtualserver_status'] == 'online') { ?>
						<li class="nav-item">
							<a class="nav-link" id="TeamSpeak-details-tab" data-toggle="tab" href="#TeamSpeak_details" role="tab" aria-controls="TeamSpeak_details" aria-selected="false"><i data-feather="monitor"></i> <?php self::__('details'); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="TeamSpeak-traffic-tab" data-toggle="tab" href="#TeamSpeak_traffic" role="tab" aria-controls="TeamSpeak_traffic" aria-selected="false"><i data-feather="activity"></i> <?php self::__('traffic'); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="TeamSpeak-edit-tab" data-toggle="tab" href="#TeamSpeak_edit" role="tab" aria-controls="TeamSpeak_edit" aria-selected="false"><i data-feather="edit"></i> <?php self::__('edit'); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="TeamSpeak-authority-tab" data-toggle="tab" href="#TeamSpeak_authority" role="tab" aria-controls="TeamSpeak_authority" aria-selected="false"><i data-feather="user-check"></i> <?php self::__('authority'); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="TeamSpeak-message-tab" data-toggle="tab" href="#TeamSpeak_message" role="tab" aria-controls="TeamSpeak_message" aria-selected="false"><i data-feather="send"></i> <?php self::__('message'); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="TeamSpeak-poke-tab" data-toggle="tab" href="#TeamSpeak_poke" role="tab" aria-controls="TeamSpeak_poke" aria-selected="false"><i data-feather="target"></i> <?php self::__('poke'); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="TeamSpeak-move-tab" data-toggle="tab" href="#TeamSpeak_move" role="tab" aria-controls="TeamSpeak_move" aria-selected="false"><i data-feather="move"></i> <?php self::__('move'); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="TeamSpeak-kick-tab" data-toggle="tab" href="#TeamSpeak_kick" role="tab" aria-controls="TeamSpeak_kick" aria-selected="false"><i data-feather="user-minus"></i> <?php self::__('kick'); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="TeamSpeak-ban-tab" data-toggle="tab" href="#TeamSpeak_ban" role="tab" aria-controls="TeamSpeak_ban" aria-selected="false"><i data-feather="user-x"></i> <?php self::__('ban'); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="TeamSpeak-channel-tab" data-toggle="tab" href="#TeamSpeak_channel" role="tab" aria-controls="TeamSpeak_channel" aria-selected="false"><i data-feather="command"></i> <?php self::__('channel'); ?></a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
	<div class="tab-content">
		<div class="tab-pane fade active show" id="TeamSpeak_main_page" role="tabpanel" aria-labelledby="TeamSpeak-main-page-tab">
			<div class="row row-sm sr">
				<div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<?php self::__('server_operations'); ?>
							<span class="float-right font-weight-500 text-info">
								<a href="ts3server://<?php echo $server_ip . ':' . $server_port; ?>"  target="_blank" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $server_ip . ':' . $server_port; ?>">
									<?php echo $server_ip . ':' . $server_port; ?>
								</a>
							</span>
						</div>
						<div class="card-body">
							<h5 class="card-title">
								<span class="text-muted"><?php self::__('server_status'); ?> </span>
								<?php if ($server_['virtualserver_status'] == 'online') {
									echo '<span class="badge badge-boxed badge-soft-success">' . self::___('open') . '</span>';
								} elseif ($server_['virtualserver_status'] == 'offline' || $server_['virtualserver_status'] == 'online virtual') {
									echo '<span class="badge badge-boxed badge-soft-danger">' . self::___('closed') . '</span>';
								} else {
									echo '<span class="badge badge-boxed badge-soft-info">' . self::___('unknown') . '</span>';
								} ?>
							</h5>
							<div class="btn-group-0 mt-4">
								<?php if ($server_['virtualserver_status'] == 'online') { ?>
									<a href="ts3server://<?php echo $server_ip . ':' . $server_port; ?>" target="_blank" class="btn btn-default badge badge-boxed badge-soft-info ml-2"><?php self::__('connect'); ?></a>
									<button onclick="ServerStop();" class="btn btn-default badge badge-boxed badge-soft-danger ml-2" type="submit"><?php self::__('stop'); ?></button>
								<?php } ?>
								<button onclick="ServerStart();" class="btn btn-default badge badge-boxed badge-soft-success ml-2" type="submit"><?php self::__('start'); ?></button>
								<?php if ($server_['virtualserver_status'] == 'online') { ?>
									<button onclick="ServerRestart();" class="btn btn-default badge badge-boxed badge-soft-warning ml-2" type="submit"><?php self::__('restart'); ?></button>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<?php if ($server_['virtualserver_status'] == 'online') { ?>
					<div class="col-md-6">
						<div class="row row-sm">
							<div class="col-12">
								<div class="card">
									<div class="card-body text-center">
										<div class="row row-sm">
											<div class="col-4">
												<small class="text-muted"><?php self::__('number_of_people'); ?></small>
												<div class="mt-2 font-weight-500">
													<span class="text-info"><?php echo $user_count; ?></span>
												</div>
											</div>
											<div class="col-4">
												<small class="text-muted"><?php self::__('ping'); ?></small>
												<div class="text-highlight mt-2 font-weight-500">
													<span class="text-info"><?php echo $ping; ?></span>
												</div>
											</div>
											<div class="col-4">
												<small class="text-muted"><?php self::__('package_loss'); ?></small>
												<div class="mt-2 font-weight-500">
													<span class="text-info"><?php echo $package_loss; ?></span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row row-sm">
							<div class="col-12">
								<div class="card">
									<div class="card-body text-center">
										<div class="row row-sm">
											<div class="col-sm-12">
												<small class="text-muted"><?php self::__('uptime'); ?></small>
												<div class="mt-2 font-weight-500">
													<span class="text-danger"><?php echo $uptime; ?></span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php if ($server_['virtualserver_status'] == 'online') { ?>
			<div class="tab-pane fade" id="TeamSpeak_details" role="tabpanel" aria-labelledby="TeamSpeak-details-tab">
				<div class="table-responsive">
					<table id="datatable_Details" class="table table-theme table-row v-middle">
						<thead>
							<tr>
								<th><span class="text-muted">#</span></th>
								<th><span class="text-muted"><?php self::__('property'); ?></span></th>
								<th><span class="text-muted"><?php self::__('content'); ?></span></th>
							</tr>
						</thead>
						<tbody>
							<?php $iid = 1; $id = 1; ?>
							<tr  data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php self::__('platform'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $server_info['virtualserver_platform']; ?></span></td>
							</tr>
							<tr data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php self::__('version'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $server_info['virtualserver_version']; ?></span></td>
							</tr>
							<tr data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php self::__('creation_date'); ?></span></td>
								<td class="flex"><span class="item-date d-sm-block text-sm"><?php echo date('H:i:s - d.m.Y', $server_info['virtualserver_created']); ?></span></td>
							</tr>
							<tr data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php self::__('uptime'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $uptime; ?></span></td>
							</tr>
							<tr data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php self::__('number_of_people'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $user_count; ?></span></td>
							</tr>
							<tr data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php echo self::___('ping') . ' / ' . self::___('package_loss'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $ping.' / '.$package_loss; ?></span></td>
							</tr>
							<tr data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php self::__('number_of_channels'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $server_info['virtualserver_channelsonline']; ?></span></td>
							</tr>
							<tr data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php self::__('show_server_in_web_list'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $server_info['virtualserver_weblist_enabled'] == '1' ? '<span class="text-success">' . self::___('yes') . '</span>' : '<span class="text-danger">' . self::___('no').'</span>'; ?></span></td>
							</tr>
							<tr data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php self::__('welcome_message'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $server_info['virtualserver_welcomemessage']; ?></span></td>
							</tr>
							<tr data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php self::__('host_message'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $server_info['virtualserver_hostmessage']; ?></span></td>
							</tr>
							<tr data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php self::__('host_message_mode'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm "><?php if ($server_info['virtualserver_hostmessage_mode'] == '0') {
									echo '<span class="text-info">' . self::___('no_message_empty') . '</span>';
								} elseif ($server_info['virtualserver_hostmessage_mode'] == '1') {
									echo '<span class="text-info">' . self::___('show_login_message_login') . '</span>';
								} elseif ($server_info['virtualserver_hostmessage_mode'] == '2') {
									echo '<span class="text-info">' . self::___('show_trboard_message_trboard') . '</span>';
								} elseif ($server_info['virtualserver_hostmessage_mode'] == '3') {
									echo '<span class="text-info">' . self::___('trboard_message_and_output_trboard_out') . '</span>';
								} ?></span></td>
							</tr>
							<tr data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php self::__('banner_link'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $server_info['virtualserver_hostbanner_url']; ?></span></td>
							</tr>
							<tr data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php self::__('banner_image_link'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $server_info['virtualserver_hostbanner_gfx_url']; ?></span></td>
							</tr>
							<tr data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php self::__('host_button_name'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $server_info['virtualserver_hostbutton_tooltip']; ?></span></td>
							</tr>
							<tr data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php self::__('host_button_link'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $server_info['virtualserver_hostbutton_url']; ?></span></td>
							</tr>
							<tr data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:250px;"><span class="item-amount d-sm-block text-sm"><?php self::__('host_button_image_link'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $server_info['virtualserver_hostbutton_gfx_url']; ?></span></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="tab-pane fade" id="TeamSpeak_traffic" role="tabpanel" aria-labelledby="TeamSpeak-traffic-tab">
				<div class="table-responsive">
					<table id="datatable_Traffic" class="table table-theme table-row v-middle">
						<thead>
							<tr>
								<th><span class="text-muted">#</span></th>
								<th><span class="text-muted"><?php self::__('property'); ?></span></th>
								<th><span class="text-muted"><?php self::__('incoming'); ?></span></th>
								<th><span class="text-muted"><?php self::__('outgoing'); ?></span></th>
							</tr>
						</thead>
						<tbody>
							<?php $iid = 1; $id = 1; ?>
							<tr  data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:300px;"><span class="item-amount d-sm-block text-sm"><?php self::__('packet_transfer'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm "><?php echo formatBytes($server_info['connection_packets_received_total'], 2); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm "><?php echo formatBytes($server_info['connection_packets_sent_total'], 2); ?></span></td>
							</tr>
							<tr  data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:300px;"><span class="item-amount d-sm-block text-sm"><?php self::__('byte_transfer'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm "><?php echo conv_traffic($server_info['connection_bytes_received_total']); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm "><?php echo conv_traffic($server_info['connection_bytes_sent_total']); ?></span></td>
							</tr>
							<tr  data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:300px;"><span class="item-amount d-sm-block text-sm"><?php self::__('bandwidth_per_seconds'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm "><?php echo conv_traffic($server_info['connection_bandwidth_received_last_second_total'])."/s"; ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm "><?php echo conv_traffic($server_info['connection_bandwidth_sent_last_second_total'])."/s"; ?></span></td>
							</tr>
							<tr  data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:300px;"><span class="item-amount d-sm-block text-sm"><?php self::__('bandwidth_per_minute'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm "><?php echo conv_traffic($server_info['connection_bandwidth_received_last_minute_total'])."/s"; ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm "><?php echo conv_traffic($server_info['connection_bandwidth_sent_last_minute_total'])."/s"; ?></span></td>
							</tr>
							<tr  data-id="<?php echo $iid++; ?>">
								<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $id++; ?></small></td>
								<td class="flex" style="width:300px;"><span class="item-amount d-sm-block text-sm"><?php self::__('file_transfer_bandwidth'); ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm "><?php echo conv_traffic($server_info['connection_filetransfer_bandwidth_received'])."/s"; ?></span></td>
								<td class="flex"><span class="item-amount d-sm-block text-sm "><?php echo conv_traffic($server_info['connection_filetransfer_bandwidth_received'])."/s"; ?></span></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="tab-pane fade" id="TeamSpeak_edit" role="tabpanel" aria-labelledby="TeamSpeak-edit-tab">
				<div class="row row-sm sr">
					<div class="col-md-12">
						<div class="card flex">
							<div class="card-body">
								<form role="form" action="" onsubmit="return false;">
									<div class="form-row">
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('server_name'); ?></label>
											<input type="text" class="form-control" name="edit__server_name" value="<?php echo $server_info['virtualserver_name']; ?>" placeholder="<?php self::__('enter_server_name'); ?>">
										</div>
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('show_server_in_web_list'); ?></label>
											<select class="form-control" name="edit__server_weblist">
												<option value="1" <?php echo ($server_info['virtualserver_weblist_enabled'] == 1) ? 'selected="selected"' : ''; ?>><?php self::__('yes'); ?></option>
												<option value="0" <?php echo ($server_info['virtualserver_weblist_enabled'] == 0) ? 'selected="selected"' : ''; ?>><?php self::__('no'); ?></option>
											</select>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('host_message'); ?></label>
											<input type="text" class="form-control" name="edit__server_host_message" value="<?php echo $server_info['virtualserver_hostmessage']; ?>" placeholder="<?php self::__('enter_host_message'); ?>"> 
										</div>
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('host_message_mode'); ?></label>
											<select class="form-control" name="edit__server_host_message_mode">
												<option value="0" <?php echo ($server_info['virtualserver_hostmessage_mode'] == 0) ? 'selected="selected"' : ''; ?>><?php self::__('no_message_empty'); ?></option>
												<option value="1" <?php echo ($server_info['virtualserver_hostmessage_mode'] == 1) ? 'selected="selected"' : ''; ?>><?php self::__('show_login_message_login'); ?></option>
												<option value="2" <?php echo ($server_info['virtualserver_hostmessage_mode'] == 2) ? 'selected="selected"' : ''; ?>><?php self::__('show_trboard_message_trboard'); ?></option>
												<option value="3" <?php echo ($server_info['virtualserver_hostmessage_mode'] == 3) ? 'selected="selected"' : ''; ?>><?php self::__('trboard_message_and_output_trboard_out'); ?></option>
											</select>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('banner_link'); ?></label>
											<input type="text" class="form-control" name="edit__server_banner_link" value="<?php echo $server_info['virtualserver_hostbanner_url']; ?>" placeholder="<?php self::__('enter_banner_link'); ?>"> 
										</div>
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('banner_image_link'); ?></label>
											<input type="text" class="form-control" name="edit__server_banner_image_link" value="<?php echo $server_info['virtualserver_hostbanner_gfx_url']; ?>" placeholder="<?php self::__('enter_banner_image_link'); ?>"> 
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-4">
											<label class="text-muted"><?php self::__('host_button_name'); ?></label>
											<input type="text" class="form-control" name="edit__server_host_button_name" value="<?php echo $server_info['virtualserver_hostbutton_tooltip']; ?>" placeholder="<?php self::__('enter_host_button_name'); ?>"> 
										</div>
										<div class="form-group col-md-4">
											<label class="text-muted"><?php self::__('host_button_link'); ?></label>
											<input type="text" class="form-control" name="edit__server_host_button_link" value="<?php echo $server_info['virtualserver_hostbutton_url']; ?>" placeholder="<?php self::__('enter_host_button_link'); ?>">
										</div>
										<div class="form-group col-md-4">
											<label class="text-muted"><?php self::__('host_button_image_link'); ?></label>
											<input type="text" class="form-control" name="edit__server_host_button_image_link" value="<?php echo $server_info['virtualserver_hostbutton_gfx_url']; ?>" placeholder="<?php self::__('enter_host_button_image_link'); ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="text-muted"><?php self::__('welcome_message'); ?></label>
										<textarea class="form-control" name="edit__server_welcome_message" placeholder="<?php self::__('enter_welcome_message'); ?>"><?php echo $server_info['virtualserver_welcomemessage']; ?></textarea>
									</div>
									<div class="text-center">
										<button onclick="ServerEdit();" class="btn btn-default badge badge-boxed badge-soft-success waves-effect waves-light" type="submit"><?php self::__('edit'); ?></button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="TeamSpeak_authority" role="tabpanel" aria-labelledby="TeamSpeak-authority-tab">
				<div class="row row-sm sr">
					<div class="col-md-12 col-lg-12 d-flex">
						<div class="card flex">
							<div class="card-body">
								<p class="text-muted"><code class="highlighter-rouge"><?php self::__('refresh_the_page_if_the_person_does_not_appear'); ?></code></p>
								<form role="form" action="" onsubmit="return false;">
									<div class="form-row">
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('person'); ?></label>
											<select class="form-control" name="authority__person">
												<option disabled selected><?php self::__('select_contact'); ?></option>
												<?php foreach ($client_list as $key) {
													if ($key['client_type'] == 0) {
														echo '<option value="' . $key['client_database_id'] . '">(' . $key['client_database_id'] . ') ' . $key['client_nickname'] . '</option>';
													}
												} ?>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('authority'); ?></label>
											<select class="form-control" name="authority__authority">
												<option disabled selected><?php self::__('select_authority'); ?></option>
												<?php foreach ($server_group_list as $key) {
													if ($key['type'] == 2){
														if($key['sgid'] == $server_additional_options['im_bot_id']) {
															echo '<option value="' . $key['sgid'] . '">(' . $key['sgid'] . ') ' . $key['name'] . '</option>';
														}
													}
													if ($key['type'] == 1){
														echo '<option value="' . $key['sgid'] . '">(' . $key['sgid'] . ') ' . $key['name'] . '</option>';
													}
												} ?>
											</select>
										</div>
									</div>
									<div class="text-center">
										<button onclick="GiveAPermission();" class="btn btn-default badge badge-boxed badge-soft-success waves-effect waves-light" type="submit"><?php self::__('give_authorization'); ?></button>
										<button onclick="GetAPermission();" class="btn btn-default badge badge-boxed badge-soft-danger waves-effect waves-light ml-2"><?php self::__('get_authorization'); ?></button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="TeamSpeak_message" role="tabpanel" aria-labelledby="TeamSpeak-message-tab">
				<div class="row row-sm sr">
					<div class="col-md-12 col-lg-12 d-flex">
						<div class="card flex">
							<div class="card-body">
								<p class="text-muted"><code class="highlighter-rouge"><?php self::__('refresh_the_page_if_the_person_does_not_appear'); ?></code></p>
								<form role="form" action="" onsubmit="return false;">
									<div class="form-group">
										<label class="text-muted"><?php self::__('person'); ?></label>
										<select class="form-control" name="message__person">
											<option disabled selected><?php self::__('select_contact'); ?></option>
											<?php foreach ($client_list as $key) {
												if ($key['client_type'] == 0) {
													echo '<option value="' . $key['clid'] . '">(' . $key['clid'] . ') ' . $key['client_nickname'] . '</option>';
												}
											} ?>
										</select>
									</div>
									<div class="form-group">
										<label class="text-muted"><?php self::__('message'); ?></label>
										<textarea class="form-control" name="message__message" placeholder="<?php self::__('enter_message'); ?>"></textarea>
									</div>
									<div class="text-center">
										<button onclick="MessageSend();" class="btn btn-default badge badge-boxed badge-soft-info waves-effect waves-light" type="submit"><?php self::__('send'); ?></button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="TeamSpeak_poke" role="tabpanel" aria-labelledby="TeamSpeak-poke-tab">
				<div class="row row-sm sr">
					<div class="col-md-12 col-lg-12 d-flex">
						<div class="card flex">
							<div class="card-body">
								<p class="text-muted"><code class="highlighter-rouge"><?php self::__('refresh_the_page_if_the_person_does_not_appear'); ?></code></p>
								<form role="form" action="" onsubmit="return false;">
									<div class="form-group">
										<label class="text-muted"><?php self::__('person'); ?></label>
										<select class="form-control" name="poke__person">
											<option disabled selected><?php self::__('select_contact'); ?></option>
											<?php foreach ($client_list as $key) {
												if ($key['client_type'] == 0) {
													echo '<option value="' . $key['clid'] . '">(' . $key['clid'] . ') ' . $key['client_nickname'] . '</option>';
												}
											} ?>
										</select>
									</div>
									<div class="form-group">
										<label class="text-muted"><?php self::__('message'); ?></label>
										<textarea class="form-control" name="poke__message" placeholder="<?php self::__('enter_message'); ?>"></textarea>
									</div>
									<div class="text-center">
										<button onclick="PokeSend();" class="btn btn-default badge badge-boxed badge-soft-info waves-effect waves-light" type="submit"><?php self::__('send'); ?></button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="TeamSpeak_move" role="tabpanel" aria-labelledby="TeamSpeak-move-tab">
				<div class="row row-sm sr">
					<div class="col-md-12 col-lg-12 d-flex">
						<div class="card flex">
							<div class="card-body">
								<p class="text-muted"><code class="highlighter-rouge"><?php self::__('refresh_the_page_if_the_person_does_not_appear'); ?></code><br><code class="highlighter-rouge"><?php self::__('refresh_the_page_while_the_channel_is_not_visible'); ?></code></p>
								<form role="form" action="" onsubmit="return false;">
									<div class="form-group">
										<label class="text-muted"><?php self::__('person'); ?></label>
										<select class="form-control" name="move__person">
											<option disabled selected><?php self::__('select_contact'); ?></option>
											<?php foreach ($client_list as $key) {
												if ($key['client_type'] == 0) {
													echo '<option value="' . $key['clid'] . '">(' . $key['clid'] . ') ' . $key['client_nickname'] . '</option>';
												}
											} ?>
										</select>
									</div>
									<div class="form-row">
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('channel'); ?></label>
											<select class="form-control" name="move__channel">
												<option disabled selected><?php self::__('select_channel'); ?></option>
												<?php foreach ($channel_List as $key) {
													echo '<option value="' . $key['cid'] . '">(' . $key['cid'] . ') ' . $key['channel_name'] . '</option>';
												} ?>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('channel_password'); ?></label>
											<input type="text" class="form-control" name="move__channel_password" placeholder="<?php self::__('enter_channel_password'); ?>"> 
										</div>
									</div>
									<div class="text-center">
										<button onclick="Move();" class="btn btn-default badge badge-boxed badge-soft-info waves-effect waves-light" type="submit"><?php self::__('move'); ?></button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="TeamSpeak_kick" role="tabpanel" aria-labelledby="TeamSpeak-kick-tab">
				<div class="row row-sm sr">
					<div class="col-md-12 col-lg-12 d-flex">
						<div class="card flex">
							<div class="card-body">
								<p class="text-muted"><code class="highlighter-rouge"><?php self::__('refresh_the_page_if_the_person_does_not_appear'); ?></code></p>
								<form role="form" action="" onsubmit="return false;">
									<div class="form-row">
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('person'); ?></label>
											<select class="form-control" name="kick__person">
												<option disabled selected><?php self::__('select_contact'); ?></option>
												<?php foreach ($client_list as $key) {
													if ($key['client_type'] == 0) {
														echo '<option value="' . $key['clid'] . '">(' . $key['clid'] . ') ' . $key['client_nickname'] . '</option>';
													}
												} ?>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('type'); ?></label>
											<select class="form-control" name="kick__type">
												<option disabled selected><?php self::__('select_type'); ?></option>
												<option value="channel"><?php self::__('channel'); ?></option>
												<option value="server"><?php self::__('server'); ?></option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="text-muted"><?php self::__('message'); ?></label>
										<textarea class="form-control" name="kick__message" placeholder="<?php self::__('enter_message'); ?>"></textarea>
									</div>
									<div class="text-center">
										<button onclick="Kick();" class="btn btn-default badge badge-boxed badge-soft-danger waves-effect waves-light" type="submit"><?php self::__('kick'); ?></button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="TeamSpeak_ban" role="tabpanel" aria-labelledby="TeamSpeak-ban-tab">
				<div class="row row-sm sr">
					<div class="col-md-12">
						<div class="card flex">
							<div class="card-body">
								<p class="text-muted"><code class="highlighter-rouge"><?php self::__('refresh_the_page_if_the_person_does_not_appear'); ?></code><br><code style="color: #00bcd4!important;"><?php self::__('time'); ?></code>: <code class="highlighter-rouge"><?php self::__('calculated_in_seconds_write_0_to_ban_indefinitely'); ?></code></p>
								<form role="form" action="" onsubmit="return false;">
									<div class="form-row">
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('person'); ?></label>
											<select class="form-control" name="ban__person">
												<option disabled selected><?php self::__('select_contact'); ?></option>
												<?php foreach ($client_list as $key) {
													if ($key['client_type'] == 0) {
														echo '<option value="' . $key['clid'] . '">(' . $key['clid'] . ') ' . $key['client_nickname'] . '</option>';
													}
												} ?>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('time'); ?></label>
											<input type="number" class="form-control" name="ban__time" placeholder="<?php self::__('enter_time'); ?>"> 
										</div>
									</div>
									<div class="form-group">
										<label class="text-muted"><?php self::__('message'); ?></label>
										<textarea class="form-control" name="ban__message" placeholder="<?php self::__('enter_message'); ?>"></textarea>
									</div>
									<div class="text-center">
										<button onclick="Ban();" class="btn btn-default badge badge-boxed badge-soft-danger waves-effect waves-light" type="submit"><?php self::__('ban'); ?></button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<table id="datatable_Ban" class="table table-theme table-row v-middle">
						<thead>
							<tr>
								<th><span class="text-muted">#</span></th>
								<th><span class="text-muted"><?php self::__('ip'); ?></span></th>
								<th><span class="text-muted"><?php self::__('name'); ?></span></th>
								<th><span class="text-muted"><?php self::__('time'); ?></span></th>
								<th><span class="text-muted"><?php self::__('invoker'); ?></span></th>
								<th><span class="text-muted"><?php self::__('description'); ?></span></th>
								<th style="cursor: unset;"></th>
							</tr>
						</thead>
						<tbody>
							<?php if ($ban_list2['errors']['0'] && $ban_list2['errors']['0'] == 'ErrorID: 1281 | Message: database empty result set') {
							} else{
								foreach ($ban_list as $key) { ?>
									<tr  data-id="<?php echo $key['banid']; ?>">
										<td class="flex" style="width:10px;"><small class="text-muted"><?php echo $key['banid']; ?></small></td>
										<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo empty($key['ip']) ? '-' : $key['ip']; ?></span></td>
										<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $key['name'] == $key['lastnickname'] ? $key['name'] : empty($key['name']) ? $key['lastnickname'] : '-'; ?></span></td>
										<td class="flex"><span class="item-date d-sm-block text-sm"><?php echo $key['duration'] == '0' ? self::__('permanent') : date('d.m.Y H:i:s', $key['created'] + $key['duration']); ?></span></td>
										<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo $key['invokername']; ?></span></td>
										<td class="flex"><span class="item-amount d-sm-block text-sm"><?php echo empty($key['reason']) ? '-' : $key['reason']; ?></span></td>
										<td class="flex">
											<span class="item-amount d-sm-block text-sm"><button onclick="BanDelete('<?php echo $key['banid']; ?>');" class="btn btn-default badge badge-boxed badge-soft-danger waves-effect waves-light" type="submit"><i data-feather="x"></i></button></span>
										</td>
									</tr>
								<?php }
							} ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="tab-pane fade" id="TeamSpeak_channel" role="tabpanel" aria-labelledby="TeamSpeak-channel-tab">
				<div class="row row-sm sr">
					<div class="col-md-12 col-lg-12 d-flex">
						<div class="card flex">
							<div class="card-body">
								<form role="form" action="" onsubmit="return false;">
									<div class="form-row">
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('type'); ?></label>
											<select class="form-control" name="create_channel__type">
												<option disabled selected><?php self::__('select_type'); ?></option>
												<option value="1">CSPACER (<?php self::__('center'); ?>)</option>
												<option value="2">SPACER (<?php self::__('left'); ?>)</option>
												<option value="3">RSPACER (<?php self::__('right'); ?>)</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label class="text-muted"><?php self::__('name'); ?></label>
											<input type="text" class="form-control" name="create_channel__name" placeholder="<?php self::__('enter_name'); ?>"> 
										</div>
									</div>
									<div class="text-center">
										<button onclick="CreateChannel();" class="btn btn-default badge badge-boxed badge-soft-success waves-effect waves-light" type="submit"><?php self::__('create'); ?></button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
<?php } else{
	echo '<div class="alert icon-custom-alert alert-outline-danger alert-danger-shadow" role="alert"><i data-feather="alert-circle" class="alert-icon"></i><div class="alert-text"><strong>' . self::___('error') . '</strong> ' . self::___('teamspeak_server_not_found') . '</div></div>';
} ?>