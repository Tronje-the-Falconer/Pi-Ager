								<button class="art-button" type="button" onclick="setconfig_blockFunction()"><?php echo _('set general configuration values'); ?></button>
								<script>
									function setconfig_blockFunction() {
										document.getElementById('setconfig').style.display = 'block';
									}
									function setconfig_noneFunction() {
										document.getElementById('setconfig').style.display = 'none';
									}
								</script>
								<p id="setconfig" class="setconfig_p">
									<form method="post">
										<div class="hg_container" >
											<?php echo _('attention! be carful what you do!'); ?>
											<!----------------------------------------------------------------------------------------Sensortyp-->
											<table style="width: 100%;" class="miniature_writing">
												<tr>
													<td class="td_png_icon"><h3><?php echo _('sensortype'); ?></h3><img src="images/sensortype.png" alt=""><br><button class="art-button" type="button" onclick="help_sensortype_blockFunction()"><?php echo _('help'); ?></button>
													</td>
													<td style=" text-align: left; padding-left: 20px;">
														<input type="radio" name="sensortype" value="1" <?php echo $checked_sens_1; ?>/><label> DHT11</label><br>
														<input type="radio" name="sensortype" value="2" <?php echo $checked_sens_2; ?>/><label> DHT22</label><br>
														<input type="radio" name="sensortype" value="3" <?php echo $checked_sens_3; ?>/><label> SHT</label><br>
														<br>
													</td>
													
												</tr>
											</table>
											<script>
												function help_sensortype_blockFunction() {
													document.getElementById('help_sensortype').style.display = 'block';
												}
												function help_sensortype_noneFunction() {
													document.getElementById('help_sensortype').style.display = 'none';
												}
											</script>
											<p id="help_sensortype" class="help_p">
												<?php  echo '<b>'._('sensortype').':</b> '._('connect your sensor according to instructions and select the right type.');
												 echo '<br><br>'; ?>
												<button class="art-button" type="button" onclick="help_sensortype_noneFunction()"><?php echo _('close'); ?></button>
											</p>
											
											<!----------------------------------------------------------------------------------------Hysterese-->
											<table style="width: 100%;" class="miniature_writing">
												<tr>
													<td class="td_png_icon"><h3><?php echo _('sensortype'); ?></h3><img src="images/sensortype.png" alt=""><br><button class="art-button" type="button" onclick="help_sensortype_blockFunction()"><?php echo _('help'); ?></button>
													</td>
													<td style=" text-align: left; padding-left: 20px;">
														<input type="radio" name="sensortype" value="1" <?php echo $checked_sens_1; ?>/><label> DHT11</label><br>
														<input type="radio" name="sensortype" value="2" <?php echo $checked_sens_2; ?>/><label> DHT22</label><br>
														<input type="radio" name="sensortype" value="3" <?php echo $checked_sens_3; ?>/><label> SHT</label><br>
														<br>
													</td>
													
												</tr>
											</table>
											<script>
												function help_sensortype_blockFunction() {
													document.getElementById('help_sensortype').style.display = 'block';
												}
												function help_sensortype_noneFunction() {
													document.getElementById('help_sensortype').style.display = 'none';
												}
											</script>
											<p id="help_sensortype" class="help_p">
												<?php  echo '<b>'._('sensortype').':</b> '._('connect your sensor according to instructions and select the right type.');
												 echo '<br><br>'; ?>
												<button class="art-button" type="button" onclick="help_sensortype_noneFunction()"><?php echo _('close'); ?></button>
											</p>
										</div>
										<td class="td_submitbutton">
											<input class="art-button" type="submit" value="<?php echo _('save'); ?>" />
										</td>
									</form>
									<button class="art-button" type="button" onclick="setconfig_noneFunction()"><?php echo _('hide'); ?></button>
								</p>
