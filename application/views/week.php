<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Week Calculator</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h2>Cari Minggu dalam Bulan</h2>
    <label for="date">Pilih Tanggal:</label>
    <input type="date" id="date">
    <button id="calculate">Hitung Minggu</button>

    <h3>Hasil:</h3>
    <p id="result">Minggu ke-?</p>

    <script>
        $(document).ready(function() {
            $('#calculate').click(function() {
                let date = $('#date').val();
                let [year, month, day] = date.split('-');

                // Menghilangkan angka nol di depan tanggal
                day = parseInt(day); // Ini akan mengubah '01' menjadi 1, '02' menjadi 2, dsb.

                console.log(day)

                $.ajax({
                    url: "<?php echo site_url('weekcalculator/getWeek') ?>" + "/" + year + "/" + month + "/" + day,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.error) {
                            $('#result').text(response.error);
                        } else {
                            $('#result').text(`Tanggal ${response.date} adalah minggu ke-${response.week}`);
                        }
                    },
                    error: function() {
                        $('#result').text('Terjadi kesalahan. Coba lagi!');
                    }
                });
            });
        });
    </script>
</body>

</html>