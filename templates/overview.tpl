<h2>Ports</h2>

<!--<h3>{$LANG.clientareaproductdetails}</h3>-->

<hr>
<h3>You have been allocated following ports:</h3>
<hr>
Quota: {$qty}
<hr>

<div class="row">
    <div class="col-sm-12 pull-left" style="padding:0; margin:0">
        <ul style="list-style:none; margin:0; padding:0 0 0 15px;">
            {foreach from=$port_box item=port}
                <li style="padding:10px 0;">IP Address: {$port.ipaddress}:{$port.port}</li>
            {/foreach}
        </ul>
    </div>
</div>

