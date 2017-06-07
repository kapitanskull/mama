<page backtop="120mm" backbottom="10mm" backleft="15mm" backright="15mm">
	
	<page_header>
		<table cellspacing="0">
			<tbody>
				<tr>
					<td style="width: 100%; font-size: 12pt; text-align: center; border: solid 2px #000000;">
						<span style="font-size: 15pt; font-weight: bold;">Motordata Research Consortium Sdn Bhd<br /></span>
						<br />
						Level 13. Heitech Village, Persiaran Kewajipan<br />
						USJ 1, UEP Subang Jaya 47600 Selangor, Malaysia<br />
						Tel: +603 8026 8888 Fax: +603 8024 1052<br />
						www.mrc.com.my<br />
						<br>
						MRC GST NO.: 001853358080
						<br>
						<br>
						PARTS PRICE CHECK (PPC)<br />
						STATEMENT OF ACCOUNT / TAX INVOICE<br />
					</td>
				</tr>
			</tbody>
		</table>
		<br />
			
		<table cellspacing="0">
			<tbody>
				<tr>
					<td style="width: 55%; font-size: 12pt; text-align: left; border: solid 1px #000000;">
						<span>Site ID : <?php echo $system_id ?><br /></span>
						<br />
						<b><?php echo $user_details["company_name"]; ?></b><br />
						<?php echo nl2br($user_details["company_address"]); ?><br />
					
						<?php echo $user_details["company_postcode"] . ", " . $user_details["company_city"];  ?><br />
						<?php echo $state_details["state_name"]; ?><br />
						<br>
						<b>Person-in-charge: </b> <?php echo $user_details["staff_name"]; ?>  <br />
					</td>
					<td style="width: 45%; font-size: 12pt; text-align: center; border: solid 1px #000000; border-collapse: collapse;   padding-right: 0px; padding-left: 0px;">
						Tax Invoice No.: PPC<?php echo date("Y")  . "-" . date("m", strtotime($monthyear)).$system_id; ?><br />
						<br />
						Generated Date: <?php echo date("d/m/Y") ;?>
					</td>
				</tr>
			</tbody>
		</table>
		<br />
		<table cellspacing="0">
			<tbody>
				<tr>
					<td style="width: 30%; border: solid 1px; text-align: left;" > Month: <?php echo (isset($monthyear) AND $monthyear != "" ) ? date("F, Y", strtotime($monthyear)) : ''; ?></td>
					<td style="width: 40%; border: solid 1px; text-align: center;"> page [[page_cu]] of [[page_nb]]</td>
					<td style="width:  30%; border: solid 1px; text-align: left;"></td>
				</tr>
			</tbody>
		</table>
	</page_header>
	
	<table cellspacing="0" style="width: 109%;position: relative; right:0.2%;">
		<thead>
			<tr>
				<th style="text-weight:bold; width:12%; border: solid 1px; text-align: center;" >Date</th>
				<th style="text-weight:bold; width:30%; border: solid 1px; text-align: center;" >Rexcxference</th>
				<th style="text-weight:bold; width:30%; border: solid 1px; text-align: center;" >Description</th>
				<th style="text-weight:bold; width:11%; border: solid 1px; text-align: center;" >Debit</th>
				<th style="text-weight:bold; width:11%; border: solid 1px; text-align: center;" >Credit</th>
				<th style="text-weight:bold; width:14%; border: solid 1px; text-align: center;" >Balance</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="width: 10%; border: solid 1px; text-align: center;" ><?php echo (isset($start_date) AND $start_date != "" ) ? date("d/m/Y", strtotime($start_date)) : ''; ?></td>
				<td style="width: 30%; border: solid 1px; text-align: left;"></td>
				<td style="width: 30%; border: solid 1px; text-align: left;" >Available Credit</td>
				<td style="width: 10%; border: solid 1px; text-align: center;" > </td>
				<td style="width: 10%; border: solid 1px; text-align: center;" ><?php echo $user_details["available_credit"]; ?></td>
				<td style="width: 10%; border: solid 1px; text-align: center;" ><?php echo $user_details["available_credit"]; ?> </td>
				
			</tr>
		
		<?php
			$available_credit = $user_details["available_credit"];
			$total_charge = 0.0 ;
			$balance_credit = $available_credit;
			$limit_row_page = 30;
			$start_row_next_page = 31;
			$count_row = 2; //exclude first row;
			$pg_number = 1;
			
				foreach($query_transaction->result() as $row){ 
					$count_row ++;
					if($count_row != $limit_row_page AND $count_row != $start_row_next_page) { ?>
						<tr>
							<td style="width: 10%; border: solid 1px; text-align: center;" ><?php echo (isset($row->create_date) AND $row->create_date != "" ) ? date("d/m/Y", strtotime($row->create_date)) : ''; ?></td>
							<td style="width: 30%; border: solid 1px; text-align: left;">
								<?php echo (isset($row->systemid) AND isset($row->cDerivativeCode) AND isset($row->create_date) AND $row->systemid != "" AND $row->cDerivativeCode != "" AND $row->create_date != ""  ) ? $row->systemid . "-" . $row->cDerivativeCode . "-" . date("Ymd", strtotime($row->create_date)) : ''; ?> 
							</td>
							<td style="width: 30%; border: solid 1px; text-align: left;" >PPC Usage Charge</td>
							<td style="width: 10%; border: solid 1px; text-align: center;" ><?php echo $user_details["chargeable_rate"]; ?> </td>
							<?php $total_charge += $user_details["chargeable_rate"]; ?>
							<td style="width: 10%; border: solid 1px; text-align: center;" ></td>
							<td style="width: 10%; border: solid 1px; text-align: center;" >
								<?php
									$balance_credit -= $user_details["chargeable_rate"];
									echo number_format($balance_credit,2); 
								?>
							</td>
						</tr>
			<?php	}
					else if($count_row == $limit_row_page){
						$pg_number++;
					?>
						<tr>
							<td style="width: 10%; border: solid 1px; text-align: center;" ><br /></td>
							<td style="width: 30%; border: solid 1px; text-align: left;"></td>
							<td style="width: 30%; border: solid 1px; text-align: left;" ></td>
							<td style="width: 10%; border: solid 1px; text-align: center;" ></td>
							<td style="width: 10%; border: solid 1px; text-align: center;" ></td>
							<td style="width: 10%; border: solid 1px; text-align: center;" ></td>
						</tr>
						<tr>
							<td style="width: 10%; border: solid 1px; text-align: center;" ></td>
							<td style="width: 30%; border: solid 1px; text-align: left;">
								
							</td>
							<td style="width: 30%; border: solid 1px; text-align: left;" >Balance C/F to Page: <?php echo $pg_number; ?></td>
							<td style="width: 10%; border: solid 1px; text-align: center;" ><?php echo number_format($total_charge,2); ?> </td>
							<td style="width: 10%; border: solid 1px; text-align: center;" ><?php echo number_format($available_credit,2); ?></td>
							<td style="width: 10%; border: solid 1px; text-align: center;" >
								<?php
									echo number_format($balance_credit,2); 
								?>
							</td>
						</tr>
			<?php	}
					else if($count_row == $start_row_next_page) {
						$count_row = 2;
						?>
						<tr>
							<td style="width: 10%; border: solid 1px; text-align: center;" ></td>
							<td style="width: 30%; border: solid 1px; text-align: left;">
							</td>
							<td style="width: 30%; border: solid 1px; text-align: left;" >Balance B/F from Page: <?php echo $pg_number - 1; ?></td>
							<td style="width: 10%; border: solid 1px; text-align: center;" ><?php echo number_format($total_charge,2); ?> </td>
							<td style="width: 10%; border: solid 1px; text-align: center;" ><?php echo number_format($available_credit,2); ?></td>
							<td style="width: 10%; border: solid 1px; text-align: center;" >
								<?php
									echo number_format($balance_credit,2); 
								?>
							</td>
						</tr>
			<?php	} ?>
					
		<?php	} ?>
			
		<?php if($count_row == ($start_row_next_page - 1)) { ?>
			<tr>
				<td style="width: 10%; border: solid 1px; text-align: center;" ></td>
				<td style="width: 30%; border: solid 1px; text-align: left;">
					
				</td>
				<td style="width: 30%; border: solid 1px; text-align: left;" >Balance B/F from Page: <?php echo $pg_number - 1; ?></td>
				<td style="width: 10%; border: solid 1px; text-align: center;" ><?php echo number_format($total_charge,2); ?> </td>
				<td style="width: 10%; border: solid 1px; text-align: center;" ><?php echo number_format($available_credit,2); ?></td>
				<td style="width: 10%; border: solid 1px; text-align: center;" >
					<?php
						echo number_format($balance_credit,2); 
					?>
				</td>
			</tr>
		<?php } ?>
			<tr>
				<td style="width: 10%; border: solid 1px; text-align: center;" ><br/></td>
				<td style="width: 30%; border: solid 1px; text-align: left;"> </td>
				<td style="width: 30%; border: solid 1px; text-align: left;" ></td>
				<td style="width: 10%; border: solid 1px; text-align: center;" ></td>
				<td style="width: 10%; border: solid 1px; text-align: center;" ></td>
				<td style="width: 10%; border: solid 1px; text-align: center;" ></td>
			</tr>
			
			<tr>
				<td style="width: 10%; border: solid 1px; text-align: center;" ></td>
				<td style="width: 30%; border: solid 1px; text-align: left;"> </td>
				<td style="width: 30%; border: solid 1px; text-align: left;" >Closing Balance</td>
				<td style="width: 10%; border: solid 1px; text-align: center;" ><?php echo number_format($total_charge,2); ?> </td>
				<td style="width: 10%; border: solid 1px; text-align: center;" ><?php echo number_format($available_credit,2); ?></td>
				<td style="width: 10%; border: solid 1px; text-align: center;" > <?php echo number_format($balance_credit,2) ?></td>
			</tr>
		
		</tbody>
		
	</table>
	<br />
	<table style="width: 108%; border: solid 1px black;position: relative; right:1%;">
		<tr>
			<td style="text-align: left; width: 62%;" ><b><?php echo number_string(number_format($balance_credit,2)); ?></b></td>
			<td style="text-align: center; width: 26%; border-right:solid 1px black; border-left:solid 1px black;"><b>PPC user available credit:</b></td>
			<td style="text-align: right; width: 21%;"><b>RM <?php echo number_format($balance_credit,2) ?></b></td>
		</tr>
	</table>
	<br />
	
	<table style="width: 108%; position: relative; right:1%;">
		<tbody>
			<tr>
				<th style="text-weight:bold; width:20%;text-align: left;" ><b>Summary of GST</b></th>
			</tr>
		</tbody>
	</table>
	<br />
	<table style="width: 108%;  border: solid 1px black; position: relative; right:1%; border-collapse: collapse; text-align: center;">
		<thead>
			<tr>
				<th style="text-weight:bold; width:30%;border: solid 1px black;"><b>Cost</b></th>
				<th style="text-weight:bold; width:20%;border: solid 1px black;"><b>GST (6%)</b></th>
				<th style="text-weight:bold; width:20%;border: solid 1px black;"><b>Total </b></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="text-weight:bold; width:12%; border: solid 1px black;">
					<?php 
						$tot_charge = $total_charge / 1.06;
						echo number_format($tot_charge,2); 
					?>
				
				</td>
				<td style="text-weight:bold; width:12%;border: solid 1px black;">
					<?php 
						$tot_gst = $total_charge - $tot_charge;
						echo number_format($tot_gst,2); 
					?>
				</td>
				<td style="text-weight:bold; width:12%;border: solid 1px black;"><?php echo number_format($total_charge,2); ?> </td>
			</tr>
		</tbody>
	</table>
	<br />
	<table style="width: 108%; position: relative; right:1%;">
	
		<tbody>
			<tr>
				<td style="text-weight:bold; width:60s%;text-align: left;"><b>Remarks: The PPC usage charges (Debit) are inclusive of 6% GST.</b></td>
			</tr>
		</tbody>
	</table>
</page>