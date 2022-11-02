<?php
$validation = \Config\Services::validation();
?>
<?php echo form_open_multipart('', 'id="form-proceeding"'); ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title"><?php echo $heading_title; ?></h3>
                    <div class="block-options">
                        <button type="submit" form="form-proceeding" class="btn btn-primary">Save</button>
                        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-primary">Cancel</a>
                    </div>
                </div>
                <div class="block-content">
                    <ul class="nav nav-tabs tabs" role="tablist">
                        <li class="nav-item tab">
                            <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block">General</span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link" id="site-tab" data-toggle="tab" href="#site" role="tab" aria-controls="site" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block">Site</span>
                            </a>
                        </li>

                        <li class="nav-item tab">
                            <a class="nav-link" id="site-account" data-toggle="tab" href="#account" role="tab" aria-controls="account" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block">Account</span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link" id="site-social" data-toggle="tab" href="#social" role="tab" aria-controls="social" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block">Social</span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link" id="site-appearance" data-toggle="tab" href="#appearance" role="tab" aria-controls="appearance" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block">Appreance</span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link" id="site-ftp" data-toggle="tab" href="#ftp" role="tab" aria-controls="ftp" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block">FTP</span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link" id="site-mail" data-toggle="tab" href="#mail" role="tab" aria-controls="mail" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block">Mail</span>
                            </a>
                        </li>
                        <li class="nav-item tab">
                            <a class="nav-link" id="site-server" data-toggle="tab" href="#server" role="tab" aria-controls="server" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-dashboard fa-lg"></i></span>
                                <span class="d-none d-sm-block">Server</span>
                            </a>
                        </li>
                    </ul>
					<div class="tab-content">
						<div class="tab-pane show active" id="general" role="tabpanel" aria-labelledby="general-tab">
							<div class="form-group row required">
								<label class="col-md-2 control-label" for="input-title">Site Title</label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_site_title', 'id' => 'config_site_title', 'placeholder'=>lang('Setting.entry_title'),'value' => set_value('config_site_title', $config_site_title))); ?>
									<div class="invalid-feedback animated fadeInDown"><?= $validation->getError('config_site_title'); ?></div>

                                </div>
							</div>
							<div class="form-group row required">
								<label class="col-md-2 control-label" for="input-tagline"><?php echo lang('Setting.entry_tagline'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_site_tagline', 'id' => 'config_site_tagline', 'placeholder'=>lang('Setting.entry_tagline'),'value' => set_value('config_site_tagline', $config_site_tagline))); ?>
									<div class="invalid-feedback animated fadeInDown"><?= $validation->getError('config_site_tagline'); ?></div>
                                </div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 control-label" for="input-image"><?php echo lang('Setting.entry_logo'); ?></label>
								<div class="col-sm-2">
									<div class="fileinput">
										<div class="thumb file-browse">
											<img src="<?php echo $thumb_logo; ?>" alt="" id="thumb_logo" />
											<input type="hidden" name="config_site_logo" value="<?php echo $config_site_logo?>" id="site_logo" />
										</div>
										<div class="btn-group" role="group">
											<a class="btn btn-primary btn-xs" onclick="image_upload('site_logo','thumb_logo')"><?php echo lang('Setting.text_clear'); ?></a>
										</div>
									</div>

								</div>
									<div class="fileinput">
										<div class="thumb file-browse">
											<img src="<?php echo $thumb_icon; ?>" alt="" id="thumb_icon" />
											<input type="hidden" name="config_site_icon" value="<?php echo $config_site_icon?>" id="site_icon" />
										</div>
										<div class="btn-group" role="group">
											<a class="btn btn-primary btn-xs" onclick="image_upload('site_icon','thumb_icon')"><?php echo lang('Setting.text_image'); ?></a>
											<a class="btn btn-danger btn-xs" onclick="$('#thumb_icon').attr('src', '<?php echo lang('Setting.no_image'); ?>'); $('#site_icon').attr('value', '');"><?php echo lang('Setting.text_clear'); ?></a>
										</div>
									</div>
								</div>
							</div>
							
							<div class="form-group row required">
								<label class="col-md-2 control-label" for="input-meta-title"><?php echo lang('Setting.entry_meta_title'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_meta_title', 'id' => 'config_meta_title', 'placeholder'=>lang('Setting.entry_meta_title'),'value' => set_value('config_meta_title', $config_meta_title))); ?>
									<div class="invalid-feedback animated fadeInDown"><?= $validation->getError('config_meta_title'); ?></div>
                                </div>
							</div>
							<div class="form-group row">
								<div class="col-md-10">
									<?php echo form_textarea(array('class'=>'form-control','name'=>'config_meta_description', 'id'=>'config_meta_description', 'style'=>'height: 100px;','value'=>set_value('config_meta_description',$config_meta_description))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="input-meta-keywords"><?php echo lang('Setting.entry_meta_keyword'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_meta_keywords', 'id' => 'config_meta_keywords', 'placeholder'=>lang('Setting.entry_meta_keyword'),'value' => set_value('config_meta_keywords', $config_meta_keywords))); ?>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="site" role="tabpanel" aria-labelledby="site-tab">
							<div class="form-group row required">
								<label class="col-md-2 control-label" for="site_owner"><?php echo lang('Setting.entry_site_owner'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_site_owner', 'id' => 'config_site_owner', 'placeholder'=>lang('Setting.entry_site_owner'),'value' => set_value('config_site_owner', $config_site_owner))); ?>
									<div class="invalid-feedback animated fadeInDown"><?= $validation->getError('config_site_owner'); ?></div>
                                </div>
							</div>
							
							<div class="form-group row required">
								<label class="col-md-2 control-label" for="input-meta-description"><?php echo lang('Setting.entry_address'); ?></label>
								<div class="col-md-10">
									<?php echo form_textarea(array('class'=>'form-control','name'=>'config_address', 'id'=>'config_address', 'style'=>'height: 100px;','value'=>set_value('config_address',$config_address))); ?>
                                    <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('config_address'); ?></div>

                                </div>
							</div>

							<div class="form-group row required">
								<label class="col-md-2 control-label" for="input-meta-keywords"><?php echo lang('Setting.entry_state'); ?></label>
								<div class="col-md-10">
									<?php echo form_dropdown('config_state_id', array(), set_value('config_state_id', $config_state_id),"id='config_state_id' class='form-control select2'"); ?>
									<div class="invalid-feedback animated fadeInDown"><?= $validation->getError('config_state_id'); ?></div>

                                </div>
							</div>
							<div class="form-group row required">
								<label class="col-md-2 control-label" for="input-meta-keywords"><?php echo lang('Setting.entry_email'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_email', 'id' => 'config_email', 'placeholder'=>lang('Setting.entry_email'),'value' => set_value('config_email', $config_email))); ?>
                                    <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('config_email'); ?></div>

                                </div>
							</div>
							<div class="form-group row required">
								<label class="col-md-2 control-label" for="input-meta-keywords"><?php echo lang('Setting.entry_telephone'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_telephone', 'id' => 'config_telephone', 'placeholder'=>lang('Setting.entry_telephone'),'value' => set_value('config_telephone', $config_telephone))); ?>
									<div class="invalid-feedback animated fadeInDown"><?= $validation->getError('config_telephone'); ?></div>

                                </div>
							</div>
						</div>

						


						<div class="tab-pane" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
							<div class="form-group row">
								<label class="col-md-2 control-label" for="site_homepage"><?php echo lang('Setting.entry_site_homepage'); ?></label>
								<div class="col-md-10">
									<?php  echo form_dropdown('config_site_homepage', option_array_value($pages, 'id', 'title'), set_value('config_site_homepage',$config_site_homepage),array('class'=>'form-control select2','id' => 'config_site_homepage') ); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="front_theme"><?php echo lang('Setting.entry_front_theme'); ?></label>
								<div class="col-md-10">
									<?php  echo form_dropdown('config_front_theme', $front_themes, set_value('config_front_theme', $config_front_theme), array('class'=>'form-control select2','id' => 'config_front_theme')); ?>
									<input type="hidden" name="config_admin_theme" value="default">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="front_template"><?php echo lang('Setting.entry_front_template'); ?></label>
								<div class="col-md-10">
									<?php  echo form_dropdown('config_front_template', $front_templates, set_value('config_front_template', $config_front_template), array('class'=>'form-control select2','id' => 'config_front_template')); ?>
									<input type="hidden" name="config_admin_template" value="default">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="header_layout"><?php echo lang('Setting.entry_header_layout'); ?></label>
								<div class="col-md-10">
									<?php  echo form_dropdown('config_header_layout', array(''=>'None','image'=>'Image','banner'=>'Banner','slider' => 'Slider'), set_value('config_header_layout',$config_header_layout),array('class'=>'form-control select2','id' => 'config_header_layout')); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="hearder_image"><?php echo lang('Setting.entry_header_image'); ?></label>
								<div class="col-md-10">
									<div class="fileinput">
										<div class="thumbnail" style="width: 130px; height: 130px;">
											<img src="<?php echo $thumb_header_image; ?>" alt="" id="thumb_header_image" />
											<input type="hidden" name="config_header_image" value="<?php echo $config_header_image?>" id="header_image" />
										</div>
										<div>
											<a class="btn btn-primary btn-xs" onclick="image_upload('header_image','thumb_header_image')">Select Image</a>
											<a class="btn btn-danger btn-xs" onclick="$('#thumb_header_image').attr('src', '<?php echo $no_image; ?>'); $('#header_image').attr('value', '');">Clear</a>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="header_banner"><?php echo lang('Setting.entry_header_banner'); ?></label>
								<div class="col-md-10">
									<?php  echo form_dropdown('config_header_banner', option_array_value($banners, 'id', 'title'), set_value('config_header_banner',$config_header_banner),array('class'=>'form-control select2','id' => 'config_header_banner')); ?>
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-md-2 control-label" for="background_image"><?php echo lang('Setting.entry_background_image'); ?></label>
								<div class="col-md-3">
									<div class="fileinput">
										<div class="thumbnail" style="width: 130px; height: 130px;">
											<img src="<?php echo $thumb_background_image; ?>" alt="" id="thumb_background_image" />
											<input type="hidden" name="config_background_image" value="<?php echo $config_background_image?>" id="background_image" />
										</div>
										<div>
											<a class="btn btn-primary btn-xs" onclick="image_upload('background_image','thumb_background_image')">Select Image</a>
											<a class="btn btn-danger btn-xs" onclick="$('#thumb_background_image').attr('src', '<?php echo $no_image; ?>'); $('#background_image').attr('value', '');">Clear</a>
										</div>
									</div>
								</div>
								<div class="col-md-7">
									<label class="control-label" for="background_position"><?php echo lang('Setting.entry_background_position'); ?></label>
									<div class="fields_wrapper">
										<div class="radio radio-info radio-inline">
											<?php echo form_radio(array('name' => 'config_background_position', 'value' => 'left','checked' => ($config_background_position == 'left' ? true : false))); ?>
											<label> Left </label>
										</div>
										<div class="radio radio-info radio-inline">
											<?php echo form_radio(array('name' => 'config_background_position', 'value' => 'center','checked' => ($config_background_position == 'center' ? true : false) )); ?>
											<label> Center </label>
										</div>
										<div class="radio radio-info radio-inline">
											<?php echo form_radio(array('class'=>'icheck','name' => 'config_background_position', 'value' => 'right','checked' => ($config_background_position == 'right' ? true : false))); ?>
											<label> Right </label>
										</div>
									</div>
									<label class="control-label" for="background_repeat"><?php echo lang('Setting.entry_background_repeat'); ?></label>
									<div class="fields_wrapper">
										<div class="radio radio-info radio-inline">
											<?php echo form_radio(array('name' => 'config_background_repeat', 'value' => 'no-repeat','checked' => ($config_background_repeat == 'no-repeat' ? true : false))); ?> 
											<label> No Repeat </label>
										</div>
										<div class="radio radio-info radio-inline">
											<?php echo form_radio(array('name' => 'config_background_repeat', 'value' => 'repeat','checked' => ($config_background_repeat == 'repeat' ? true : false) )); ?> 
											<label> Tile </label>
										</div>
										<div class="radio radio-info radio-inline">
											<?php echo form_radio(array('name' => 'config_background_repeat', 'value' => 'repeat-x','checked' => ($config_background_repeat == 'repeat-x' ? true : false))); ?> 
											<label>Tile Horizontally</label>                                   
										</div>
										<div class="radio radio-info radio-inline">
											<?php echo form_radio(array('class'=>'icheck','name' => 'config_background_repeat', 'value' => 'repeat-y','checked' => ($config_background_repeat == 'repeat-y' ? true : false))); ?>                                  
											<label>Tile Vertically</label>
										</div>
									</div>
									<label class="control-label" for="background_attachment"><?php echo lang('Setting.entry_background_attachment'); ?></label>
									<div class="fields_wrapper">	
										<div class="radio radio-info radio-inline">
											<?php echo form_radio(array('name' => 'config_background_attachment', 'value' => 'scroll','checked' => ($config_background_attachment == 'scroll' ? true : false))); ?> 
											<label>Scroll</label>
										</div>
										<div class="radio radio-info radio-inline">
											<?php echo form_radio(array('name' => 'config_background_attachment', 'value' => 'fixed','checked' => ($config_background_attachment == 'fixed' ? true : false) )); ?>                               
											<label>Fixed</label>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="background_color"><?php echo lang('Setting.entry_background_color'); ?></label>
								<div class="col-md-4">
									
									<div class="input-group colorpicker-component">
										<?php echo form_input(array('class'=>'form-control','name' => 'config_background_color', 'id' => 'config_background_color', 'value' => set_value('config_background_color', $config_background_color))); ?>
										<span class="input-group-addon"><i></i></span>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="text_color"><?php echo lang('Setting.entry_text_color'); ?></label>
								<div class="col-md-4">
									<div class="input-group colorpicker-component">
										<?php echo form_input(array('class'=>'form-control','name' => 'config_text_color', 'id' => 'config_text_color', 'value' => set_value('config_text_color', $config_text_color))); ?>
										<span class="input-group-addon"><i></i></span>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="ftp" role="tabpanel" aria-labelledby="ftp-tab">
							<div class="form-group row">
								<label class="col-md-2 control-label" for="ftp_host"><?php echo lang('Setting.entry_ftp_host'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_ftp_host', 'id' => 'config_ftp_host', 'value' => set_value('config_ftp_host', $config_ftp_host))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="ftp_port"><?php echo lang('Setting.entry_ftp_port'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_ftp_port', 'id' => 'config_ftp_port', 'value' => set_value('config_ftp_port', $config_ftp_port))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="ftp_username"><?php echo lang('Setting.entry_ftp_username'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_ftp_username', 'id' => 'config_ftp_username', 'value' => set_value('config_ftp_username', $config_ftp_username))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="ftp_password"><?php echo lang('Setting.entry_ftp_password'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_ftp_password', 'id' => 'config_ftp_password', 'value' => set_value('config_ftp_password', $config_ftp_password))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="ftp_root"><?php echo lang('Setting.entry_ftp_root'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_ftp_root', 'id' => 'config_ftp_root', 'value' => set_value('config_ftp_root', $config_ftp_root))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="ftp_enable"><?php echo lang('Setting.entry_ftp_enable'); ?></label>
								<div class="col-md-10">
									<div class="radio radio-info radio-inline">
										<?php echo form_radio(array('name' => 'config_ftp_enable', 'value' => 'Yes','checked' => ($config_ftp_enable == 'Yes' ? true : false))); ?>
										<label>Yes</label>
									</div>
									<div class="radio radio-info radio-inline">
										<?php echo form_radio(array('name' => 'config_ftp_enable', 'value' => 'No','checked' => ($config_ftp_enable == 'No' ? true : false) )); ?>
										<label>No</label>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="mail" role="tabpanel" aria-labelledby="mail-tab">
							<div class="form-group row">
								<label class="col-md-2 control-label" for="mail_protocol"><?php echo lang('Setting.entry_mail_protocol'); ?></label>
								<div class="col-md-10">
									<?php  echo form_dropdown('config_mail_protocol', array('mail'=>'Mail','smtp' => 'SMTP'), set_value('config_mail_protocol',$config_mail_protocol),array('class'=>'form-control select2','id' => 'config_mail_protocol')); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="mail_parameter"><?php echo lang('Setting.entry_mail_parameter'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_mail_parameter', 'id' => 'config_mail_parameter', 'value' => set_value('config_mail_parameter', $config_mail_parameter))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="smtp_host"><?php echo lang('Setting.entry_smtp_host'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_smtp_host', 'id' => 'config_smtp_host', 'value' => set_value('config_smtp_host', $config_smtp_host))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="smtp_username"><?php echo lang('Setting.entry_smtp_username'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_smtp_username', 'id' => 'config_smtp_username', 'value' => set_value('config_smtp_username', $config_smtp_username))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="smtp_password"><?php echo lang('Setting.entry_smtp_password'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_smtp_password', 'id' => 'config_smtp_password', 'value' => set_value('config_smtp_password', $config_smtp_password))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="smtp_port"><?php echo lang('Setting.entry_smtp_port'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_smtp_port', 'id' => 'config_smtp_port', 'value' => set_value('config_smtp_port', $config_smtp_port))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="smtp_timeout"><?php echo lang('Setting.entry_smtp_timeout'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_smtp_timeout', 'id' => 'config_smtp_timeout', 'value' => set_value('config_smtp_timeout', $config_smtp_timeout))); ?>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="server" role="tabpanel" aria-labelledby="server-tab">
							<div class="form-group row">
								<label class="col-md-2 control-label" for="ssl"><span data-toggle="tooltip" title="<?php echo lang('Setting.help_ssl'); ?>"><?php echo lang('Setting.entry_ssl'); ?></span></label>
								<div class="col-md-10">
									<div class="radio radio-info radio-inline">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_ssl', 'value' => 'Yes','checked' => ($config_ssl == 'Yes' ? true : false))); ?>
										<label> Yes </label>
									</div>
									<div class="radio radio-info radio-inline">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_ssl', 'value' => 'No','checked' => ($config_ssl == 'No' ? true : false) )); ?>
										<label> No </label>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="robots"><span data-toggle="tooltip" title="<?php echo lang('Setting.help_robots'); ?>"><?php echo lang('Setting.entry_robots'); ?></span></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control tags','name' => 'config_robots', 'id' => 'config_robots', 'value' => set_value('config_robots', $config_robots))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="time_zone"><?php echo lang('Setting.entry_time_zone'); ?></label>
								<div class="col-md-10">
									<select class="form-control select2" name="config_time_zone" id="config_time_zone">
										<option value="0">Please, select timezone</option>
										<?php foreach($timezone as $optgroup=>$zone){?>
											<optgroup label="<?=$optgroup?>">
												<?php foreach($zone as $key=>$value){?>
													<option value="<?=$key?>" <?=($config_time_zone==$key)?"selected='selected'":""?>><?=$value?></option>
												<?}?>
											</optgroup>
										<?}?>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="date_format"><?php echo lang('Setting.entry_date_format'); ?></label>
								<div class="col-md-10">
									<div class="radio radio-success">
										<?php echo form_radio(array('name' => 'config_date_format', 'value' => 'F j, Y','checked' => ($config_date_format == 'F j, Y' ? true : false))); ?>
										<label for="radio1"><?=date("F j, Y")?></label>
										<code class="label label-default">F j, Y</code>
									</div>
									<div class="radio radio-success">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_date_format', 'value' => 'Y/m/d','checked' => ($config_date_format == 'Y/m/d' ? true : false))); ?>
										<label for="radio1"><?=date("Y/m/d")?></label>
										<code class="label label-default">Y/m/d</code>
									</div>
									<div class="radio radio-success">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_date_format', 'value' => 'm/d/Y','checked' => ($config_date_format == 'm/d/Y' ? true : false))); ?>
										<label for="radio1"><?=date("m/d/Y")?></label>
										<code class="label label-default">m/d/Y</code>
									</div>
									<div class="radio radio-success">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_date_format', 'value' => 'd/m/Y','checked' => ($config_date_format == 'd/m/Y' ? true : false))); ?>
										<label for="radio1"><?=date("d/m/Y")?></label>
										<code class="label label-default">d/m/Y</code>
									</div>
									<div class="radio radio-success">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_date_format', 'value' => 'custom','checked' => ($config_date_format == 'custom' ? true : false))); ?>
										<label for="radio1">Custom</label>
										<?php echo form_input(array('name' => 'config_date_format_custom', 'id' => 'config_date_format_custom', 'value' => set_value('config_date_format_custom', $config_date_format_custom))); ?> <?=date($config_date_format_custom)?>
									</div>
									
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="time_format"><?php echo lang('Setting.entry_time_format'); ?></label>
								<div class="col-md-10">
									<div class="radio radio-success">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_time_format', 'value' => 'g:i a','checked' =>  ($config_time_format == 'g:i a' ? true : false))); ?>
										<label for="radio1"><?=date("g:i a")?></label>
										<code class="label label-default">g:i a</code>
									</div>
									<div class="radio radio-success">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_time_format', 'value' => 'g:i A','checked' =>  ($config_time_format == 'g:i A' ? true : false))); ?>
										<label for="radio1"><?=date("g:i A")?></label>
										<code class="label label-default">g:i A</code>
									</div>
									<div class="radio radio-success">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_time_format', 'value' => 'H:i','checked' =>  ($config_time_format == 'H:i' ? true : false))); ?>
										<label for="radio1"><?=date("H:i")?></label>
										<code class="label label-default">H:i</code>
									</div>
									<div class="radio radio-success">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_time_format', 'value' => 'custom','checked' =>  ($config_time_format == 'custom' ? true : false))); ?>
										<label>Custom</label>
										<?php echo form_input(array('name' => 'config_time_format_custom', 'id' => 'config_time_format_custom', 'value' => set_value('config_time_format_custom', $config_time_format_custom))); ?> <?=date($config_time_format_custom)?>
									</div>
								</div>
							</div>
							<div class="form-group row required">
								<label class="col-md-2 control-label" for="pagination_limit_front"><?php echo lang('Setting.entry_pagination_limit_front'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_pagination_limit_front', 'id' => 'config_pagination_limit_front', 'value' => set_value('config_pagination_limit_front', $config_pagination_limit_front))); ?>
									<div class="invalid-feedback animated fadeInDown"><?= $validation->getError('config_pagination_limit_front'); ?></div>

                                </div>
							</div>
							<div class="form-group row required">
								<label class="col-md-2 control-label" for="pagination_limit_admin"><?php echo lang('Setting.entry_pagination_limit_admin'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_pagination_limit_admin', 'id' => 'config_pagination_limit_admin', 'value' => set_value('config_pagination_limit_admin', $config_pagination_limit_admin))); ?>
                                    <div class="invalid-feedback animated fadeInDown"><?= $validation->getError('config_pagination_limit_admin'); ?></div>
                                </div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="seo_url"><span data-toggle="tooltip" title="<?php echo lang('Setting.help_seo_url'); ?>"><?php echo lang('Setting.entry_seo_url'); ?></span></label>
								<div class="col-md-10">
									<div class="radio radio-info radio-inline">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_seo_url', 'value' => 'Yes','checked' => ($config_seo_url == 'Yes' ? true : false))); ?>
										<label> Yes </label>
									</div>
									<div class="radio radio-info radio-inline">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_seo_url', 'value' => 'No','checked' => ($config_seo_url == 'No' ? true : false) )); ?>
										<label> No </label>
									</div>                             
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="max_file_size"><?php echo lang('Setting.entry_file_max_size'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_max_file_size', 'id' => 'config_max_file_size', 'value' => set_value('config_max_file_size', $config_max_file_size))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="file_extensions"><?php echo lang('Setting.entry_file_extensions'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control tags','name'=>'config_file_extensions', 'id'=>'config_file_extensions', 'value'=>set_value('config_file_extensions',$config_file_extensions))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="file_mimetypes"><?php echo lang('Setting.entry_file_mimetypes'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control tags','name'=>'config_file_mimetypes', 'id'=>'config_file_mimetypes','value'=>set_value('config_file_mimetypes',$config_file_mimetypes))); ?>		
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="maintenance_mode"><?php echo lang('Setting.entry_maintenance_mode'); ?></label>
								<div class="col-md-10">
									<div class="radio radio-info radio-inline">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_maintenance_mode', 'value' => '1','checked' => ($config_maintenance_mode == '1' ? true : false))); ?>
										<label> Yes </label>
									</div>
									<div class="radio radio-info radio-inline">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_maintenance_mode', 'value' => '0','checked' => ($config_maintenance_mode == '0' ? true : false))); ?>
										<label> No </label>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="compression_level"><?php echo lang('Setting.entry_compression_level'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_compression_level', 'id' => 'config_compression_level', 'value' => set_value('config_compression_level', $config_compression_level))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="encryption_key"><?php echo lang('Setting.entry_encryption_key'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_encryption_key', 'id' => 'config_encryption_key', 'value' => set_value('config_encryption_key', $config_encryption_key))); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="display_error"><?php echo lang('Setting.entry_display_error'); ?></label>
								<div class="col-md-10">
									<div class="radio radio-info radio-inline">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_display_error', 'value' => 'Yes','checked' => ($config_display_error == 'Yes' ? true : false))); ?>
										<label> Yes </label>
									</div>
									<div class="radio radio-info radio-inline">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_display_error', 'value' => 'No','checked' => ($config_display_error == 'No' ? true : false))); ?>
										<label> No </label>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="log_error"><?php echo lang('Setting.entry_log_error'); ?></label>
								<div class="col-md-10">
									<div class="radio radio-info radio-inline">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_log_error', 'value' => 'Yes','checked' => ($config_log_error == 'Yes' ? true : false))); ?>
										<label> Yes </label>
									</div>
									<div class="radio radio-info radio-inline">
										<?php echo form_radio(array('class'=>'icheck','name' => 'config_log_error', 'value' => 'No','checked' => ($config_log_error == 'No' ? true : false))); ?>
										<label> No </label>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="error_log_filename"><?php echo lang('Setting.entry_error_log_filename'); ?></label>
								<div class="col-md-10">
									<?php echo form_input(array('class'=>'form-control','name' => 'config_error_log_filename', 'id' => 'config_error_log_filename', 'value' => set_value('config_error_log_filename', $config_error_log_filename))); ?>
								</div>
							</div>
						</div>
					</div>
			    </div>
		    </div>
	    </div>
    </div>
<?php echo form_close(); ?>
<?php js_start(); ?>
<script type="text/javascript"><!--
    $(document).ready(function() {
		$('select[name=\'config_country_id\']').bind('change', function() {
			$.ajax({
				url: '<?php echo admin_url("localisation/country/state"); ?>/' + this.value,
				dataType: 'json',
				beforeSend: function() {
					$('select[name=\'config_country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
				},		
				complete: function() {
					$('.wait').remove();
				},			
				success: function(json) {
					
					html = '<option value="">Select State</option>';
			
					if (json['state'] != '') {
						for (i = 0; i < json['state'].length; i++) {
							html += '<option value="' + json['state'][i]['id'] + '"';
							
							if (json['state'][i]['id'] == '<?php echo $config_state_id; ?>') {
								html += ' selected="selected"';
							}
			
							html += '>' + json['state'][i]['name'] + '</option>';
						}
					} else {
						html += '<option value="0" selected="selected">Select State</option>';
					}
					
					$('select[name=\'config_state_id\']').html(html);
					//$('select[name=\'config_state_id\']').select2();   
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});
		$('select[name=\'config_country_id\']').trigger('change');
    });
	
	function image_upload(field, thumb) {
		window.KCFinder = {
			callBack: function(url) {
				
				window.KCFinder = null;
				var lastSlash = url.lastIndexOf("uploads/");
				
				var fileName=url.substring(lastSlash+8);
				url=url.replace("images", ".thumbs/images"); 
				$('#'+thumb).attr('src', url);
				$('#'+field).attr('value', fileName);
				$.colorbox.close();
			}
		};
		$.colorbox({href:BASE_URL+"storage/plugins/kcfinder/browse.php?type=images",width:"850px", height:"550px", iframe:true,title:"Image Manager"});	
	};
//--></script>
<?php js_end(); ?>