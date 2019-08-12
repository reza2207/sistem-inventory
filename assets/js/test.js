
function bilangan(angka){
	
	if(angka >= 0){
		let bilangan = angka;
		let	number_string = bilangan.toString(),
		sisa 	= number_string.length % 3,
		rupiah 	= number_string.substr(0, sisa),
		ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
			
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');

		}
		return rupiah;
	}else{
		let bilangan = angka;
		let	number_string = bilangan.toString().substr(1),
		sisa 	= number_string.length % 3,
		rupiah 	= number_string.substr(0, sisa),
		ribuan 	= number_string.substr(sisa).match(/\d{3}/g);

		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');

		}

		return '-'+rupiah;

	}

	

}

function tanggal(tgl){

	let pecah = tgl.split('-');
	return pecah[2]+'-'+pecah[1]+'-'+pecah[0];
}