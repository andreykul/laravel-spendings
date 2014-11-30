$(function(){
	$('.amount-included').change(function(){
		//Grab the type of amount
		type = $(this).attr('data-type');

		//Grab current amount
		amount = parseFloat($('#'+type).text().replace(',',''));

		//Update to new value
		value = parseFloat(this.value);
		if (this.checked)
			amount += value;
		else amount -= value;

		//Update text
		$('#'+type).text(amount.formatMoney(2,'.',','));
	});

	modal_body = $('#transaction-notes .modal-body');
	modal_title = $('#transaction-notes .modal-title');
	base_uri = $('base').attr('href');

	$('.notes').click(function(){
		var transaction_id = $(this).attr('data-transaction-id');
		modal_title.text("");
		modal_body.text("");

		$.ajax({
			type: "GET",
			url : base_uri + "/" + transaction_id + "/notes",
			success: function(response){
				if (response.error) {
					modal_title.text("Error");
					modal_body.text(response.text);
				}
				else {
					modal_title.text(response.description);
					modal_body.text(response.notes);
				}
				
			}
		});
	});
});

Number.prototype.formatMoney = function(c, d, t){
	var n = this, 
	c = isNaN(c = Math.abs(c)) ? 2 : c, 
	d = d == undefined ? "." : d, 
	t = t == undefined ? "," : t, 
	s = n < 0 ? "-" : "", 
	i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
	j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };