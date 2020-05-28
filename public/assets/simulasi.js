$(document).ready(function() {
    var skor;

    //Sumber Daya Manusia
    function simulasi_penilaian_dosen() {
        simulasi_hitung_dtps();
        simulasi_persentase_dtps_s3();
        simulasi_persentase_dtps_gblk();
        simulasi_persentase_dtps_sp();
        simulasi_persentase_dtps_dtt();
        simulasi_rasio_mahasiswa_dtps();
    }

    function simulasi_kinerja_dosen()
    {
        simulasi_persentase_bimbingan();
        simulasi_skor_ewmp();
        simulasi_skor_prestasi();
    }

    function simulasi_pkm_dosen()
    {
        simulasi_publikasi_jurnal();
        simulasi_publikasi_seminar();
        simulasi_karya_ilmiah();
        simulasi_luaran_pkm();
    }

    $('.form-isi').bind('keyup change', function(){

        /**** Kerja Sama ****/
        simulasi_kerjasama();

        /**** Mahasiswa ****/
        simulasi_mhs_seleksi();
        simulasi_mhs_asing();

        /**** SDM ****/
        simulasi_penilaian_dosen();
        simulasi_kinerja_dosen();
        simulasi_pkm_dosen();

        /**** Penelitian DTPS ****/
        simulasi_penelitian_dtps();

        /**** Tridharma ****/
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

    /******************** Penilaian Dosen ********************/
    function simulasi_hitung_dtps()
    {
        var cont = $("#simulasi-kecukupan-dosen");
        var dtps = cont.find("#dtps").val();

        if(dtps>=12) {
            skor = 4;
        } else if(dtps>=6 && dtps<12) {
            skor = dtps/3;
        } else {
            skor = 0;
        }

        cont.find("#skor_dtps").val(skor.toFixed(2));
    }

    function simulasi_persentase_dtps_s3()
    {
        var cont    = $("#simulasi-persentase-s3");
        var dtps    = cont.find("#dtps").val();
        var dtps_s3 = cont.find("#dtps_s3").val();
        var persentase = (dtps_s3/dtps)*100;

        if(persentase>=50) {
            skor = 4;
        } else if (persentase<50) {
            skor = 2 + ((4*persentase)/100);
        } else {
            skor = 0;
        }

        cont.find("#persentase_dtps_s3").val(persentase.toFixed(2)+"%");
        cont.find("#skor_dtps_s3").val(skor.toFixed(2));
    }

    function simulasi_persentase_dtps_gblk()
    {
        var cont      = $("#simulasi-persentase-gubes");
        var dtps      = cont.find("#dtps").val();
        var dtps_gblk = cont.find("#dtps_gblk").val();
        var persentase = (dtps_gblk/dtps)*100;

        if(persentase>=40) {
            skor = 4;
        } else if (persentase<40) {
            skor = 2 + ((4*persentase)/100);
        } else {
            skor = 0;
        }

        cont.find("#persentase_dtps_gblk").val(persentase.toFixed(2)+"%");
        cont.find("#skor_dtps_gblk").val(skor.toFixed(2));
    }

    function simulasi_persentase_dtps_sp()
    {
        var cont    = $("#simulasi-dtps-bersertifikat");
        var dtps    = cont.find("#dtps").val();
        var dtps_sp = cont.find("#dtps_sp").val();
        var persentase = (dtps_sp/dtps)*100;

        if(persentase>=80) {
            skor = 4;
        } else if (persentase<80) {
            skor = 1 + (((15*persentase)/100)/4);
        } else {
            skor = 0;
        }

        cont.find("#persentase_dtps_sp").val(persentase.toFixed(2)+"%");
        cont.find("#skor_dtps_sp").val(skor.toFixed(2));
    }

    function simulasi_persentase_dtps_dtt()
    {
        var cont    = $("#simulasi-persentase-dtt");
        var dosen   = cont.find("#dosen").val();
        var dtps    = cont.find("#dtps").val();
        var dtt 	= cont.find("#dtt").val();
        // var desimal 	= (dtps_ttp/dtps);
        // var persentase 	= desimal*100;

        var desimal         = (dtt/dosen);
        var persentase      = desimal*100;
        var persentase_dtps = (dtps/dosen)*100;

        if(persentase<=10) {
            skor = 4;
        } else if (persentase>10 && persentase<=40) {
            skor = (16 - (40*desimal))/3;
        } else if (persentase > 40) {
            skor = 0;
        }

        cont.find("span.persentase_dtps").text(persentase_dtps.toFixed(2)+"%");
        cont.find("span.persentase_dtt").text(persentase.toFixed(2)+"%");
        cont.find("#skor").val(skor.toFixed(2));
    }

    function simulasi_rasio_mahasiswa_dtps()
    {
        var cont        = $("#simulasi-rasio-mahasiswa");
        var dtps        = cont.find("#dtps").val();
        var mahasiswa   = cont.find("#mahasiswa").val();

        var rasio_dosen 	= parseFloat((dtps/mahasiswa)*100);
        var rasio_mahasiswa = 100-rasio_dosen;

        if(rasio_dosen>=15 && rasio_dosen <= 25) {
            skor=4;
        } else if(rasio_dosen<15) {
            skor = (4*rasio_dosen)/15;
        } else if(rasio_dosen>25 && rasio_dosen<=35) {
            skor = (70-(2*rasio_dosen))/5;
        } else if (rasio_dosen>35) {
            skor=0;
        }

        cont.find("#rasio_mahasiswa").val(rasio_mahasiswa.toFixed(0));
        cont.find("#rasio_dtps").val(rasio_dosen.toFixed(0));
        cont.find("#skor_rasio_dtpm").val(skor.toFixed(2));
    }


    /******************** Kinerja Dosen ********************/
    function simulasi_persentase_bimbingan()
    {
        var cont           = $("#simulasi-beban-bimbingan");
        var tot_pembimbing = cont.find("#total_pembimbing").val();
        var tot_bimbingan  = cont.find("#total_bimbingan").val();

        //Persentase Pembimbing <= 10 Mahasiswa dengan Total Pembimbing
        var desimal    = (tot_bimbingan/tot_pembimbing);
        var persentase = desimal*100;

        if(persentase>20) {
            skor = (5*desimal)-1;
        } else if (persentase<=20) {
            skor = 0;
        }

        cont.find("#persentase_bimbingan").val(persentase.toFixed(2)+'%');
        cont.find("#skor_bimbingan").val(skor.toFixed(2));
    }

    function simulasi_skor_ewmp()
    {
        var cont        = $("#simulasi-swmp-dtps");
        var tot_dosen   = cont.find("#total_dtps").val();
        var tot_rata_sks= cont.find("#total_rata_sks").val();

        var rata_sks  = tot_rata_sks/tot_dosen;

        if(rata_sks>=12 && rata_sks<=13) {
            skor = 4;
        } else if(rata_sks>=6 && rata_sks<12) {
            skor = ((4*rata_sks)-24)/5;
        } else if(rata_sks>=13 && rata_sks<=18) {
            skor = (72-(4*rata_sks))/5;
        } else if(rata_sks<6 || rata_sks>18) {
            skor = 0;
        }

        cont.find("#rata_sks").val(rata_sks.toFixed(2));
        cont.find("#skor").val(skor.toFixed(2));
    }

    function simulasi_skor_prestasi()
    {
        var cont                = $("#simulasi-prestasi-dtps");
        var dtps 				= parseInt(cont.find('#total_dtps').val());
        var dtps_prestasi 		= parseInt(cont.find('#dtps_prestasi').val());
        var dtps_prestasi_inter = parseInt(cont.find('#dtps_prestasi_inter').val());

        var rata_prestasi = dtps_prestasi/dtps;

        if(rata_prestasi>=0.5 || dtps_prestasi_inter>=1) {
            skor = 4;
        } else if(rata_prestasi<=0.5) {
            skor = 2+(4*rata_prestasi);
        } else {
            skor = 0;
        }

        cont.find('#rata_prestasi_dtps').val(rata_prestasi.toFixed(2));
        cont.find('#skor_prestasi_dtps').val(skor.toFixed(2));

    }


    /******************** PkM Dosen ********************/
    function simulasi_publikasi_jurnal()
    {
        var cont    = $("#simulasi-publikasi-jurnal");
        var dt      = parseInt(cont.find("#dtps").val());
        var a1      = parseInt(cont.find("#jurnal_nonakre").val());
        var a2      = parseInt(cont.find("#jurnal_nasional").val());
        var a3      = parseInt(cont.find("#jurnal_inter").val());
        var a4      = parseInt(cont.find("#jurnal_inter_rep").val());

        var faktor_a = 0.1;
        var faktor_b = 1;
        var faktor_c = 2;
        var skor = 0;

        rl = a1/dt;
        rn = (a2+a3)/dt;
        ri = a4/dt;

        cont.find("span.rata_a1").text(rl.toFixed(2));
        cont.find("span.rata_a3").text(rn.toFixed(2));
        cont.find("span.rata_a4").text(ri.toFixed(2));

        if(ri >= faktor_a) {
            skor = 4;
            Rumus = "Skor = 4";
        } else if(ri < faktor_a && rn >= faktor_b) {
            skor = 3+(ri/faktor_a);
            rumus = "3 + (RI / faktor a)";
        } else if((ri > 0 && ri < faktor_a) || (rn > 0 && rn < faktor_b)) {
            skor = 2+(2*(ri/faktor_a)) + (rn/faktor_b) - ((ri*rn) / (faktor_a*faktor_b));
            rumus = "2 + (2 * (RI / a)) + (RN / b) 0 ((RI * RN) / (faktor a * faktor b))";
        } else if(ri==0 && rn==0 && rl>=faktor_c) {
            skor = 2;
            rumus = "Skor = 2";
        } else if(ri==0 && rn==0 && rl<faktor_c) {
            skor = (2*rl)/faktor_c;
            rumus = "Skor = (2*RL)/faktor c";
        } else {
            skor = 0;
            rumus = 0;
        }

        cont.find("#skor_publikasi_jurnal").val(skor.toFixed(2));
        cont.find("span.rumus_jurnal").text(rumus);
    }

    function simulasi_publikasi_seminar()
    {
        var cont    = $("#simulasi-publikasi-seminar");
        var dt      = parseInt(cont.find("#dtps").val());
        var b1      = parseInt(cont.find("#publikasi_lokal").val());
        var b2      = parseInt(cont.find("#publikasi_nasional").val());
        var b3      = parseInt(cont.find("#publikasi_inter").val());

        var faktor_a = 0.1;
        var faktor_b = 1;
        var faktor_c = 2;
        var skor = 0;

        rl = b1/dt;
        rn = b2/dt;
        ri = b3/dt;

        cont.find("span.rata_b1").text(rl.toFixed(2));
        cont.find("span.rata_b2").text(rn.toFixed(2));
        cont.find("span.rata_b3").text(ri.toFixed(2));

        if(ri >= faktor_a) {
            skor = 4;
            Rumus = "4";
        } else if(ri < faktor_a && rn >= faktor_b) {
            skor = 3+(ri/faktor_a);
            rumus = "3 + (RI / faktor a)";
        } else if((ri > 0 && ri < faktor_a) || (rn > 0 && rn < faktor_b)) {
            skor = 2+(2*(ri/faktor_a)) + (rn/faktor_b) - ((ri*rn) / (faktor_a*faktor_b));
            rumus = "2 + (2 * (RI / a)) + (RN / b) 0 ((RI * RN) / (faktor a * faktor b))";
        } else if(ri==0 && rn==0 && rl>=faktor_c) {
            skor = 2;
            rumus = "Skor = 2";
        } else if(ri==0 && rn==0 && rl<faktor_c) {
            skor = (2*rl)/faktor_c;
            rumus = "Skor = (2*RL)/faktor c";
        } else {
            skor = 0;
            rumus = 0;
        }

        cont.find("#skor_publikasi_seminar").val(skor.toFixed(2));
        cont.find("span.rumus_seminar").text(rumus);
    }

    function simulasi_karya_ilmiah()
    {
        var cont    = $("#simulasi-karya-sitasi");
        var dt      = parseInt(cont.find("#dtps").val());
        var as      = parseInt(cont.find("#karya_ilmiah").val());

        rs = as/dt;

        cont.find("span.rata_rs").text(rs.toFixed(2));

        if(rs>=0.5) {
            skor = 4;
            rumus = "4";
        } else if(rs<0.5) {
            skor = 2+(4*rs);
            rumus = "2 + (4 * RS)";
        } else {
            skor = 0;
            rumus = "Tidak ada Skor kurang dari 2";
        }

        cont.find("#skor_karya_ilmiah").val(skor.toFixed(2));
        cont.find("span.rumus_karya_ilmiah").text(rumus);
    }

    function simulasi_luaran_pkm()
    {
        var cont    = $("#simulasi-luaran-pkm");
        var dt      = parseInt(cont.find("#dtps").val());
        var na      = parseInt(cont.find("#pkm_paten").val());
        var nb      = parseInt(cont.find("#pkm_cipta").val());
        var nc      = parseInt(cont.find("#pkm_produk").val());
        var nd      = parseInt(cont.find("#pkm_buku").val());

        rlp   = ((4 * na) + (2 * (nb + nc)) + nd) / dt;
        rumus_rlp = "(4 * NA + 2 * (NB + NC) + ND) / NDT";

        if(rlp>=1) {
            skor = 4;
            rumus = 4;
        } else if(rlp<1) {
            skor = 2 + (2 * rlp);
            rumus = "2 + (2 * RLP)";
        } else {
            skor = null;
            rumus = "Tidak ada Skor kurang dari 2";
        }

        cont.find("#rlp").val(rlp.toFixed(2));
        cont.find("span.rumus_rlp").text(rumus_rlp);
        cont.find("#skor").val(skor.toFixed(2));
        cont.find("span.rumus").text(rumus);
    }

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
