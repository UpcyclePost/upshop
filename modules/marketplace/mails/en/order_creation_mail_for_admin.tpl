{foreach $list as $seller}
	<table class="table" style="width:100%">
		<tr>
			<td width="10" style="padding:7px 0">&nbsp;</td>
			<td style="padding:7px 0">
				<font size="2" face="Open-sans, sans-serif" color="#555454">
					<p data-html-only="1" style="border-bottom:1px solid #D6D4D4;margin:3px 0 7px;text-transform:uppercase;font-weight:500;font-size:18px;padding-bottom:10px">
						Seller Details						</p>
					<span style="color:#777">
						<span style="color:#333"><strong>Name:</strong></span> {$seller['seller_firstname']|escape:'html':'UTF-8'} {$seller['seller_lastname']|escape:'html':'UTF-8'}<br />
						<span style="color:#333"><strong>Email:</strong></span> {$seller['seller_email']|escape:'html':'UTF-8'}
					</span>
				</font>
			</td>
			<td width="10" style="padding:7px 0">&nbsp;</td>
		</tr>
	</table>


	<font size="2" face="Open-sans, sans-serif" color="#555454">
		<table class="table table-recap" bgcolor="#ffffff" style="width:100%;border-collapse:collapse"><!-- Title -->
			<thead>
				<tr>
					<th style="border:1px solid #D6D4D4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">Product</th>
					<th style="border:1px solid #D6D4D4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">Unit price</th>
					<th style="border:1px solid #D6D4D4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">Quantity</th>
					<th style="border:1px solid #D6D4D4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">Total price</th>
				</tr>
			</thead>
			<tbody>
				{foreach $seller['product'] as $product}
					<tr>
						<td style="border:1px solid #D6D4D4;">
							<table class="table">
								<tr>
									<td width="10">&nbsp;</td>
									<td>
										<font size="2" face="Open-sans, sans-serif" color="#555454">
											<strong>{$product['name']|escape:'html':'UTF-8'}</strong>
										</font>
									</td>
									<td width="10">&nbsp;</td>
								</tr>
							</table>
						</td>
						<td style="border:1px solid #D6D4D4;">
							<table class="table">
								<tr>
									<td width="10">&nbsp;</td>
									<td align="right">
										<font size="2" face="Open-sans, sans-serif" color="#555454">
											{$product['unit_price']|escape:'html':'UTF-8'}
										</font>
									</td>
									<td width="10">&nbsp;</td>
								</tr>
							</table>
						</td>
						<td style="border:1px solid #D6D4D4;">
							<table class="table">
								<tr>
									<td width="10">&nbsp;</td>
									<td align="right">
										<font size="2" face="Open-sans, sans-serif" color="#555454">
											{$product['qty']|escape:'html':'UTF-8'}
										</font>
									</td>
									<td width="10">&nbsp;</td>
								</tr>
							</table>
						</td>
						<td style="border:1px solid #D6D4D4;">
							<table class="table">
								<tr>
									<td width="10">&nbsp;</td>
									<td align="right">
										<font size="2" face="Open-sans, sans-serif" color="#555454">
											{$product['total_price']|escape:'html':'UTF-8'}
										</font>
									</td>
									<td width="10">&nbsp;</td>
								</tr>
							</table>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</font>
	<br /><br />
{/foreach}