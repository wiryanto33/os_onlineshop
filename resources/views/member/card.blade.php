<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Anggota</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f3f3f3;
        }

        .card {
            width: 85.6mm;
            height: 54mm;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            position: relative;
            margin-bottom: 20px;
            color: white;
            text-align: center;
        }

        .card img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        .content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            height: 100%;
            padding: 10px;
            box-sizing: border-box;
            margin-top: 25px;
        }

        .member {
            z-index: 1;
            position: absolute;
            /* Atur teks agar bisa diposisikan secara absolut */
            bottom: 25px;
            /* Posisikan teks di bagian bawah */
            left: 10px;
            /* Posisikan teks di sebelah kiri */
            margin: 0;
            /* Hilangkan margin default (opsional) */
            padding: 10px;
            /* Tambahkan padding jika diperlukan */
            color: #b59c0d;
            /* Warna teks */
            font-weight: bold;

        }

        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            margin-top: -30px;
            /* Mengangkat posisi gambar profil */
        }

        .header .profile-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid #fff;
            object-fit: cover;
            position: relative;
            z-index: 2;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        h2 {
            margin: 5px 0;
            font-size: 16px;
        }

        .details {
            font-size: 12px;
            margin-top: 10px;
        }

        .footer {
            margin-top: auto;
            font-size: 10px;
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <div class="card">
        @if ($card_back)
            <img src="{{ $card_back }}" alt="Card Back">
        @else
            <p>No background available</p>
        @endif
        <div class="content">
            <div class="header">
                <img src="{{ $foto_profile ?? asset('default-profile.png') }}" alt="Profile Picture" class="profile-img">
                <h2>{{ $name }}</h2>
                <p style="font-size: 12px; margin: 0;">{{ $email }}</p>
            </div>
            <div class="details">
                <p><strong>Alamat:</strong> {{ $address }}</p>
            </div>
            <div class="footer">
                OneSevenStore - {{ date('Y') }}
            </div>
        </div>
    </div>

    <div class="card">
        @if ($card_front)
            <img src="{{ $card_front }}" alt="Card Front">
        @else
            <p>No background available</p>
        @endif
        <div class="member">
            <h1>{{ $role }}</h1>
        </div>
    </div>
</body>

</html>
