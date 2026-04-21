<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Smart Presence | Cileungsi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #0f172a;
            background-image: radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
                              radial-gradient(circle at 90% 80%, rgba(168, 85, 247, 0.15) 0%, transparent 40%);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh; color: white; display: flex; align-items: center;
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px; padding: 2rem; box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }
        #clock { font-size: 3.5rem; font-weight: 800; letter-spacing: -2px; margin: 10px 0; color: #f8fafc; }
        .indicator {
            border-radius: 16px; padding: 1rem; margin-bottom: 1.5rem;
            transition: 0.3s; border: 1px solid transparent;
        }
        .in-range { background: rgba(16, 185, 129, 0.15); border-color: #10b981; color: #34d399; }
        .out-range { background: rgba(239, 68, 68, 0.15); border-color: #ef4444; color: #f87171; }
        .btn-absensi {
            border: none; border-radius: 16px; padding: 1.2rem; font-weight: 700;
            display: flex; flex-direction: column; align-items: center; transition: 0.3s;
        }
        .btn-in { background: #6366f1; color: white; }
        .btn-out { background: #334155; color: white; }
        .btn-absensi:disabled { opacity: 0.3; filter: grayscale(1); }
        .btn-absensi:not(:disabled):hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
        .history { background: rgba(255,255,255,0.05); border-radius: 12px; padding: 1rem; margin-top: 1.5rem; }
        .dot { height: 8px; width: 8px; border-radius: 50%; display: inline-block; margin-right: 5px; }
        .pulse { animation: pulse-animation 2s infinite; }
        @keyframes pulse-animation { 0% { box-shadow: 0 0 0 0px rgba(16, 185, 129, 0.4); } 100% { box-shadow: 0 0 0 15px rgba(16, 185, 129, 0); } }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="glass-card text-center">
                <p class="text-uppercase small fw-bold text-info mb-0">Presence System</p>
                <div id="clock">00:00:00</div>
                <p class="text-white-50 mb-4"><?= date('l, d F Y') ?></p>

                <div id="status-box" class="indicator out-range">
                    <i class="fas fa-location-crosshairs mb-2"></i>
                    <div id="dist-text" class="fw-bold">Mencari Sinyal GPS...</div>
                    <small id="range-msg">Izinkan akses lokasi di browser HP</small>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <button onclick="proses('in')" id="btn-in" class="btn-absensi btn-in w-100" disabled>
                            <i class="fas fa-fingerprint fa-2x mb-2"></i> Masuk
                        </button>
                    </div>
                    <div class="col-6">
                        <button onclick="proses('out')" id="btn-out" class="btn-absensi btn-out w-100" disabled>
                            <i class="fas fa-sign-out-alt fa-2x mb-2"></i> Pulang
                        </button>
                    </div>
                </div>

                <div class="history text-start">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-white-50 small">Absen Masuk:</span>
                        <span class="fw-bold text-success"><?= $absensi['jam_masuk'] ?? '--:--' ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-white-50 small">Absen Pulang:</span>
                        <span class="fw-bold text-danger"><?= $absensi['jam_pulang'] ?? '--:--' ?></span>
                    </div>
                </div>
            </div>
            <p class="text-center mt-4 text-white-50 small">Kampung Empu No.1, Setu Sari</p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // KOORDINAT TARGET: Kampung Empu No.1
    // const DEST_LAT = -6.423932; 
    // const DEST_LONG = 107.027731;
    // const MAX_RAD = 50; // Radius 50 Meter

    // KOORDINAT TARGET: Morena Rent
    const DEST_LAT = -6.381267; 
    const DEST_LONG = 106.926272;
    const MAX_RAD = 50; // Radius 50 Meter

    let uLat, uLong;
    let hasIn = <?= ($absensi && $absensi['jam_masuk']) ? 'true' : 'false' ?>;
    let hasOut = <?= ($absensi && $absensi['jam_pulang']) ? 'true' : 'false' ?>;

    setInterval(() => {
        document.getElementById('clock').innerText = new Date().toLocaleTimeString('id-ID', {hour12:false});
    }, 1000);

    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(success, error, { enableHighAccuracy: true });
    }

    function success(pos) {
        uLat = pos.coords.latitude;
        uLong = pos.coords.longitude;
        const dist = getDist(uLat, uLong, DEST_LAT, DEST_LONG);
        
        const box = $('#status-box');
        $('#dist-text').text(`Jarak: ${dist.toFixed(1)} Meter`);

        if (dist <= MAX_RAD) {
            box.attr('class', 'indicator in-range pulse');
            $('#range-msg').text("Lokasi Valid. Silahkan Absen.");
            if (!hasIn) $('#btn-in').prop('disabled', false);
            if (hasIn && !hasOut) $('#btn-out').prop('disabled', false);
        } else {
            box.attr('class', 'indicator out-range');
            $('#range-msg').text("Di luar radius 50m kantor.");
            $('.btn-absensi').prop('disabled', true);
        }
    }

    function getDist(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = (lat2-lat1) * Math.PI/180;
        const dLon = (lon2-lon1) * Math.PI/180;
        const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)**2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    function proses(type) {
        $.post("<?= base_url('absensi/proses') ?>", {type:type, latitude:uLat, longitude:uLong}, function(res){
            const data = JSON.parse(res);
            alert(data.msg);
            if(data.status == 'success') location.reload();
        });
    }

    function error() { alert("Gagal mendapatkan lokasi. Pastikan GPS aktif."); }
</script>
</body>
</html>