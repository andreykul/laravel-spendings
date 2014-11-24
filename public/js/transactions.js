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