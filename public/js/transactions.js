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

	notes_textarea = $('#notes');
	modal_title = $('.modal-title');
	success_alert = $('.modal .alert-success');
	error_alert = $('.modal .alert-danger');
	base_uri = $('base').attr('href');

	$('.notes').click(function(){
		var transaction_id = $(this).attr('data-transaction-id');
		notes_textarea.val("");
		modal_title.text("");

		$('#save-notes').attr('data-transaction-id', transaction_id);

		$.ajax({
			type: "GET",
			url : base_uri + "/" + transaction_id + "/notes",
			success: function(transaction){
				notes_textarea.val(transaction.notes);
				modal_title.text(transaction.header);
			}
		});
	});

	$('#save-notes').click(function(){
		var transaction_id = $(this).attr('data-transaction-id');

		var notes = $('#notes').val();

		$.ajax({
			type: "POST",
			url : base_uri + "/" + transaction_id + "/notes",
			data: { notes: notes },
			success: function(response){
				if (response.error) {
					error_alert.text(response.text);
					error_alert.show();
				}
				else success_alert.show();
				

				setTimeout(function(){ 
					success_alert.hide();
					error_alert.hide();
				}, 2000 );
			}
		});
	})
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