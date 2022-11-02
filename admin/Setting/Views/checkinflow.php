<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title float-left"><?php echo $heading_title; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-danger" form="form-setting"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a>
				</div>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart(null,array('class' => 'form-horizontal', 'id' => 'form-setting','role'=>'form')); ?>
					<ul class="nav nav-tabs tabs" role="tablist">
                        <li class="nav-item tab">
                            <a class="nav-link active" id="phone-tab" data-toggle="tab" href="#phone" role="tab" aria-controls="phone" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block"><?php echo $tab_phone; ?></span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link" id="address-tab" data-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block"><?php echo $tab_address; ?></span>
                            </a>
                        </li>
								<li class="nav-item tab">
                            <a class="nav-link" id="sign-tab" data-toggle="tab" href="#sign" role="tab" aria-controls="sign" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block"><?php echo $tab_sign; ?></span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link" id="card-tab" data-toggle="tab" href="#card" role="tab" aria-controls="card" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block"><?php echo $tab_card; ?></span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link" id="photo-tab" data-toggle="tab" href="#photo" role="tab" aria-controls="photo" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block"><?php echo $tab_photo; ?></span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link" id="notification-tab" data-toggle="tab" href="#notification" role="tab" aria-controls="notification" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block"><?php echo $tab_notification; ?></span>
                            </a>
                        </li>
                    </ul>
					<div class="tab-content">
						<div class="tab-pane show active" id="phone" role="tabpanel" aria-labelledby="phone-tab">					
							<div class="form-group row">
                                <label for="input-phone" class="col-sm-3 control-label">Display Text</label>
                                <div class="col-sm-9">
                                 	<?php echo form_input(array('class'=>'form-control','name' => 'checkin_phone_label', 'id' => 'checkin_phone_label', 'placeholder'=>"Display Text",'value' => set_value('checkin_phone_label', $checkin_phone_label))); ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-phone" class="col-sm-3 control-label">OTP Verification</label>
                                <div class="col-sm-9">
                                 	<?php echo form_checkbox(array('name' => 'checkin_phone_otp', 'value' => 'true','checked' => ($checkin_phone_otp == 'true' ? true : false))); ?>	
                                 </div>
                            </div>
						</div>
						<div class="tab-pane" id="address" role="tabpanel" aria-labelledby="address-tab">					
							<div class="form-group row">
                                <label for="input-phone" class="col-sm-3 control-label">Host<small id="passwordHelpBlock" class="form-text text-muted">
  										Enable or disable host selection screen
  									</small></label>
                                <div class="col-sm-9">
                                 	<?php echo form_input(array('class'=>'form-control','name' => 'checkin_address_host', 'id' => 'checkin_address_host', 'placeholder'=>"Display Text",'value' => set_value('checkin_address_host', $checkin_address_host))); ?>
                                </div>
                            </div>
                            <p>Address Fields</p>
							<div class="table-responsive">
							  	<table id="address-table" class="table table-striped table-bordered table-hover">
								  	<thead>
					                    <tr>
					                      <td class="text-left">Field Name</td>
					                      <td class="text-right">Field Type</td>
					                      <td class="text-right">Value</td>
					                      <td class="text-right">Required</td>
					                      <td></td>
					                    </tr>
	                  				</thead>
                  				 	<tbody>
                    				<?php $address_row=0;?>
										<?php $value_row=0;?>
                    				<?php foreach($checkin_address_field as $address){?>
                    					<tr id="address-row<?php echo $address_row;?>">
												<td><input type="text" name="checkin_address_field[<?php echo $address_row; ?>][name]" value="<?php echo  $address['name'];?>" class="form-control"/></td>
												<td>
													<select name="checkin_address_field[<?php echo $address_row; ?>][type]" class="form-control">
													<?php foreach($fieldTypes as $key=>$value){?>
														<option value="<?php echo $key;?>" <?=($address['type']==$key)?"selected='selected'":""?>><?php echo $value;?></option>
													<?php }?>
													</select>
												</td>
												<td>
													<?php if($address['type']=="text"){?>
													<div class="input-group mb-2" id="value-row<?php echo $address_row; ?><?php echo $value_row; ?>">
														<input type="text" name="checkin_address_field[<?php echo $address_row; ?>][value][]" value="<?php echo  $address['value'][0];?>" class="form-control" />
														<div class="input-group-append">
															<span class="input-group-text"><i class="fa fa-minus-circle"></i></span>
														</div>
													</div>
													<?php }else if($address['type']=="radio" || $address['type']=="checkbox" || $address['type']=="select"){?>
													<?php foreach($address['value'] as $val){?>
														<div class="input-group mb-2" id="value-row<?php echo $address_row; ?><?php echo $value_row; ?>">
															<input type="text" name="checkin_address_field[<?php echo $address_row; ?>][value][]" value="<?php echo $val;?>" class="form-control" />
															<div class="input-group-append">
																<span class="input-group-text" onclick="$('#value-row<?php echo $address_row; ?><?php echo $value_row; ?>').remove();"><i class="fa fa-minus-circle"></i></span>
															</div>
														</div>
													<?php $value_row++;}?>
													<?php }?>
													<button type="button" onclick="addValues(this,<?php echo $address_row; ?>)" data-toggle="tooltip" title="add value" class="btn btn-danger optionbtn">Add Values</button>
												</td>
												<td>
												<?php echo form_checkbox(array('class'=>'icheck','name' => 'checkin_address_field['.$address_row.'][required]', 'value' => 'true','checked' => (isset($address['required']) ? true : false) )); ?>
												</td>
												<td><button type="button" onclick="$('#address-row<?php echo $address_row;?>').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
											</tr>
							  		<?php $address_row++;
							  		}?>
							  		</tbody>
							  		<tfoot>
					                    <tr>
					                      <td colspan="4"></td>
					                      <td class="text-left"><button type="button" onclick="addField();" data-toggle="tooltip" title="Add" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
					                    </tr>
					                </tfoot>
							  	</table>
							</div>
						</div>
						<div class="tab-pane" id="sign" role="tabpanel" aria-labelledby="sign-tab">					
							<div class="form-group row">
								  <label for="input-phone" class="col-sm-3 control-label">Visitors Signature <small class="form-text text-muted">If enabled new visitors should signature </small>
									</label>
								  <div class="col-sm-9">
										<?php echo form_checkbox(array('name' => 'checkin_sign_info', 'value' => 'true','checked' => ($checkin_sign_info == 'true' ? true : false))); ?>	
									</div>
							</div>
						</div>
						<div class="tab-pane" id="card" role="tabpanel" aria-labelledby="card-tab">					
							<div class="form-group row">
                                <label for="input-phone" class="col-sm-3 control-label">Visitors ID Card <small class="form-text text-muted">If enabled new visitors should scan their ID Card </small>
                                	</label>
                                <div class="col-sm-9">
                                 	<?php echo form_checkbox(array('name' => 'checkin_card_info', 'value' => 'true','checked' => ($checkin_card_info == 'true' ? true : false))); ?>	
                                 </div>
                            </div>
						</div>
						<div class="tab-pane" id="photo" role="tabpanel" aria-labelledby="photo-tab">					
							<div class="form-group row">
								  <label for="input-phone" class="col-sm-3 control-label">Visitors photo  <small class="form-text text-muted">If enabled new visitors should take their photos </small></label>
								  <div class="col-sm-9">
										<?php echo form_checkbox(array('name' => 'checkin_photo_info', 'value' => 'true','checked' => ($checkin_photo_info == 'true' ? true : false))); ?>	
									</div>
							 </div>
						</div>
						<div class="tab-pane" id="notification" role="tabpanel" aria-labelledby="notification-tab">					
							<div class="form-group row">
								  <label for="input-phone" class="col-sm-3 control-label">Host Notifications  <small class="form-text text-muted">Enable or disable E-mail and SMS notifications sent to host </small></label>
								  <div class="col-sm-9">
										<?php echo form_checkbox(array('name' => 'checkin_notification_host', 'value' => 'true','checked' => ($checkin_notification_host == 'true' ? true : false))); ?>	
									</div>
							 </div>
							 <div class="form-group row">
								  <label for="input-phone" class="col-sm-3 control-label">Visitor Notifications   <small class="form-text text-muted">Enable or disable SMS notifications sent to visitor </small></label>
								  <div class="col-sm-9">
										<?php echo form_checkbox(array('name' => 'checkin_notification_visitor', 'value' => 'true','checked' => ($checkin_notification_visitor == 'true' ? true : false))); ?>	
									</div>
							 </div>
							 <div class="form-group row">
								  <label for="input-phone" class="col-sm-3 control-label">Visitor SMS Text</label>
								  <div class="col-sm-9">
										<?php echo form_input(array('class'=>'form-control','name' => 'checkin_notification_sms', 'id' => 'checkin_notification_sms', 'placeholder'=>"sms Text",'value' => set_value('checkin_notification_sms', $checkin_notification_sms))); ?>
								  </div>
							 </div>
						</div>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>

<?php js_start(); ?>
<script type="text/javascript"><!--
   var address_row = <?php echo $address_row ;?>;
   var value_row = <?php echo $value_row ;?>;

  	function addField() {
  		value_row = 0;
  		html = '<tr id="address-row' + address_row + '">';
	  	html += '	<td><input type="text" name="checkin_address_field[' + address_row + '][name]" value="" class="form-control" /></td>';
  		html += '  	<td><select name="checkin_address_field[' + address_row + '][type]" class="form-control">';
	    <?php foreach($fieldTypes as $key=>$value){?>
		html += '    	<option value="<?php echo $key;?>"><?php echo $value;?></option>';
	    <?php }?>
		html += '  </select></td>';
		html += '	<td class="moreoption">';
		html += '		<div class="input-group mb-2" id="value-row' + address_row + value_row +'">';
	    html += '   		<input type="text" name="checkin_address_field[' + address_row + '][value][]" value="" class="form-control" />';
	    html += '   		<div class="input-group-append">'
	    html += '       		<span class="input-group-text"><i class="fa fa-minus-circle"></i></span>';
	    html += '    		</div>';
	    html += '		</div>';
	    html += '		<button type="button" onclick="addValues(this,'+address_row+')" data-toggle="tooltip" title="add value" class="btn btn-danger optionbtn">Add Values</button></td>';
  		html += '	<td><input type="checkbox" name="checkin_address_field[' + address_row + '][required]" value="true" class="form-control" /></td>';
		html += '  	<td><button type="button" onclick="$(\'#address-row' + address_row + '\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';

	 	$('#address-table tbody').append(html);
	  	address_row++;
	  	value_row++;
	}

	function addValues(obj,row){row
		sub_html='<div class="input-group mb-2" id="value-row' + row + value_row +'">';
	    sub_html+='   <input type="text" name="checkin_address_field[' + row + '][value][]" value="" class="form-control" />';
	    sub_html+='   <div class="input-group-append">'
	    sub_html+='       <span class="input-group-text" onclick="$(\'#value-row' + row + value_row + '\').remove();"><i class="fa fa-minus-circle"></i></span>';
	    sub_html+='    </div>';
	    sub_html+='</div>';
		$(obj).before(sub_html);
		value_row++;
	}
//--></script>
<?php js_end(); ?>