$(document).ready(function() {
    var skor;

    $('.form-isi').bind('keyup change', function(){

        //Kerja Sama
        simulasi_kerjasama();

        //Mahasiswa
        simulasi_mhs_seleksi();
        simulasi_mhs_asing();

        //Penelitian DTPS
        simulasi_penelitian_dtps();

        //Tridharma
        simulasi_skor_ipk();
        simulasi_prestasi_mhs();
        simulasi_tingkat_lembaga();

    })

    /***************************************************************/
    /********************** FUNGSI KERJA SAMA **********************/
    /***************************************************************/
    function simulasi_kerjasama(){
        var cont        = $('#simulasi-kerjasama');
        var dosen 	   	= parseInt(cont.find('#sim_dosen').val());
        var pendidikan 	= parseInt(cont.find('#sim_pendidikan').val());
        var penelitian 	= parseInt(cont.find('#sim_penelitian').val());
        var pkm		   	= parseInt(cont.find('#sim_pkm').val());
        var NI 	   		= parseInt(cont.find('#sim_internasional').val());
        var NN   		= parseInt(cont.find('#sim_nasional').val());
        var NL 			= parseInt(cont.find('#sim_lokal').val());
        var faktor_jenis_a 		= 3;
        var faktor_jenis_b 		= 2;
        var faktor_jenis_c 		= 1;
        var faktor_tingkat_a 	= 2;
        var faktor_tingkat_b 	= 6;
        var faktor_tingkat_c 	= 9;
        var skor_a;
        var skor_b;
        var skor;

        var RPend  = (faktor_jenis_a*pendidikan);
        var RPene  = (faktor_jenis_b*penelitian);
        var RPkm   = (faktor_jenis_c*pkm);

        cont.find('span.rata_pendidikan').text(RPend.toFixed(2));
        cont.find('span.rata_penelitian').text(RPene.toFixed(2));
        cont.find('span.rata_pkm').text(RPkm.toFixed(2));

        //Skor A
        rata_a = (RPend+RPene+RPkm)/dosen;
        if(rata_a >= 4) {
            skor_a  = 4;
            rumus_a = "4";
        } else if(rata_a < 4) {
            skor_a  = rata_a;
            rumus_a = "((a x N1) + (b x N2) + (c x N3)) / NDTPS"
        } else {
            skor_a = 0;
            rumus_a = null;
        }
        cont.find('#rumus_a').text(rumus_a);
        cont.find('#skor_a').val(skor_a.toFixed(2));

        //Skor B
        if(NI >= faktor_tingkat_a) {
            skor_b  = 4;
            rumus_b = "4";
        } else if(NI < faktor_tingkat_a && NN >= faktor_tingkat_b) {
            skor_b  = 3 + (NI/faktor_tingkat_a);
            rumus_b = "3 + (NI / a)";
        } else if((NI > 0 && NI < faktor_tingkat_a) || (NN > 0 && NN < faktor_tingkat_b)) {
            skor_b  = 2 + (2 * (NI/faktor_tingkat_a)) + (NN/faktor_tingkat_b) - ((NI * NN)/(faktor_tingkat_a * faktor_tingkat_b));
            rumus_b = "2 + (2 x (NI/a)) + (NN/b) - ((NI x NN)/(a x b))"
        } else if (NI == 0 && NN == 0 && NL >= faktor_tingkat_c) {
            skor_b  = 2;
            rumus_b = "2";
        } else if (NI == 0 && NN == 0 && NL < faktor_tingkat_c) {
            skor_b  = (2 * NL) / faktor_tingkat_c;
            rumus_b = "(2 x NL) / c";
        } else {
            skor_b = 0;
            rumus_b = null;
        }
        cont.find('#rumus_b').text(rumus_b);
        cont.find('#skor_b').val(skor_b.toFixed(2));

        //Skor Total
        skor  = ((2*skor_a)+skor_b)/3
        rumus = "((2 x A) + B) / 3"
        cont.find('#rumus').text(rumus);
        cont.find('#skor').val(skor.toFixed(2));
    }

    /************************************************************************/
    /*************************** FUNGSI MAHASISWA ***************************/
    /************************************************************************/
    function simulasi_mhs_seleksi(){
        var cont  = $('#simulasi-seleksi-mahasiswa');
        var calon = parseInt(cont.find("#mhs_calon").val());
        var baru  = parseInt(cont.find("#mhs_baru").val());

        var rasio_baru  = parseFloat((baru/calon)*10).toFixed(0);
        var rasio_calon = 10-rasio_baru;

        if(rasio_calon>=5) {
            skor = 4;
        } else if(rasio_calon<5) {
            skor = (4*rasio_calon)/5;
        }

        cont.find("#rasio_calon").val(rasio_calon);
        cont.find("#rasio_baru").val(rasio_baru);
        cont.find("#skor_seleksi").val(skor);
    }

	function simulasi_mhs_asing(){
        var cont        = $('#simulasi-mahasiswa-asing');
		var mahasiswa   = parseInt(cont.find('#mahasiswa_aktif').val());
		var asing_full  = parseInt(cont.find('#mahasiswa_asing_full').val());
		var asing_part  = parseInt(cont.find('#mahasiswa_asing_part').val());
		var skor_a      = parseInt(cont.find('#skor_asing_a').val());

		var persentase_asing_full = (asing_full/mahasiswa)*100;
		var persentase_asing_part = (asing_part/mahasiswa)*100;
		var persentase_asing 	  = persentase_asing_full+persentase_asing_part;

		if(persentase_asing>=1) {
			skor_b = 4;
		} else if (persentase_asing<1) {
			skor_b = 2+((200*persentase_asing)/100);
		} else {
			skor_b = 0;
        }

        skor = ((2*skor_a) + skor_b) / 3;

		cont.find('span.persentase_asing_full').text(persentase_asing_full.toFixed(2));
		cont.find('span.persentase_asing_part').text(persentase_asing_part.toFixed(2));
		cont.find('#persentase_asing').val(persentase_asing.toFixed(2)+"%");
		cont.find('#skor_asing_b').val(skor_b.toFixed(2));
		cont.find('#skor_asing').val(skor.toFixed(2));
    }

    /************************************************************************/
    /****************************** FUNGSI SDM ******************************/
    /************************************************************************/



    /*************************************************************************/
    /*************************** FUNGSI PENELITIAN ***************************/
    /*************************************************************************/
    function simulasi_penelitian_dtps()
    {
        var cont = $("#simulasi-penelitian-dtps");
        var ndt  = parseInt(cont.find("#ndt").val());
        var ni   = parseInt(cont.find("#ni").val());
        var nn   = parseInt(cont.find("#nn").val());
        var nl   = parseInt(cont.find("#nl").val());

        var a = 0.05;
        var b = 0.3;
        var c = 1

        ri = ni/3/ndt;
        rn = nn/3/ndt;
        rl = nl/3/ndt;

        cont.find('span.rata_inter').text(ri.toFixed(2));
        cont.find('span.rata_nasional').text(rn.toFixed(2));
        cont.find('span.rata_lokal').text(rl.toFixed(2));

        if(ri>=a) {
            skor = 4;
            rumus = "4";
        } else if(ri<a && rn>=b) {
            skor = 3+(ri/a);
            rumus = "3 + (RI / a)";
        } else if((ri > 0 && ri < a) || (rn < 0 && rn > b)) {
            skor = 2 + (2*(ri/a))+(rn/b)-((ri*rn)/(a*b));
            rumus = "2 + (2 * (RI / a)) + (RN / b) - ((RI * RN) / (a * b))";
        } else if(ri==0 && rn==0 && rl>=c) {
            skor = 2;
            rumus = "2";
        } else if(ri==0 && rn==0 && rl<c) {
            skor = (2*rl)/c;
            rumus = "(2 * RL) / c";
        } else {
            skor  = 0;
            rumus = null;
        }

        cont.find("#skor_penelitian").val(skor.toFixed(2));
        cont.find(".rumus_penelitian").text(rumus);
    }

    /************************************************************************/
    /*************************** FUNGSI TRIDHARMA ***************************/
    /************************************************************************/
    function simulasi_skor_ipk()
    {
        var cont            = $("#simulasi-capaian-ipk");
        var total_mahasiswa = parseFloat(cont.find("#total_mahasiswa").val());
        var rata_ipk        = parseFloat(cont.find('#rata_ipk').val());

        if(rata_ipk>=3.25) {
            skor = 4;
            skor = skor.toFixed(2);
        } else if(rata_ipk>=2.00 && rata_ipk<3.25) {
            skor = ((8*rata_ipk)-6)/5;
            skor = skor.toFixed(2);
        } else {
            skor = "Tidak ada skor di bawah 2";
        }

        cont.find("#skor_ipk").val(skor);

    }

    function simulasi_prestasi_mhs()
    {
        var cont    = $("#simulasi-prestasi-mhs");
        var nm      = parseInt(cont.find("#nm").val());
        var ni      = parseInt(cont.find("#ni").val());
        var nn      = parseInt(cont.find("#nn").val());
        var nw      = parseInt(cont.find("#nw").val());

        var a = 0.05;
        var b = 0.5;
        var c = 2;

        ri = ni/nm;
        rn = nn/nm;
        rw = nw/nm;

        cont.find('span.rata_inter').text(ri.toFixed(2));
        cont.find('span.rata_nasional').text(rn.toFixed(2));
        cont.find('span.rata_lokal').text(rw.toFixed(2));

        if(ri>=a) {
            skor = 4;
            skor = skor.toFixed(2);
            rumus = "4";
        } else if(ri<a && rn>=b) {
            skor = 3+(ri/a);
            skor = skor.toFixed(2);
            rumus = "3 + (RI / a)";
        } else if((ri>0 && ri<a) || (rn>0 && rn<b)) {
            skor = 2 + (2*(ri/a))+(rn/b)-((ri*rn)/(a*b));
            skor = skor.toFixed(2);
            rumus = "2 + (2 * (RI / a)) + (RN / b) - ((RI * RN) / (a * b))";
        } else if(ri==0 && rn==0 && (rw<=c && rw>=0)) {
            skor = 1+(rw/c);
            skor = skor.toFixed(2);
            rumus = "1 + (RW / c)";
        } else if(ri==0 && rn==0 && rw>c) {
            skor = 2;
            skor = skor.toFixed(2);
            rumus = "2";
        } else {
            skor = 0;
            rumus = "Tidak ada skor di bawah 1";
        }

        cont.find("#skor_prestasi").val(skor);
        cont.find(".rumus_prestasi").text(rumus);
    }

    function simulasi_tingkat_lembaga()
    {
        var cont    = $("#simulasi-lembaga-lulusan");
        var na      = parseInt(cont.find("#na").val());
        var ni      = parseInt(cont.find("#ni").val());
        var nn      = parseInt(cont.find("#nn").val());
        var nl      = parseInt(cont.find("#nl").val());

        var a 	 = 5;
        var b 	 = 20;
        var c 	 = 90;

        ri = (ni/na)*100;
        rn = (nn/na)*100;
        rl = (nl/na)*100;

        cont.find('span.rata_inter').text(ri.toFixed(2));
        cont.find('span.rata_nasional').text(rn.toFixed(2));
        cont.find('span.rata_lokal').text(rl.toFixed(2));

        if(ri>=a) {
            skor = 4;
            skor = skor.toFixed(2);
            rumus = "4";
        } else if(ri<a && rn>=b) {
            skor = 3+(ri/a);
            skor = skor.toFixed(2);
            rumus = "3 + (RI / a)";
        } else if((ri>0 && ri<a) || (rn>0 && rn<b)) {
            skor = 2 + (2*(ri/a))+(rn/b)-((ri*rn)/(a*b));
            skor = skor.toFixed(2);
            rumus = "2 + (2 * (RI / a)) + (RN / b) - ((RI * RN) / (a * b))";
        } else if(ri==0 && rn==0 && rl>=c) {
            skor = 2;
            skor = skor.toFixed(2);
            rumus = "2";
        } else if(ri==0 && rn==0 && rl<c) {
            skor = (2*rl)/c;
            skor = skor.toFixed(2);
            rumus = "(2 * RL) / c";
        } else {
            skor = 0;
            rumus = null;
        }

        cont.find("#skor_lembaga").val(skor);
        cont.find(".rumus_lembaga").text(rumus);
    }
});
