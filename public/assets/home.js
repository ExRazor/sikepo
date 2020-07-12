
    //Hitung
    $('.hitung').each(function () {
        $(this).prop('Counter',0).animate({
            Counter: $(this).data('value')
        }, {
            duration: 2000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });

    function chart(url,type) {
        var tahun   = new Array();
        var jumlah  = new Array();
        var canvas  = $('#chart');
        var type_chart;

        $.get(url, function(response){
            switch(type) {
                case "Penelitian":
                    type_chart = response.Penelitian;
                break;
                case "Pengabdian":
                    type_chart = response.Pengabdian;
                break;
                case "Publikasi":
                    type_chart = response.Publikasi;
                break;
                case "Luaran":
                    type_chart = response.Luaran;
                break;
            }

            $.each(type_chart, function(index,data){
                tahun.push(index);
                jumlah.push(data);
            });

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: tahun,
                    datasets: [
                        {
                            label: type,
                            data: jumlah,
                            borderColor: '#27AAC8',
                            borderWidth: 1,
                            fill: true
                        }
                    ]
                },
                options: {
                    legend: {
                        display: false,
                        labels: {
                            display: false
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        });
    }

    function pengabdian(url) {
        var tahun   = new Array();
        var jumlah  = new Array();
        var canvas  = $('#pengabdian');

        $.get(url, function(response){
            $.each(response.pengabdian, function(index,data){
                tahun.push(index);
                jumlah.push(data);
            });

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: tahun,
                    datasets: [
                        {
                            label: 'Pengabdian',
                            data: jumlah,
                            borderColor: '#27AAC8',
                            borderWidth: 1,
                            fill: false
                        }
                    ]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        });
    }
